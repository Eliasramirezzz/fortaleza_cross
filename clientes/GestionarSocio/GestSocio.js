// =========================== Variable Global ==============================
const BASE_DEFAULT_URL = '/Fortaleza_Cross/img/ImgUser/user_default.png';// Ruta que el login guarda

// =========================== Cambiar foto de perfil==============================
//Elementos del DOM
const fileInput = document.getElementById('nueva_foto');
const previewNueva = document.getElementById('previewNueva');
const formFoto = document.getElementById('formFoto');
const mensajeDiv = document.getElementById('mensaje'); // Asumo que tienes un div con id="mensaje"

// ⚙️ CONFIGURACIÓN DE VERIFICACIÓN
const MAX_FILE_SIZE_MB = 10; // Máximo peso de archivo permitido (2 MB)
const MIN_DIMENSION_PX = 100; // Mínimo ancho/alto (ej: 100x100)
const MAX_DIMENSION_PX = 800; // Máximo ancho/alto (ej: 800x800)

// 1. Manejar Selección de Archivo y Previsualización
fileInput.addEventListener('change', function () {
    mensajeDiv.style.display = 'none'; // Ocultar mensajes previos
    previewNueva.style.display = 'none';

    // Si no se selecciona ningún archivo, salimos
    if (this.files.length === 0) {
        previewNueva.src = "";
        return;
    }

    const file = this.files[0];

    // --- A. Verificación de Tipo (Aunque el HTML lo limita, es buena práctica) ---
    if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
        mostrarMensaje('error', 'Tipo no válido', 'Solo se permiten imágenes JPG y PNG.');
        this.value = ''; // Limpiar el input
        return;
    }

    // --- B. Verificación de Peso (Tamaño) ---
    const fileSizeMB = file.size / (1024 * 1024); // Convertir bytes a MB
    if (fileSizeMB > MAX_FILE_SIZE_MB) {
        mostrarMensaje('error', 'Tamaño excedido', `El archivo no puede superar los ${MAX_FILE_SIZE_MB} MB.`);
        this.value = '';
        return;
    }

    // --- C. Verificación de Dimensiones y Carga de Previsualización ---
    const reader = new FileReader();

    reader.onload = function (e) {
        // Crear un objeto Image para medir las dimensiones
        const img = new Image();
        img.onload = function () {
            const width = img.width;
            const height = img.height;

            if (width < MIN_DIMENSION_PX || height < MIN_DIMENSION_PX) {
                mostrarMensaje('error', 'Demasiado pequeña', `La imagen debe ser al menos de ${MIN_DIMENSION_PX}x${MIN_DIMENSION_PX} píxeles.`);
                fileInput.value = '';
                previewNueva.style.display = 'none';
                return;
            }
            if (width > MAX_DIMENSION_PX || height > MAX_DIMENSION_PX) {
                mostrarMensaje('info', 'Demasiado grande', `La imagen será redimensionada a ${MAX_DIMENSION_PX}x${MAX_DIMENSION_PX} en el servidor.`);
                // Continuamos ya que el backend lo redimensionará, pero avisamos.
            }

            // Si pasa las verificaciones (o solo requiere aviso), mostramos la previsualización
            previewNueva.src = e.target.result;
            previewNueva.style.display = 'block';
            mostrarMensaje('success', 'Archivo listo', `Imagen seleccionada: ${file.name}`);

        };
        img.src = e.target.result; // Cargar la imagen para que se ejecute img.onload
    };

    reader.readAsDataURL(file); // Iniciar la lectura del archivo
});

