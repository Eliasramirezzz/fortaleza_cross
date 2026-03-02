<?php

header("Content-Type: application/json"); // Establecer el encabezado de la respuesta como JSON
require_once "../conexion/conexion.php";
try {
    //Verificar Parametro del GET
    $seccion = isset($_GET['seccion']) ? $_GET['seccion'] : null;
    if ($seccion == null) {
        echo json_encode(["success" => false, "error" => "No se proporciono la seccion"]);
        exit;
    }
    //Envio el html correspondiente.
    switch ($seccion) {
        case 'divisor':
            $html = '<!--divisor-->
                        <div class="divisor"></div>';
            break;

        case 'eventos':
            $html = '<!-- Eventos importantes -->
                    <section id="eventos" class="eventos">
                        <div class="eventos-contenedor">
                            <!-- Contenedor verde: Texto -->
                            <div class="evento-info" id="evento-info">
                            <h2>Eventos Importantes</h2>
                            <p>📅 18 de Diciembre, 2025</p>
                            <p>🎉 Competencia WOD CrossFit Summer 2025</p>
                            <p>💡 Ubicación: Gimnasio Central</p>
                            <p>✍️ ¡Ven y participa!</p>
                        </div>

                        <!-- Contenedor negro: Carrusel de imágenes -->
                        <div class="evento-carrusel">
                            <div class="carousel">
                                <div class="carousel-item">
                                    <img src="../img/event-public-1.png" alt="Evento 1" />
                                </div>
                                <div class="carousel-item">
                                    <img src="../img/pub2.jpg" alt="Evento 2" />
                                </div>
                                <div class="carousel-item">
                                    <img src="../img/pub3.jpg" alt="Evento 3" />
                                </div>
                            </div>

                            <!-- Botones para navegar -->
                            <button class="carousel-btn prev">&#10094;</button>
                            <button class="carousel-btn next">&#10095;</button>
                            </div>
                        </div>
                        <script src="../js/carrusel_pub.js"></script>
                    </section>';

            $css = '../css/carrusel_pub.css';
            $js = '../js/carrusel_pub.js';
            $title = 'Eventos - Fortaleza Cross';
            echo json_encode(["success" => true, "html" => $html, "css" => $css, "js" => $js, "title" => $title], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit; 

        case 'horarios':
            $html = '<!--Horarios General-->
                    <section class="seccion-horarios" id="seccion-horarios">
                        <h2 class="titulo-seccion-horarios" id="titulo-seccion-horarios">¡Horario General!</h2>
                        <div class="contenedor-tarjeta-horarios">
                            <div class="tarjeta-horario">
                                <h3 class="titulo-tarjeta-horario">Nuestro Horario de Atención</h3>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Turno</th>
                                            <th>Horario</th>
                                            <th>Actividades Comunes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--No hay turno mañana asi que lo documentamos nms por ahora
                                        <tr>
                                            <td rowspan="2" class="turno-manana">Mañana</td>
                                            <td>08:00 - 10:00</td>
                                            <td>Entrenamiento Libre</td>
                                        </tr>
                                        <tr>
                                            <td>10:00 - 12:00</td>
                                            <td>Entrenamiento Libre</td>
                                        </tr>  -->
                                    


                                        <tr>
                                            <td rowspan="3" class="turno-tarde">Tarde</td>
                                            <td>14:00 - 15:15</td>
                                            <td>Entrenamiento Libre, Plan Contratado</td>
                                        </tr>
                                        <tr>
                                            <td>18:00 - 19:00</td>
                                            <td>Entrenamiento Libre y Plan Contratado</td>
                                        </tr>
                                        <tr>
                                            <td>19:15 - 20:15</td>
                                            <td>Entrenamiento Libre y Plan Contratado</td>
                                        </tr>

                                        
                                        <tr>
                                            <td class="turno-noche">Noche</td>
                                            <td>20:15 - 21:15</td>
                                            <td>Entrenamiento Libre</td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                                <p class="nota-horario">
                                    Estos horarios y actividades son generales y pueden variar. Para horarios específicos de clases y disponibilidad de instructores, debes iniciar sesión y consulta el horario detallado para miembros.
                                </p>
                            </div>
                        </div>
                    </section>';
                    
            $css = '../css/horarios.css';
            $title = 'Horarios - Fortaleza Cross';
            echo json_encode(["success" => true, "html" => $html, "css" => $css, "title" => $title], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit; 
        
        case 'membresias':
            require_once "../conexion/conexion.php";

            $stmt = $pdo->prepare("SELECT * FROM membresias WHERE activa = 1");
            $stmt->execute();
            $membresias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($membresias)) {
                echo json_encode(["success" => false, "error" => "No hay membresías activas disponibles."]);
                exit;
            }

            $html = '
            <section class="seccion-membresias" id="membresias">
                <h2 class="titulo-seccion-membresias">
                        <span class="letra">¡</span>
                        <span class="letra">M</span>
                        <span class="letra">e</span>
                        <span class="letra">m</span>
                        <span class="letra">b</span>
                        <span class="letra">r</span>
                        <span class="letra">e</span>
                        <span class="letra">s</span>
                        <span class="letra">í</span>
                        <span class="letra">a</span>
                        <span class="letra">s</span>
                        <span class="letra">&nbsp;</span> <span class="letra">y</span>
                        <span class="letra">&nbsp;</span> <span class="letra">P</span>
                        <span class="letra">r</span>
                        <span class="letra">e</span>
                        <span class="letra">c</span>
                        <span class="letra">i</span>
                        <span class="letra">o</span>
                        <span class="letra">s</span>
                        <span class="letra">!</span>
                    </h2>
                <div class="contenedor-tarjetas-membresia">
            ';

            foreach ($membresias as $p) {
                $items = explode(';', $p['caracteristicas']); // <-- ¡ESTE ES EL CAMBIO!
                $lista = '';
                
                foreach ($items as $i) {
                    $item_limpio = trim($i); // Usamos trim() para eliminar espacios en blanco sobrantes
                    if (!empty($item_limpio)) {
                        // Aseguramos que el contenido sea seguro antes de mostrarlo
                        $lista .= "<li>" . htmlspecialchars($item_limpio) . "</li>";
                    }
                }

                // 1. Lógica para determinar si es especial/recomendada
                $clase_especial = '';
                if ($p['especial'] == 1) {
                    $clase_especial = ' tarjeta-recomendada'; // Agrega un espacio inicial
                }

                // Badge opcional (Solo se muestra si 'badge' tiene contenido)
                $badge_html = !empty($p["badge"]) ? "<span class='badge-recomendado'>" . htmlspecialchars($p['badge']) . "</span>" : "";
                
                // Badge opcional
                //$badge = $p["badge"] ? "<span class='badge-recomendado'>{$p['badge']}</span>" : "";

                // 2. Insertar la clase condicional en el <div>
                $html .= "
                <div class='tarjeta-membresia{$clase_especial}'>
                    {$badge_html}
                    <h3 class='titulo-plan'>" . htmlspecialchars($p['nombre']) . "</h3>
                    <p class='precio-plan'>$" . number_format($p['precio'], 2, ",", ".") . "</p>
                    <p class='descripcion-plan'>" . htmlspecialchars($p['descripcion']) . "</p>
                    <ul class='caracteristicas-plan'>
                        {$lista}
                    </ul>
                    <p class='descripcion-plan' style='text-align:left;'>" . nl2br(htmlspecialchars($p['descripcion_larga'])) . "</p>
                </div>
                ";
            }

            $html .= '
                    </div>
                    <div class="tarjeta-nota-accion">
                            <p class="mensaje-accion">
                                ¡Para Inscribirte o Anotarte! ✍️
                                <br>
                                Debes iniciar sesión con tu cuenta. Si aún no tienes una, ¡regístrate!
                            </p>
                            <div class="botones-accion">
                                <a href="../login/login.php" class="boton-login">Iniciar Sesión</a> 
                                <a href="../login/registrar/registrar.php" class="boton-registro">Registrarse</a>
                            </div>
                        </div>
                    <script src="../js/planes.js" defer></script>
                </section>';

            $css = '../css/planes.css';
            $js = '../js/planes.js';
            $title = 'Membresias - Fortaleza Cross';

            echo json_encode([
                "success" => true, 
                "html" => $html, 
                "css" => $css, 
                "js" => $js, 
                "title" => $title
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            exit;

        case 'ubicacion':
            $html = '<!--Ubicación-->
                    <section class="seccion-ubicacion" id="ubicacion">
                        <h2 class="titulo-seccion-ubicacion" id="titulo-seccion-ubicacion">¡Dónde estamos ubicados!</h2>
                        
                        <div class="contenedor-ubicacion">
                            <!-- Dirección y localidad -->
                            <div class="info-direccion">
                                <h3 class="nombre-localidad">Prci, Roque Saenz Peña</h3>
                                <a class="direccion-gym" href="https://maps.app.goo.gl/fZy2w5ngPKkngREs8" target="_blank">Direccion Güemes Calle 8 entre 1 y 3 del centro.</a>
                            </div>

                            <!-- Imagen + Mapa en formato horizontal -->
                            <div class="contenedor-flex-ubicacion">
                                <!-- Imagen del gimnasio -->
                                <div class="imagen-gym">
                                    <img src="../img/img_maps_fortaleza_cross.png" alt="Foto del gimnasio">
                                </div>

                                <!-- Mapa -->
                                <div class="contenedor-mapa">
                                    <iframe
                                        src="https://www.google.com/maps?q=-26.79332453869426,-60.440557918716884&hl=es&z=17&output=embed"
                                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                                    </iframe>
                                </div>
                            </div>

                            <!-- Horarios -->
                            <div class="horarios-gym" id="horarios-gym">
                                <p class="texto-horario">
                                    🕒 Horarios de atención: Lunes a Viernes de 08:00 a 21:15 
                                </p>
                            </div>
                        </div>
                    </section>';
            $css = '../css/ubicacion.css';
            $title = 'Ubicación - Fortaleza Cross';
            echo json_encode(["success" => true, "html" => $html, "css" => $css, "title" => $title], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit; 
            
        case 'tienda':
            $html = '';
            $css = '';
            $title = 'Tienda - Fortaleza Cross';
            echo json_encode(["success" => true, "html" => $html, "css" => $css, "title" => $title], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
            
        case 'gap':
            $html = '<!--Gap-->
                    <section class="seccion-gap">
                    <div class="contenedor-gap">
                        <h2>GAP: Glúteos, Abdominales y Piernas</h2>
                        <p>El entrenamiento GAP está diseñado para fortalecer y tonificar los glúteos, abdominales y piernas. Ideal para quienes buscan mejorar la resistencia muscular, la estética y la salud postural.</p>
                        
                        <div class="beneficios-gap">
                            <h3>Beneficios:</h3>
                            <ul>
                                <li>Tonificación muscular localizada</li>
                                <li>Mejora de la postura y equilibrio</li>
                                <li>Reducción de grasa localizada</li>
                                <li>Prevención de lesiones articulares</li>
                            </ul>
                        </div>

                        <div class="horarios-gap">
                            <h3>Horarios Disponibles:</h3>
                            <p>Lunes, Miércoles y Viernes - 18:00 hs a 19:00 hs</p>
                        </div>

                        <div class="img-gap">
                            <img src="../img/img-gap.png" alt="Clase de GAP">
                        </div>
                    </div>
                </section>';
            $css = '../css/personalizados/gap.css';
            $title = 'GAP - Fortaleza Cross';
            echo json_encode(["success" => true, "html" => $html, "css" => $css, "title" => $title], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        case 'funcional':
            $html = '<!--Funcional-->
                    <section class="seccion-funcional">
                        <div class="contenedor-funcional">
                            <h2>Entrenamiento Funcional</h2>
                            <p>El entrenamiento funcional trabaja el cuerpo en su conjunto, mejorando fuerza, flexibilidad, coordinación y resistencia a través de movimientos naturales y multiarticulares.</p>

                            <div class="beneficios-funcional">
                                <h3>Beneficios:</h3>
                                <ul>
                                    <li>Mejora del rendimiento físico general</li>
                                    <li>Prevención de lesiones</li>
                                    <li>Trabajo integral de todos los grupos musculares</li>
                                    <li>Adaptable a cualquier nivel de condición física</li>
                                </ul>
                            </div>

                            <div class="horarios-funcional">
                                <h3>Horarios Disponibles:</h3>
                                <p>Martes y Jueves - 19:00 hs a 20:00 hs</p>
                            </div>

                            <div class="img-funcional">
                                <img src="../img/img-funcional.png" alt="Entrenamiento Funcional">
                            </div>
                        </div>
                    </section>';
            $css = '../css/personalizados/funcional.css';
            $title = 'Funcional - Fortaleza Cross';
            echo json_encode(["success" => true, "html" => $html, "css" => $css, "title" => $title], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        case 'musculacion':
            $html = '<!--Musculación-->
                <section class="seccion-musculacion">
                    <div class="contenedor-musculacion">
                        <h2>Área de Musculación</h2>
                        <p>Disponemos de máquinas de última generación, zona de peso libre y asesoramiento personalizado para maximizar tus entrenamientos de fuerza e hipertrofia muscular.</p>

                        <div class="beneficios-musculacion">
                            <h3>Beneficios:</h3>
                            <ul>
                                <li>Incremento de masa muscular</li>
                                <li>Mejora de la fuerza y resistencia</li>
                                <li>Salud ósea y articular</li>
                                <li>Reducción de grasa corporal</li>
                            </ul>
                        </div>

                        <div class="asesoria-musculacion">
                            <h3>Asesoramiento:</h3>
                            <p>Contamos con entrenadores capacitados para armar rutinas personalizadas según tus objetivos.</p>
                        </div>

                        <div class="img-musculacion">
                            <img src="../img/img-musculacion.png" alt="Área de Musculación">
                        </div>
                    </div>
                </section>';
            $css = '../css/personalizados/musculacion.css';
            $title = 'Musculación - Fortaleza Cross';
            echo json_encode(["success" => true, "html" => $html, "css" => $css, "title" => $title], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
            
        case 'levantamiento':
            $html = '<!--Levantamiento-->
                <section class="seccion-levantamiento">
                    <div class="contenedor-levantamiento">
                        <h2>Levantamiento Olímpico</h2>
                        <p>Disciplina enfocada en mejorar la técnica de levantamiento de pesas olímpico, fuerza explosiva, coordinación y control corporal. Ideal para atletas y entusiastas del alto rendimiento.</p>

                        <div class="beneficios-levantamiento">
                            <h3>Beneficios:</h3>
                            <ul>
                                <li>Aumento de potencia y explosividad</li>
                                <li>Mejora de la técnica deportiva</li>
                                <li>Desarrollo de fuerza máxima</li>
                                <li>Preparación física integral</li>
                            </ul>
                        </div>

                        <div class="horarios-levantamiento">
                            <h3>Horarios Disponibles:</h3>
                            <p>Lunes a Viernes - 17:00 hs a 18:30 hs</p>
                        </div>

                        <div class="img-levantamiento">
                            <img src="../img/img-levantamiento.png" alt="Levantamiento Olímpico">
                        </div>
                    </div>
                </section>'
                
                ;
            $css = '../css/personalizados/levantamiento.css';
            $title = 'Levantamiento - Fortaleza Cross';
            echo json_encode(["success" => true, "html" => $html, "css" => $css, "title" => $title], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        
        case 'PF':
            $html = '<section class="seccion-PF" id="Preguntas_frecuentes">
                        <h2>Preguntas Frecuentes</h2>
                        <div class="contenedor-pf" id="contenedor-pf">
                            <!-- Aquí se insertan los acordeones desde JS -->
                        </div>

                        <!--Campo para que el usuario escriba su propia pregunta -->
                        <div class="acordeon-user-question" id="pregunta_personalizada">
                            <label for="pregunta-personal">¿Tienes otra pregunta? Escríbela aquí:</label>
                            <textarea id="pregunta-personal" rows="4" placeholder="Escribe tu pregunta..."></textarea>
                            <button id="enviar-pregunta">Enviar pregunta por WhatsApp 
                            <img src="../img/icoWhat.png" alt="" width="30px"></button>
                            <p id="respuesta-servidor" style="margin-top: 10px; color: green;"></p>
                        </div>
                    </section>';
            $css = '../css/preguntas_frecuentes.css';
            $js = '../js/preguntas_frecuentes.js';
            $title = 'Preguntas Frecuentes - Fortaleza Cross';

            echo json_encode(["success" => true, "html" => $html, "css" => $css, "js" => $js, "title" => $title], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        default:
            echo json_encode(["success" => false, "error" => "Seccion no encontrada"]);
            exit;
    }
    
}catch(PDOException $e){
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>



