<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

//  envolver todo en un bloque try...catch...finally
try {
    // Decodificar el JSON
    $datos = json_decode(file_get_contents("php://input"), true); 

    //Verificación de Datos
    if (!isset($datos['id_admin'], $datos['email'], $datos['plan'], $datos['fecha_alta'], $datos['estado_pago']) 
    || empty($datos['id_admin']) 
    || empty($datos['email']) 
    || empty($datos['fecha_alta']) 
    || empty($datos['plan']) 
    || empty($datos['estado_pago'])) 
    {
        // Si falla, el mensaje debe ser claro y no mencionar campos que no envías.
        echo json_encode(["success" => false, 
        "message" => "Faltan datos requeridos: Email, Plan, Fecha o Estado de Pago."]);
        exit;
    }
    
    // Sanitización de Datos
    $id_admin = filter_var($datos['id_admin'], FILTER_SANITIZE_NUMBER_INT);
    $plan = filter_var($datos['plan'], FILTER_SANITIZE_NUMBER_INT);
    $email_limpio = filter_var(trim($datos['email']), FILTER_SANITIZE_EMAIL);
    $fecha_alta = trim($datos['fecha_alta']);
    $estado_pago = trim($datos['estado_pago']);

    // Validación
    if (!filter_var($email_limpio, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "El email proporcionado no es válido."]);
        exit;
    }
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_alta)) {
        echo json_encode(["success" => false, "message" => "El formato de la fecha de alta no es válido (Debe ser YYYY-MM-DD)."]);
        exit;
    }
    
    // Verificación de Existencia de Usuario
    $sql = "SELECT id_usuario FROM usuarios WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email_limpio]);
    $registro_usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$registro_usuario) {
        echo json_encode(["success" => false, "message" => "El email no existe en el sistema. El cliente debe registrarse como usuario primero."]);
        exit;
    }
    $id_usuario = $registro_usuario['id_usuario'];
    
    // Obtener ID del Cliente 
    $sql = "SELECT c.id_cliente FROM clientes c WHERE c.id_usuario_fk = :id_user LIMIT 1"; 
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_user' => $id_usuario]); 
    $registro_cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$registro_cliente) {
        echo json_encode(["success" => false, "message" => "Usuario encontrado, pero no tiene registro de cliente asociado. Verifique el perfil."]);
        exit;
    }
    $id_cliente = $registro_cliente['id_cliente'];

    //  Verificar si ya tiene membresía activa en ese plan 
    $sql_check = "SELECT id
                FROM membresias_clientes 
                WHERE id_cliente = :id_cliente 
                AND id_membresia = :id_plan 
                AND estado = 'activa' 
                LIMIT 1";
    
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([
        ':id_cliente' => $id_cliente, 
        ':id_plan' => $plan
    ]);
    $membresia_existente = $stmt_check->fetch(PDO::FETCH_ASSOC);
    if ($membresia_existente) {
        echo json_encode(["success" => false, "message" => "El cliente ya tiene una membresía activa para este plan."]);
        exit;
    }

    // Buscar el precio real de la membresía
    $sql = "SELECT precio FROM membresias WHERE id_membresia = :id_plan";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_plan' => $plan]);
    $registro_membresia = $stmt->fetch(PDO::FETCH_ASSOC);
    $precio_membresia = $registro_membresia['precio'] ?? 0; // fallback a 0 si no encuentra


    // =========================================================
    // INICIO DE TRANSACCIÓN 
    // =========================================================
    $pdo->beginTransaction();

    // Insertar en membresias_clientes
    $sql = "INSERT INTO membresias_clientes (id_cliente, id_membresia, fecha_inicio, estado) 
            VALUES (:id_cliente, :id_plan, :fecha_alta, 'activa')";
    $stmt = $pdo->prepare($sql);
    $resultado = $stmt->execute([
        ':id_cliente' => $id_cliente,
        ':id_plan' => $plan,
        ':fecha_alta' => $fecha_alta,
    ]);
    
    if (!$resultado) {
        $pdo->rollBack();
        echo json_encode(["success" => false, 
        "message" => "Error al registrar la membresía del socio."]);
        exit;
    }
    $id_memCliente = $pdo->lastInsertId();

    // Procesar la fecha de vencimiento basada en la Fecha de alta del cliente
    $fecha_alta_dt = new DateTime($fecha_alta); 

    // Obtener día, mes y año de la fecha de alta
    $dia = (int)$fecha_alta_dt->format('d');
    $mes = (int)$fecha_alta_dt->format('m');
    $anio = (int)$fecha_alta_dt->format('Y');

    // Lógica para calcular fecha de vencimiento
    if ($dia <= 10) {
        // Si se registra antes o el mismo día 10 → vence el 10 de este mes
        $fecha_vencimiento = new DateTime("$anio-$mes-10");
    } else {
        // Si se registra después del día 10 → vence el 10 del mes siguiente
        $fecha_vencimiento = new DateTime("$anio-$mes-10");
        $fecha_vencimiento->modify('+1 month');
    }

    // Convertir a string para guardar en la BD
    $fecha_vencimiento_str = $fecha_vencimiento->format('Y-m-d');

    // Insertar en Pagos
    $estado_pago_int = ($estado_pago === 'pagado') ? 1 : 0;

    $sql_pago = "INSERT INTO pagos (id_membresia, id_MemCliente, monto, fecha_pago, fecha_vencimiento, metodo_pago, estado) VALUES (:id_membresia, :id_memCliente, :monto, :fecha_pago, :fecha_vencimiento, :metodo_pago, :estado)";

    $stmt_pago = $pdo->prepare($sql_pago);
    $resultado_pago = $stmt_pago->execute([
        ':id_membresia' => $plan,
        ':id_memCliente' => $id_memCliente,
        // Lógica Corregida para Monto, Fecha y Método:
        ':monto' => ($estado_pago_int === 1) ? $precio_membresia : NULL, // Si pagó, ponemos el monto real
        ':fecha_pago' => ($estado_pago_int === 1) ? $fecha_alta : NULL, // Si pagó, ponemos una fecha TEMPORAL
        ':fecha_vencimiento' => $fecha_vencimiento_str,
        ':metodo_pago' => ($estado_pago_int === 1) ? 'Efectivo' : NULL, // Si pagó, ponemos un método TEMPORAL
        ':estado' => $estado_pago_int // 1 (Pagado) o 0 (Pendiente)
    ]);

    if (!$resultado_pago) {
        $pdo->rollBack();
        // Mensaje de error ajustado para ser más específico
        echo json_encode(["success" => false, "message" => "Error al registrar el registro de pago. Asegúrese que las columnas de 'pagos' acepten NULL."]);
        exit;
    }

    // Si todo fue exitoso, CONFIRMAMOS la transacción.
    $pdo->commit();

    // Respuesta Final
    echo json_encode(["success" => true, "message" => "Socio registrado al plan y pago inicial creado con éxito."]); 
    exit;

} catch(PDOException $e) {
    // Si la transacción está activa, deshacemos los cambios
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["success" => false, "message" => "Error de base de datos: " . $e->getMessage()]);
    exit;
} catch(Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["success" => false, "message" => "Error interno del servidor: " . $e->getMessage()]);
    exit;
}
// OMITIR EL CIERRE DE PHP