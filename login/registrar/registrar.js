
//================= Funciones =====================
function cargar_video_CR() {
    // Cargar video gif.
    const cargarVideoCrearCuenta = "gif_crear_cuenta.mp4"; // Asegúrate de que esta ruta sea CORRECTA
    const video_CrearCuenta = document.getElementById('video-fondo-crearCuenta');
    video_CrearCuenta.src = cargarVideoCrearCuenta;
    video_CrearCuenta.load(); // Carga el video
    video_CrearCuenta.play() // Intenta reproducirlo
        .catch(error => {
            console.warn("La reproducción automática del video fue bloqueada o falló:", error);
            // Si la reproducción automática falla (por políticas del navegador),
            // puedes considerar mostrar un botón de "Play" al usuario si es necesario.
        });
}
//Volver al login
document.getElementById('logo-container').addEventListener('click', () => {
    window.location.href = '../login.php';
});

/*Volver al home */
document.getElementById('irHomeReg').addEventListener('click', () => {
    window.location.href = '../../home/index.php';
});


// Obtener contenedor del formulario 2 de registro.
const formPaso2 = document.getElementById("formRegistroPaso2");
// =========================================================
// FUNCIONES DE UTILIDAD PARA VALIDACIÓN VISUAL
// =========================================================
/**
 * Muestra un estado de validación para un campo.
 * @param {HTMLElement} inputElement - El elemento input a validar.
 * @param {boolean} isValid - True si es válido, False si es inválido.
 * @param {string} [message=''] - Mensaje a mostrar.
 */
function setValidationState(inputElement, isValid, message = '') {
    // Busca el contenedor padre que contiene el input y el icono/mensaje
    const formCampo = inputElement.closest('.form-campo');
    if (!formCampo) {
        console.warn('No se encontró .form-campo para el input:', inputElement);
        return;
    }
    const inputContainer = formCampo.querySelector('.input-container');
    const validationIcon = formCampo.querySelector('.validation-icon');
    const validationMessage = formCampo.querySelector('.validation-message'); // Ahora busca dentro del formCampo
    // Limpiar estados previos
    if (inputContainer) {
        inputContainer.classList.remove('valid', 'invalid');
    }
    if (validationIcon) {
        validationIcon.classList.remove('success', 'error');
        // No necesitas limpiar innerHTML si usas ::before con content: url()
        // validationIcon.innerHTML = '';
    }
    if (validationMessage) {
        validationMessage.textContent = '';
        validationMessage.classList.remove('success-text', 'error-text');
    }
    // Establecer nuevos estados
    if (message) { // Si hay un mensaje, muestra el texto
        if (validationMessage) {
            validationMessage.textContent = message;
            if (isValid) {
                validationMessage.classList.add('success-text');
            } else {
                validationMessage.classList.add('error-text');
            }
        }
    } else { // Si no hay mensaje, limpia el texto
        if (validationMessage) {
            validationMessage.textContent = '';
        }
    }
    if (isValid) {
        if (inputElement.value.trim() !== '') { // Solo si tiene contenido para mostrar "válido"
            if (inputContainer) inputContainer.classList.add('valid');
            if (validationIcon) validationIcon.classList.add('success');
        } else { // Si está vacío pero es "válido" (ej. no requerido), no mostrar icono verde
             // Esto es útil si un campo no es requerido y el usuario lo deja vacío.
             // En este caso, no muestra verde, solo quita el rojo.
            if (inputContainer) inputContainer.classList.remove('valid', 'invalid');
            if (validationIcon) validationIcon.classList.remove('success', 'error');
        }
    } else { // Si es inválido, siempre muestra rojo
        if (inputContainer) inputContainer.classList.add('invalid');
        if (validationIcon) validationIcon.classList.add('error');
    }
}
/**
 * Reinicia el estado visual de un campo (elimina colores e iconos).
 * @param {HTMLElement} inputElement - El elemento input a reiniciar.
 */
