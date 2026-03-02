<?php
// Hacer que la cookie de sesión expire al cerrar el navegador
session_set_cookie_params([
    'lifetime' => 0,        // 0 = se borra cuando se cierra el navegador
    'path' => '/',
    'domain' => '',         // dejalo vacío para localhost
    'secure' => false,      // true si usás HTTPS
    'httponly' => true,     // seguridad extra
    'samesite' => 'Lax'     // evita CSRF
]);
//Necesario para poder usar la pagina 
session_start();
if (!isset($_SESSION['acceso_permitido']) || $_SESSION['acceso_permitido'] !== true) {
    header("Location: ../home/index.php"); // o donde esté tu index
    exit();
}
$seccion = $_GET['seccion'] ?? 'nada';
// lógica de contenido
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!--Metadatos para la pagina-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Fortaleza Cross Saenz Peña Chaco">
    <meta name="description" content="Seccion de Fortaleza Cross">
    <meta name="author" content="Equipo de desarrollo Fortaleza Cross">
    <link rel="shortcut icon" href="../img/fortaleza.png" type="image/x-icon">

    <!--Fuentes de letras modernas de google-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Iconos para contactos-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Estilos para la pagina-->
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../bin/mensaje_modal.css">
    <title></title>
</head>

<body>
    <!-- HEADER -->
    <header class="header">
        <div class="logo-container" id="volver_home">
            <img src="../img/fortaleza.png" alt="Logo Fortaleza" class="logo" id="logo">
        </div>

        <nav class="nav">
            <ul class="nav-list">
                <li class="submenu-personalizado">
                    <a href="#">PERSONALIZADO</a>
                    <ul class="dropdown-personalizado">
                        <li><a href="../home/secciones.php?seccion=gap">Gap</a></li>
                        <li><a href="../home/secciones.php?seccion=funcional">Funcional</a></li>
                        <li><a href="../home/secciones.php?seccion=musculacion">Musculacion</a></li>
                        <li><a href="../home/secciones.php?seccion=levantamiento">Levantamiento</a></li>
                    </ul>
                </li>
                <li><a href="../home/secciones.php?seccion=horarios">HORARIOS</a></li>
                <li><a href="../home/secciones.php?seccion=membresias">MEMBRESIAS</a></li>
                <li><a href="../home/secciones.php?seccion=ubicacion">UBICACION</a></li>
                <!--<li><a href="../home/secciones.php?seccion=tienda">TIENDA</a></li> -->
                <li><a href="../home/secciones.php?seccion=eventos">EVENTOS</a></li>
                <li><a href="../home/secciones.php?seccion=PF">PREGUNTAS FRECUENTES</a></li>
            </ul>
        </nav>

        <div class="header-icons">
            <a href="../login/login.php" title="Iniciar Sesión" class="login-link">
                <i class="fas fa-user-circle icon login-icon"></i> 
                <span class="login-text">Iniciar Sesión</span>
            </a>
        </div>
    </header>

    <main id="contenedor">
        
    </main>

    <script src="../bin/modal_controller.js"></script>
    <script src="../js/header_responsive.js"></script>
    <script src="../home/section.js" defer></script>
</body>
</html>