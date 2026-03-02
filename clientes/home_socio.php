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
    <!--Metadatos para la pagina-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Fortaleza Cross Saenz Peña Chaco">
    <meta name="description" content="Pagina de clientes de Fortaleza Cross">
    <link rel="shortcut icon" href="../img/fortaleza.png" type="image/x-icon">

    <!--Fuentes de letras modernas de google-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!--Enlaces a Css-->
    <link rel="stylesheet" href="../clientes/home_socio.css">
    <link rel="stylesheet" href="../css/home_socios/estados_socios.css">
    <link rel="stylesheet" href="../css/home_socios/tablaPlanesSocios.css">
    <link rel="stylesheet" href="../css/home_socios/publicidadSocio.css">
    <link rel="stylesheet" href="../css/home_socios/modalPagos.css">
    <link rel="stylesheet" href="../css/home_socios/modalDetalleClases.css">
    <link rel="stylesheet" href="../bin/mensaje_modal.css"> <!-- Diseño de los mensajes-->

    <title>Fortaleza Cross - Cliente/Socio </title>
</head>

<body>
    <header class="header">
        <div class="logo-container" id="logo-container">
            <img src="../img/fortaleza.png" alt="Logo Fortaleza" class="logo" id="logo">
        </div>

        <!-- Botón hamburguesa visible solo en móvil -->
        <button class="menu-toggle" id="menuToggle">&#9776;</button>

        <nav class="nav" id="navMenu">
            <ul class="nav-list">
            <li class="submenu-tipo-plan-cliente">
                <a href="#" class="submenu-toggle">TIPO DE PLAN</a>
                <ul class="dropdown-plan-clientes" id="dropdownPlanClientes">
                </ul>
            </li>
            <li><a href="#" id="navPagos">PAGOS</a></li>
            </ul>
        </nav>

        <div class="header-icons">
            <div class="user-menu">
                <a href="#" class="login-link" id="userMenuToggle">
                    <span id="userIconContainer" class="user-icon-container">
                        <i class="fas fa-user-circle icon login-icon" id="userIcon"></i>
                    </span>
                <span class="login-text" id="NameUser"></span>
                </a>
                <!-- Submenu del usuario -->
                <ul class="dropdown-user" id="dropdownUser">
                <li><a href="GestionarSocio/GestSocio.php?gestion=CamImg">Cambiar foto</a></li>
                <li><a href="../login/login.php">Cambiar Usuario</a></li>
                <li><a href="../home/index.php">Volver al Home Público</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Modal para realizare el pago-->
    <div id="modalPago" class="modalPago">
        <div class="modal-content-pago">
            <span class="closePago" id="closePago">&times;</span>
            <h2>Mensaje de Pago</h2>

            <textarea id="mensajePago" rows="8" placeholder="Escriba aquí su mensaje..."></textarea>

            <select id="planPago">
            </select>

            <button id="enviarPago">
                Enviar
                <img src="../img/icoWhat.png" alt="" width="30px">
            </button>
        </div>
    </div>


    <main>
        <!-- Contenedor Estado de Suscripción -->
        <section class="container-estado-suscripcion" id="container-estado-suscripcion">
            <!-- Nombre del socio -->
            <div class="estado-nombre">
                <span class="ENvalor" id="ENvalor"></span> <!-- esto después lo cargos con PHP -->
            </div>
            <!-- Estado de su plan -->
            <div class="estado-plan">
                <span class="label">Estado de su plan:</span>
                <span id="EstadoPlan" class="valor estado">Al día</span> 
            </div>
        </section>

        <div id="modalSocio" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close-btn" id="closeModal">&times;</span>
                    <h2 class="modal-title">Detalle del Socio</h2>
                </div>
                <div class="modal-body">
                    <div class="socio-info" id="socio-info">
                        <!-- aca se carga contenido dinamico -->
                    </div>
                    <hr class="divider">
                    <div class="plan-info" id="plan-info">
                        <!-- aca se carga contenido dinamico -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Mensaje de Bienvenida-->
        <section class="container-mensaje-bienvenida">
            <h2 class="titulo-bienvenida" id="titulo-bienvenida"></h2>
            <p class="texto-bienvenida">Estamos listos para tu entrenamiento de hoy. ¡No te rindas! 💪</p>
        </section>
        
        <!-- Contenido Tabla de Planes inscripto -->
        <section class="container-tabla-planes">
            <h2>Planes Disponibles</h2>

            <table class="tabla-planes">
                <thead>
                    <tr>
                        <th>Nombre del Plan</th>
                        <th>Duracion (en Dia)</th>
                        <th>Precio</th>
                        <th>Ver clases</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyPlanes">
                    <!-- aca se carga contenido dinamico -->
                </tbody>
            </table>
        </section>

        <!-- Modal Detalles Planes -->
        <div id="modalDetalleClase" class="modal-detalle"> 
            <div class="modal-detalle-content">
                <span id="closeDetalleClase" class="close">&times;</span>
                <h2 id="tituloModal"></h2> <div id="contenidoPlan"> 
                    <p>Horarios y clases incluidas en este plan:</p>
                    
                    <table class="modal-detalle-table">
                        <thead>
                            <tr>
                                <th>Clase</th>
                                <th>Día</th>
                                <th>Horario Disponibles</th>
                                <th>Duracion</th>
                                <th>Entrenador</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyClases"></tbody>
                    </table>
                </div>
                
            </div>
        </div>
        
        <!-- Contenido para publicidad, eventos o información -->
        <section class="container-publicidad">
            <h2 class="titulo-publicidad">Noticias, Eventos y Publicidad</h2>

            <div class="contenido-publicidad">
                <div class="scroll-wrapper">
                    <button class="btn-scroll left" id="scrollLeft">&#9664;</button>
                
                    <div class="tarjetas-scroll" id="tarjetasScroll">
                        <article class="tarjeta">
                            <h3>Nuestro instagram</h3>
                            <p>¡Segúinos en instagram! Aqui encontraras informacion de nuestros eventos y promociones. </p>
                            <img src="../img/imgPublicidad/public1.png" alt="Publicidad de seguirnos">
                            <a href="https://www.instagram.com/fortaleza_box/" target="_blank">Leer más</a>
                            <h3 style="margin-top: 20px;">Nuestro facebook</h3>
                            <p>¡Segúinos en facebook! Aqui encontraras informacion de nuestros eventos y promociones. </p>
                            <img src="../img/imgPublicidad/public1b.png" alt="Publicidad de seguirnos">
                            <a href="https://www.facebook.com/fortalezacross" target="_blank"> Leer más</a>

                        </article>

                        <article class="tarjeta">
                            <h3>El ejercicio al aire libre aporta beneficios únicos</h3>
                            <p>La integración de la naturaleza en la rutina de actividad física es una gran oportunidad para lograr una vida más equilibrada y saludable. Un estudio de Harvard respalda esto, mostrando que quienes entrenan cerca de áreas verdes potencian su respuesta inmunitaria. Además, la práctica al aire libre aumenta la liberación de dopamina y serotonina.</p>
                            <img src="../img/imgPublicidad/public2.png" alt="">
                            <a href="https://www.infobae.com/salud/2025/09/10/beneficios-comprobados-de-ejercitarse-bajo-el-sol-segun-la-ciencia/" target="_blank">Leer más</a>
                        </article>

                        <article class="tarjeta">
                            <h3>Cuánto tiempo se tarda en formar el hábito de ir al gimnasio</h3>
                            <p>Aunque se cree que 21 días son suficientes para crear un hábito, la ciencia demuestra que el promedio real para integrar una rutina de gimnasio es de seis meses.</p>
                            <img src="../img/imgPublicidad/public3.png" alt="">
                            <p>La clave, según los expertos, es la paciencia y la progresión gradual para evitar la frustración y el abandono.</p>
                            <a href="https://www.infobae.com/salud/2025/09/09/cuanto-tiempo-se-tarda-en-formar-el-habito-de-ir-al-gimnasio-segun-la-ciencia/" target="_blank">Leer más</a>
                        </article>
                        
                        <article class="tarjeta">
                            <h3>Estos dos entrenamientos te mantienen sano según un experto</h3>
                            <p>El Dr. Mark Hyman, experto en longevidad, destaca el poder de dos ejercicios: el entrenamiento de alta intensidad por intervalos (HIIT) y el de fuerza. Juntos, renuevan las "fábricas de energía" del cuerpo, previniendo enfermedades crónicas sin necesidad de pasar horas en el gimnasio. Puedes practicar ambos con solo unas pocas sesiones semanales.</p>
                            <img src="../img/imgPublicidad/public4.png" alt="">
                            <a href="https://timesofindia.indiatimes.com/life-style/health-fitness/health-news/these-two-workouts-can-keep-illnesses-at-bay-according-to-a-top-doctor/articleshow/123870622.cms" target="_blank">Leer más</a>
                        </article>

                        <article class="tarjeta">
                            <h3>El simple hábito que te ayuda a vivir más según Harvard</h3>
                            <p>Un estudio de Harvard recomienda los pequeños movimientos diarios para alargar la vida. </p>
                            <img src="../img/imgPublicidad/public5.png" alt="">
                            <p>Hábitos sencillos como subir escaleras, estirarse en el escritorio o caminar al hablar por teléfono pueden reducir el sedentarismo y mejorar la salud del corazón. La clave no está en rutinas extenuantes, sino en la consistencia de pequeñas acciones.</p>
                            
                            <a href="https://www.infobae.com/salud/2025/09/14/los-habitos-sencillos-que-recomienda-harvard-para-alargar-la-vida-sin-ir-al-gimnasio/" target="_blank">Leer más</a>
                        </article>
                    </div>

                    <button class="btn-scroll right" id="scrollRight">&#9654;</button>
                </div>
            </div>
        </section>
        
        <a href="https://api.whatsapp.com/send?phone=543644222296&text=Hola,%20me%20gustaría%20saber%20más%20sobre%20sus%20clases." target="_blank" class="whatsapp-float-btn" id="whatsappLink" title="Chatea con nosotros por WhatsApp">
            <img class="ico_what" src="../img/icoWhat.png" alt="Icono_Whatsapp" width="50px">
        </a>
    </main>

    <!--FOOTER-->
    <footer>
        <div class="footer-contenido">
            <div class="footer-columna footer-marca">
                <a href="#container-estado-suscripcion" class="footer-logo-inferior">
                    <img src="../img/fortaleza.png" alt="Logo Fortaleza Cross"> </a>
                <p>Tu viaje hacia una vida más fuerte y saludable.</p>
                <div class="redes-sociales-footer">
                    <a href="https://www.facebook.com/fortalezacross?rdid=l8JKr2LQ3xDXak9E#" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/fortaleza_box/?hl=es" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-columna footer-contacto">
                <h3>Contáctanos</h3>
                <p><i class="fas fa-map-marker-alt"></i> Dirección:  Guemes Calle 8 entre 1 y 3 del centro.</p>
                <p><i class="fas fa-phone-alt"></i> Teléfono: +54 36-44  2222-96</p>
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
            <span class="desarrollador"> <!-- Desarrollado por
                <a href="https://github.com/Eliasramirezzz" target="_blank">Ramirez Elias</a> --> 
                <img src="../img/LogoERH.png" alt="Mi marca" width="100px">
            </span>
        </div>
    </footer>

    <!-- Script Externos -->
    <script src="home_socio.js" defer></script>
    <script src="../bin/modal_controller.js"></script>

</body>
</html>