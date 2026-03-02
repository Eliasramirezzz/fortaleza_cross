<?php
// Carga PHPMailer
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/PHPMailer-master/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json");
require_once '../../conexion/conexion.php'; //conexion a 
// la BD
require_once 'config_email.php'; //Configuracion de ip (Solo eso por ahora).

session_start();

try {
    $datos = json_decode(file_get_contents("php://input"), true);

    if (!isset($datos['token'], $datos['email']) || empty($datos['token']) || empty($datos['email'])) {
        echo json_encode(["success" => false, "message" => "No se ha proporcionado un email o token."]);
        exit;
    }
    //Sanitizar
    $token = filter_var($datos['token'], FILTER_SANITIZE_STRING);
    $email = filter_var($datos['email'], FILTER_SANITIZE_EMAIL);

    $stmt = $pdo->prepare("SELECT * FROM registro_pendiente WHERE email = :email AND token = :token");
    $stmt->execute([':email' => $email, ':token' => $token]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($resultados)) {
        echo json_encode(["success" => false, "message" => "Token o email no válido."]);
        exit;
    }
    //=== Insercion temporal en la tabla registro_pendiente===
    //Verficar que todos los capos no esten vacios.
    if (!isset($datos['nombre'], $datos['apellido'], $datos['dni'], $datos['telefono'], $datos['fecha_nacimiento'], $datos['usuario'], $datos['password']) || empty($datos['nombre']) || empty($datos['apellido']) || empty($datos['dni']) || empty($datos['telefono']) || empty($datos['fecha_nacimiento']) || empty($datos['usuario']) || empty($datos['password'])){
        echo json_encode(["success" => false, "message" => "verifique que todos los campos esten completo."]);
    }
    //Sanitizar datos
    $nombre = filter_var($datos['nombre'], FILTER_SANITIZE_STRING);
    $apelido = filter_var($datos['apellido'], FILTER_SANITIZE_STRING);
    $dni = filter_var($datos['dni'], FILTER_SANITIZE_STRING);
    $telefono = filter_var($datos['telefono'], FILTER_SANITIZE_STRING);
    $fecha_nacimiento = filter_var($datos['fecha_nacimiento'], FILTER_SANITIZE_STRING);
    $usuario = filter_var($datos['usuario'], FILTER_SANITIZE_STRING);
    $password = filter_var($datos['password'], FILTER_SANITIZE_STRING);
    // Hashear la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    //Este creado me sirbe para controlar el tiempo en que se envia el enlace de confirmacion y vrifica que no pase mas de 2 min.
    $creadoEn = date('Y-m-d H:i:s');

    // Actualizacion en la tabla registro_pendiente.
    $sql = "UPDATE registro_pendiente SET nombre=:nombre, apellido=:apellido, dni=:dni, telefono=:telefono, fecha_nacimiento=:fecha_nacimiento, usuario=:usuario, clave=:password, creado_en=:creado_en WHERE email=:email and token=:token";
    //Verificacion de la actualizacion
    $stmt = $pdo->prepare($sql);
    $resultado = $stmt->execute([
        ':email' => $email,
        ':token' => $token,
        ':nombre' => $nombre,
        ':apellido' => $apelido,
        ':dni' => $dni,
        ':telefono' => $telefono,
        ':fecha_nacimiento' => $fecha_nacimiento,
        ':usuario' => $usuario,
        ':password' => $passwordHash, // La contraseña ha sido hasheada
        ':creado_en' => $creadoEn
    ]);
    if (!$resultado) { //Verificar como salio la actualizacion
        echo json_encode(["success" => false, "message" => "Error al actualizar los datos en la tabla registro_pendiente."]);
        exit;
    }

    //Si todo sale bien pasamos a enviar el url de confirmacion al gmail
    // Configurar PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tu_correo';
    $mail->Password = 'tu_contrasena'; // ¡Nunca subas esto a Git!
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('tu_correo', 'Fortaleza Cross');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Confirma tu correo para completar el registro';

    // URL de confirmación
    $host = $_SERVER['HTTP_HOST'];
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    // Defino una url para una red local.
    $urlConfirmacion = BASE_URL . "/Fortaleza_Cross/login/registrar/confirmar.php?token=$token&email=" . urlencode($email);

    // Cargar plantilla
    $rutaPlantilla = __DIR__ . '/plantilla_correo.php';
    if (!file_exists($rutaPlantilla)) {
        echo json_encode(["success" => false, "message" => "No se encontró la plantilla HTML."]);
        exit;
    }
    $plantilla = file_get_contents($rutaPlantilla);
    $plantilla = str_replace('{{URL_CONFIRMACION}}', $urlConfirmacion, $plantilla);
    $plantilla = str_replace('fortaleza.png', "$protocolo://Fortaleza_Cross/login/registrar/fortaleza.png", $plantilla);
        
    $mail->Body = $plantilla;
    $mail->send();

    echo json_encode(["success" => true, "message" => "Confirme el correo para completar el registro. Le hemos enviado un correo a su cuenta"]); //Confirmamos que salio bien en el servidor y le enviamos un msj al cliente esperando que confirme en su gmail.
    exit;

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error al enviar correo: " . $e->getMessage()]);
    exit;
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error de base de datos: " . $e->getMessage()]);
    exit;
}
?>
