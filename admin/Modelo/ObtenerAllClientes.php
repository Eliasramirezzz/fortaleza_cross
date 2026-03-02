<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

// Envolver todo en un bloque try...catch...finally
try {
    // Decodificar el JSON
    $datos = json_decode(file_get_contents("php://input"), true); 

    if (!isset($datos['id_admin']) || empty($datos['id_admin'])) {
        echo json_encode(["success" => false, "message" => "No se proporcionó un ID de usuario válido."]);
        exit;
    }

    $idAdmin = filter_var($datos['id_admin'], FILTER_VALIDATE_INT);

    // Obtener todos los planes.
    $sql = "SELECT c.id_cliente, c.nombre, c.apellido, c.dni, c.telefono, c.email, c.fecha_alta, c.estado
            FROM clientes c 
            INNER JOIN usuarios u ON u.id_usuario = c.id_usuario_fk
            WHERE u.rol = 'cliente'
            ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($clientes)) {
        echo json_encode(["success" => false, 
        "message" => "No se encontraron clientes."]);
        exit;
    }

    echo json_encode(["success" => true, 
        "message" => "Clientes encontrados.",
        "dataClientes" => $clientes]);
    exit;


}catch(PDOException $e) {
    echo json_encode(["success" => false, 
    "message" => "Error al obtener los planes: " . $e->getMessage()]);
    exit;
}