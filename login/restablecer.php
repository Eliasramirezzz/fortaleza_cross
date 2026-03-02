<?php
//Conexion a la BD
require_once '../conexion/conexion.php';
//Encabezado del json
header("Content-Type: application/json");

try{
    // Obtener los datos del cuerpo de la solicitud JSON
    $datos = json_decode(file_get_contents("php://input"), true);
    
    // Verificar si se decodificó correctamente y si los parámetros existen
    if (json_last_error() !== JSON_ERROR_NONE || 
        !isset($datos['token']) || empty($datos['token']) || 
        !isset($datos['password']) || empty($datos['password']) || 
        !isset($datos['confirm_password']) || empty($datos['confirm_password'])) {
        
        echo json_encode(["success" => false, "message" => "Datos de entrada inválidos."]);
        exit;
    }
    
    // Asignar los datos del JSON a variables
    $token = $datos['token'];
    $password = $datos['password'];
    $confirm_password = $datos['confirm_password'];

    // Sanitizar los datos (opcional pero recomendado)
    $token = htmlspecialchars(trim($token));
    $password = htmlspecialchars(trim($password));
    $confirm_password = htmlspecialchars(trim($confirm_password));

    // Verificar si las contraseñas son iguales
    if ($password !== $confirm_password) {
        echo json_encode(["success" => false, "message" => "Las contraseñas no coinciden."]);
        exit;
    }

    // Actualizar la contraseña
    $stmt = $pdo->prepare("UPDATE usuarios SET password = :password WHERE token_recuperacion = :token");
    $stmt->execute([':password' => password_hash($password, PASSWORD_DEFAULT),
                    ':token' => $token]);
    
    // Si hubo error o no se actualizó ninguna fila
    if ($stmt->rowCount() === 0) {
        echo json_encode(["success" => false, "message" => "Error al actualizar la contraseña o token inválido."]);
        exit;
    }
    
    // Si todo salió bien
    echo json_encode(["success" => true, "message" => "Contraseña actualizada."]);
    
}catch(PDOException $e){
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>