// 2. Manejar Envío del Formulario
formFoto.addEventListener('submit', async (e) => {
    e.preventDefault();

    // 1. Verificación final de que se seleccionó un archivo
    if (fileInput.files.length === 0) {
        showCustomModal('error', 'Error de Subida', 'Por favor, selecciona una imagen primero.');
        return;
    }

    // 2. Mostrar modal de confirmación
    const confirm = await ModalConfirmacion('¿Estás seguro de que quieres cambiar tu foto de perfil?');

    if (!confirm) {
        return;
    }

    // 3. Preparar datos
    const id_usuario = sessionStorage.getItem('idUser');
    const formData = new FormData(formFoto);
    // Adjuntar el ID de usuario (clave para el backend)
    formData.append('id_usuario', id_usuario);

    // Importante: no usar JSON.stringify aquí, FormData maneja todo internamente.

    // 4. Petición asíncrona para subir la imagen
    try {
        showCustomModal('loading', 'Cargando...', 'Subiendo imagen, por favor espera...');

        // Enviar la petición al backend
        const respuUpdateImg = await fetch('../GestionarSocio/UploadImageCliente.php', {
            method: 'POST',
            body: formData // <-- USAR formData, NO JSON.stringify(datas)
        });

        // Verificar respuesta del servidor
        if (!respuUpdateImg.ok) {
            closeCustomModal();
            showCustomModal('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
            return;
        }

        const resultUpdateImg = await respuUpdateImg.json();

        if (resultUpdateImg.success) {
            showCustomModal('success', '¡Éxito! 🎉', resultUpdateImg.message);
            // 1. Guardar la URL devuelta (que ya es ABSOLUTA)
            sessionStorage.setItem('fotoUrl', resultUpdateImg.newImageUrl);

            // 2. Actualizar la previsualización: Usa la ruta ABSOLUTA directamente
            document.getElementById('previewNueva').src = resultUpdateImg.newImageUrl;
            document.getElementById('previewActual').src = resultUpdateImg.newImageUrl; // O el ID que estés usando

            //recargar la pagina
            setTimeout(() => {
                location.reload();
            }, 3000);

        } else {
            mostrarMensaje('error', 'Error al Guardar', resultUpdateImg.message);
        }
    } catch (error) {
        console.error('Error de red al subir imagen:', error);
        showCustomModal('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
    }
});

// 3. Funciones Auxiliares (Debes tenerlas definidas en tu proyecto)
/** Muestra un mensaje en el div #mensaje */
function mostrarMensaje(type, title, message) {
    // Asegurarse de limpiar clases previas
    mensajeDiv.className = 'mensaje';

    if (type) {
        mensajeDiv.classList.add(type);
    }
    mensajeDiv.innerHTML = `<strong>${title}:</strong> ${message}`;
    mensajeDiv.style.display = 'block';
}

/** Muestra el modal de confirmación y devuelve una promesa (Sí/No) */
function ModalConfirmacion(message) {
    // Esto es un placeholder, debes usar tu implementación real.

    return new Promise(resolve => {
        const modal = document.getElementById('modalConfirmacion');
        const btnConfirmar = document.getElementById('btnConfirmar');
        const btnCancelar = document.getElementById('btnCancelar');
        const modalMessage = modal.querySelector('p');

        modalMessage.textContent = message;
        modal.style.display = 'flex';

        const handleConfirm = () => {
            modal.style.display = 'none';
            btnConfirmar.removeEventListener('click', handleConfirm);
            btnCancelar.removeEventListener('click', handleCancel);
            resolve(true);
        };

        const handleCancel = () => {
            modal.style.display = 'none';
            btnConfirmar.removeEventListener('click', handleConfirm);
            btnCancelar.removeEventListener('click', handleCancel);
            resolve(false);
        };

        btnConfirmar.addEventListener('click', handleConfirm);
        btnCancelar.addEventListener('click', handleCancel);
    });
}


document.addEventListener("DOMContentLoaded", function () {
    showCustomModal('cargando', '⏳ Cargando la pagina', 'Por favor, espere mientras se carga la imagen de perfil.');
    // Obtengo los datos necesario para la seccion activa
    //Elementos del DOM
    const id_usuario = sessionStorage.getItem('idUser');
    let FotoUserURL = sessionStorage.getItem('fotoUrl');
    // Parsear URL Foto
    let urlParaCargar;

    // 1. Determinar qué URL usar
    // Verificamos si la sesión tiene una URL de foto y si esta URL es la nueva (absoluta, empieza con /)
    if (FotoUserURL && FotoUserURL.startsWith('/')) {
        urlParaCargar = FotoUserURL;
    } else {
        // Si la sesión está vacía o tiene la ruta antigua/relativa, usamos la por defecto.
        urlParaCargar = BASE_DEFAULT_URL;
    }
    // 2. Verificar si hay un id
    if (!id_usuario || id_usuario === "" || id_usuario == null) {
        closeCustomModal();
        showCustomModal('error', '❌ Error al ingresar', 'No se encuentra disponible la pagina para este usuario, intente nuevamente mas tarde 😢');
        setTimeout(() => {
            window.location.href = '../../login/login.php';
        }, 5000);
        return;
    }
    //Verificar si hay un id
    if (!id_usuario || id_usuario === "" || id_usuario == null) {
        closeCustomModal();
        showCustomModal('error', '❌ Error al ingresar', 'No se encuentra disponible la pagina para este usuario, intente nuevamente mas tarde 😢');
        setTimeout(() => {
            window.location.href = '../../login/login.php';
        }, 5000);
        return;
    }
    //-------------------- Cargar la imagen de perfil actual --------------------
    // Otengo etiqueta img para insertar la img de perfil actual
    const imgActual = document.getElementById("previewActual");

    // La variable urlParaCargar ahora contiene la ruta ABSOLUTA que funciona en el navegador.
    imgActual.src = urlParaCargar;

    closeCustomModal();

});