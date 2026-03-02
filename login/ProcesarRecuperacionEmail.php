<?php
// Carga PHPMailer esto siempre va primero
require __DIR__ . '/registrar/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/registrar/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/registrar/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Conexion a la BD y configuracion de ip
require_once '../conexion/conexion.php';
require_once 'registrar/config_email.php'; // Asegúrate de que BASE_URL esté definido aquí

header('Content-Type: application/json'); // Establecer el encabezado de la respuesta como JSON

try{
    // Validar GET y sanitizar
    if (!isset($_GET['email']) || empty($_GET['email'])) {
        echo json_encode(["success" => false, "message" => "No se ha proporcionado un email válido."]);
        exit;
    }
    $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Por favor ingrese un correo válido."]);
        exit;
    }
    // Verificación de que el email exista en la BD
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$usuario) {
        echo json_encode(["success" => false, "message" => "El correo ingresado no existe en Fortaleza Cross."]);
        exit;
    }
    // ====================== GENERACIÓN Y ALMACENAMIENTO DEL TOKEN ======================
    $token = bin2hex(random_bytes(32)); // Genera un token seguro y aleatorio
    $tokenExpiracion = date('Y-m-d H:i:s', strtotime('+2 minutes')); // Expira en 2 minutos

    $stmt = $pdo->prepare("UPDATE usuarios SET token_recuperacion = :token, token_expiracion = :expiracion WHERE email = :email");
    $stmt->execute([
        ':token' => $token,
        ':expiracion' => $tokenExpiracion,
        ':email' => $email
    ]);
    // ====================== CONFIGURACIÓN Y ENVÍO DEL CORREO ======================
    //Si existe debemos enviarle el enlace para verificar que el dueño de la cuenta sea el que esta intentando recuperar la contraseña
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tu_correo';
    $mail->Password = 'tu_contraseña'; // ¡Nunca subas esto a Git!
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('tu_correo', 'Fortaleza Cross');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Solicitud de recuperación de contraseña de Fortaleza Cross';

    // ====================== URL DE RECUPERACIÓN SEGURA ======================
    // La URL de confirmación ahora incluye el token, no el email
    // La ruta ConfirmarRecuperacion.php debe existir
    $urlRecuperacion = BASE_URL . "/Fortaleza_Cross/login/ConfirmarRecuperacion.php?token=" . urlencode($token);
    // ====================== PLANTILLA DE EMAIL DINÁMICA ======================
    // Esta es una plantilla de HTML simple para un email
    $plantilla = file_get_contents(__DIR__ . '/plantilla_email_recuperacion.php');
    $plantilla = str_replace('{{URL_RECUPERACION}}', $urlRecuperacion, $plantilla);

    $mail->Body = $plantilla;
    $mail->send();

    echo json_encode(["success" => true, "message" => "Se ha enviado un enlace de recuperación a tu correo. Por favor, revisa tu bandeja de entrada."]);
    exit;
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "No se pudo enviar el email. Error: {$mail->ErrorInfo}"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>