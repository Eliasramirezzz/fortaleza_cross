<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();
$_SESSION['acceso_permitido'] = true;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Fortaleza Cross Saenz Peña Chaco">
    <meta name="description" content="Página de clientes de Fortaleza Cross">
    <link rel="shortcut icon" href="../../img/fortaleza.png" type="image/x-icon">
    <link rel="stylesheet" href="GestSocio.css">
    <link rel="stylesheet" href="../../bin/mensaje_modal.css"> <!-- Diseño de los mensajes-->
    <title>Cambiar Foto del Socio - Fortaleza Cross</title>
</head>

<body>
    <!-- 🔹 Button volver atras -->
    <div>
        <a href="../home_socio.php" class="btn-volver">&#8592; Volver atras</a>
    </div>

    <!-- 🔹 Encabezado -->
    <div class="header-container">
        <h1>Cambiar Foto de Perfil</h1>
    </div>

    <!-- 🔹 Mensajes -->
    <div id="mensaje" class="mensaje"></div>

    <!-- 🔹 Sección principal -->
    <section class="cambiar-foto">
        <div class="foto-actual">
            <h3>Foto Actual</h3>
            <img id="previewActual" alt="Foto actual del socio">
        </div>

        <div class="subir-foto">
            <h3>Nueva Foto</h3>
            
            <form id="formFoto">
                <label for="nueva_foto" class="label-file">
                    📸 Seleccionar imagen
                    <input type="file" id="nueva_foto" name="nueva_foto" accept="image/*" required>
                </label>

                <div id="previewContainer">
                    <img id="previewNueva" src="" alt="" style="display: none;">
                </div>

                <button type="submit" class="btn-guardar">Guardar Cambios</button>
            </form>
        </div>
    </section>

    <!-- 🔹 Modal -->
    <div id="modalConfirmacion" class="modal" style="display: none;">
        <div class="modal-content">
            <p>¿Deseas confirmar el cambio de imagen?</p>
            <button id="btnConfirmar" class="btn-confirmar">Sí, cambiar</button>
            <button id="btnCancelar" class="btn-cancelar">Cancelar</button>
        </div>
    </div>

    <script src="GestSocio.js" defer></script>
    <script src="../../bin/modal_controller.js"></script>
</body>
</html>
