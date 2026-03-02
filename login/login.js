const volver_inicio = document.getElementById('logo-container');

volver_inicio.addEventListener('click', () => {
    window.location.href = '../home/index.php';
});

// Bloque para mostrar u ocultar contraseña.
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

togglePassword.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    togglePassword.classList.toggle('fa-eye');
    togglePassword.classList.toggle('fa-eye-slash'); // cambia el icono también
});

//Captar el evento de la ventna login cuando realize un submit a traves de la clase login-form
const loginForm = document.querySelector('.login-form');
loginForm.addEventListener('submit', async (event) => {
    event.preventDefault(); // Detener el comportamiento predeterminado del formulario

    //Obtener los datos del formulario
    const formData = new FormData(loginForm);
    const email = formData.get('email');
    const password = formData.get('password');

    //Verificar si marco la casilla de recordarme y proceder a guardar su gmail o no.
    const emailInput = document.getElementById('email');
    const rememberCheckbox = document.getElementById('remember-me');

    // Recuperar si hay email guardado
    const savedEmail = localStorage.getItem('rememberedEmail');
    if (savedEmail) {
        emailInput.value = savedEmail;
        rememberCheckbox.checked = true;
    }
    if (rememberCheckbox.checked) {
        localStorage.setItem('rememberedEmail', emailInput.value);
    } else {
        localStorage.removeItem('rememberedEmail');
    }

    //vamos a usar un excepcion de error en caso de que falle el proceso del login
    try {
        //Vamos a validar que ambos campos no este vacios
        if (email === '' || password === '') {
            showCustomModal('error', '❌ Error de inicio de sesión', '🔒 Por favor, ingresa tu correo y contraseña.');
        }
        //Validamos que el email sea correcto
        if (email.indexOf('@') === -1) {
            showCustomModal('error', '❌ Error de inicio de sesion', '🔒 Por favor, ingresa un correo electrónica válido.');
        }

        //Diccionario asociativo para enviar como json.
        var datos = {
            email: email,
            password: password
        }
        //Pasamos por metodo POST al servidor los datos para obtener una respuesta.
        showCustomModal('cargando', '⏳ Verificando email', 'Verificando si el correo se encuentra registrado');
        //Realizamos la busqueda en el servidor.
        const respuestaLogin = await fetch('../login/procesar_login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        //Verifico que no hay error del servidor
        if (!respuestaLogin.ok) {
            closeCustomModal();
            showCustomModal('error', '❌ Error del servidor', 'Error al iniciar sesion.\nEl servidor no esta respondiendo');
            return;
        }
        //Respuesta del servidor como resultado
        const resultadoLogin = await respuestaLogin.json();

        //Verifico que no hay error en la respuesta
        if (!resultadoLogin.success) {
            closeCustomModal();
            showCustomModal('warning', '🔒 Error', resultadoLogin.message);
            return;
        }

        if (resultadoLogin.success) {
            // Guardo token y datos del usuario en sessionStorage
            sessionStorage.setItem('token', resultadoLogin.token);
            sessionStorage.setItem('email', email);
            sessionStorage.setItem('idUser', resultadoLogin.idUser);
            sessionStorage.setItem('fotoUrl', resultadoLogin.fotoUrl);
            closeCustomModal();
            //Muestro un mensaje de bienvenida al cliente si existe.
            showCustomModal('success', '🔓 ¡HOLA! ', '🤗 Bienvenido nuevamente a 🏋️ FORTALEZA CROSS 🏋️ ');
            //Si no hay error entonces redirecciono al index para clientes registrados
            setTimeout(() => {
                window.location.href = resultadoLogin.redirect;
            }, 2000);
        }

    } catch (error) {
        showCustomModal('error', '❌ Error de inicio de sesión', 'Error al iniciar sesion.\nVerifique sus datos y vuelva a intentarlo');
    }
});



