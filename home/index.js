//Imagen logo es el icono de la pagina.
const imaLogo = document.getElementById('logo');
imaLogo.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
})

//Para insertar codigo HTML en cada divisor desde JS
document.addEventListener('DOMContentLoaded', () => {
    //Usar querySelectorAll para obtener todos los elementos con la clase 'divisor'
    var divisores = document.querySelectorAll('.divisor');

    // Contenido HTML a insertar (lo definimos una sola vez)
    const contenidoDivisor = `
        <div class="texto-desplazado">
            <span>¡BIENVENIDOS A FORTALEZA CROSS!</span> &bull;
            <span>¡TRANSFORMA TU CUERPO Y MENTE!</span> &bull;
            <span>¡DESCUENTOS ESPECIALES!</span> &bull;
            <span>¡UNETE A NUESTRA COMUNIDAD IMPARABLE!</span> &bull;
            <span>¡BIENVENIDOS A FORTALEZA CROSS!</span> &bull;
            <span>¡TRANSFORMA TU CUERPO Y MENTE!</span> &bull;
            <span>¡DESCUENTOS ESPECIALES!</span> &bull;
            <span>¡UNETE A NUESTRA COMUNIDAD IMPARABLE!</span>
        </div>
    `;
    // Recorrer cada elemento encontrado y asignar el innerHTML
    divisores.forEach(divisor => {
        divisor.innerHTML = contenidoDivisor;
    });

   document.getElementById('form-contacto').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const msg = document.getElementById('contacto-msg');
        const btn = document.getElementById('btn-enviar'); // Seleccionamos el botón
    // 1. Mostrar "Enviando..." y deshabilitar el botón
        btn.textContent = 'Enviando su mensaje...';
        btn.disabled = true;
        msg.textContent = ''; // Limpiamos el mensaje anterior

        const dates = new FormData(form);
        
        try {
            const respForm = await fetch(`../home/formulario_contacto/form_contacto.php`,{ 
                method: 'POST', 
                body: dates}
            );

            const texto = await respForm.text();
            // si la respuesta es JSON válido:
            const data = JSON.parse(texto);

            // 2. Manejar la respuesta
            if (data.ok) {
                msg.textContent = '¡Mensaje enviado! Te responderemos pronto.';
                msg.style.color = '#0f1c24'; // Le damos color al mensaje de éxito
                //agrandamos la letra
                msg.style.fontSize = '1.5em';
                form.reset();
            } else {
                msg.textContent = 'No se pudo enviar: ' + (data.msg || 'intenta más tarde.');
                msg.style.color = 'red'; // Le damos color al mensaje de error
                msg.style.fontSize = '1.5em';
            }
        } catch (err) {
            console.error("Error:", err);
            msg.textContent = 'Error de conexión. Probá de nuevo.';
        } finally {
            // 3. Volver a habilitar el botón y cambiar su texto original
            btn.textContent = 'Enviar';
            btn.disabled = false;

        }
    });

});


