<?php 
include_once '../../conexion/conexion.php';
header("Content-Type: application/json");

try{
    $datos = json_decode(file_get_contents("php://input"), true);
    //Verificar que no este vacio el campo id
    if (!isset($datos['id_usuario']) || empty($datos['id_usuario'])){
        echo json_encode (["success" => false, 
        "message" => "No se encontro un id de usuario"]);
        exit;
    }
    //Sanitiza id.
    $idUser = filter_var($datos['id_usuario'], FILTER_VALIDATE_INT);

    //Obtener estado de su plan atraves de su id comparando con la del cliente.
    $sql = "SELECT * 
            FROM  VistaEstadoCliente 
            WHERE id_cliente = (
                SELECT c.id_cliente 
                FROM usuarios u 
                INNER JOIN clientes c ON c.id_usuario_fk = u.id_usuario 
                WHERE c.id_usuario_fk = :idUser limit 1
            )";
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindParam(':idUser', $idUser);
    $stmt -> execute();
    $resulEstado = $stmt->fetchAll(PDO::FETCH_ASSOC); // Trae todos los planes

    
    //Retornamos solo retornamos esto si el cliente es nuevo.
    if (empty($resulEstado)) {//Verificamos que la consulta tenga almenos 1 registro
        //Resultado devolvemos con las claves verdadera pero con sus valores con un string 'sin valor'. El objeto de datos por defecto
        $datosSinMembresia = [
            "id_cliente" => "Sin ID",
            "Nombre_Membresia" => "Sin membresia",
            "Duracion_Membresia" => "0", 
            "nombre" => "No tiene membresia",
            "apellido" => "No tiene membresia", 
            "ultimo_pago" => "No hay fecha",
            "vence_hasta" => "No hay fecha",
            "estado_plan" => "No hay estado",
            "dias_restantes" => "Sin membresia"
        ];
        // Convertimos el objeto simple en un ARRAY que contiene ese objeto.
        $resulEstado = [$datosSinMembresia]; // <--- ¡Ahora es un array!

        echo json_encode(["success" => true,
        "message" => "Cliente sin membresia",
        "dataStatus" => $resulEstado,
        "sinMembresia" => true,
        "estadoGlobal" => "Sin membresia"]);
        exit;
    }

    $estadoGlobal = "Al dia";
    foreach ($resulEstado as $plan) {
        if ($plan['estado_plan'] === "Vencido") {
            $estadoGlobal = "Vencido";
            break;
        }
    }

    //Mando todos los datos de una sola ves, pero si llega a fallar en alguna de las condiciones entonces manda un false en el success.
    echo json_encode(["success" => true,
    "message" => "Se han Procesado todos los datos",
    "dataStatus" => $resulEstado,
    "sinMembresia" => false,
    "estadoGlobal" => $estadoGlobal]);
    exit;

}catch(PDOException $e){
    echo json_encode(["success" => false,
    "message" => "Error al procesar los datos del cliente" . $e ->getMessage()]);
    exit;
}