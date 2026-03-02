
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
    <meta name="description" content="Pagina de configuracion de Fortaleza Cross">
    <link rel="shortcut icon" href="../img/fortaleza.png" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Enlaces a Css-->
    <link rel="stylesheet" href="../bin/mensaje_modal.css"> 
    <link rel="stylesheet" href="configuracion.css">
    <link rel="stylesheet" href="../css/home_admin/seccionTarjetaMembresia.css">
    <link rel="stylesheet" href="../css/home_admin/modalConfirmar.css">
    <title> Configuracion - Fortaleza Cross</title>
</head>

<body>
    <header class="header">
        <div class="logo-container" id="logo-container">
            <img src="../img/fortaleza.png" alt="Logo Fortaleza" class="logo" id="logo">
        </div>

        <button class="menu-toggle" id="menuToggle">&#9776;</button>

        <nav class="nav" id="navMenu">
            <ul class="nav-list">
                <li class="nav-item"><a href="home_admin.php" class="nav-link">Volver al panel de adminstracion</a></li>
            </ul>
        </nav>

        <div class="header-icons">
            <div class="user-menu">
                <a href="#" class="login-link" id="userMenuToggle">
                    <span id="userIconContainer" class="user-icon-container">
                        <i class="fas fa-user-circle icon login-icon" id="userIcon"></i>
                    </span>
                    <span class="login-text" id="NameAdmin"></span>
                </a>
                <ul class="dropdown-user" id="dropdownUser">
                    <li><a href="../login/login.php">Cambiar Usuario</a></li>
                    <li><a href="../home/index.php">Volver al Home Público</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main class="config-main-content">
        <div class="main-header">
            <h1 id="TitlePanel">Panel de Configuracion</h1>
        </div>
    
        <section class="seccion-gestion-membresia">
            <h2>Gestión de Membresías (Registro, Edición y Eliminación) 🛠️</h2>

            <div class="contenedor-tarjeta-gestion">

                <div class="tarjeta-membresia tarjeta-gestion">
                    <h3 class="titulo-tarjeta">Acción: <span id="modo-accion">Registrar Nueva</span></h3>

                    <form id="form-membresia">
                        
                        <div class="campo-formulario campo-select-control">
                            <label for="id_membresia">Seleccionar Membresía:</label>
                            <select name="id_membresia" id="id_membresia" class="select-option">
                            </select>
                        </div>
                        
                        <div class="campo-formulario">
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" placeholder="Ej: Tarjeta Premium" maxlength="100">
                        </div>
                        
                        <div class="campo-formulario">
                            <label for="badge">Badge/Etiqueta Superior:</label>
                            <input type="text" id="badge" name="badge" placeholder="Ej: Básico" maxlength="50">
                        </div>

                        <div class="campo-formulario">
                            <label for="precio">Precio:</label>
                            <input type="number" id="precio" name="precio" placeholder="Ej: 50.00" min="0" step="0.01">
                        </div>
                        
                        <div class="campo-formulario">
                            <label for="duracion_dias">Duración (días):</label>
                            <input type="number" id="duracion_dias" name="duracion_dias" placeholder="Ej: 30" min="1" step="1">
                        </div>

                        <div class="campo-formulario">
                            <label for="descripcion">Descripción Corta (Pago incluye):</label>
                            <textarea id="descripcion" name="descripcion" rows="2" placeholder="Ej: Pago Mensual Incluye: ..."></textarea>
                        </div>

                        <div class="campo-formulario">
                            <label for="caracteristicas">Características (Separadas por **;**):</label>
                            <textarea id="caracteristicas" name="caracteristicas" rows="4" placeholder="Ej: Sin costo de lanzamiento; Sin límites de socios"></textarea>
                        </div>

                        <div class="campo-formulario">
                            <label for="descripcion_larga">Descripción Larga (Detalles del plan):</label>
                            <textarea id="descripcion_larga" name="descripcion_larga" rows="4" placeholder="Ej: Plan mensual. Se realiza un entrenamiento semanal..."></textarea>
                        </div>
                        
                        <div class="campo-formulario-checks">
                            <input type="checkbox" id="activa" name="activa" value="1" checked>
                            <label for="activa"> Membresía Activa</label>
                        </div>
                        
                        <div class="campo-formulario-checks">
                            <input type="checkbox" id="especial" name="especial" value="1">
                            <label for="especial"> Membresía Especial (Destacada)</label>
                        </div>
                        
                        <button type="submit" id="main-action-button" class="boton-accion-principal">Registrar Membresía</button>
                    </form>

                </div>

                <div class="contenedor-preview-tarjeta">
                    <h3 class="titulo-preview">Ejemplo</h3>
                    <div class="tarjeta-membresia tarjeta-preview">
                        <img src="../img/tarjeta_muestra.png" alt="No se puedo cargar la imagen de previsualización" class="imagen-preview">
                    </div>
                </div>
            </div>

            <div class="contenedor-estado-accion">
                <button type="button" class="boton-estado" id="btnEstadoMembresia" disabled>Desactivar Membresía</button>
                </button>
                <p class="nota-eliminar">Utiliza el botón de **Seleccionar Membresía** para elegir cuál modificar o desactivar.</p>
            </div>

            <!-- Este modal de confirmación se muestra cuando se registra un socio al plan-->
            <div id="confirmRegisterOverlay" class="cr-overlay" role="dialog" aria-modal="true" aria-hidden="true">
                <div class="cr-modal" tabindex="-1">
                    <div class="cr-header">
                        <div class="cr-icon" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="white" fill-opacity="0.15"/>
                                <path d="M11 17h2v2h-2zM12 7a3 3 0 00-3 3h2a1 1 0 112 0c0 1-1 1.5-1.8 2.1C10.6 13 10 13.7 10 15h2c0-.5.2-1 .6-1.4.7-.5 1.8-1.2 1.8-2.6A3 3 0 0012 7z" fill="white"/>
                            </svg>
                        </div>
                        <h3 id="cr-title" class="cr-title"></h3>
                    </div>
                    <div id="cr-msg" class="cr-message"></div>
                    <div class="cr-actions">
                        <button type="button" class="cr-btn cr-no" id="cr-noBtn">No</button>
                        <button type="button" class="cr-btn cr-yes" id="cr-yesBtn">Sí</button>
                    </div>
                </div>
            </div>
        </section>
    
    </main>

    <footer>
        <div class="footer-contenido">
            <div class="footer-columna footer-marca">
                <a href="#TitlePanel" class="footer-logo-inferior">
                    <img src="../img/fortaleza.png" alt="Logo Fortaleza Cross"> </a>
                <p>Tu viaje hacia una vida más fuerte y saludable.</p>
                <div class="redes-sociales-footer">
                    <a href="https://www.facebook.com/fortalezacross?rdid=l8JKr2LQ3xDXak9E#" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/fortaleza_box/?hl=es" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-columna footer-contacto">
                <h3>Contáctanos</h3>
                <p><i class="fas fa-map-marker-alt"></i> Dirección:  Guemes Calle 8 entre 1 y 3 del centro.</p>
                <p><i class="fas fa-phone-alt"></i> Teléfono: +54 36-44  2222-96</p>
                <p><i class="fas fa-envelope"></i> Email: fortalezacross@gmail.com</p>
            </div>

            <div class="footer-columna footer-enlaces-rapidos">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="../data/politica_privacidad.html" target="_blank">Política de Privacidad</a></li>
                    <li><a href="../data/terminos_condiciones.html" target="_blank">Términos y Condiciones</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-copyright">
            <p>&copy; 2025 Fortaleza Cross. Todos los derechos reservados.</p>
            <span class="desarrollador"> <!--Desarrollado por
                <a href="https://github.com/Eliasramirezzz" target="_blank">Ramirez Elias</a>  -->
                <img src="../img/LogoERH.png" alt="Mi marca" width="100px">
            </span>
        </div>
    </footer>

    <script src="../bin/modal_controller.js"></script>
    <script src="configuracion.js" defer></script>
</body>   
</html>