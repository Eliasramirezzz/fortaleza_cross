<?php
//Enabezado del json recibido.
header("Content-Type: application/json");
//Conexion a la base de datos.
require_once("../../conexion/conexion.php");// Incluir directamente la conexión, sin config.php

//Inicio de sesion.
session_start();

//Proceso el gmail ingresado.
try{
    //Desempaqueto el array recibido.
    $datos = json_decode(file_get_contents("php://input"), true);
    //Sanitizo el gmail.
    $email = filter_var($datos['email'], FILTER_SANITIZE_EMAIL);

    //Verifico si esta vacio el gmail.
    if (!isset($datos['email']) || empty($datos['email'])) {
        echo json_encode(["success" => false, "message" => "El email introducido no puede estar vacio"]);
        exit;
    }
    //Verificar que sea un gmail valido.
    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "El email introducido no es valido"]);
        exit;
    }
    
    // Creo la consulta.
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    // Preparo la consulta.
    $stmt = $pdo -> prepare($sql);
    // Paso parametro ala consulta.
    $stmt -> bindParam(":email", $email, PDO::PARAM_STR);
    //Realizo la consulta
    $stmt -> execute();
    // Obtengo los resultados de la consulta.
    $resultados = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    if (!empty($resultados)) {
        echo json_encode(["success" => false, "message" => "El email introducido ya se encuentra registrado"]);
        //cerrar sesion
        session_destroy();
        exit;
    }
    //Si no esta registrado este email si va a poder pasar al formulario 2 con el token que le habilita para el registro en el otro archivo php.

    // Genero un token seguro
    $token = bin2hex(random_bytes(32)); // 64 caracteres
    // Guardar token en tabla `registro_pendiente`
    // Eliminar si ya existe el email antes de insertar a la tabla de registro pendientes.
    $pdo->prepare("DELETE FROM registro_pendiente WHERE email = :email")->execute(['email' => $email]);// Sirbe cuando el email ya se encuentra registrado o quiere registrarse por otro

    // Insertar nuevo registro
    $stmt = $pdo->prepare("INSERT INTO registro_pendiente (email, token) VALUES (:email, :token)");
    $stmt->execute([
        'email' => $email,
        'token' => $token
    ]); // IMPORTANTE: no se aplico binParametro porque esos datos son generados por el servidor y es seguro para la consulta.

    if (empty($resultados)) {
        echo json_encode([
            "success" => true, 
            "message" => "Email disponible",
            "token" => $token
        ]); // Es valido para poder registrar ese email, y tambien le doy una clave de acceso.
        exit;
    } 

}catch(PODException $e){
    echo json_encode([
        "success" => false, 
        "error" => $e->getMessage()
    ]);
    exit();
}
?>

