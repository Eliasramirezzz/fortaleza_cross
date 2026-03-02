<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    $datos = json_decode(file_get_contents("php://input"), true); //Decodificar el JSON
    //Verficamos que haya un id admin
    if (!isset($datos['id_admin']) || empty($datos['id_admin'])) {
        echo json_encode(["success" => false, 
        "message" => "No se ha proporcionado un id de administrador."]);
        exit;
    }
    //Verificamos que haya un id pago
    if (!isset($datos['id_pago']) || empty($datos['id_pago'])) {
        echo json_encode(["success" => false, 
        "message" => "No se ha proporcionado un id de pago."]);
        exit;
    }
    //Sanitizacion
    $idAdmin = filter_var($datos['id_admin'], FILTER_VALIDATE_INT);
    $idPago = filter_var($datos['id_pago'], FILTER_VALIDATE_INT);

    //Realizamo la eliminacion del pago con transaccion
    $pdo->beginTransaction();

    // OBTENER EL ID DEL PADRE (membresias_clientes) ANTES DE ELIMINAR AL HIJO (pagos)
    $sql_select = "SELECT id_MemCliente FROM pagos WHERE id_pago = :id_pago";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->bindParam(':id_pago', $idPago);
    $stmt_select->execute();
    $pago_info = $stmt_select->fetch(PDO::FETCH_ASSOC);

    if (!$pago_info || !$pago_info['id_MemCliente']) {
        // Manejar el caso si el pago o la FK no existen
        $pdo->rollBack();
        echo json_encode(['success' => false, 
        'message' => 'Error: Pago o ID de Membresía Cliente no encontrado.']);
        exit;
    }
    //Obtengo el id de la membresia-cliente del pago que quiero eliminar
    $id_MembresiaCliente = $pago_info['id_MemCliente'];

    // ELIMINAR AL HIJO (La tabla 'pagos')
    $sql_pago = "DELETE FROM pagos WHERE id_pago = :id_pago";  // Esto debe ejecutarse primero para liberar la restricción.
    $stmt_pago = $pdo->prepare($sql_pago);
    $stmt_pago->bindParam(':id_pago', $idPago);
    $stmt_pago->execute();

    // ELIMINAR AL PADRE (La tabla 'membresias_clientes')
    $sql_membresia = "DELETE FROM membresias_clientes WHERE id = :id_membresia_cliente";  // Una vez que el registro hijo ya no existe, podemos eliminar al padre.
    $stmt_membresia = $pdo->prepare($sql_membresia);
    $stmt_membresia->bindParam(':id_membresia_cliente', $id_MembresiaCliente);
    $resultado = $stmt_membresia->execute();

    if (!$resultado) {
        $pdo->rollBack();
        echo json_encode(["success" => false, 
                        "message" => "Error al eliminar el pago."]);
        exit;
    }

    $pdo->commit();
    echo json_encode(["success" => true, 
                    "message" => "Pago eliminado con exito."]);
    exit;

}catch(Exception $e){
    echo json_encode(["success" => false, 
    "message" => "Error al decodificar el JSON."]);
    exit;
}catch(PDOException $e){
    echo json_encode(["success" => false, 
    "message" => "Error al realizar la consulta: " . $e->getMessage()]);
    exit;
}