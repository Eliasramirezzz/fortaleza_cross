<?php
//Conexion al BD
require_once '../../conexion/conexion.php';

//Realizar la consulta segura.
try{
    //Validar GET 
    if (!isset($_GET['token']) || empty($_GET['token'])){
        echo json_encode(["success" => false, "message" => "No se ha proporcionado un token "]);
        exit;
    }
    if (!isset($_GET['email']) || empty($_GET['email'])){
    echo json_encode(["success" => false, "message" => "No se ha proporcionado un email válido."]);
    exit;
}
    //Sanitizar
    $token = filter_var($_GET['token'], FILTER_SANITIZE_STRING);
    $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

    // Verificar si el token aún existe en registro_pendiente
    $stmt = $pdo->prepare("SELECT email FROM registro_pendiente WHERE token = :token");
    $stmt->execute([':token' => $token]);
    $pendiente = $stmt->fetch(PDO::FETCH_ASSOC);
    //Si esta el token hacemos lo siguiente:
    if ($pendiente) {
        // Token aún existe → no confirmado
        echo json_encode([
            "success" => true,
            "message" => "El enlace aún no fue confirmado.",
            "estado" => false
        ]);
        exit;
    }
    // Token ya no existe. Verificamos si el email fue registrado.
    // Suponiendo que en el registro, el email se guarda en la tabla cliente o usuario, para evitar coherencia con el front-end
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($registro) {
        // El usuario fue registrado correctamente
        echo json_encode([
            "success" => true,
            "message" => "Enlace confirmado correctamente.",
            "estado" => true,
            "expirado" => false
        ]);
        exit;
    } else {
        // Token ya no está, pero tampoco hay registro → probablemente expiró
        echo json_encode([
            "success" => true,
            "message" => "El token ha expirado o el enlace no es válido.",
            "estado" => false,
            "expirado" => true
        ]);
        exit;
    }

}catch(PDOException $e){
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
