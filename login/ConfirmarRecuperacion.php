<?php
// Conexión a la BD
require_once '../conexion/conexion.php';
require_once 'registrar/config_email.php'; 

// Verifica si se proporcionó un token
if (!isset($_GET['token']) || empty($_GET['token'])) {
    // Si no hay token, muestra un error
    // Podrías redirigirlo a una página de error o mostrar un mensaje
    echo "<h1>Error</h1><p>Token de recuperación no válido o faltante.</p>";
    exit;
}
//Sanitizar el token
$token = filter_var($_GET['token'], FILTER_SANITIZE_STRING);
try {
    // 1. Busca el usuario con ese token
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE token_recuperacion = :token AND token_expiracion > NOW()");
    $stmt->execute([':token' => $token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Si el token no es válido o ha expirado
    if (!$usuario) {
        // Redirige al usuario a una página de error o al login con un mensaje
        // Para simplicidad, mostramos un mensaje aquí
        echo "<h1>Error</h1><p>El enlace de recuperación ha expirado o no es válido. Por favor, solicita uno nuevo.</p>";
        exit;
    }

    // 3. Si el token es válido, sirve el formulario para cambiar la contraseña
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../img/icono.ico" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="../bin/mensaje_modal.css">
        <title>Restablecer Contraseña - Fortaleza Cross</title>
        <style>
            /* =========================================================
            ESTILOS PARA EL FORMULARIO DE RESTABLECER CONTRASEÑA
            ========================================================= */
            /* Estilos generales para el cuerpo de la página de restablecer */
            body {
                background-color: #0b1120; /* Fondo oscuro similar a tu login */
                color: #E0E0E0;
                font-family: 'Poppins', sans-serif; /* Asegúrate de tener esta fuente importada */
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                padding: 20px;
            }
            /* Estilo para el contenedor principal del formulario */
            .container {
                background-color: #1A2A3A; /* Contenedor oscuro */
                border-radius: 12px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
                max-width: 450px;
                width: 100%;
                padding: 40px;
                text-align: center;
                box-sizing: border-box; /* Incluye padding y borde en el ancho/alto */
            }
            .container h1 {
                color: #00BFFF; /* Color de acento */
                font-size: 2rem;
                margin-bottom: 10px;
            }
            .container p {
                font-size: 1rem;
                line-height: 1.6;
                margin-bottom: 30px;
                color: #B0C4DE;
            }
            /* Estilos para los grupos de input (label + input) */
            .input-group {
                margin-bottom: 25px;
                text-align: left;
            }
            .input-group label {
                display: block;
                margin-bottom: 8px;
                color: #B0C4DE;
                font-weight: 500;
            }
            /* Estilos para el wrapper del input de contraseña (input + ícono) */
            .password-wrapper {
                display: flex;
                align-items: center;
                position: relative; /* Mantén el position: relative para el contenedor */
                background-color: #2c3e50;
                border: 1px solid #4a6584;
                border-radius: 8px;
                width: 100%;
                box-sizing: border-box;
            }
            .password-wrapper input[type="password"] {
                /* El input ahora ocupa todo el espacio disponible */
                flex-grow: 1; 
                border: none;
                background-color: transparent;
                color: #ffffff;
                padding: 12px 15px;
                padding-right: 40px; /* Agrega padding a la derecha para el ícono */
                outline: none;
                font-size: 1rem;
            }
            .password-wrapper input[type="text"] {
                /* El input ahora ocupa todo el espacio disponible */
                flex-grow: 1; 
                border: none;
                background-color: #0b1120;
                color: #ffffff;
                padding: 12px 15px;
                padding-right: 40px; /* Agrega padding a la derecha para el ícono */
                outline: none;
                font-size: 1rem;
            }
            .password-wrapper .toggle-password {
                cursor: pointer;
                color: #b0c4de;
                font-size: 1.1rem;
                position: absolute; /* Cambia a position: absolute */
                right: 15px;       /* Posiciona el ícono a la derecha */
                top: 50%;          /* Alinea el ícono verticalmente */
                transform: translateY(-50%); /* Ajusta la posición vertical */
                z-index: 10;
            }
            /* Estilo para el contenedor del input cuando hay un error de validación */
            .password-wrapper.error {
                border: 1px solid #dc3545; /* Color rojo para el borde */
                box-shadow: 0 0 5px rgba(220, 53, 69, 0.5); /* Sombra roja para resaltar */
            }
            /* Opcional: Estilo para el texto de error debajo del input */
            .error-message {
                color: #dc3545; /* Texto rojo */
                font-size: 0.85rem;
                margin-top: 5px;
                text-align: left;
                display: none; /* Oculto por defecto */
            }
            /* Estilos para el botón de "Cambiar Contraseña" */
            .btn-confirmar {
                width: 100%;
                padding: 14px;
                background-color: #4CAF50; /* Verde para la acción de confirmar */
                color: #1A2A3A;
                border: none;
                border-radius: 8px;
                font-size: 1.1rem;
                font-weight: bold;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
            .btn-confirmar:hover {
                background-color: #66bb6a; /* Un verde más claro al pasar el mouse */
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Nueva Contraseña</h1>
            <p>Ingresa y confirma tu nueva contraseña.</p>
            <form id="from-restablecer">
                <input type="hidden" id="token" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="input-group">
                    <label for="password">Nueva Contraseña</label>
                    <div class="password-wrapper" id="wrapper-password">
                        <input type="password" id="password" name="password" required>
                        <i class="fa-solid fa-eye-slash toggle-password" id="togglePassword"></i>
                    </div>
                    <span class="error-message" id="error-password"></span>
                </div>
                
                <div class="input-group">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <div class="password-wrapper" id="wrapper-confirm-password">
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <i class="fa-solid fa-eye-slash toggle-password" id="toggleConfirmPassword"></i>
                    </div>
                    <span class="error-message" id="error-confirm-password"></span>
                </div>
                
                <button type="submit" class="btn-confirmar">Cambiar Contraseña</button>
            </form>
        </div>
        <script src="../bin/modal_controller.js"></script>
        <script src="restablecer.js" defer></script>
        </body>
    </html>
    <?php
} catch (PDOException $e) {
    echo "<h1>Error</h1><p>Error en la base de datos: " . $e->getMessage() . "</p>";
}
?>