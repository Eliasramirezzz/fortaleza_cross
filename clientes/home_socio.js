// ---------------- Variables Globales ---------------------
let dataUser = null;
let dataProcesUser = null;
let dataListPlanes = null;
let dataListClases = null;
const TELEFONO_GIMNASIO = "543644222296"; // Reemplaza con el numero del gym

//---------------  seccion noticia o publicidad --------------- 
//Elementos del scroll
const scrollContainer = document.getElementById("tarjetasScroll");
const btnLeft = document.getElementById("scrollLeft");
const btnRight = document.getElementById("scrollRight");
// Cantidad de desplazamiento 
const scrollAmount = 300;

btnLeft.addEventListener("click", () => {
    scrollContainer.scrollBy({
        left: -scrollAmount,
        behavior: "smooth"
    });
});
btnRight.addEventListener("click", () => {
    scrollContainer.scrollBy({
        left: scrollAmount,
        behavior: "smooth"
    });
});
// ---------------  Parte para el header --------------- 
const menuToggle = document.getElementById("menuToggle");
const navMenu = document.getElementById("navMenu");
const logo = document.getElementById("logo");

// Mostrar / ocultar menú hamburguesa
menuToggle.addEventListener("click", () => {
    navMenu.classList.toggle("active");
});

// Submenús: abrir/cerrar con click
document.querySelectorAll(".submenu-toggle").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.preventDefault();
        const parent = btn.parentElement;
        parent.classList.toggle("open");
    });
});

//Subir al principio cuando se presione en el icono de la pagina. 
logo.addEventListener("click", () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
})

//Cargar la foto del usuario en el header
// La variable recibida es 'urlfoto'
function CargarFotoUsuario(urlfoto) {
    const userIconContainer = document.getElementById('userIconContainer');

    if (!userIconContainer) {
        console.error("No se encontró el contenedor de icono de usuario.");
        return;
    }

    // 💥 CORRECCIÓN CRUCIAL: Usar 'urlfoto' en lugar de 'fotoUrl'
    const img = document.createElement('img');
    img.src = urlfoto; // <--- ¡USAR LA VARIABLE CORRECTA!
    img.alt = 'Foto de perfil';
    img.classList.add('icon', 'profile-pic');
    img.id = 'userIcon';

    // Reemplazar siempre, incluso si es la default, para que aplique el estilo circular.
    userIconContainer.innerHTML = ''; // Limpiar el contenedor (quita el <i>)
    userIconContainer.appendChild(img); // Añadir la imagen

}

// ----------------- Ver datos del socio en el estado -----------------
//Manejo del modal
var datosSocios = document.getElementById('ENvalor');
var contModalS = document.getElementById('modalSocio');
var cerrarModalS = document.getElementById('closeModal');

function CargarDatosAlModal() {
    var infoSocio = document.getElementById('socio-info');
    var infoPlan = document.getElementById('plan-info');

    // Cargo info personal
    infoSocio.innerHTML = `
        <div class="info-group">
            <strong>Nombre:</strong>
            <span id="modalNombre">${dataUser.nombre} ${dataUser.apellido}</span>
        </div>
        <div class="info-group">
            <strong>Fecha de alta:</strong>
            <span id="modalFecha">${dataUser.fecha_alta}</span>
        </div>
        <div class="info-group">
            <strong>Email:</strong>
            <span id="modalEmail">${dataUser.email}</span>
        </div>
        <div class="info-group">
            <strong>Teléfono:</strong>
            <span id="modalTelefono">${dataUser.telefono}</span>
        </div>
    `;

    // Cargo todos los planes
    let planesHTML = dataProcesUser.map(plan => {
        const estadoClase = plan.estado_plan === "Al dia" || plan.estado_plan === "Sin pago" ? "activo" : "inactivo";

        return `
            <div class="plan-card">
                <div class="info-group" id="TitlePlan">
                    <strong>Plan:</strong>
                    <span>${plan.Nombre_Membresia} (${plan.Duracion_Membresia} días)</span>
                </div>
                <div class="info-group">
                    <strong>Estado:</strong>
                    <span class="estado ${estadoClase}">${plan.estado_plan}</span>
                </div>
                <div class="info-group">
                    <strong>Último Pago:</strong>
                    <span>${plan.ultimo_pago ?? "Sin registro"}</span>
                </div>
                <div class="info-group">
                    <strong>Vence hasta:</strong>
                    <span>${plan.vence_hasta ?? "N/A"}</span>
                </div>
                <div class="info-group">
                    <strong>Días restantes:</strong>
                    <span>${plan.dias_restantes ?? 0}</span>
                </div>
            </div>
        `;
    }).join("");

    infoPlan.innerHTML = planesHTML;
}

