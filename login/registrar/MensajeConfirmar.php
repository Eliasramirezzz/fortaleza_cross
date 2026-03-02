<?php
// Carga el archivo de configuración del proyecto
$projectFolder = explode("/", $_SERVER['PHP_SELF'])[1]; // Ej: "FORTALEZA_CROSS"
require_once($_SERVER['DOCUMENT_ROOT'] . "/$projectFolder/config.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="fortaleza.png" type="image/x-icon">
    <link rel="stylesheet" href="registrar.css">
    <link rel="stylesheet" href="<?= rtrim(BASE_URL, '/') ?>/bin/mensaje_modal.css"> <!-- Para mostrar mensaje modal -->
    <title>Gracias por Registrarse - Fortaleza Gym</title>
</head>
<style>
    .hh1{
        text-align: center;
        color: black;
    }
    .pp{
        text-align: center;
        color: black;
    }
</style>
<body>
    <h1 class="hh1">Fortaleza Cross</h1>
    <p class="pp">Puedes salir de esta venta pagina y volver a la pagina principal</p>
    <script src="<?= rtrim(BASE_URL, '/') ?>/bin/modal_controller.js"></script> <!-- Para mostrar mensaje modal -->
    <script src="MensajeConfirmar.js" defer></script>
</body>
</html>