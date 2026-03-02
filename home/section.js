//Para volver al home desde el section.html
const volverHome = document.getElementById('volver_home');
volverHome.addEventListener('click', () => {
    window.location.href = '../home/index.php';
})
//Cargar la secion en la pagina section.html
window.addEventListener('DOMContentLoaded', async (event) => {
    event.preventDefault();
    //Obtener la seccion de la url
    const urlParams = new URLSearchParams(window.location.search);
    const seccion = urlParams.get('seccion');
    if (seccion == null) {
        alert("seccion no encontrada");
        return;
    }

    if (seccion == 'tienda') {
        //mostrar mensaje
        showCustomModal('info', 'Tienda', 'Todavia no se ha implementado la seccion de tienda.\nEstamos desarrollando esta seccion.\nGracias por su comprension.');
        //Volver al home luego de presionar fuer del modal o btn
        const overlay = document.getElementById('customModalOverlay');
        overlay.addEventListener('click', () => {
            window.location.href = '../home/index.php';
        });
        return;
    }

    //obtengo el id del contenedor donde voy a colocar el html.
    const contenedor = document.getElementById('contenedor');

    //Ahora busco en el servidor el html de la seccion.
    try {
        const respSeccion = await fetch(`../home/section.php?seccion=${seccion}`);

        //Verificar la respuesta HTTP
        if (!respSeccion.ok) {
            showCustomModal('error', 'Error del servidor', 'Error al obtener respuesta del servidor.');
            return;
        }

        /*
        const text = await respSeccion.text();
        console.log("Respuesta del servidor:", text);
        */

        //Convertir a JSON la respuesta.
        const resuSeccion = await respSeccion.json();

        //Verificar la respuesta del servidor.
        if (!resuSeccion.success) {
            showCustomModal('error', 'Error al procesar la seccion', resuSeccion.message);
            return;
        }

        //insertar el html en el contenedor
        contenedor.innerHTML = resuSeccion.html;

        //insertar el css en el link del html
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = resuSeccion.css;
        document.head.appendChild(link);

        // Insertar el título primero que el js. (se simula el mismo flujo del html SEO 'optimizador de busqueda para pagina de google', aunque no funciona lo mismo), nunca usar return cuando hay que cargar varias partes de un documento dinamicamente y se sigue una estructura, porque no cargara la pagina correctamente.
        document.title = resuSeccion.title && resuSeccion.title.trim() !== "" ? resuSeccion.title : seccion;

        // Solo insertar el JS si existe
        if (resuSeccion.js) {
            const script = document.createElement('script');
            script.src = resuSeccion.js;
            document.body.appendChild(script);
            //no se puede poner return si no hay erro critico porque sino romperia el flujo de carga y puede ser que debajo haya contenido para cargar.
        }

    } catch (error) {
        showCustomModal('error', 'Error', 'Error al obtener la seccion.\nVerifique y vuelva a intentarlo mas tarde.');
        //Darle 3 segundo de retraso para volver al home
        setTimeout(() => {
            window.location.href = '../home/index.php';
        }, 4000);
    }
})




