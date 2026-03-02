<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    $datos = json_decode(file_get_contents("php://input"), true); //Decodificar el JSON

    // Verificar que exista una id de admin
    if(!isset($datos['id_admin'])){
        echo json_encode(["success" => false, "message" => "ID de administrador no proporcionado o no valido."]);
        exit;
    }

    // Verificar que la id de admin sea valida
    $id_admin = filter_var($datos['id_admin'], FILTER_VALIDATE_INT);
    if(!filter_var($id_admin, FILTER_VALIDATE_INT)){
        echo json_encode(["success" => false, "message" => "ID de administrador no es valido o no proporcionado."]);
        exit;
    }

    // Verificar que la id de admin exista en la bd.
    $sql = "SELECT * FROM usuarios WHERE id_usuario = :id_admin";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
    $stmt->execute();
    if($stmt->rowCount() == 0){
        echo json_encode(["success" => false, "message" => "ID de administrador no encontrado, verifique que sea valido e intente nuevamente."]);
        exit;
    }

    // Validar y sanitizar la id de la membresia.
    $id_membresia = filter_var($datos['id_membresia_a_desactivar'], FILTER_VALIDATE_INT);
    if(!filter_var($id_membresia, FILTER_VALIDATE_INT)){
        echo json_encode(["success" => false, "message" => "ID de membresia no es valido o no proporcionado."]);
        exit;
    }

    // Verificar que la membresía exista Y esté ACTIVA (activa = 1)
    $sql = "SELECT id_membresia, activa FROM membresias WHERE id_membresia = :id_membresia AND activa = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_membresia', $id_membresia, PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() == 0){
    // Si no encuentra una fila, la membresía o no existe, O YA ESTÁ DESACTIVADA.
        // Verificación secundaria para el mensaje:
        $sql_check_exists = "SELECT 1 FROM membresias WHERE id_membresia = :id_membresia";
        $stmt_check = $pdo->prepare($sql_check_exists);
        $stmt_check->bindParam(':id_membresia', $id_membresia, PDO::PARAM_INT);
        $stmt_check->execute();
        
        if ($stmt_check->rowCount() > 0) {
            // La membresía existe, pero no estaba activa (activa = 0)
            $message = "La membresía ya se encuentra desactivada.";
        } else {
            // La membresía no existe
            $message = "La membresía que deseas desactivar no existe en el sistema.";
        }

        echo json_encode(["success" => false, "message" => $message]);
        exit;
        }

    // Incio trasaccion 
    $pdo->beginTransaction();

    // Si todo sale bien lo desactivo
    $sql = "UPDATE membresias SET activa = 0 WHERE id_membresia = :id_membresia";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_membresia', $id_membresia, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->rowCount();

    if($result == 0){
        $pdo->rollBack();
        // Mensaje más específico basado en rowCount=0
        echo json_encode(["success" => false, "message" => "La membresía ya estaba desactivada o no existe.", "error" => $stmt->errorInfo()[2]]);
        exit;
    }

    $pdo->commit();
    // Si todo sale bien.
    echo json_encode(["success" => true, "message" => "Membresia desactivada con exito."]);
    exit;

}catch(Exception $e){
    echo json_encode(["success" => false, "message" => "Error al desactivar la membresia, intente nuevamente.", "error" => $e->getMessage()]);
    exit;
}catch(Error $e){
    echo json_encode(["success" => false, "message" => "Error al desactivar la membresia, intente nuevamente.", "error" => $e->getMessage()]);
    exit;
}catch(PDOException $e){
    echo json_encode(["success" => false, "message" => "Error al desactivar la membresia, intente nuevamente.", "error" => $e->getMessage()]);
    exit;
}