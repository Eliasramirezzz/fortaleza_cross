<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    $datos = json_decode(file_get_contents("php://input"), true); //Decodificar el JSON
    if (!isset($datos['id_admin']) || empty($datos['id_admin'])) {
        echo json_encode(["success" => false, "message" => "No se ha proporcionado un id de administrador."]);
        exit;
    }
    //Sanitizacion 
    $idAdmin = filter_var($datos['id_admin'], FILTER_VALIDATE_INT);

    //Consulta al servidor
    $sql = "SELECT u.id_usuario, c.nombre, c.apellido, u.email
        FROM usuarios u
        INNER JOIN clientes c ON c.id_usuario_fk = u.id_usuario
        WHERE u.id_usuario = :id_admin"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_admin', $idAdmin, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        echo json_encode(["success" => true, "message" => "Datos del administrador obtenidos con exito.", "dataAdmin" => $resultado]);
        exit;
    } else {
        echo json_encode(["success" => false, "message" => "No se encontraron datos del administrador."]);
        exit;
    }

}catch(PDOException $e){
    echo json_encode(["success" => false, "message" => "Error al obtener los datos del administrador: " . $e->getMessage()]);
    exit;
}