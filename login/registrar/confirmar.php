<?php
require_once '../../conexion/conexion.php';

try {
    // Verifico que exista y que no esté vacío
    if (empty($_GET['token']) || empty($_GET['email'])) {
        header("Location: MensajeConfirmar.php?estado=error&mensaje=" . urlencode("No se ha proporcionado un email o token."));
        exit;
    }

    // Sanitizo
    $token = filter_var($_GET['token'], FILTER_SANITIZE_STRING);
    $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

    // Obtengo todos los datos de ese token
    $stmt = $pdo->prepare("SELECT * FROM registro_pendiente WHERE email = :email AND token = :token");
    $stmt->execute([
        ':email' => $email,
        ':token' => $token
    ]);
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    // Condición principal de verificación
    if (!$registro) {
        header("Location: MensajeConfirmar.php?estado=error&mensaje=" . urlencode("Token inválido o ya usado."));
        exit;
    }

    // Verifico que el token no haya caducado
    $fecha_creacion_token = strtotime($registro['creado_en']);
    $tiempoActual = time();
    $tiempoTolerable = 120; // 2 minutos

    if (($tiempoActual - $fecha_creacion_token) > $tiempoTolerable) {
        // Borro el registro pendiente si caducó
        $stmt = $pdo->prepare("DELETE FROM registro_pendiente WHERE email = :email AND token = :token");
        $stmt->execute([
            ':email' => $email,
            ':token' => $token
        ]);
        header("Location: MensajeConfirmar.php?estado=error&mensaje=" . urlencode("Token caducado."));
        exit;
    }

    // Verificar que no exista el usuario ya registrado
    $stmtCheck = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :email");
    $stmtCheck->execute([':email' => $registro['email']]);
    if ($stmtCheck->fetch()) {
        header("Location: MensajeConfirmar.php?estado=error&mensaje=" . urlencode("Este correo ya está registrado."));
        exit;
    }

    // ===== INICIO TRANSACCIÓN =====
    $pdo->beginTransaction();

    // Insertar en tabla usuarios
    $stmtUsuario = $pdo->prepare("INSERT INTO usuarios (usuario, password, rol, email) 
                                VALUES (:usuario, :password, 'cliente', :email)");
    $stmtUsuario->execute([
        ':usuario' => $registro['usuario'],
        ':password' => $registro['clave'], // Ya viene hasheada
        ':email' => $registro['email']
    ]);

    $idUsuario = $pdo->lastInsertId();

    // Insertar en tabla clientes
    $stmtCliente = $pdo->prepare("INSERT INTO clientes (
        nombre, apellido, dni, email, telefono, fecha_nacimiento,
        direccion, fecha_alta, genero, estado, id_usuario_fk
    ) VALUES (
        :nombre, :apellido, :dni, :email, :telefono, :fecha_nacimiento,
        :direccion, NOW(), :genero, 'Activo', :id_usuario_fk
    )");

    $stmtCliente->execute([
        ':nombre' => $registro['nombre'],
        ':apellido' => $registro['apellido'],
        ':dni' => $registro['dni'],
        ':email' => $registro['email'],
        ':telefono' => $registro['telefono'],
        ':fecha_nacimiento' => $registro['fecha_nacimiento'],
        ':direccion' => '',
        ':genero' => 'Otro',
        ':id_usuario_fk' => $idUsuario
    ]);

    // Eliminar el registro pendiente
    $stmtDelete = $pdo->prepare("DELETE FROM registro_pendiente WHERE id = :id");
    $stmtDelete->execute([':id' => $registro['id']]);

    // Confirmar transacción
    $pdo->commit();

    header("Location: MensajeConfirmar.php?estado=ok&mensaje=" . urlencode("Registro confirmado con éxito."));
    exit;

} catch (PDOException $e) {
    // Si hay un error, revertimos todo
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Detectar error por clave duplicada en DNI
    if ($e->getCode() == 23000 && strpos($e->getMessage(), '1062') !== false) {
        $mensajeError = "El DNI ya está registrado o pertenece  a otro usuario. Por favor, prueba con otro DNI o contacta con Soporte.";
    } else {
        $mensajeError = "Ocurrió un error al registrar. Inténtalo más tarde.";
    }

    header("Location: MensajeConfirmar.php?estado=error&mensaje=" . urlencode($mensajeError));
    exit;
}
?>
