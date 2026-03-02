//Evento de olvidaste tu contraseña, vamos a enviar un correo para resetear contraseña
const olvidaste_contrasena = document.getElementById('forgot-password');
olvidaste_contrasena.addEventListener('click', (event) => {
    event.preventDefault();
    //============= funciones del modal ====================
    //Activo el modal de olvidaste tu contraseña
    const modal_recuperar = document.getElementById('modal-recuperar');
    modal_recuperar.style.display = 'flex';
    //Cerrar modal al presionar el boton de cerrar
    document.getElementById('cerrar-modal').addEventListener('click', () => {
        modal_recuperar.style.display = 'none';
    });
    //Cerrar modal al presiona fuera de ella.
    window.addEventListener('click', (event) => {
        if (event.target === modal_recuperar) {
            modal_recuperar.style.display = 'none';
        }
    });
    //============= Procesar recuperacion ====================
    const enviar_recuperar = document.getElementById('enviar-recuperar');
    //Evento para enviar el correo de recuperacion
    enviar_recuperar.addEventListener('click', async () => {
        showCustomModal('cargando', ' ⏳ Procesando email...','Verificando que email sea valido y este registrado');
        const emailRecuperarInput = document.getElementById('email-recuperar'); // Obtener el campo de email
        const emailrecu = emailRecuperarInput.value.trim(); // Obtener el valor del campo de email
        const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;//Formato de Gmail correcto
         // Validar el campo de email
        if (emailrecu === "") {
            closeCustomModal();
            showCustomModal('error', '❌ Error de Validación', 'Por favor, ingresa tu correo electrónico (no puede estar vacio).');
            return;
        }
        if (!gmailRegex.test(emailrecu)) {
            closeCustomModal();
            showCustomModal('error', '❌ Formato incorrecto', 'El correo ingresado debe ser un Gmail válido (ej: usuario@gmail.com)')
            return;
        }

        try {
            // Enviar la solicitud al servidor usando fetch
            const respuestaRecuperar = await fetch(`ProcesarRecuperacionEmail.php?email=${encodeURIComponent(emailrecu)}`);

            // Verificar si la respuesta del servidor fue exitosa a nivel HTTP
            if (!respuestaRecuperar.ok) {
                closeCustomModal();
                showCustomModal('error', '❌ Error del servidor', `No se pudo procesar su solicitud (Código: ${respuestaRecuperar.status}).`);
                return;
            }

            // Parsear la respuesta JSON del servidor
            const resultadoRecuperar = await respuestaRecuperar.json();
             // Cerrar el modal de cargando
            closeCustomModal(); 

            // Manejar la respuesta lógica del servidor
            if (resultadoRecuperar.success) {
                // Cerrar el modal de cargando
                closeCustomModal(); 
                // Si el servidor respondió con éxito
                showCustomModal('success', '📨 Enlace Enviado', resultadoRecuperar.message);
                document.getElementById('modal-recuperar').style.display = 'none';
                document.getElementById('email-recuperar').value = '';
                return;
            } else {
                // Cerrar el modal de cargando
                closeCustomModal(); 
                // Si el servidor respondió con un error lógico (ej: email no existe)
                showCustomModal('warning', '⚠️ Error', resultadoRecuperar.message);
                return;
            }
            
        } catch (error) {
            // Manejar errores de conexión o del fetch
            closeCustomModal();
            showCustomModal('error', '❌ Error al enviar correo de recuperación', 'Hubo un problema de conexión. Verifique y vuelva a intentarlo en un momento.');
        }
    })
});

