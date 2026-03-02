window.addEventListener('DOMContentLoaded', () => {
    //Obtenemos los datos del url por parametros.
    const params = new URLSearchParams(window.location.search);
    const estado = params.get('estado');
    const mensaje = params.get('mensaje');

    if (estado === 'ok') {
        showCustomModal('success', '¡Gracias por registrarte!', mensaje);
        setTimeout(() => {
            window.location.href = '../../login/login.php';
        }, 5000);
    } else if (estado === 'error') {
        showCustomModal('error', 'Error de confirmación', mensaje || 'Algo salió mal.');
        setTimeout(() => {
            window.location.href = '../../login/registrar/registrar.php'; // volver a intentar
        }, 5000);
    } else {
        showCustomModal('warning', 'Estado desconocido', 'No se pudo determinar el resultado.');
    }
})