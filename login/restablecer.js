document.addEventListener('DOMContentLoaded', () => {
    // Declaración de variables del DOM en el ámbito global
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const formRestablecer = document.getElementById('from-restablecer');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

    // Nuevos elementos para el diseño visual
    const wrapperPassword = document.getElementById('wrapper-password');
    const wrapperConfirmPassword = document.getElementById('wrapper-confirm-password');
    const errorPassword = document.getElementById('error-password');
    const errorConfirmPassword = document.getElementById('error-confirm-password');
    
    // -------------  Lógica de validación en tiempo real ----------------
    function VerificarContrasenas() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        // Limpiar validaciones anteriores
        passwordInput.setCustomValidity('');
        confirmPasswordInput.setCustomValidity('');
        wrapperPassword.classList.remove('error');
        wrapperConfirmPassword.classList.remove('error');
        errorPassword.style.display = 'none';
        errorConfirmPassword.style.display = 'none';

        // Validar que la contraseña no esté vacía
        if (password.length === 0) {
            return; // No validar si el campo está vacío
        }
        // Validar que las contraseñas coincidan
        if (password !== confirmPassword) {
            confirmPasswordInput.setCustomValidity('Las contraseñas no coinciden.');
            wrapperConfirmPassword.classList.add('error');
            errorConfirmPassword.textContent = 'Las contraseñas no coinciden.';
            errorConfirmPassword.style.display = 'block';
        }
    }
    // Agregar 'listeners' a los eventos 'input' de los campos para la validación en tiempo real
    passwordInput.addEventListener('input', VerificarContrasenas);
    confirmPasswordInput.addEventListener('input', VerificarContrasenas);

    //---------------- Lógica para mostrar u ocultar la contraseña ------------------
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        });
    }
    if (toggleConfirmPassword && confirmPasswordInput) {
        toggleConfirmPassword.addEventListener('click', () => {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            toggleConfirmPassword.classList.toggle('fa-eye');
            toggleConfirmPassword.classList.toggle('fa-eye-slash');
        });
    }
    // -------------- Evento de envío del formulario -------------------
    formRestablecer.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formRestablecer = document.getElementById('from-restablecer');
        
        VerificarContrasenas(); //Verificar que las contraseñas sean iguales

        //Procesamos la respuesta
        try{
            // Mostrar el modal de carga hasta que se procese la solicitud
            showCustomModal('cargando', '⏳ Validando Contraseña ingresadas...','Verificando que la contraseña sea correcta...');

            //Diccionario asociativo para enviar como json.
            var datos = {
                token: formRestablecer.token.value,
                password: formRestablecer.password.value,
                confirm_password: formRestablecer.confirm_password.value
            }
            //Pasamos por metodo POST al servidor los datos para obtener una respuesta.
            const respuestaRestablecer = await fetch('restablecer.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(datos)
            });

            //Verificar la respuesta HTTP del servidor
            if (!respuestaRestablecer.ok) {
                closeCustomModal();
                showCustomModal('error','❌ Error al obtener respuesta del servidor','Por favor, intente nuevamente en unos momentos.');
                return;
            }

            const resultadoRestablecer = await respuestaRestablecer.json();
            console.log("Respuesta del servidor:", resultadoRestablecer);

            //Si hubo error al actualizar 
            if (!resultadoRestablecer.success) {
                closeCustomModal();
                showCustomModal('error','❌ Error al restablecer contraseña',resultadoRestablecer.message);
                return;
            }
            //Si todo salio bien
            if (resultadoRestablecer.success) {
                closeCustomModal();
                showCustomModal('success','✅ Restablecimiento de contraseña','La contraseña ha sido cambiada correctamente');
                setTimeout(() => {
                    window.location.href = '../login/login.php';
                    return;
                }, 3000);
            }
        }catch(error){
            console.log(error);
            showCustomModal('error', '❌ Error al restablecer contraseña', 'Verifique los datos y vuelva a intentarlo por favor.');
            return; 
            
        }
    });
});