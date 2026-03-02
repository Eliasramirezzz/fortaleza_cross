<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    //Decodificar el JSON
    $datos = json_decode(file_get_contents("php://input"), true);

    // ====================== Validaciones y Santiziacion de datos ======================
    //Verificar que haya un id_usuario
    if(!isset($datos['id_usuario']) || empty($datos['id_usuario'])){
        echo json_encode(["success" => false, "message" => "No se proporcionó un ID de usuario válido."]);
        exit;
    }

    // Verificar que no haya campos vacíos para datos del cliente
    if (empty($datos['nombre']) || empty($datos['apellido']) || empty($datos['dni']) || empty($datos['telefono']) || empty($datos['fecha_nacimiento']) || empty($datos['genero'])) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    // Verificar que no haya campos vacíos para datos del usuario del cliente
    if (empty($datos['usuario']) || empty($datos['password']) || empty($datos['confirpassword']) || empty($datos['email']) || empty($datos['rol'])) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    // Sanitizacion de los datos.
    $idUsuario = filter_var($datos['id_usuario'], FILTER_VALIDATE_INT);
    $nombre = filter_var($datos['nombre'], FILTER_SANITIZE_STRING);
    $apellido = filter_var($datos['apellido'], FILTER_SANITIZE_STRING);
    $dni = filter_var($datos['dni'], FILTER_SANITIZE_STRING);
    $telefono = filter_var($datos['telefono'], FILTER_SANITIZE_STRING);
    $fechaNacimiento = filter_var($datos['fecha_nacimiento'], FILTER_SANITIZE_STRING);
    $genero = filter_var($datos['genero'], FILTER_SANITIZE_STRING);
    $usuario = filter_var($datos['usuario'], FILTER_SANITIZE_STRING);
    $password = filter_var($datos['password'], FILTER_SANITIZE_STRING);
    $confirpassword = filter_var($datos['confirpassword'], FILTER_SANITIZE_STRING);
    $email = filter_var($datos['email'], FILTER_SANITIZE_EMAIL);
    $rol = filter_var($datos['rol'], FILTER_SANITIZE_STRING);
    
    // Datos dinamico
    $fechaAlta = date('Y-m-d');
    $estado = 'Activo';

    // ====================== Validaciones estricta ======================
     // Verificar que el id usuario este en la base de datos
    $sql = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $idUsuario);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {
        echo json_encode(["success" => false, "message" => "No se encontró un usuario con el ID proporcionado."]);
        exit;
    }

    // Verificar que el email no este registrado
    $sql = "SELECT * FROM clientes WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        echo json_encode(["success" => false, "message" => "El email ya está registrado."]);
        exit;
    }

    // Validar que nombre, apellido, genero y rol no contengan numeros
    if (preg_match("/[0-9]/", $nombre) || preg_match("/[0-9]/", $apellido) || preg_match("/[0-9]/", $genero) || preg_match("/[0-9]/", $rol)) {
        echo json_encode(["success" => false, "message" => "El nombre, apellido, género y rol no pueden contener números."]);
        exit;
    }

    // Validar que dni tenga 8 digitos
    if (!preg_match('/^\d{8}$/', $dni)) {
        echo json_encode(["success" => false, "message" => "El DNI debe tener 8 dígitos."]);
        exit;
    }

    // Validar que telefono tenga 10 digitos y 11 digitos con '-'
    if (!preg_match('/^\d{10}$/', $telefono) && !preg_match('/^\d{11}$/', $telefono)) {
        echo json_encode(["success" => false, "message" => "El teléfono debe tener 10 o 11 dígitos con '-' como separador ejemplo 1122-345678."]);
        exit;
    }

    // Verificar que la contraseña y la confirmación de la contraseña coincidan
    if ($password !== $confirpassword) {
        echo json_encode(["success" => false, "message" => "Las contraseñas no coinciden."]);
        exit;
    }

    // Validar que fecha sea valida:
    try {
        $fecha = new DateTime($fechaNacimiento);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "La fecha de nacimiento no es válida."]);
        exit;
    }

    // Validar que rol sea valida
    if ($rol !== 'cliente' && $rol !== 'admin') {
        echo json_encode(["success" => false, "message" => "El rol no es válido."]);
        exit;
    }

    // ====================== Procesar registro ======================
    // Encriptar la contraseña
    $password = password_hash($password, PASSWORD_DEFAULT);

    // inicio transaccion
    $pdo->beginTransaction();
    
    // Inserto primero el usuario
    $sql = "INSERT INTO usuarios (usuario, password, rol, email) VALUES (:usuario, :password, :rol, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':rol', $rol);
    $resultado = $stmt->execute();

    if (!$resultado) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "message" => "Error al insertar el usuario."]);
        exit;
    }

    // Obtengo el id del usuario insertado
    $idUsuarioInsertado = $pdo->lastInsertId();

    //Inserto ahora en cleinte.
    $sql = "INSERT INTO clientes (nombre, apellido, dni, email, telefono, fecha_nacimiento, fecha_alta, genero, estado, id_usuario_fk) 
            VALUES (:nombre, :apellido, :dni, :email, :telefono, :fecha_nacimiento, :fecha_alta, :genero, :estado, :id_usuario_fk)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':dni', $dni);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':fecha_nacimiento', $fechaNacimiento);
    $stmt->bindParam(':fecha_alta', $fechaAlta);
    $stmt->bindParam(':genero', $genero);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':id_usuario_fk', $idUsuarioInsertado);
    $resultado = $stmt->execute();

    if (!$resultado) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "message" => "Error al insertar el usuario."]);
        exit;
    }
    // Confirmo la transaccion
    $pdo->commit();

    echo json_encode(["success" => true, "message" => "Registro exitoso."]);
    exit;

}catch(Exception $e){
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}catch(PDOException $e){
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}catch(Error $e){
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}