<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    $datos = json_decode(file_get_contents("php://input"), true); //Decodificar el JSON
    if (!isset($datos['id_admin']) || empty($datos['id_admin'])) {
        echo json_encode(["success" => false, "message" => "No se ha proporcionado un id de administrador."]);
        exit;
    }

    $idAdmin = filter_var($datos['id_admin'], FILTER_VALIDATE_INT);

    $sql = "SELECT * FROM vistadatospagocliente
            ORDER BY Nombre_Cliente asc;";
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute();
    $resultados = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    if ($stmt -> rowCount() == 0) {
        //Array vacio
        $resultados = [];
        echo json_encode(["success" => true, 
        "message" => "No hay registros de pagos de clientes.",
        "dataPagos" => $resultados
        ]);
        exit;
    }

    if (!$resultados) {
        echo json_encode(["success" => false, "message" => "No se encontraron pagos de clientes."]);
        exit;
    }

    echo json_encode(["success" => true, 
    "message" => "Pagos de clientes encontrados.",
    "dataPagos" => $resultados]);
    exit;

}catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error al enviar correo: " . $e->getMessage()]);
    exit;
}catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error de base de datos: " . $e->getMessage()]);
    exit;
}