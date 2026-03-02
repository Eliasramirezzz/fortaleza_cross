<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    $datos = json_decode(file_get_contents("php://input"), true); //Decodificar el JSON
    if (!isset($datos['id_admin']) || empty($datos['id_admin'])) {
        echo json_encode(["success" => false, "message" => "No se ha proporcionado un id de administrador."]);
        exit;
    }
    //Sanitizar id
    $idAdmin = filter_var($datos['id_admin'], FILTER_VALIDATE_INT);

    $sql = "SELECT * FROM membresias";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Verficar si hay datos sino muestro un mensaje pero devuelvo un arrays si o si.
    if (empty($planes)) {
        echo json_encode(["success" => false, 
        "message" => "No se encontraron planes.",
        "dataPlanes" => $planes]);
        exit;
    }
    echo json_encode(["success" => true, 
    "message" => "Planes encontrados", 
    "dataPlanes" => $planes]);
    exit; 

}catch(Exception $e){
    echo json_encode(["success" => false, "message" => "Error al obtener los planes: " . $e->getMessage()]);
    exit;
}