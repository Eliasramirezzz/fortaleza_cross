<?php 
// Establecer la zona horaria para asegurar la hora correcta
date_default_timezone_set('America/Argentina/Buenos_Aires'); 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    // 1. Validación y Sanitización de Entrada
    $datos = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($datos['id_admin']) || empty($datos['id_admin']) ) {
        echo json_encode(["success" => false, "message" => "No se ha proporcionado un id de administrador."]);
        exit;
    }
    if(!isset($datos['id_pago']) || empty($datos['id_pago']) ) {
        echo json_encode(["success" => false, "message" => "No se ha proporcionado un id de pago."]);
        exit;
    }
    if (!isset($datos['metodo_pago']) || empty($datos['metodo_pago']) ) {
        echo json_encode(["success" => false, "message" => "No se ha proporcionado un metodo de pago."]);
        exit;
    }
    
    $idAdmin = filter_var($datos['id_admin'], FILTER_VALIDATE_INT);
    $idPlan = filter_var($datos['id_pago'], FILTER_VALIDATE_INT);
    $metodoPago = filter_var($datos['metodo_pago'], FILTER_SANITIZE_STRING);

    // Obtener Información de Pago y Membresía
    $sql_info = "SELECT p.id_MemCliente, m.precio, m.duracion_dias 
                FROM pagos p
                INNER JOIN membresias_clientes mc ON p.id_MemCliente = mc.id
                INNER JOIN membresias m ON mc.id_membresia = m.id_membresia
                WHERE p.id_pago = :id_pago";
    $stmt_info = $pdo->prepare($sql_info);
    $stmt_info->execute(['id_pago' => $idPlan]);
    $pago_info = $stmt_info->fetch(PDO::FETCH_ASSOC);

    if (!$pago_info || !$pago_info['precio']) {
        echo json_encode(["success" => false, "message" => "Error: No se pudo obtener la información de pago y membresía."]);
        exit;
    }

    $id_MemCliente = $pago_info['id_MemCliente'];
    $montoPlan = $pago_info['precio']; 
    // Creamos una fecha y hora de "ahora", luego la convertimos a formato Y-m-d (solo fecha).
    $now = new DateTime('now');
    $fecha_pago_actual = $now->format('Y-m-d'); // Esto extrae solo la fecha (Ej: 2025-10-12)
    $dia_corte_fijo = 10;
    
    //Solo buscamos en pagos con estado = 1 (Pagados/Al día)
    $sql_last_vto = " SELECT fecha_vencimiento 
                FROM pagos 
                WHERE id_MemCliente = :id_MemCliente 
                AND id_pago != :id_pago 
                AND estado = 1
                ORDER BY fecha_vencimiento DESC 
                LIMIT 1";
    $stmt_last_vto = $pdo->prepare($sql_last_vto);
    $stmt_last_vto->execute([
        'id_MemCliente' => $id_MemCliente, 
        'id_pago' => $idPlan
    ]); 
    $last_vto_row = $stmt_last_vto->fetch(PDO::FETCH_ASSOC);
    $last_vto = $last_vto_row ? $last_vto_row['fecha_vencimiento'] : null;
    $ultimo_vto_timestamp = empty($last_vto) ? 0 : strtotime($last_vto);

    // El límite se fija al día 10 de hace dos meses. (Ej: Hoy 03-10, límite 10-08)
    $limite_dos_meses_str = date('Y-m-d', strtotime('-2 months', strtotime(date('Y-m-') . $dia_corte_fijo)));
    $limite_dos_meses_timestamp = strtotime($limite_dos_meses_str);
    
    // Si hay un VTO registrado Y es ANTERIOR o IGUAL al límite de 2 meses atrás
    if ($ultimo_vto_timestamp > 0 && $ultimo_vto_timestamp <= $limite_dos_meses_timestamp) { 
        // Interrumpimos la operación y enviamos el mensaje de eliminación.
        echo json_encode(["success" => false, 
        "message" => "ALERTA: El cliente tiene más de dos meses de deuda (último VTO: $last_vto). Debe ELIMINAR la membresía y volverlo a registrar."]);
        exit;
    }

    // 5. Determinar la Fecha Base para la Extensión (Si la deuda es menor a 2 meses)
    if (empty($last_vto)) {
        // Si es el primer pago, usamos la fecha de corte actual (o próxima).
        $base_date_str = date('Y-m-') . $dia_corte_fijo; 
        if (strtotime($base_date_str) < strtotime($fecha_pago_actual)) {
            $base_date_str = date('Y-m-d', strtotime('+1 month', strtotime($base_date_str)));
        }
        $fecha_base_extension = $base_date_str;
    } else {
        // Si hay historial (vencido o al día), siempre extendemos a partir del último VTO.
        $fecha_base_extension = $last_vto;
    }

    // 6. Calcular Nuevo Vencimiento (Siempre el 10 del mes siguiente a la base)
    $fecha_vencimiento_nueva = date('Y-m-d', strtotime('+1 month', strtotime($fecha_base_extension))); 
    $fecha_vencimiento_nueva = date('Y-m-d', strtotime(date('Y-m', strtotime($fecha_vencimiento_nueva)) . '-10'));

    
    // 7. Determinar el Nuevo Estado (Pagado=1 vs Pendiente=0)
    // Si la nueva VTO es futura, el cliente está 'Al día' (1). Si no, es 'Pendiente' (0).
    if (strtotime($fecha_vencimiento_nueva) > strtotime($fecha_pago_actual)) {
        $nuevo_estado = 1; 
        $mensaje_final = "Pago registrado. Cliente al día hasta $fecha_vencimiento_nueva.";
    } else {
        $nuevo_estado = 0; 
        $mensaje_final = "Saldado registrado. El cliente aún está Pendiente (VTO: $fecha_vencimiento_nueva) y requiere otro pago.";
    }

    // 8. Ejecutar Transacción
    $pdo->beginTransaction();

    $sql_update_pago = "UPDATE pagos 
                        SET monto = :monto, 
                            fecha_pago = :fecha_pago, 
                            metodo_pago = :metodo_pago, 
                            fecha_vencimiento = :fecha_vencimiento_nueva,
                            estado = :nuevo_estado 
                        WHERE id_pago = :id_pago";
    $stmt_pago = $pdo->prepare($sql_update_pago);
    $resultado_pago = $stmt_pago->execute([
        'id_pago' => $idPlan,
        'monto' => $montoPlan,
        'fecha_pago' => $fecha_pago_actual,
        'metodo_pago' => $metodoPago,
        'fecha_vencimiento_nueva' => $fecha_vencimiento_nueva,
        'nuevo_estado' => $nuevo_estado, 
    ]);

    if (!$resultado_pago) {
        $pdo->rollBack();
        echo json_encode(["success" => false, 
        "message" => "Error al marcar el pago como pagado."]);
        exit;
    }

    $pdo->commit();
    echo json_encode(["success" => true, 
    "message" => $mensaje_final]); 
    exit;

} catch(PDOException $e) {
    // Manejo de errores de base de datos
    // NO se usa pdo->rollBack() si la transacción no ha iniciado
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["success" => false, 
    "message" => "Error al consultar en la base de datos: " . $e->getMessage()]);
    exit;
} catch(Exception $e) {
    // Manejo de errores de PHP generales
    echo json_encode(["success" => false, 
    "message" => "Ocurrió un error inesperado: " . $e->getMessage()]);
    exit;
} catch(Error $e) {
    // Manejo de errores de parsing JSON (si `json_decode` falla)
    echo json_encode(["success" => false, 
    "message" => "Error en la aplicación."]);
    exit;
}