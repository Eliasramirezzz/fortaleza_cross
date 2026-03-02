<?php
//Vamos a controlar que el usuario si o si hay pasado por home/index.php si no, no lo dejara entrar entrar a la pagina que este intentado manipulando la url.
// Hacer que la cookie de sesión expire al cerrar el navegador
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
    <meta name="description" content="Pagina principal Fortaleza Cross">
    <link rel="shortcut icon" href="../img/fortaleza.png" type="image/x-icon">

    <!--Fuentes de letras modernas de google-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Iconos para contactos-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!--Enlaces a Css-->
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../home/index.css">
    <link rel="stylesheet" href="../css/carrusel_gifs.css">
    <link rel="stylesheet" href="../css/tarjeta_wod.css">
    <link rel="stylesheet" href="../css/form_contacto.css">
    <link rel="stylesheet" href="../css/preguntas_frecuentes.css">
    <link rel="stylesheet" href="../css/acerca_nosotro.css">

    <title>Fortaleza Cross - Bienvenidos</title>
</head>

<body>
    <!-- HEADER -->
    <header class="header">
        <div class="logo-container" id="logo-container">
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
                <!-- <li><a href="../home/secciones.php?seccion=tienda">TIENDA</a></li> -->
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

    <!--MAIN-->
    <main>
        
        <!-- Carrusel de Gifs-->
        <section class="carousel-container-gifs">
            <div class="carousel-gifs" id="carousel-gifs">
                <img src="../img/carrusel/carr1.png" alt="IMAGEN 1" data-name="carr1">
                <img src="../img/carrusel/carr2.png" alt="IMAGEN 2" data-name="carr2">
                <img src="../img/carrusel/carr3.png" alt="IMAGEN 3" data-name="carr3">
            </div>
            <div class="indicators-gifs" id="indicators-gifs"></div>  
        </section>

        <!--Presentación de Fortaleza Cross-->
        <section class="presentacion_fc">
            <h1 class="bienvenidos" id="bienvenidos">¡Bienvenidos a Fortaleza Cross!</h1>
            <span class="text_presentacion">
                Prepárate para transformar tu cuerpo y mente. En Fortaleza Cross, no solo entrenamos músculos, ¡construimos una comunidad imparable! Aquí encontrarás el ambiente perfecto para superar tus límites, alcanzar tus objetivos y descubrir la verdadera fuerza que llevas dentro.

                ¿Listo/a para el desafío? Explora nuestra página para conocer nuestras clases, horarios y todo lo que te ofrecemos para iniciar tu camino hacia una vida más activa y saludable. ¡Te esperamos para que te conviertas en tu versión más fuerte!
            </span>
        </section>

        <!--divisor-->
        <div class="divisor"></div>

        <!--Publicidad-->
        <div class="publicidad-fortaleza">
            <img src="../img/publicidad_fortaleza.png" alt="Publicidad Fortaleza Cross - ¡No te pierdas nuestras ofertas!">
        </div>

        <!--divisor-->
        <div class="divisor"></div>

        <!--Crossfit general-->
        <section id="seccionCrossFitGeneral">
            <h2 class="titulo-seccion" id="titulo-seccion">¡El WOD de la Semana en Fortaleza Cross!</h2>
            <div class="contenedor-wod-semanal">

                <article class="tarjeta-wod" id="wod-lunes">
                    <h3 class="dia-tarjeta">Lunes</h3>

                    <div class="contenido-tarjeta">
                        <div class="detalles-wod">
                            <p class="etiqueta-detalle">Entrada en calor: 3 rondas</p>
                            <ul class="lista-ejercicios">
                                <li>Ejercicio 1:15 Deadlifts (con ketbell)</li>
                                <li>Ejercicio 2:10 push press por brazo(con ketbell)</li>
                                <li>Ejercicio 3:15 sit up(con disco)</li>
                            </ul>
                            <p class="etiqueta-detalle">CORE(zona media) : 3 rondas</p>
                            <ul class="lista-ejercicios">
                                <li>Ejercicio 1:20 hollow rock (con disco)</li>
                                <li>Ejercicio 20 :V-ups </li>
                                <li>Ejercicio 3:4 mountan climbers </li>
                            </ul>
                            
                            <!-- acá se cargará lo extra -->    
                            <div class="extra-detalle"></div>
                        </div>

                        <div class="extra-wod"></div>
                        <a id="WODbtn-lunes" class="btn-info-wod" data-dia="lunes">Ver Detalles Completos</a>
                    </div>
                </article>

                <article class="tarjeta-wod" id="wod-martes">
                    <h3 class="dia-tarjeta">Martes</h3>

                    <div class="contenido-tarjeta">
                        <p class="tipo-wod">Tipo: Entrada en calor: AMRAP(10 minutos) </p>
                        <ul class="lista-ejercicios">
                            <li>Ejercicio 1:10 burpes</li>
                            <li>Ejercicio 2:10 push ups </li>
                            <li>Ejercicio 3:15 god morning </li>
                        </ul>
                        <p class="tipo-wod">Bloque de Fuerza :back squat(sentadilla con la barra atras) </p>
                        <ul class="lista-ejercicios">
                            <li>Ejercicio 1:5x5 con el 50% del rm</li>
                            <li>Ejercicio 2:3x5 con el 60% del rm </li>
                            <li>Ejercicio 3:3x4 con el 70%</li>
                            <li>Ejercicio 3:3x3 con el 80%</li>
                            <li>Ejercicio 3:3x1 con el 90%</li>
                        </ul>

                        <!-- acá se cargará lo extra -->    
                        <div class="extra-detalle"></div>
                    </div>

                    <div class="extra-wod"></div>
                    <a id="WODbtn-martes" class="btn-info-wod" data-dia="martes">Ver Detalles Completos</a>
                </article>

                <article class="tarjeta-wod" id="wod-miercoles">
                    <h3 class="dia-tarjeta">Miércoles</h3>
                    
                    <div class="contenido-tarjeta">
                        <p class="tipo-wod">Entrada en calor:3 rondas</p>
                        <div class="detalles-wod">
                            <p class="etiqueta-detalle">Ejercicios:</p>
                            <ul class="lista-ejercicios">
                                <li>Ejercicio 1:15 squat jump </li>
                                <li>Ejercicio 2:walk out 7 </li>
                                <li>Ejercicio 2:15wall ball </li>
                            </ul>
                            <p class="tipo-wod">core :tabata 8 rondas(20 segundos de trabajo y 10 segundos de descanso)</p>
                            <!-- acá se cargará lo extra -->    
                            <div class="extra-detalle"></div>
                        </div>
                        <div class="extra-wod"></div>
                        <a id="WODbtn-miercoles" class="btn-info-wod" data-dia="miercoles">Ver Detalles Completos</a>
                    </div>
                </article>

                <article class="tarjeta-wod" id="wod-jueves">
                    <h3 class="dia-tarjeta">Jueves</h3>

                    <div class="contenido-tarjeta">
                        <p class="tipo-wod">Entrada en calor : 5 rondas</p>
                        <div class="detalles-wod">
                            <p class="etiqueta-detalle">Ejercicios:</p>
                            <ul class="lista-ejercicios">
                                <li>Ejercicio 1: 5squat(peso corporal)</li>
                                <li>Ejercicio 2:10 sit ups</li>
                                <li>Ejercicio 3:10 yoga push ups</li>
                                <li>Ejercicio 4:5 suicidios(ida y vuelta:1)</li>
                            </ul>
                            <!-- acá se cargará lo extra -->    
                            <div class="extra-detalle"></div>
                        </div>
                    </div>
                    <div class="extra-wod"></div>
                    <a id="WODbtn-jueves" class="btn-info-wod" data-dia="jueves">Ver Detalles Completos</a>
                </article>

                <article class="tarjeta-wod" id="wod-viernes">
                    <h3 class="dia-tarjeta">Viernes</h3>

                    <div class="contenido-tarjeta">
                        <p class="tipo-wod">Entrada en calor : escalera desendente del 10 al 1 </p>
                        <div class="detalles-wod">
                            <p class="etiqueta-detalle">Ejercicios:</p>
                            <ul class="lista-ejercicios">
                                <li>Ejercicio 1: suicidios</li>
                                <li>Ejercicio 2: burpes</li>
                                <li>Ejercicio 3: step box</li>
                                <li>Ejercicio 4: wall ball lungues( 1 estocada + 1 wall ball=1)</li>
                            </ul>
                        <!-- acá se cargará lo extra -->    
                        <div class="extra-detalle"></div>
                    </div>
                    <div class="extra-wod"></div>
                    <a id="WODbtn-viernes"  class="btn-info-wod" data-dia="viernes">Ver Detalles Completos</a>
                </article>
            </div>

            <script src="../js/crossfitGeneral.js"></script>
        </section>

        <!--divisor-->
        <div class="divisor"></div>

        <!--Formulario de contacto-->
        <section class="seccion-contacto" id="contacto">
            <h2 class="titulo-contacto" id="titulo-contacto">FORMULARIO DE CONTACTO</h2>

            <div class="contenedor-contacto">
                <!-- Lado izquierdo: información de contacto -->
                <div class="info-contacto">
                    <h3 class="subtitulo-formulario">Contactos</h3>
                    <p><i class="fas fa-envelope"></i> fortalezacross@gmail.com</p>
                    <p><i class="fas fa-map-marker-alt"></i>Direccion Güemes Calle 8 entre 1 y 3 del centro - Chaco Saenz Peña.</p>
                    <p><i class="fas fa-phone"></i> +54 36-44  2222-96</p>
                    <div class="redes-sociales">
                        <a href="https://www.facebook.com/fortalezacross?rdid=l8JKr2LQ3xDXak9E#"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://api.whatsapp.com/send?phone=543644222296&text=Hola,%20quisiera%20saber%20más%20sobre%20Fortaleza_Cross%20."><i class="fab fa-whatsapp"></i></a>
                        <a href="https://www.instagram.com/fortaleza_box/?hl=es"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <!-- Lado derecho: formulario -->
                <form class="formulario-contacto" id="form-contacto">
                    <h3 class="subtitulo-formulario">Formulario</h3>

                    <input type="text" name="nombre" placeholder="Nombre" required>
                    <input type="email" name="correo" placeholder="Correo electrónico" required>
                    <input type="text" name="asunto" placeholder="Asunto" required>
                    <textarea name="mensaje" placeholder="Mensaje" rows="5" required></textarea>

                    <!-- Honeypot (oculto a humanos) -->
                    <input type="text" name="website" class="hp" autocomplete="off" tabindex="-1" aria-hidden="true">

                    <button type="submit" id="btn-enviar">Enviar</button>

                    <!-- feedback al usuario -->
                    <p id="contacto-msg" aria-live="polite"></p>
                </form>
            </div>
        </section>

        <!--divisor-->
        <div class="divisor"></div>

        <!--Acerca de nosotros-->
        <section class="seccion-acerca-de-nosotros">
            <h2 id="acerca-nosotros">Acerca de Nosotros</h2>

            <div class="contenedor-AN">
                <p>En Fortaleza Cross , ubicado en el corazón de Presidencia Roque Sáenz Peña, Chaco, creemos firmemente que la verdadera fuerza se construye desde adentro, no solo a nivel físico, sino también mental y comunitario. Nacimos de la pasión por el CrossFit y el deseo de crear un espacio donde cada persona, sin importar su nivel de experiencia, se sienta bienvenida y motivada a superar sus límites.</p>

                <p>Somos más que un simple gimnasio; somos una comunidad. Nuestra filosofía se basa en un **ambiente laboral y de entrenamiento excepcional**, donde el respeto, el compañerismo y la superación personal son pilares fundamentales. Nuestros entrenadores, altamente cualificados y apasionados, no solo te guiarán a través de rutinas desafiantes de CrossFit, sino que también te inspirarán a adoptar un estilo de vida más saludable y activo.</p>

                <p>Desde el primer día, notarás la diferencia. Aquí, cada levantamiento, cada salto y cada WOD (Workout of the Day) se convierte en una oportunidad para conectar con otros miembros, celebrar logros y empujarnos mutuamente hacia adelante. En Fortaleza Cross, la energía es contagiosa, los lazos de amistad se fortalecen con cada gota de sudor, y el progreso es una meta compartida.</p>

                <p>Invitamos a todos los residentes de Sáenz Peña y sus alrededores a unirse a nuestra familia Fortaleza Cross. Ven y descubre un lugar donde tus metas de fitness se hacen realidad en un entorno de apoyo, diversión y pura adrenalina. ¡Prepárate para transformar tu cuerpo, tu mente y tu espíritu con nosotros!</p>
                
                <h3>Nuestra Misión</h3>
                <p>Ser el referente en CrossFit en la región, fomentando un estilo de vida activo y saludable a través de entrenamientos desafiantes y un ambiente de comunidad inigualable.</p>

                <h3>Nuestros Valores</h3>
                <ul>
                    <li>Comunidad: Construimos lazos fuertes y nos apoyamos mutuamente.</li>
                    <li>Superación: Creemos en el potencial de cada persona para ir más allá.</li>
                    <li>Integridad: Entrenamos con honestidad y respeto.</li>
                    <li>Pasión: Amamos lo que hacemos y lo transmitimos en cada sesión.</li>
                    <li>Ambiente Positivo: Un espacio de energía, diversión y motivación constante.</li>
                </ul>

                <p>¡Te esperamos en Fortaleza Cross para que seas parte de esta experiencia única!</p>
            </div>
        </section>
        <a href="https://api.whatsapp.com/send?phone=543644222296&text=Hola,%20me%20gustaría%20saber%20más%20sobre%20Fortaleza_Cross%20." target="_blank" class="whatsapp-float-btn" id="whatsappLink" title="Chatea con nosotros por WhatsApp">
            <img class="ico_what" src="../img/icoWhat.png" alt="Icono_Whatsapp" width="50px">
        </a>
    </main>

    <!--FOOTER-->
    <footer>
        <div class="footer-contenido">
            <div class="footer-columna footer-marca">
                <a href="#logo" class="footer-logo-inferior">
                    <img src="../img/fortaleza.png" alt="Logo Fortaleza Cross"> </a>
                <p>Tu viaje hacia una vida más fuerte y saludable.</p>
                <div class="redes-sociales-footer">
                    <a href="https://www.facebook.com/fortalezacross?rdid=l8JKr2LQ3xDXak9E#" aria-label="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/fortaleza_box/?hl=es" aria-label="Instagram" target="_blank"><i class="fab fa-instagram" ></i></a>
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
                    <li><a href="#bienvenidos">Quiénes Somos</a></li>
                    <li><a href="../data/politica_privacidad.html" target="_blank">Política de Privacidad</a></li>
                    <li><a href="../data/terminos_condiciones.html" target="_blank">Términos y Condiciones</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-copyright">
            <p>&copy; 2025 Fortaleza Cross. Todos los derechos reservados.</p>
            <span class="desarrollador">
                <!-- Desarrollado por
                <a href="https://github.com/Eliasramirezzz" target="_blank">Ramirez Elias</a> -->
                <img src="../img/LogoERH.png" alt="Mi marca" width="100px">
            </span>
        </div>
    </footer>

    <!--Funcionalidades-->
    <script src="../js/carrusel_gifs.js"></script>
    <!--<script src="../js/preguntas_frecuentes.js"></script> -->
    <script src="../js/header_responsive.js"></script>
    <script src="../home/index.js" defer></script>
</body>
</html>