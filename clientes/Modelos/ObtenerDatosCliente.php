<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    $datos = json_decode(file_get_contents("php://input"), true);
    //Verificar que no este vacio el campo id 
    if (!isset($datos['id_usuario']) || empty($datos['id_usuario'])){
        echo json_encode (["success" => false, 
        "message" => "No se encontro un id de usuario"]);
        exit;
    }
    //Sanitizar id
    $idUser = filter_var($datos['id_usuario'], FILTER_VALIDATE_INT);

    //Obtener los datos.
    $sql = "SELECT * FROM clientes WHERE id_usuario_fk = :idusuario";
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindParam(":idusuario", $idUser);
    $stmt -> execute();
    $resultado = $stmt -> fetch(PDO::FETCH_ASSOC);

    //Verificamos si un cliente con esa id de usuarios.
    if (!$resultado || empty($resultado)){
        echo json_encode(["success" => false, 
        "message" => "No se encontro ningun cliente con ese usuario"]);
        exit;
    }
    //Si hay un cliente entonces le pasamos todos los datos,
    if ($resultado){
        echo json_encode(["success" => true, 
        "message" => "Cliente encontrado", 
        "datas" => $resultado]);
        exit;
    }

}catch (PDOException $e){
    echo json_encode(["success" => false,
    "message" => "Error al obtener los datos del id: " . $e ->getMessage()]);
    exit;
}