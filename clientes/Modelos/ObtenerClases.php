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
    //Sanitizar id
    $idUser = filter_var($datos['id_usuario'], FILTER_VALIDATE_INT);

    //Realizamos la consulta al servidor para obtener las clases a la que esta inscripto y no el socio
    $sql = "SELECT 
            v.id_clase,
            v.Nombre_Clase,
            v.dia_semana,
            v.hora_inicio,
            v.hora_fin,
            v.Nombre_Entrenador,
            v.Apellido,
            v.Detalle,
            v.Precio_Entrenamiento,
            v.video,
            CASE WHEN i.id_inscripcion IS NOT NULL THEN 'Inscripto' ELSE 'No inscripto' END AS estado
        FROM vistainfoclases v
        LEFT JOIN inscripciones_clases i 
            ON v.id_clase = i.id_clase 
            AND i.id_cliente = (
                SELECT c.id_cliente
                FROM usuarios u 
                INNER JOIN clientes c ON u.id_usuario = c.id_usuario_fk
                WHERE c.id_usuario_fk = :IdUser
            );
        ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':IdUser', $idUser);
    $stmt->execute();
    $resultClass = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Verificamos si la consulta tuvo exito
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, 
        "message" => "Clases encontradas", 
        "dataClass" => $resultClass]);
        exit;
    } else {
        echo json_encode(["success" => false, 
        "message" => "No se encontraron clases"]);
        exit;
    }

}catch(Exception $e){
    echo json_encode(["success" => false, 
    "message" => "Error al obtener las clases" . $e->getMessage()]);
}