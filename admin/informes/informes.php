
<?php
//Seccion valida para el cliente hasta que cierre el navegador
session_set_cookie_params([
    'lifetime' => 0,        // 0 = se borra cuando se cierra el navegador
    'path' => '/',
    'domain' => '',         // dejalo vacío para localhost
    'secure' => false,      // true si usás HTTPS
    'httponly' => true,     // seguridad extra
    'samesite' => 'Lax'     // evita CSRF
]);
//Necesario para que mientrs tenga abierto el navegador pueda aceder al sistema.
session_start();
$_SESSION['acceso_permitido'] = true;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Fortaleza Cross Saenz Peña Chaco">
    <meta name="description" content="Pagina del administrador de Fortaleza Cross">
    <link rel="shortcut icon" href="../../img/fortaleza.png" type="image/x-icon">
    <title>Fortaleza Cross - Informes</title>
</head>

<body>
    <h1>
        Informes 
    </h1>    
</body>
</html>