function resetValidationState(inputElement) {
    const formCampo = inputElement.closest('.form-campo');
    if (!formCampo) return;

    const inputContainer = formCampo.querySelector('.input-container');
    const validationIcon = formCampo.querySelector('.validation-icon');
    const validationMessage = formCampo.querySelector('.validation-message');

    if (inputContainer) {
        inputContainer.classList.remove('valid', 'invalid');
        // No es necesario manipular style.borderColor o boxShadow si las clases lo hacen
    }
    if (validationIcon) {
        validationIcon.classList.remove('success', 'error');
        // validationIcon.innerHTML = ''; // No necesario si usas ::before
    }
    if (validationMessage) {
        validationMessage.textContent = '';
        validationMessage.classList.remove('success-text', 'error-text');
    }
}

// =========================================================
// FUNCIONES DE VALIDACIÓN POR CAMPO (REUTILIZABLES)
// =========================================================
// Las mantengo aquí para que tengas el código completo en un solo lugar.
function validateNombre(nombre) {
    if (!nombre || nombre.trim() === '') {
        return { isValid: false, message: 'El nombre no puede estar vacío.' };
    }
    // Permite letras y espacios en medio de palabras, pero no al inicio/final ni múltiples seguidos
    if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ]+(?: [A-Za-zÁÉÍÓÚáéíóúÑñ]+)*$/.test(nombre)) {
        return { isValid: false, message: 'Solo letras y un espacio entre palabras (ej: Juan Carlos).' };
    }
    return { isValid: true, message: 'Nombre válido.' };
}

function validateApellido(apellido) {
    if (!apellido || apellido.trim() === '') {
        return { isValid: false, message: 'El apellido no puede estar vacío.' };
    }
    // Asegura solo letras sin espacios (tu validación original)
    if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ]+$/.test(apellido)) {
        return { isValid: false, message: 'El apellido debe contener solo letras y no espacios.' };
    }
    return { isValid: true, message: 'Apellido válido.' };
}

function validateDni(dni) {
    if (!dni || dni.trim() === '') {
        return { isValid: false, message: 'El DNI no puede estar vacío.' };
    }
    if (!/^\d{8,10}$/.test(dni)) {
        return { isValid: false, message: 'Debe tener entre 8 y 10 dígitos numéricos.' };
    }
    return { isValid: true, message: 'DNI válido.' };
}

function validateTelefono(telefono) {
    if (!telefono || telefono.trim() === '') {
        return { isValid: false, message: 'El teléfono no puede estar vacío.' };
    }
    // Regex ajustada para permitir solo 10 dígitos, sin guiones, etc.
    if (!/^\d{10}$/.test(telefono)) {
        return { isValid: false, message: 'Debe tener 10 dígitos numéricos (sin guiones ni espacios).' };
    }
    return { isValid: true, message: 'Teléfono válido.' };
}

function validateFechaNacimiento(fechaNacimiento) {
    if (!fechaNacimiento || fechaNacimiento.trim() === '') {
        return { isValid: false, message: 'La fecha de nacimiento no puede estar vacía.' };
    }
    // La validación de formato AAAA-MM-DD ya la maneja input type="date" en navegadores modernos
    // Aún así, un regex básico para asegurar formato y luego la lógica de fecha
    if (!/^\d{4}-\d{2}-\d{2}$/.test(fechaNacimiento)) {
        return { isValid: false, message: 'Formato de fecha inválido (AAAA-MM-DD).' };
    }
    const today = new Date().toISOString().split('T')[0];
    if (fechaNacimiento > today) {
        return { isValid: false, message: 'La fecha no puede ser futura.' };
    }
    // Opcional: Validar edad mínima, por ejemplo, mayor de 18
    const birthDate = new Date(fechaNacimiento);
    const ageDiffMs = Date.now() - birthDate.getTime();
    const ageDate = new Date(ageDiffMs); // miliseconds from epoch
    const age = Math.abs(ageDate.getUTCFullYear() - 1970);
    if (age < 10) {
        return { isValid: false, message: 'Debes ser mayor de 10 años.' };
    }
    return { isValid: true, message: 'Fecha válida.' };
}

function validateUsuario(usuario) {
    if (!usuario || usuario.trim() === '') {
        return { isValid: false, message: 'El usuario no puede estar vacío.' };
    }
    if (!/^[a-zA-Z]+$/.test(usuario)) {
        return { isValid: false, message: 'El usuario debe contener solo letras (sin espacios ni números).' };
    }
    // if (/\s/.test(usuario)) { // Redundante si la regex ya prohíbe espacios
    //     return { isValid: false, message: 'El usuario no puede contener espacios en blanco.' };
    // }
    return { isValid: true, message: 'Usuario válido.' };
}

