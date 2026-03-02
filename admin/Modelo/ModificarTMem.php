<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    $datos = json_decode(file_get_contents("php://input"), true); //Decodificar el JSON

    // Verificar si hay una id de admin 
    if(!isset($datos['id_admin'])){
        echo json_encode(["success" => false, "message" => "ID de administrador no proporcionado"]);
        exit;
    }

    // Preparo los datos del frontend
    $id_admin = $datos['id_admin'];// Sanitizar id de admin
    $id_membresia = isset($datos['id_membresia']) ? intval($datos['id_membresia']) : null;
    $nombre = isset($datos['nombre']) ? trim($datos['nombre']) : '';
    $badge = isset($datos['badge']) ? trim($datos['badge']) : '';
    $precio = isset($datos['precio']) ? floatval($datos['precio']) : 0.0;
    $duracion_dias = isset($datos['duracion_dias']) ? intval($datos['duracion_dias']) : 0;
    $descripcion = isset($datos['descripcion']) ? trim($datos['descripcion']) : '';
    $caracteristicas = isset($datos['caracteristicas']) ? trim($datos['caracteristicas']) : '';
    $descripcion_larga = isset($datos['descripcion_larga']) ? trim($datos['descripcion_larga']) : '';
    // especial y activa son checkbox, verificar si están seteados (1 true 0 false)
    $especial = isset($datos['especial']) && $datos['especial'] == 1 ? 1 : 0;
    $activa = isset($datos['activa']) && $datos['activa'] == 1 ? 1 : 0;

    // Verificar si la id de admin es un entero
    if(!filter_var($id_admin, FILTER_VALIDATE_INT)){
        echo json_encode(["success" => false, "message" => "ID de administrador no es valido"]);
        exit;
    }

    // Verificar que la id de admin sea válida
    $sql = "SELECT * FROM usuarios WHERE id_usuario = :id_admin";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
    $stmt->execute();
    if($stmt->rowCount() == 0){
        echo json_encode(["success" => false, "message" => "ID de administrador no encontrado, verifique que sea valido e intente nuevamente."]);
        exit;
    }

    // Verifico que el id de membresia exista para actualizar sus datos
    $sql = "SELECT * FROM membresias WHERE id_membresia = :id_membresia";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_membresia', $id_membresia, PDO::PARAM_INT);
    $stmt->execute();
    if($stmt->rowCount() == 0){
        echo json_encode(["success" => false, "message" => "La membresia que deseas actualizar no existe en el sistema, verifique que no hay sido eliminado antes de esta accion."]);
        exit;
    }

    // ===================== Verificar y sanitizar los datos =====================
    // Verificar que nombre no este vacio
    if (empty($nombre)){
        echo json_encode(["success" => false, "message" => "Nombre de la membresía no proporcionado."]);
        exit;
    }
    // Verificar que badge no este vacio y que no contenga numeros
    if (empty($badge) || preg_match('/\d/', $badge)){
        echo json_encode(["success" => false, "message" => "Badge de la membresía no proporcionado o contiene números."]);
        exit;
    }
    // Verificar nombre y badge sean texto y validos
    if(empty($nombre) || !is_string($nombre) || strlen($nombre) > 100 || empty($badge) || !is_string($badge) || strlen($badge) > 100){
        echo json_encode(["success" => false, "message" => "Nombre o badge no válidos, deben ser texto y no exceder los límites de 100 caracteres."]);
        exit;
    }
    // Verificar que precio sera numerico, mayo a 0 y que no este vacio.
    if(!is_numeric($precio) || $precio <= 0 || empty($precio)){
        echo json_encode(["success" => false, "message" => "Precio debe ser un número y mayor a 0, tambien verifique que no este vacio."]);
        exit;
    }
    // Verificar que duracion días sea un entero y no pase mas de 30 dias y no este vacio
    if(!is_int($duracion_dias) || $duracion_dias > 30 || empty($duracion_dias)){
        echo json_encode(["success" => false, "message" => "Duración debe ser un número entero y no mayor a 30 días, tambien verifique que no este vacio."]);
        exit;
    }

    // Verificar que descripcion y caracteristicas sean texto y validos y no este vacio
    if(empty($descripcion) || !is_string($descripcion) || strlen($descripcion) > 500 || empty($caracteristicas) || !is_string($caracteristicas) || strlen($caracteristicas) > 500){
        echo json_encode(["success" => false, "message" => "Descripcion o caracteristicas no válidas, deben ser texto y no exceder los límites de 500 caracteres, tambien verifique que no este vacio."]);
        exit;
    }
    // Verificar que descripcion_larga sea texto y validos y no este vacio
    if(empty($descripcion_larga) || !is_string($descripcion_larga) || strlen($descripcion_larga) > 1000){
        echo json_encode(["success" => false, "message" => "Descripcion larga no válida, debe ser texto y no exceder los límites de 1000 caracteres, tambien verifique que no este vacio."]);
        exit;
    }
    // Verificar que especial y activa sean booleanos (0 o 1)
    if(!in_array($especial, [0, 1]) || !in_array($activa, [0, 1])){
        echo json_encode(["success" => false, "message" => "Valores de especial o activa no válidos, contactese con soporte para mas ayuda."]);
        exit;
    }

    // ==================== Actualizar datos de la membresia ====================
    // Iniciar Transaction
    $pdo->beginTransaction();

    $sql = "UPDATE membresias SET nombre = :nombre, badge = :badge, precio = :precio, duracion_dias = :duracion_dias, descripcion = :descripcion, caracteristicas = :caracteristicas, descripcion_larga = :descripcion_larga, especial = :especial, activa = :activa WHERE id_membresia = :id_membresia";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_membresia', $id_membresia, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':badge', $badge, PDO::PARAM_STR);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':duracion_dias', $duracion_dias, PDO::PARAM_INT);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':caracteristicas', $caracteristicas, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion_larga', $descripcion_larga, PDO::PARAM_STR);
    $stmt->bindParam(':especial', $especial, PDO::PARAM_INT);
    $stmt->bindParam(':activa', $activa, PDO::PARAM_INT);
    $result = $stmt->execute();

    if (!$result) {
        $pdo->rollBack();
        echo json_encode(["success" => true, "message" => "Error al actualizar la membresía, intente nuevamente mas tarde."]);
    }

    // Finalizacion de transaccion
    $pdo->commit();
    echo json_encode(["success" => true, "message" => "Membresía actualizada exitosamente."]);
    exit;

}catch(Exception $e){
    echo json_encode(["success" => false, "message" => "Error al decodificar JSON: " . $e->getMessage()]);
    exit;

}catch(PDOException $e){
    echo json_encode(["success" => false, "message" => "Error de base de datos: " . $e->getMessage()]);
    exit;
}