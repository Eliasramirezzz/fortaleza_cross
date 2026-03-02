<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Fortaleza Gym</title>
    <link rel="icon" href="../img/icono.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- En tu <head> para los iconos de mostrar o ocultar contraseña -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../img/fortaleza.png" type="image/x-icon">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="../bin/mensaje_modal.css">
</head>
<body>
    <header class="header">
        <div class="logo-container" id="logo-container">
            <img src="../img/fortaleza.png" alt="Logo Fortaleza" class="logo" id="logo">
            <span>Ir al inicio</span>
        </div>
    </header>

    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img src="../img/fortaleza.png" alt="Logo Fortaleza" class="login-logo">
                <h1>Iniciar Sesión</h1>
                <p>Bienvenido de nuevo, socio.</p>
            </div>

            <form action="login.js" method="POST" class="login-form">
                <div class="input-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="tu@ejemplo.com" required autocomplete="email">
                </div>

                <!-- Poner el icono para ver o ocultar la contraseña -->
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required autocomplete="current-password">
                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                    </div>
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember-me" name="remember-me">
                        <label for="remember-me">Recordarme</label>
                    </div>
                    <a id="forgot-password" class="forgot-password">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="login-button">Iniciar Sesión</button>
            </form>

            <div class="login-footer">
                <p>¿No tienes una cuenta? <a href="../login/registrar/registrar.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
    <!-- Modal para recuperar contraseña -->
    <div id="modal-recuperar" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <span class="close-modal" id="cerrar-modal">&times;</span>
            <h2>¿Olvidaste tu contraseña?</h2>
            <p>Por favor, ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
            <div id="form-recuperar">
                <div class="input-group">
                    <label for="email-recuperar">Correo Electrónico</label>
                    <input type="email" id="email-recuperar" name="email-recuperar" placeholder="tu@ejemplo.com" required>
                </div>
                <button id="enviar-recuperar" class="login-button">Confirmar</button>
            </div>
        </div>
    </div>                  

    <script src="../bin/modal_controller.js"></script>
    <script src="recuperar_password.js"></script>
    <script src="../login/login.js" defer></script>
    
</body>
</html>