function validatePassword(password) {
    if (!password || password.trim() === '') {
        return { isValid: false, message: 'La contraseña no puede estar vacía.' };
    }
    if (password.length < 4 || password.length > 16) {
        return { isValid: false, message: 'Debe tener entre 4 y 16 caracteres.' };
    }
    return { isValid: true, message: 'Contraseña válida.' };
}

function validateConfirmPassword(password, confirmPassword) {
    if (!confirmPassword || confirmPassword.trim() === '') {
        return { isValid: false, message: 'Confirma tu contraseña.' };
    }
    if (password !== confirmPassword) {
        return { isValid: false, message: 'Las contraseñas no coinciden.' };
    }
    return { isValid: true, message: 'Contraseñas coinciden.' };
}

// =========================================================
// EVENT LISTENERS PARA VALIDACIÓN EN TIEMPO REAL
// =========================================================
// Mapeo de campos a sus funciones de validación
const fieldValidators = {
    nombre: validateNombre,
    apellido: validateApellido,
    dni: validateDni,
    telefono: validateTelefono,
    fechaNacimiento: validateFechaNacimiento,
    usuario: validateUsuario,
    password: validatePassword,
    // confirmPassword necesita acceso a password, se maneja de forma especial
    confirmPassword: (value) => { // Función anónima para pasar password como argumento
        const passwordValue = document.getElementById('password').value;
        return validateConfirmPassword(passwordValue, value);
    }
};

// Añadir event listeners para cada input
Object.keys(fieldValidators).forEach(fieldId => {
    const inputElement = document.getElementById(fieldId);
    if (inputElement) {
        // Usa 'input' para validación al escribir
        inputElement.addEventListener('input', () => {
            const validationResult = fieldValidators[fieldId](inputElement.value);
            setValidationState(inputElement, validationResult.isValid, validationResult.message);
            
            // Lógica especial para password y confirmPassword para revalidar el otro
            if (fieldId === 'password' || fieldId === 'confirmPassword') {
                const passwordInput = document.getElementById('password');
                const confirmPasswordInput = document.getElementById('confirmPassword');
                
                // Si ambas existen y no están vacías, revalidar confirmPassword
                if (passwordInput && confirmPasswordInput && (passwordInput.value || confirmPasswordInput.value)) {
                    const confirmResult = validateConfirmPassword(passwordInput.value, confirmPasswordInput.value);
                    setValidationState(confirmPasswordInput, confirmResult.isValid, confirmResult.message);
                } else if (confirmPasswordInput) {
                    // Si alguna está vacía, limpiar el estado de confirmPassword
                    resetValidationState(confirmPasswordInput);
                }
            }
        });

        // Opcional: También podrías usar 'blur' si quieres validar al salir del campo
        // inputElement.addEventListener('blur', () => {
        //     const validationResult = fieldValidators[fieldId](inputElement.value);
        //     setValidationState(inputElement, validationResult.isValid, validationResult.message);
        // });
    }
});

