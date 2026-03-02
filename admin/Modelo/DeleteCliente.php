<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    $datos = json_decode(file_get_contents("php://input"), true); //Decodificar el JSON
    //1 Verficamos que haya un id admin y un id cliente
    if (!isset($datos['id_admin']) || empty($datos['id_admin']) || !isset($datos['id_cliente']) || empty($datos['id_cliente'])) {
        echo json_encode(["success" => false, 
        "message" => "No se ha proporcionado un id de administrador."]);
        exit;
    }
    //Sanitizar
    $idAdmin = filter_var($datos['id_admin'], FILTER_VALIDATE_INT);
    $idCliente = filter_var($datos['id_cliente'], FILTER_VALIDATE_INT);

    // 2. Obtener IDs Relacionados (id_usuario_fk y los id_memCliente)
    $sql_info = "SELECT id_usuario_fk FROM clientes WHERE id_cliente = :id_cliente";
    $stmt_info = $pdo->prepare($sql_info);
    $stmt_info->execute([':id_cliente' => $idCliente]);
    $cliente_info = $stmt_info->fetch(PDO::FETCH_ASSOC);

    if (!$cliente_info) {
        // Si el cliente no existe, devolvemos un éxito suave.
        echo json_encode(["success" => true, 
        "message" => "Cliente no encontrado. Asumimos eliminado correctamente."]);
        exit;
    }
    // Extraemos el id_usuario_fk
    $idUsuario = $cliente_info['id_usuario_fk'];

    // Obtener los IDs de membresias_clientes asociados al cliente
    $sql_mem_clientes = "SELECT id FROM membresias_clientes WHERE id_cliente = :id_cliente";
    $stmt_mem_clientes = $pdo->prepare($sql_mem_clientes);
    $stmt_mem_clientes->execute([':id_cliente' => $idCliente]);
    $membresias_ids = $stmt_mem_clientes->fetchAll(PDO::FETCH_COLUMN, 0);

    // INICIO DE TRANSACCIÓN 

    $pdo->beginTransaction();

    // 3. Eliminación en Cascada (De los más dependientes a los menos)
    $error_borrado = false;

    // A. Eliminar Pagos (Dependen de membresias_clientes)
    // Usamos 'IN' para borrar los pagos asociados a todas las membresías del cliente.
    if (!empty($membresias_ids)) {
        $in_clause = implode(',', array_fill(0, count($membresias_ids), '?'));
        $sql_pagos = "DELETE FROM pagos WHERE id_MemCliente IN ($in_clause)";
        $stmt_pagos = $pdo->prepare($sql_pagos);
        if (!$stmt_pagos->execute($membresias_ids)) {
            $error_borrado = true;
        }
    }

    // B. Eliminar Membresías Cliente (Dependen de clientes)
    if (!$error_borrado) {
        $sql_mem_clientes_del = "DELETE FROM membresias_clientes WHERE id_cliente = :id_cliente";
        $stmt_mem_clientes_del = $pdo->prepare($sql_mem_clientes_del);
        if (!$stmt_mem_clientes_del->execute([':id_cliente' => $idCliente])) {
            $error_borrado = true;
        }
    }

    // C. Eliminar Inscripciones a Clases (Dependen de clientes)
    if (!$error_borrado) {
        $sql_inscripciones = "DELETE FROM inscripciones_clases WHERE id_cliente = :id_cliente";
        $stmt_inscripciones = $pdo->prepare($sql_inscripciones);
        if (!$stmt_inscripciones->execute([':id_cliente' => $idCliente])) {
            $error_borrado = true;
        }
    }

    // D. Eliminar Cliente (Tabla principal afectada)
    if (!$error_borrado) {
        $sql_cliente = "DELETE FROM clientes WHERE id_cliente = :id_cliente";
        $stmt_cliente = $pdo->prepare($sql_cliente);
        if (!$stmt_cliente->execute([':id_cliente' => $idCliente])) {
            $error_borrado = true;
        }
    }

    // E. Eliminar Usuario (Dependiente de clientes)
    // NOTA: Es importante que id_usuario_fk sea la única referencia a la tabla 'usuarios'.
    // Si otros roles/tablas lo usan, esta eliminación DEBE omitirse.
    if (!$error_borrado && !empty($idUsuario)) {
        $sql_usuario = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt_usuario = $pdo->prepare($sql_usuario);
        if (!$stmt_usuario->execute([':id_usuario' => $idUsuario])) {
            $error_borrado = true;
        }
    }

    // 4. Conclusión de la Transacción
    if ($error_borrado) {
        $pdo->rollBack();
        echo json_encode(["success" => false, 
        "message" => "Error al eliminar registros relacionados. La operación fue revertida."]);
        exit;
    }

    $pdo->commit();
    // FIN DE TRANSACCIÓN 

    echo json_encode(["success" => true, 
    "message" => "Cliente y todos sus datos relacionados (membresías, pagos, usuario) eliminados correctamente."]);
    exit;

}catch(PDOException $e){
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // El error 1451 (FK) ahora debería ser capturado internamente.
    // Si aparece aquí, es una FK no contemplada.
    echo json_encode(["success" => false, 
    "message" => "Error de base de datos. Intente nuevamente. Detalles: " . $e->getMessage()]);
    exit;
} catch(Exception $e){
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["success" => false, 
    "message" => "Error interno al eliminar el cliente: " . $e->getMessage()]);
    exit;
}