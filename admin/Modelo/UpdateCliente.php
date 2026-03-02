<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    $datos = json_decode(file_get_contents("php://input"), true); //Decodificar el JSON
    
    if (!isset($datos['id_admin']) || empty($datos['id_admin'])) {
        echo json_encode(["success" => false, 
        "message" => "No se ha proporcionado un id de administrador."]);
        exit;
    }
    if (!isset($datos['id_cliente']) || empty($datos['id_cliente'])) {
        echo json_encode(["success" => false, 
        "message" => "No se ha proporcionado un id de cliente."]);
        exit;
    }

    //Verificamos que no esten vacios los campos
    if (
        empty($datos['apellido']) ||
        empty($datos['nombre']) ||
        empty($datos['dni']) ||
        empty($datos['telefono']) ||
        empty($datos['email']) ||
        empty($datos['estado']) ||
        empty($datos['fecha_alta'])
    ) {
        echo json_encode([
            "success" => false,
            "message" => "Verifique que todos los campos estén completos."
        ]);
        exit;
    }
    //Validamos los nuevos datos del cliente a registrar
    $idCliente = filter_var($datos['id_cliente'], FILTER_VALIDATE_INT);
    $apellido = filter_var($datos['apellido'], FILTER_SANITIZE_STRING);
    $nombre = filter_var($datos['nombre'], FILTER_SANITIZE_STRING);
    $dni = filter_var($datos['dni'], FILTER_SANITIZE_STRING);
    $telefono = filter_var($datos['telefono'], FILTER_SANITIZE_STRING);
    $email = filter_var(trim($datos['email']), FILTER_SANITIZE_EMAIL);
    $estado =  trim($datos['estado']);
    $fecha_alta = trim($datos['fecha_alta']);

    //                                      Validacion Criticos
    // Validación de Formato de Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, 
        "message" => "El formato del Email ingresado no es válido."]);
        exit;
    }
    // Validación de Formato de DNI (solo números)
    if (!ctype_digit($dni) || strlen($dni) < 6 || strlen($dni) > 10) { 
        echo json_encode(["success" => false, 
        "message" => "El DNI debe contener solo números (6 a 10 dígitos)."]);
        exit;
    }
    // Validación de Formato de Teléfono (solo números y opcionalmente un espacios despues de 4 digitos y debe tener 10 digito u 11 con espacio eje: 1234 567890 o 1234567890)
    if (!preg_match('/^\d{4}\s?\d{6}$/', $telefono)) {
        echo json_encode(["success" => false, 
        "message" => "El formato del Teléfono ingresado no es válido."]);
        exit;
    }
    // Validación de Unicidad de DNI y Verifica si existe OTRO cliente con este DNI.
    $sql_dni_check = "SELECT COUNT(*) FROM clientes WHERE dni = :dni AND id_cliente != :id_cliente";
    $stmt_dni_check = $pdo->prepare($sql_dni_check);
    $stmt_dni_check->execute([
        ':dni' => $dni, 
        ':id_cliente' => $idCliente
    ]);
    if ($stmt_dni_check->fetchColumn() > 0) {
        echo json_encode(["success" => false, 
        "message" => "El DNI ingresado ya está registrado para otro cliente."]);
        exit;
    }
    // Verifica si existe OTRO cliente con este Email.
    $sql_email_check = "SELECT COUNT(*) FROM clientes WHERE email = :email AND id_cliente != :id_cliente";
    $stmt_email_check = $pdo->prepare($sql_email_check);
    $stmt_email_check->execute([
        ':email' => $email, 
        ':id_cliente' => $idCliente
    ]);
    if ($stmt_email_check->fetchColumn() > 0) {
        echo json_encode(["success" => false, 
        "message" => "El Email ingresado ya está registrado para otro cliente."]);
        exit;
    }

    //Verificamos que el id cliente sea valido (se encuentre en la BD).
    $sql = "SELECT id_cliente FROM clientes WHERE id_cliente = :id_cliente LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_cliente' => $idCliente]);
    $statement_cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$statement_cliente) {
        echo json_encode(["success" => false, 
        "message" => "No se pudo encontrar un cliente con el id proporcionado."]);
        exit;
    }

    // Pasamos a actualizar los datos del cliente con transaccion.
    // =========================================================
    // INICIO DE TRANSACCIÓN 
    // =========================================================
    $pdo->beginTransaction();

    $sql = "UPDATE clientes SET apellido = :apellido, nombre = :nombre, dni = :dni, telefono = :telefono, email = :email, estado = :estado, fecha_alta = :fecha_alta WHERE id_cliente = :id_cliente";
    $stmt = $pdo->prepare($sql);
    $resultado = $stmt->execute([
        ':apellido' => $apellido,
        ':nombre' => $nombre,
        ':dni' => $dni,
        ':telefono' => $telefono,
        ':email' => $email,
        ':estado' => $estado,
        ':fecha_alta' => $fecha_alta,
        ':id_cliente' => $idCliente
    ]);

    if (!$resultado) {
        $pdo->rollBack();
        echo json_encode(["success" => false, 
            "message" => "Error al actualizar los datos del cliente."]);
        exit;
    }
    // Si todo fue exitoso, CONFIRMAMOS la transacción.
    $pdo->commit();
    echo json_encode(["success" => true, 
    "message" => "Datos del cliente actualizados correctamente."]);
    // =========================================================
    // FIN DE TRANSACCIÓN 
    // =========================================================


}catch(Exception $e){
    echo json_encode(["success" => false, "message" => "Error interno del servidor: " . $e->getMessage()]);
    exit;
}catch(PDOException $e){
    echo json_encode(["success" => false, "message" => "Error de base de datos: " . $e->getMessage()]);
    exit;
}