//=================== EJECUCION PRINCIPAL  =====================
// Evento automático para mostrar el video y preparar la página
document.addEventListener('DOMContentLoaded', () => {
    cargar_video_CR(); // Llama a la función para cargar el video al inicio
    // Obtener los formularios del contenedor derecho.
    const paso1 = document.getElementById("registroPaso1");
    const paso2 = document.getElementById("registroPaso2");

    const formPaso1 = document.getElementById("formRegistroPaso1");
    const formPaso2 = document.getElementById("formRegistroPaso2");
    const btnAtras = document.querySelector(".btn-atras");

    //Evento para pasar al siguiente formulario (datos clientes)
    formPaso1.addEventListener("submit", async (e) => {
        e.preventDefault();
        //Obtenemos gmail ingresado
        var gmail = document.getElementById('email').value.trim();  

        // Regex para validar un Gmail válido
        const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

        //Verificamos el gmail si existe si no lo registramos
        try{
            //Validamos Gmail
            if (gmail === "") {
                showCustomModal('error', '❌ Error con el gmail ingresado', 'El gmail ingresado no es valido.\nVerifique y vuelva a intentarlo'); 
                return;

            }else if (!gmailRegex.test(gmail)) {
                showCustomModal('error', '❌ Formato incorrecto', 'El correo ingresado debe ser un Gmail válido (ej: usuario@gmail.com)')
                return;

            }else{
                showCustomModal('cargando', '⏳ Verificando correo','Verificando si el correo ingresado esta registrado');
                
                const datos = {email: gmail};
                
                //Consultar si el correo existe en la BD
                const respuestaGmail = await fetch(`ProcesarGmail.php`,{
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(datos)
                });
                //Verificamos el resultado de la consulta al servidor
                if (!respuestaGmail.ok){
                    closeCustomModal();
                    showCustomModal('error', '❌ Error al consultar el servidor', 'Error al obtener respuesta del servidor.\nVerifique y vuelva a intentarlo');
                    return;
                } 
                //Respuesta del servidor como resultado
                const resultadoGmail = await respuestaGmail.json();

                if (resultadoGmail.success === false){
                    closeCustomModal();
                    showCustomModal('warning','⚠️ Stop', resultadoGmail.message);
                    return;
                }
                // Guardar token en sesión (temporalmente)
                sessionStorage.setItem('token_registro', resultadoGmail.token);
                sessionStorage.setItem('email_registro', gmail); // opcional si querés validarlo después

                //Si todo sale bien pasamos al sigueinte formulario
                paso1.classList.remove("active");
                paso2.classList.add("active");

                //Volver a atras
                btnAtras.addEventListener("click", () => {
                    paso2.classList.remove("active");
                    paso1.classList.add("active");
                    formPaso2.reset(); //se resetea el formulario 2 cuando vuelve atras.
                });                
            }
            closeCustomModal();
            showCustomModal('info', ' Email disponible' , '✅ El correo ingresado es valido.\nPuedes continuar con el registro');
        }catch(error){
            showCustomModal('error', '❌ Error en la validacion', 'Error al validar el correo.\nVerifique y vuelva a intentarlo');
        }
    });

    //Evento del segundo formulario para registrar al cliente.
    formPaso2.addEventListener("submit", async (e) => {
        e.preventDefault();
        let formIsValid = true; //Verificar si esta valido el formulario
        //Procesar Registro del nuevo cliente
        try{
            const formData = new FormData(formPaso2);

            // 1. Ejecuta todas las validaciones para asegurar que todos los campos estén cubiertos.
            // Esto actualizará los estados visuales finales.
            for (const fieldId of Object.keys(fieldValidators)) {
                const inputElement = document.getElementById(fieldId);
                if (inputElement) {
                    let validationResult;
                    if (fieldId === 'confirmPassword') {
                        const passwordValue = document.getElementById('password').value;
                        validationResult = validateConfirmPassword(passwordValue, inputElement.value);
                    } else {
                        validationResult = fieldValidators[fieldId](inputElement.value);
                    }
                    
                    setValidationState(inputElement, validationResult.isValid, validationResult.message);
                    if (!validationResult.isValid) {
                        formIsValid = false;
                    }
                }
            }

            // 2. Si hay algún error, detiene el proceso y muestra un modal general.
            if (!formIsValid) {
                closeCustomModal(); // Asegura que el modal de "Cargando" no se muestre
                showCustomModal('error', '❌ Error en el Formulario', 'Por favor, corrige los campos marcados en rojo.');
                return; // Detiene el envío del formulario
            }
            
            // 3. Si todas las validaciones pasan, muestra el modal de cargando y procede con el envío al backend.
            showCustomModal('cargando', '⏳ Registrando cliente...', 'Registrando al cliente. Espere por favor');

            const datos = {
                nombre: formData.get('nombre'),
                apellido: formData.get('apellido'),
                dni: formData.get('dni'),
                telefono: formData.get('telefono'),
                fecha_nacimiento: formData.get('fechaNacimiento'),
                usuario: formData.get('usuario'),
                password: formData.get('password'),
                confipassword: formData.get('confirmPassword'),
                email: sessionStorage.getItem('email_registro'),
                token: sessionStorage.getItem('token_registro')
            };

            //Si todo sale bien pasamos al registro en el servidor.
            const respuestaRegistro = await fetch('ProcesarRegistro.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(datos)
            });
            //Verificamos el resultado de la consulta al servidor
            if (!respuestaRegistro.ok){
                closeCustomModal();
                showCustomModal('error', '❌ Error al consultar el servidor', 'Error al obtener respuesta del servidor.\nVerifique y vuelva a intentarlo');
                return;
            } 
            //Respuesta del servidor como resultado
            const resultadoRegistro = await respuestaRegistro.json();

            if (!resultadoRegistro.success){
                closeCustomModal();
                showCustomModal('error', '❌ Error al registrar', resultadoRegistro.message);
                return;
            }
            //Verificamos si confirmo o no luego de que todo salga bien.
            //necesario cerrar el primer modal 
            closeCustomModal();
            //Abro el modal de esperando confirmacion
            showCustomModal('cargando', '⏳ ESPERANDO...⏳','Le hemos enviado un 📨 correo para confirmar su registro.\n Verifique su 📩 bandeja de entrada (en su gmail) y presione en "confirmar" para proceder con el registro.\n⚠️¡EL ENLACE DE CONFIRMACION EXPIRA🕒 CUMPLIENDO 2 MINUTO!⚠️');

            //implementar backen para controlar el estado del token (si caduco o no).
            var TiempoTranscurrido = 0; //Contador de tiempo.
            var TiempoMaximo = 120; //120 segundo = 2 minuto.
            var TiempoIntervalo = 5000; // 5 segundo para repetir.
            var tokenActivo =  sessionStorage.getItem ('token_registro'); // Token de validacion.
            var gmailActivo = sessionStorage.getItem('email_registro');

            try {//Se pone intervaloID porque es el primer intervalo para sacarlo del bucle en caso de que se termine el tiempo o se confirme.
                const IntervaloID = setInterval(async () => {
                    TiempoTranscurrido += TiempoIntervalo / 1000; // este calculo significa que: En timepotranscurrido se guardara, el tiempointervalo que es 3000seg pero lo dividimos entre 1000 para que nos de 3seg lo cual nos sirbe para llegar a 60seg, mientras que 3000seg nos sirbe para que el setintervalo interprete 3seg (3000) en su formato porque trabaja con 3000ms.
                    console.log(TiempoTranscurrido); //Borrar 
                    //Consultar a la bd si confirmo o no
                    const respuestaConfi = await fetch(`VerificarConfirmacion.php?token=${tokenActivo}&email=${gmailActivo}`);
                    //Varificar que no hay error en la consulta al servidor
                    if (!respuestaConfi.ok){
                        closeCustomModal();
                        showCustomModal('error', 'Error al consultar el servidor', 'Error al obtener respuesta del servidor.\nVerifique y vuelva a intentarlo');
                        return; 
                    }
                    const resultadoConfi = await respuestaConfi.json();

                    if (!resultadoConfi.success){
                        closeCustomModal();
                        showCustomModal('error','Error al obtener respuesta del servidor', resultadoConfi.message);
                        return;
                    }
                    
                    // Si el token ya no está
                    if (resultadoConfi.estado){
                        clearInterval(IntervaloID);
                        closeCustomModal();
                        showCustomModal('success','¡Registro Exitoso!','Necesitamos que ingrese sus datos nuevamente en el login');
                        setTimeout(() => {
                            window.location.href = '../../login/login.php';
                        }, 5000);
                        return;
                    } else if (resultadoConfi.expirado) {
                        // Token ya expiró (backend lo eliminó pero no registró al usuario)
                        clearInterval(IntervaloID);
                        closeCustomModal();
                        showCustomModal('warning','Tiempo agotado','El enlace ha expirado. Por favor, vuelve a registrarte.');
                        setTimeout(() => {
                            window.location.href = 'registrar.php';
                        }, 5000);
                        return;
                    } else if (TiempoTranscurrido >= TiempoMaximo){
                        clearInterval(IntervaloID);
                        closeCustomModal();
                        showCustomModal('warning','Tiempo agotado','No se confirmó a tiempo el enlace.');
                        setTimeout(() => {
                            window.location.href = 'registrar.php'; 
                        }, 5000);
                    }
                    
                }, TiempoIntervalo)
            } catch (error) {
                clearInterval(IntervaloID);
                closeCustomModal();
                showCustomModal('error', 'Error inesperado', error.message);
            }
        }catch(error){
            closeCustomModal();
            showCustomModal('error', 'Error al registrar', 'Verifique los datos y vuelva a intentarlo por favor.');
        }
    })
});

