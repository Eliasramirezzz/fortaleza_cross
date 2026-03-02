<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Carga PHPMailer
require __DIR__ . '/../../data/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../../data/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/../../data/PHPMailer-master/src/Exception.php';

// api/contacto.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok'=>false,'msg'=>'Método no permitido']); exit;
}

// 1) Captura y saneo
$nombre  = trim($_POST['nombre']  ?? '');
$email   = trim($_POST['correo'] ?? '');
$asunto  = trim($_POST['asunto']  ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');
$hp      = trim($_POST['website'] ?? ''); // honeypot

// 2) Anti-bots: si honeypot viene con algo, respondemos "ok" silencioso
if ($hp !== '') {
    echo json_encode(['ok'=>true,'msg'=>'Gracias']); exit;
}

// 3) Validaciones básicas
$errors = [];
if ($nombre === '' || mb_strlen($nombre) < 2)  $errors[] = 'Nombre inválido';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido';
if ($asunto === '' || mb_strlen($asunto) < 3)  $errors[] = 'Asunto requerido';
if ($mensaje === '' || mb_strlen($mensaje) < 1)$errors[] = 'Mensaje muy corto';

if ($errors) {
    http_response_code(422);
    echo json_encode(['ok'=>false,'msg'=>implode(', ', $errors)]); exit;
}

// 4) PHPMailer
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // ⚠️ Usa variables de entorno o archivo config fuera del webroot
    $mail->Username = getenv('SMTP_USER') ?: 'tu_correo';
    $mail->Password = getenv('SMTP_PASS') ?: 'tu_password_de_app_que_creaste_en_tu_gmail'; // App Password, NO el password normal
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // From = tu cuenta SMTP (dominio tuyo o Gmail). No uses el mail del usuario acá.
    $mail->setFrom($mail->Username, 'Fortaleza Cross (Web)');
    // A quién le llega (entrenador/administrador)
    $mail->addAddress('tu_correo', 'Entrenador'); // poné el real
    // Para que al responder, le responda al usuario
    $mail->addReplyTo($email, $nombre);

    // Contenido
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'N/A';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $bodyHtml = "
        <h2>Nuevo mensaje desde el sitio</h2>
        <ul>
        <li><strong>Nombre:</strong> " . htmlspecialchars($nombre) . "</li>
        <li><strong>Email:</strong> " . htmlspecialchars($email) . "</li>
        <li><strong>Asunto:</strong> " . htmlspecialchars($asunto) . "</li>
        <li><strong>IP:</strong> " . htmlspecialchars($ip) . "</li>
        </ul>
        <p><strong>Mensaje:</strong><br>" . nl2br(htmlspecialchars($mensaje)) . "</p>
        <hr><small>UA: " . htmlspecialchars($ua) . "</small>
    ";

    $mail->isHTML(true);
    $mail->Subject = 'Contacto web: ' . $asunto;
    $mail->Body    = $bodyHtml;
    $mail->AltBody = "Nuevo mensaje:\nNombre: $nombre\nEmail: $email\nAsunto: $asunto\n\nMensaje:\n$mensaje\n\nIP: $ip";

    $mail->send();
    echo json_encode(['ok'=>true,'msg'=>'¡Mensaje enviado!']);
} catch (Exception $e) {
    // Log interno opcional: error_log('Mailer Error: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['ok'=>false,'msg'=>'No se pudo enviar el mensaje. Intenta más tarde.']);
}
