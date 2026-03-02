<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    $datos = json_decode(file_get_contents("php://input"), true);

    if (!isset($datos['id_usuario']) || empty($datos['id_usuario'])){
        echo json_encode (["success" => false, 
        "message" => "No se encontro un id de usuario"]);
        exit;
    }

    $idUser = filter_var($datos['id_usuario'], FILTER_VALIDATE_INT);
    
    // 1️⃣ Consulta principal: planes asociados al socio
    $sql = "SELECT * 
            FROM  vistaAllPlanes
            WHERE id_cliente = (
                SELECT c.id_cliente 
                FROM usuarios u 
                INNER JOIN clientes c ON c.id_usuario_fk = u.id_usuario 
                WHERE c.id_usuario_fk = :id_usuario limit 1)
            ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $idUser, PDO::PARAM_INT);
    $stmt->execute();
    $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2️⃣ Consulta secundaria: clases asociadas a los planes
    $sqlClases = "SELECT * FROM vistaclasespormembresia";
    $stmtClases = $pdo->prepare($sqlClases);
    $stmtClases->execute();
    $clases = $stmtClases->fetchAll(PDO::FETCH_ASSOC);
    
    //Verificamos si la consulta tuvo exito
     // 3️⃣ Respuesta
    if ($stmtClases->rowCount() > 0) {
        echo json_encode([
            "success" => true,
            "message" => "Planes encontrados",
            "dataPlanes" => $planes,
            "dataClases" => $clases  // <- Nuevo array de clases
        ]);
        exit;
    } else {
        echo json_encode([
            "success" => false, 
            "message" => "No se encontraron planes"
        ]);
        exit;
    }

}catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error al consultar los planes de base de datos: "]) . $e->getMessage();
    exit;
}