datosSocios.addEventListener('click', () => {
    // Añade la clase 'modal-show' para activar la animación
    contModalS.classList.add('modal-show');
    // Añade la clase 'no-scroll' al body para bloquear el scroll de la página
    document.body.classList.add('no-scroll');
    //Cargo todo los datos del socio en el modal
    CargarDatosAlModal();

})

cerrarModalS.addEventListener('click', () => {
    // Elimina la clase 'modal-show' para ocultar
    contModalS.classList.remove('modal-show');
    // Elimina la clase 'no-scroll' del body para permitir el scroll de la página
    document.body.classList.remove('no-scroll');
})

window.addEventListener('click', (event) => {
    if (event.target === contModalS) {
        // Elimina la clase 'modal-show' si se hace clic fuera
        contModalS.classList.remove('modal-show');
        // Elimina la clase 'no-scroll' del body para permitir el scroll de la página
        document.body.classList.remove('no-scroll');
    }
})

// ----------------------------- Seccion de Planes-----------------------------
// Función para abrir el modal con las clases del plan
function mostrarVerClases(plan) {
    // Referencia al contenedor principal del modal
    const modal = document.getElementById("modalDetalleClase");
    // Referencias internas
    const titulo = document.getElementById("tituloModal");
    const tbodyClases = document.getElementById("tbodyClases");

    // Título dinámico
    titulo.textContent = `Clases del plan: ${plan.nombre_plan}`;

    // Filtrar clases de este plan (por id_membresia)
    const clasesPlan = dataListClases.filter(
        // Asegúrate de que los tipos de datos coincidan (int vs string)
        c => c.id_membresia == plan.id_membresia
    );

    // Renderizar filas
    if (clasesPlan.length > 0) {
        tbodyClases.innerHTML = clasesPlan.map(clase => `
            <tr>
                <td>${clase.nombre_entrenamiento}</td>
                <td>${clase.dia_semana}</td>
                <td>${clase.hora_inicio} - ${clase.hora_fin}</td> 
                <td>${clase.duracion_minutos} min</td>
                <td class="trainer-name">${clase.nombre_entrenador} ${clase.Apellido_Entrenador}</td>
            </tr>
        `).join('');
    } else {
        tbodyClases.innerHTML = `
            <tr>
                <td colspan="4" style="text-align:center; color:#999;">
                    No hay clases asignadas. (Los Horario personalizado no se muestran aqui).\nLas clases pueden variar según la disponibilidad y el plan contratado.
                </td>
            </tr>`;
    }

    // Mostrar el modal principal
    modal.style.display = "block";

    // Botón cerrar
    const closeBtn = document.getElementById("closeDetalleClase");
    closeBtn.onclick = () => modal.style.display = "none"; // Cierra el modal principal

    // Cerrar modal al hacer click fuera
    window.onclick = (event) => {
        if (event.target === modal) modal.style.display = "none"; // Cierra el modal principal
    };
}

// --------------------------Seccion de Inscripcion por whatsapp--------------------------
/**
* Genera la URL de WhatsApp con un mensaje predefinido para la inscripción.
* @param {object} clase - Objeto con los detalles de la clase.
* @returns {string} La URL completa de la API de WhatsApp.
 */
function generarEnlaceInscripcionWhatsapp(plan) {
    const nombrePlan = plan.nombre_plan || 'Plan Desconocido';
    const emailSocio = sessionStorage.getItem('email') || '[Email no encontrado]';
    const nombreSocio = sessionStorage.getItem('nombreCompletoSocio') || '[Nombre no proporcionado]';

    // 2. Construcción del Mensaje (más específico para inscripción a plan)
    const mensaje = `👋 ¡Hola! Deseo inscribirme al siguiente plan en Fortaleza Cross 💪:

*Plan Seleccionado:* ${nombrePlan}
*Mi Email:* ${emailSocio} 
*Mi Nombre:* ${nombreSocio}

Por favor, envíenme los pasos a seguir para completar la inscripción/pago y comenzar a entrenar. ¡Gracias!👋 `;

    // Codifica el mensaje para que sea seguro en la URL
    const mensajeCodificado = encodeURIComponent(mensaje);

    // Construye la URL final
    const url = `https://api.whatsapp.com/send?phone=${TELEFONO_GIMNASIO}&text=${mensajeCodificado}`;

    return url;
}

