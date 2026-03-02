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
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="registrar.css">
    <link rel="stylesheet" href="<?= rtrim(BASE_URL, '/') ?>/bin/mensaje_modal.css">
    
    <title>Crear Cuenta - Fortaleza Gym</title>
</head>
<body>
    <header class="header">
        <div class="logo-container" id="logo-container">
            <img src="fortaleza.png" alt="Logo Fortaleza" class="logo" id="logo">
            <span>Ir al Login</span>
        </div>
        <span id="irHomeReg">Ir al Inicio</span>
    </header>

    <section class="seccion-registrar" id="seccion-registrar">
        <h2 id="titulo-registrar">Regístrate</h2>
        <div class="contenedor-registrar">
            <div class="registro-izquierdo">
                <div id="gif_crear_cuenta">
                    <video id="video-fondo-crearCuenta" autoplay muted loop playsinline></video> 
                </div>
                <div class="registro-izquierdo-contenido">
                    <h3>¡Únete a Nuestra Comunidad Fortaleza Cross!</h3>
                    <p>Comienza tu viaje hacia una vida más fuerte y saludable. Regístrate hoy y accede a:</p>
                    <ul>
                        <li><i class="fa-solid fa-dumbbell"></i> Entrenamientos Exclusivos</li>
                        <li><i class="fa-solid fa-chart-line"></i> Seguimiento de Progreso</li>
                        <li><i class="fa-solid fa-users"></i> Comunidad de Apoyo</li>
                        <li><i class="fa-solid fa-calendar-check"></i> Reservas Sencillas</li>
                    </ul>
                </div>
            </div>

            <div class="registro-derecho">
                <div class="registro-paso registro-paso-1 active" id="registroPaso1">
                    <h3>Crea tu Cuenta</h3>
                    <form id="formRegistroPaso1">
                        <div class="form-campo">
                            <label for="email"><i class="fa-solid fa-envelope"></i>Ingrese su Email</label>
                            <input type="email" id="email" name="email" placeholder="tu.email@gmail.com" required>
                            <span class="error-message" id="emailError"></span>
                        </div>
                        
                        <button type="submit" class="btn-siguiente">Siguiente</button> 
                        <label for="">Se registrar con su cuenta de Google</label>
                    </form>
                </div>

                <div class="registro-paso registro-paso-2" id="registroPaso2">
                    <h3>Completa tus Datos</h3>
                    <form id="formRegistroPaso2">
                        <div class="form-grupo-doble">
                            <div class="form-campo">
                                <label for="nombre"><i class="fa-solid fa-user"></i> Nombre</label>
                                <div class="input-container"> <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
                                    <span class="validation-icon"></span> </div>
                                <p class="validation-message" id="error-nombre"></p> </div>
                            <div class="form-campo">
                                <label for="apellido"><i class="fa-solid fa-user"></i> Apellido</label>
                                <div class="input-container"> <input type="text" id="apellido" name="apellido" placeholder="Tu apellido" required>
                                    <span class="validation-icon"></span>
                                </div>
                                <p class="validation-message" id="error-apellido"></p>
                            </div>
                        </div>

                        <div class="form-grupo-doble">
                            <div class="form-campo">
                                <label for="dni"><i class="fa-solid fa-id-card"></i> DNI</label>
                                <div class="input-container"> <input type="text" id="dni" name="dni" placeholder="Tu DNI" required>
                                    <span class="validation-icon"></span>
                                </div>
                                <p class="validation-message" id="error-dni"></p>
                            </div>
                            <div class="form-campo">
                                <label for="telefono"><i class="fa-solid fa-phone"></i> Teléfono</label>
                                <div class="input-container"> <input type="tel" id="telefono" name="telefono" placeholder="Ej: 3734-123456 (debe tener 10 digitos)" required>
                                    <span class="validation-icon"></span>
                                </div>
                                <p class="validation-message" id="error-telefono"></p>
                            </div>
                        </div>

                        <div class="form-campo">
                            <label for="fechaNacimiento"><i class="fa-solid fa-calendar-days"></i> Fecha de Nacimiento</label>
                            <div class="input-container"> <input type="date" id="fechaNacimiento" name="fechaNacimiento" required>
                                <span class="validation-icon"></span>
                            </div>
                            <p class="validation-message" id="error-fechaNacimiento"></p>
                        </div>

                        <span class="separadorDatos">Datos de Usuario</span>

                        <div class="form-campo">
                            <label for="usuario"><i class="fa-solid fa-user-circle"></i> Usuario</label>
                            <div class="input-container"> <input type="text" id="usuario" name="usuario" placeholder="Crea tu nombre de usuario" required>
                                <span class="validation-icon"></span>
                            </div>
                            <p class="validation-message" id="error-usuario"></p>
                        </div>
                        
                        <div class="form-campo">
                            <label for="password"><i class="fa-solid fa-lock"></i> Contraseña</label>
                            <div class="input-container"> <input type="password" id="password" name="password" placeholder="Crea tu contraseña 'debe tener entre 4 y 16 caracteres'" required>
                                <span class="validation-icon"></span>
                            </div>
                            <p class="validation-message" id="error-password"></p>
                        </div>
                        
                        <div class="form-campo">
                            <label for="confirmPassword"><i class="fa-solid fa-lock"></i> Confirmar Contraseña</label>
                            <div class="input-container"> <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirma tu contraseña" required>
                                <span class="validation-icon"></span>
                            </div>
                            <p class="validation-message" id="error-confirmPassword"></p>
                        </div>

                        <button type="button" class="btn-atras">Atrás</button>
                        <button type="submit" class="btn-confirmar">Confirmar Registro</button>
                    </form>
                </div>
            </div> 
        </div>
    </section>
    
    <script src="<?= rtrim(BASE_URL, '/') ?>/bin/modal_controller.js"></script>
    <script src="registrar.js" defer></script>
    <!---->
</body>
</html>