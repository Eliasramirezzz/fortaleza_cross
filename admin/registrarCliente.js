// ------------------Seccion Registrar Cliente cuenta ----------------------
// Función para mostrar el estado visual y el mensaje
function setValidationState(inputElement, isValid, message = '') {
    const parentGroup = inputElement.closest('.reg-cli-input-group');
    let messageElement = parentGroup.querySelector('.validacion-mensaje');

    // 1. Si no existe el elemento de mensaje, lo creamos y lo inyectamos
    if (!messageElement) {
        messageElement = document.createElement('small');
        messageElement.classList.add('validacion-mensaje');
        parentGroup.appendChild(messageElement);
    }

    // 2. Limpiamos clases y aplicamos el estado
    inputElement.classList.remove('input-success', 'input-error');
    messageElement.classList.remove('mensaje-success', 'mensaje-error');
    messageElement.textContent = ''; // Limpiamos el texto

    if (isValid) {
        inputElement.classList.add('input-success');
        if (message) {
            messageElement.classList.add('mensaje-success');
            messageElement.textContent = message;
        }
    } else {
        inputElement.classList.add('input-error');
        messageElement.classList.add('mensaje-error');
        messageElement.textContent = message;
    }
}

// Función principal de validación por campo
function validateField(inputElement) {
    const value = inputElement.value.trim();
    const id = inputElement.id;
    let isValid = true;
    let message = '';

    // Reglas de validación
    switch (id) {
        case 'nombre':
        case 'apellido':
            if (value.length < 3) {
                isValid = false;
                message = 'Debe tener al menos 3 caracteres.';
            } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value)) {
                isValid = false;
                message = 'Solo se permiten letras y espacios.';
            }
            break;
        case 'dni':
            if (!/^\d{8}$/.test(value)) {
                isValid = false;
                message = 'DNI debe tener 8 dígitos.';
            }
            break;
        case 'telefono':
            if (!/^\d{4}-\d{6,}$/.test(value) && !/^\d{8,}$/.test(value)) {
                isValid = false;
                message = 'Formato de teléfono no válido (Ej: 3644-000000 o 3644000000).';
            }
            break;
        case 'usuario':
            if (value.length < 4) {
                isValid = false;
                message = 'El usuario debe tener al menos 4 caracteres.';
            } else if (!/^[a-zA-Z0-9_]+$/.test(value)) {
                isValid = false;
                message = 'Solo se permiten letras, números y guiones bajos.';
            }
            // NOTA: La validación de unicidad de usuario debe ir en la función de envío (fetch)
            break;
        case 'email':
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                isValid = false;
                message = 'Formato de correo electrónico no válido.';
            }
            break;
        case 'password':
            if (value.length < 4) {
                isValid = false;
                message = 'La contraseña debe tener al menos 4 caracteres.';
            }
            break;
        case 'confirpassword':
            const password = document.getElementById('password').value;
            if (value !== password) {
                isValid = false;
                message = 'Las contraseñas no coinciden.';
            }
            break;
        case 'fecha_nacimiento':
            const today = new Date();
            const birthDate = new Date(value);
            // Comprobamos que no sea una fecha futura
            if (birthDate > today) {
                isValid = false;
                message = 'La fecha de nacimiento no puede ser futura.';
            }
            // Podrías agregar aquí lógica de mayoría de edad si fuera necesario
            break;
        case 'genero':
        case 'rol':
            if (value === '') {
                isValid = false;
                message = 'Debe seleccionar una opción.';
            }
            break;
    }

    // Si es requerido y está vacío, siempre es error.
    if (inputElement.hasAttribute('required') && value === '' && id !== 'fecha_nacimiento') {
        isValid = false;
        message = 'Este campo es obligatorio.';
    }

    // Aplicar el estado visual y el mensaje
    setValidationState(inputElement, isValid, message);

    return isValid;
}

// ================= Registrar Cliente ==================
async function registrarCliente() {
    const form = document.getElementById('formRegistroCliente');
    const formData = new FormData(form);
    const id_usuario = sessionStorage.getItem('idUser'); // Adjuntar el ID de usuario (clave para el backend)

    // Convertir FormData a objeto
    const data = Object.fromEntries(formData.entries());
    data.id_usuario = id_usuario;

    // llamar ala funciona  ModalConfirmacionPlan que esta en el archivo home_socio.js
    const confirmado = await ModalConfirmacionPlan(
        'Confirmar Registro Cliente',
        `¿Estás seguro que deseas registrar este cliente?`
    );

    if (confirmado) {
        // Importante: no usar JSON.stringify aqui, FormData maneja todo internamente.
        console.log("Ya estamos dentro de registrar cleiete");

        try {
            showCustomModal('cargando', '⏳ Registrando cliente...', 'Registrando al cliente. Espere por favor');
            const respuRegCliente = await fetch('../admin/Modelo/ProcesarRegistroCliente.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (!respuRegCliente.ok) {
                showCustomModal('error', '❌ Error al consultar el servidor', 'Error al obtener respuesta del servidor.\nVerifique y vuelva a intentarlo');
                return;
            }

            const resultRegCliente = await respuRegCliente.json();

            if (resultRegCliente.success) {
                closeCustomModal();
                showCustomModal('success', '✅ Cliente registrado', resultRegCliente.message);
                setTimeout(() => {
                    // Recargar la pagina
                    window.location.reload();
                }, 3000);
            } else {
                showCustomModal('error', '❌ Error', resultRegCliente.message);
            }
        } catch (error) {
            showCustomModal('error', '❌ Error inesperado', error.message);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formRegistroCliente');
    const inputFields = form.querySelectorAll('input, select');

    // 1. Añadir listeners a todos los campos
    inputFields.forEach(input => {
        // Validación al escribir (input) y al perder el foco (blur)
        input.addEventListener('input', () => {
            // Solo validamos si ya tiene un error o si tiene contenido, para no mostrar error en campos vacíos al cargar.
            if (input.classList.contains('input-error') || input.value.trim() !== '') {
                validateField(input);
            }
        });

        input.addEventListener('blur', () => {
            // Validamos completamente cuando el usuario sale del campo
            validateField(input);
        });

        // Caso especial para 'select' (cambio de valor)
        if (input.tagName === 'SELECT') {
            input.addEventListener('change', () => {
                validateField(input);
            });
        }
    });

    // 2. Bloquear el envío del formulario si hay errores
    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Detenemos el envío por defecto

        let isFormValid = true;

        // Re-validar todos los campos al intentar enviar
        inputFields.forEach(input => {
            if (!validateField(input)) {
                isFormValid = false;
            }
        });

        if (isFormValid) {
            // Si todo es válido, aquí llamarías a tu función de envío por AJAX/Fetch
            console.log('Formulario válido. Listo para enviar al servidor.');
            registrarCliente();

        } else {
            console.log('Formulario tiene errores. Revise los campos rojos.');
            showCustomModal('error', '❌ Formulario con errores', 'Revise los campos rojos.');
            // Opcional: enfocar el primer campo con error
            const firstError = form.querySelector('.input-error');
            if (firstError) {
                firstError.focus();
            }
        }
    });
});