// -------------------------  Modal de Pagos --------------------------
const realizarPagos = document.getElementById('navPagos');
const modalPagos = document.getElementById('modalPago');
const closeModalPagos = document.getElementById('closePago');
const SelectPlanes = document.getElementById('planPago');
const mensajePago = document.getElementById('mensajePago');
const enviarPagoBtn = document.getElementById('enviarPago');
const body = document.body; // Referencia al body
const NUMERO_ADMIN_WHATSAPP = '543644222296';

//Abrir modal
realizarPagos.addEventListener('click', () => {
    modalPago.style.display = 'block';
    body.classList.add('modal-open'); // Bloquea el scroll
    let filaPlanesSelect = '<option value="" disabled selected>Sus planes</option>';
    dataProcesUser.forEach(element => {
        filaPlanesSelect += `
        <option value="${element.Nombre_Membresia}">${element.Nombre_Membresia}</option>
        `
    });
    SelectPlanes.innerHTML = filaPlanesSelect;
    document.getElementById('mensajePago').value = '';
    // Inicializa el mensaje al abrir el modal
    actualizarMensajePago();

})
//Cerrar modal
closeModalPagos.addEventListener('click', () => {
    modalPago.style.display = 'none';
    body.classList.remove('modal-open'); // Restaura el scroll
})
//Cerrar modal al presiona fuera de ella.
window.addEventListener('click', (event) => {
    if (event.target === modalPago) {
        modalPago.style.display = 'none';
        body.classList.remove('modal-open'); // Restaura el scroll
    }
})
//Enviar Mensaje de Pago al API de Whatsapp
enviarPagoBtn.addEventListener('click', () => {

    // tomamos el contenido actual del textarea
    const planSeleccionado = SelectPlanes.value;
    const mensajeFinal = mensajePago.value.trim(); // Tomamos lo que el usuario modificó en el textarea

    //  Validación Básica
    if (!planSeleccionado || planSeleccionado === 'Sus planes') {
        showCustomModal('info', '¡Aviso!', 'Por favor, selecciona un plan de la lista antes de enviar.');
        return;
    }

    // Reemplazamos los saltos de línea (\n) por la codificación de WhatsApp (%0A)
    const mensajeCodificado = encodeURIComponent(mensajeFinal);

    const urlWhatsApp = `https://api.whatsapp.com/send?phone=${NUMERO_ADMIN_WHATSAPP}&text=${mensajeCodificado}`;

    // Abrir y Cerrar
    window.open(urlWhatsApp, '_blank');

    modalPago.style.display = 'none';
    document.body.classList.remove('modal-open');

});

function actualizarMensajePago() {
    const planSeleccionado = SelectPlanes.value;
    const emailSocio = sessionStorage.getItem('email');

    // Si no se ha seleccionado un plan (opción inicial), limpiar el textarea o mostrar instrucción
    if (!planSeleccionado || planSeleccionado === 'Sus planes') {
        mensajePago.value = '';
        mensajePago.placeholder = "Seleccione un plan para generar el mensaje automático.";
        return;
    }
    // Construcción del Mensaje Automático
    let mensajeCompleto = `¡Hola, soy ${emailSocio}!
    Quiero solicitar el pago u activación del siguiente plan.

    PLAN SELECCIONADO: ${planSeleccionado}

    Por favor, envíame las instrucciones para realizar el pago. ¡Gracias!`;

    // Asignar el mensaje generado al textarea
    mensajePago.value = mensajeCompleto;
    mensajePago.placeholder = ""; // Limpiamos el placeholder una vez que hay contenido
}
SelectPlanes.addEventListener('change', actualizarMensajePago);

