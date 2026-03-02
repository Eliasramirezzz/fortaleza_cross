<?php
// Este archivo contiene configuraciones generales del servidor y se debe ubicar en la raíz del proyecto

// Detecta automáticamente la ruta base del proyecto (ej: http://localhost/FORTALEZA_CROSS/)
function base_url() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];

    // El nombre del script que se está ejecutando, ej: /FORTALEZA_CROSS/login/registrar/registrar.php
    $script_name = $_SERVER['SCRIPT_NAME'];

    // Eliminamos el nombre del archivo para quedarnos con la ruta: /FORTALEZA_CROSS/login/registrar/
    $path = str_replace(basename($script_name), '', $script_name);

    // Tomamos solo el primer segmento del path, que es el nombre de la carpeta raíz
    $parts = explode('/', trim($path, '/'));
    $root_folder = isset($parts[0]) ? '/' . $parts[0] . '/' : '/';

    return $protocol . $host . $root_folder;
}

// Definimos la constante BASE_URL para usarla en cualquier archivo PHP
define('BASE_URL', base_url());
?>
