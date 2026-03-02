<?php
//Encabezado de json.
header("Content-Type: application/json");
//Abro conexion a BD
require_once '../conexion/conexion.php'; //base de datos
//Inicio sesion
session_start();

//Capturamos los datos y evitamos problemas, sin romper la pagina.
try {
    //Obtener los datos del formulario
    $datos = json_decode(file_get_contents("php://input"), true);

    // Verificar que se hayan recibido correctamente los datos
    if (!isset($datos['email']) || !isset($datos['password'])) {
        echo json_encode(["success" => false, "message" => "Por favor ingrese su correo y contraseña"]);
        exit;
    }
    //Sanetizamos los datos
    $email = filter_var($datos["email"], FILTER_VALIDATE_EMAIL);
    $password_ingresada = $datos["password"]; // Guardamos la contraseña en una variable separada para la verificación

    //Mostrar mensaje de error si no ingreso un email y/o password
    if (empty($email) || empty($password_ingresada)) {
        echo json_encode(["success" => false, "message" => "Por favor ingrese su correo y contraseña"]);
        exit;
    }
    //Validacion para email valido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Por favor ingrese un correo valido"]);
        exit;
    }
    //================== Consulta al Servidor ==================
    //Primero Verificamos que el email exista si existe verificamos el password.
    //Creo SQL
    $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
    //Ejecuto SQL en la base de datos y guardo el resultado
    $stmt = $pdo -> prepare($sql);
    //Asigno parametros
    $stmt -> bindParam(":email", $email);
    $stmt -> execute();
    //Obtengo resultado
    $resultado = $stmt -> fetch(PDO::FETCH_ASSOC);

    //Si no hay resultados, significa que el email no existe
    if (!$resultado) {
        echo json_encode(["success" => false, "message" => "No se encontró ningún usuario con el email ingresado."]);
        exit;
    }

    // Obtener el hash de la contraseña de la base de datos
    $password_hash_bd = $resultado["password"];

    // Usar password_verify() para comparar la contraseña ingresada con el hash
    if (password_verify($password_ingresada, $password_hash_bd)) {
        // Si la contraseña es correcta pasamos a obtener los datos del usuario para pasarle al cliente.

        //Creamos sessiones seguras 
        $_SESSION['acceso_permitido'] = true;
        $_SESSION['tipo_usuario'] = $resultado['rol'];
        $_SESSION['id_usuario'] = $resultado['id_usuario'];

        //Creamos la url segun el rol del usuario
        $urlDestino = ($resultado['rol'] === 'cliente')
        ? '../clientes/home_socio.php'
        : '../admin/home_admin.php'; #Operador ternario: (condicion) ? if : else

        //  Obtener la URL de la foto o usar una por defecto
        $fotoUrl = $resultado['foto'] ?? 'Fortaleza_Cross/img/ImgUser/user_default.png'; // Usa la ruta real a tu imagen por defecto

        //Pasamos los datos al frontend
        echo json_encode([
            "success" => true, 
            "message" => "Usuario encontrado.", 
            "rol" => $resultado['rol'],
            "idUser" => $resultado['id_usuario'],
            "fotoUrl" => $fotoUrl,
            "redirect" => $urlDestino]);
        exit;

    } else {
        // La contraseña es incorrecta
        echo json_encode(["success" => false, "message" => "Contraseña incorrecta."]);
        exit;
    }

}catch( PDOException $e){
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}
?>