// ================================= Ejecucion automatica  =================================
document.addEventListener('DOMContentLoaded', async (e) => {
    e.preventDefault();
    //Elementos del DOM
    const id_usuario = sessionStorage.getItem('idUser');
    let FotoUserURL = sessionStorage.getItem('fotoUrl');

    showCustomModal('cargando', '⏳ Cargando la pagina', 'Espere por favor, mientras cargamos sus datos');

    //Verificar si hay un id
    if (!id_usuario || id_usuario === "" || id_usuario == null) {
        closeCustomModal();
        showCustomModal('error', '❌ Error al ingresar', 'No se encuentra disponible la pagina para este usuario, intente nuevamente mas tarde 😢');
        setTimeout(() => {
            window.location.href = '../login/login.php';
        }, 5000);
        return;
    }
    // Verificamos si hay una url de foto de usuario
    const defaultUrl = 'Fortaleza_Cross/img/ImgUser/user_default.png'; // Ruta por defecto
    if (!FotoUserURL || FotoUserURL === "" || FotoUserURL == null) {
        FotoUserURL = defaultUrl;
        sessionStorage.setItem('fotoUrl', FotoUserURL);
    }

    // 2. Cargar la foto del usuario en el header
    // Llamamos a la función SIEMPRE que tengamos una URL válida (ya sea la personalizada o la por defecto).
    CargarFotoUsuario(FotoUserURL);

    // ------------------ Procesamiento de modo seguro y sincronico con el servidor -----------
    try {
        //Pedimos al servidor los datos del cliente
        const respuDataUser = await fetch(`../clientes/Modelos/ObtenerDatosCliente.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_usuario: id_usuario })
        })
        //Pedimos al servidor los datos del estado del cliente
        const respuDataPUser = await fetch(`../clientes/Modelos/ProcesarEstadoCliente.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_usuario: id_usuario })
        });
        const respuDataAllPlanes = await fetch(`../clientes/Modelos/ObtenerPlanes.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_usuario: id_usuario })
        });

        //Verificar problemas del servidor.
        if (!respuDataUser.ok || !respuDataPUser.ok || !respuDataAllPlanes.ok) {
            closeCustomModal();
            showCustomModal('error', '❌ Error del servidor', 'Error al procesar los datos del cliente.\nEl servidor no esta respondiendo 😢');
            //volver al login
            setTimeout(() => {
                window.location.href = '../login/login.php';
            }, 3000);
            return;
        };

        const resultDataUser = await respuDataUser.json();
        const resultDataPUser = await respuDataPUser.json();
        const resultDatasAllPlanes = await respuDataAllPlanes.json();

        // Validar errores graves
        if (!resultDataUser.success) {
            closeCustomModal();
            showCustomModal('error', '❌ Error al obtener datos del cliente', resultDataUser.message);
            setTimeout(() => window.location.href = '../login/login.php', 3000);
            return;
        }
        if (!resultDataPUser.success) {
            closeCustomModal();
            showCustomModal('error', '❌ Error al procesar estado', resultDataPUser.message);
            setTimeout(() => window.location.href = '../login/login.php', 3000);
            return;
        }
        if (!resultDatasAllPlanes.success) {
            closeCustomModal();
            showCustomModal('error', '❌ Error al obtener planes', resultDatasAllPlanes.message);
            setTimeout(() => window.location.href = '../login/login.php', 3000);
            return;
        }

        // ¿Tiene membresía?
        var TieneMembresia = null;
        if (resultDataPUser.sinMembresia === true) {
            TieneMembresia = false;
            closeCustomModal();
            showCustomModal('warning', 'ℹ️ Bienvenido', 'Todavía no tenés ninguna membresía registrada. Si no estas inscripto en niguna clase, puedes inscribirte en la sección de clases. ¡Gracias!');
        }

        //Si todo funciona seguimos con la manipulacion dinamica
        //------------------ Cargamos los datos dinamicamente ------------------
        const nombreUsuarioHeader = document.getElementById('NameUser'); //Nombre en el header
        const nombreUsuario = document.getElementById('ENvalor'); //Nombre en el estado
        const EstadoPlan = document.getElementById('EstadoPlan');
        const nombreTituloBienvenido = document.getElementById('titulo-bienvenida');
        const tbodyPlanes = document.getElementById('tbodyPlanes');
        const TiposPlanes = document.getElementById('dropdownPlanClientes');
        //Obtenemos los datos del servidor y los hacemos globales
        dataUser = resultDataUser.datas; //Lista de datos del clientes
        dataProcesUser = resultDataPUser.dataStatus; //Lista los datos del estado.
        dataListPlanes = resultDatasAllPlanes.dataPlanes;
        dataListClases = resultDatasAllPlanes.dataClases;

        // --------------Depuracion Solo en desarrollo usar--------------------
        /* console.log(dataUser);
        console.log(dataProcesUser);
        console.log(dataListPlanes);*/

        //---------------Cargamos los datos dinamicamente-------------------

        //Header Nombre usuarios
        nombreUsuarioHeader.textContent = `${dataUser.nombre}`;
        //Estado Nombre
        nombreUsuario.textContent = `😎 ${dataUser.nombre} ${dataUser.apellido} (ver detalle-plan)`;
        //titulos
        nombreTituloBienvenido.textContent = `¡Bienvenido ${dataUser.nombre} ${dataUser.apellido}!`;

        //Estado Plan
        // dataProcesUser = array de planes
        const estadoGlobal = resultDataPUser.estadoGlobal;

        if (!dataProcesUser || dataProcesUser.length === 0 /* || resultDataPUser.sinMembresia*/) {
            EstadoPlan.classList.remove('activo', 'vencido');
            EstadoPlan.textContent = "Sin membresia";
        } else {
            EstadoPlan.textContent = estadoGlobal;
            if (estadoGlobal === "Al dia" || estadoGlobal === "Sin pago") {
                EstadoPlan.classList.add('activo');
                EstadoPlan.classList.remove('vencido');
            } else {
                EstadoPlan.classList.add('vencido');
                EstadoPlan.classList.remove('activo');
            }
        }

        //Planes disponibles e inscriptos
        tbodyPlanes.innerHTML = ""; //Limpiamos la tabla
        //Insertamos los registro en la tabla plan
        var fila = "";
        dataListPlanes.forEach(element => {
            //Verificar si esta inscripto en la clase o no
            if (element.estado_inscripcion === "Inscripto") {
                fila += `
                <tr>
                    <td data-label="Nombre del Plan">${element.nombre_plan}</td>
                    <td data-label="Duracion">${element.duracion_dias}</td>
                    <td data-label="Precio">$${element.precio}</td>
                    <td data-label="Ver clases">
                        <span class="ver-clases">Ver clases</span>
                    </td>
                    <td data-label="Estado">
                        <span class="estado inscrito">Inscripto</span>
                    </td>
                    <td data-label="Acciones">
                        <a href="#" class="btn-video"> ✅ </a>
                    </td>
                </tr>`;
            } else {
                fila += `
                <tr>
                    <td data-label="Nombre del Plan">${element.nombre_plan}</td>
                    <td data-label="Duracion">${element.duracion_dias}</td>
                    <td data-label="Precio">$${element.precio}</td>
                    <td data-label="Ver clases">
                        <span class="ver-clases">Ver clases</span>
                    </td>
                    <td data-label="Estado">
                        <span class="estado no-inscrito">No inscripto</span>
                    </td>
                    <td data-label="Acciones">
                        <button class="btn-inscribirse">Inscribirse</button>
                    </td>
                </tr>`;
            }
        });
        tbodyPlanes.innerHTML = fila;

        // Delegación de eventos
        tbodyPlanes.addEventListener('click', (e) => {
            e.preventDefault(); // Detiene el comportamiento por defecto de TODOS los enlaces/botones

            // Encontramos la fila y el índice
            const fila = e.target.closest('tr');
            // Asegúrate de que el clic realmente ocurrió dentro de una fila <tr>
            if (!fila) return;

            const index = Array.from(tbodyPlanes.children).indexOf(fila);
            const planSeleccionado = dataListPlanes[index];

            // LÓGICA DEL BOTÓN "VER Clases"
            if (e.target.classList.contains('ver-clases')) {
                mostrarVerClases(planSeleccionado);
            }

            // LÓGICA DEL BOTÓN "INSCRIBIRSE"
            if (e.target.classList.contains('btn-inscribirse')) {
                // ... (tu lógica de modal y setTimeout) ...
                showCustomModal('info', '¡Aviso!', 'Se abrira un enlace de WhatsApp para inscribirte al plan.');
                const enlace = generarEnlaceInscripcionWhatsapp(planSeleccionado);
                setTimeout(() => {
                    closeCustomModal();
                    window.open(enlace, '_blank');
                }, 3000);
            }
        });

        //Completar tipo de planes
        TiposPlanes.innerHTML = ""; //Limpiamos la tabla
        //Insertamos los registro en la tabla
        var filaPlanes = "";
        dataProcesUser.forEach(element => {
            filaPlanes += `<li><a href="#">${element.Nombre_Membresia}</a></li>`;
        });
        TiposPlanes.innerHTML = filaPlanes;

        closeCustomModal();
    } catch (error) {
        console.log(error);
        closeCustomModal();
        showCustomModal('error', '❌ Error al cargar la pagina', 'Intente nuevamente mas tarde');
        setTimeout(() => {
            window.location.href = '../login/login.php';
        }, 3000);
    }
});



