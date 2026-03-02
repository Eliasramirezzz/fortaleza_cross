// =========================== Variables Globales ===========================
let idAdmin = null;
let dataPlanes = [];
const botonAccion = document.getElementById('main-action-button'); // ID inicial: main-action-button
const modoAccionSpan = document.getElementById('modo-accion');
const botonEstado = document.getElementById('btnEstadoMembresia');
const formMembresia = document.getElementById('form-membresia');

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

// =========================== Delegacion de Eventos ===========================
// Delegacion 
document.addEventListener('change', async (e) => {
    // ------------------ Selección de membresía y autocompletar campos ------------------
    if (e.target && e.target.id === 'id_membresia') {
        const selectMembresia = e.target;
        const selectedValue = selectMembresia.value;

        if (selectedValue === 'registrarMembresia') {
            // Modo registrar nueva membresia
            RegistrarNuevaMembresia();
        } else if (selectedValue) {
            // Modo editar membresia existente
            ModificarMembresia(selectedValue);
        }
    }
});

// =========================== Funciones Auxiliares ===========================
function CargarFotoUsuario(urlfoto) {
    const userIconContainer = document.getElementById('userIconContainer');

    if (!userIconContainer) {
        console.error("No se encontró el contenedor de icono de usuario.");
        return;
    }
    const img = document.createElement('img');
    img.src = urlfoto; // <--- ¡USAR LA VARIABLE CORRECTA!
    img.alt = 'Foto de perfil';
    img.classList.add('icon', 'profile-pic');
    img.id = 'userIcon';

    // Reemplazar siempre, incluso si es la default, para que aplique el estilo circular.
    userIconContainer.innerHTML = ''; // Limpiar el contenedor (quita el <i>)
    userIconContainer.appendChild(img); // Añadir la imagen
}

function CargarMembresiasSelect() {
    const selectMembresia = document.getElementById('id_membresia');
    // Limpiar opciones previas
    selectMembresia.innerHTML = '';
    // Agregamos la opcion para registrar por defecto.
    const defaultOption = document.createElement('option');
    defaultOption.value = 'registrarMembresia';
    defaultOption.textContent = '-- Registrar Nueva Membresía --';
    selectMembresia.appendChild(defaultOption);

    // Agregar una opción por cada membresia
    dataPlanes.forEach((plan) => {
        const option = document.createElement('option');
        option.value = plan.id_membresia;  // ← Cambiado
        option.textContent = `${plan.nombre} - $${plan.precio}`;
        selectMembresia.appendChild(option);
    });

    // Seleccionamos la opcion por defecto
    selectMembresia.value = 'registrarMembresia';
}

// Función auxiliar para escapar el HTML 
function escapeHtml(str) {
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Modal de confirmacion
function ModalConfirmacionPlan(title = '¿Estás seguro?', message = 'Esta acción no se puede deshacer.') {
    // 1. Obtener elementos del DOM (ya inyectados en el HTML)
    const overlay = document.getElementById('confirmRegisterOverlay');
    if (!overlay) {
        return Promise.resolve(false);
    }

    // Si ya es visible, resolvemos para evitar duplicados
    if (overlay.getAttribute('aria-hidden') === 'false') {
        return Promise.resolve(false);
    }

    // Elementos internos
    const modalEl = overlay.querySelector('.cr-modal');
    const titleEl = overlay.querySelector('#cr-title');
    const messageEl = overlay.querySelector('#cr-msg');
    const yesBtn = overlay.querySelector('#cr-yesBtn');
    const noBtn = overlay.querySelector('#cr-noBtn');

    // 2. Insertar contenido dinámico y escapar HTML
    titleEl.textContent = escapeHtml(title);
    messageEl.textContent = escapeHtml(message);

    return new Promise((resolve) => {
        let previousOverflow = '';

        // 3. Función de limpieza y resolución de la promesa
        function cleanup(result) {
            // Animación de salida (opcional: añadir una clase de fade-out)
            overlay.classList.remove('cr-visible');
            overlay.setAttribute('aria-hidden', 'true');

            // remove listeners
            yesBtn.removeEventListener('click', onYes);
            noBtn.removeEventListener('click', onNo);
            overlay.removeEventListener('click', onOverlayClick);
            document.removeEventListener('keydown', onKeydown);

            // Retraso para la animación (150ms)
            setTimeout(() => {
                // restaurar overflow
                document.body.style.overflow = previousOverflow || '';
                resolve(Boolean(result));
            }, 150);
        }

        // 4. Handlers
        function onYes(e) { e && e.preventDefault && e.preventDefault(); cleanup(true); }
        function onNo(e) { e && e.preventDefault && e.preventDefault(); cleanup(false); }
        function onOverlayClick(e) { if (e.target === overlay) cleanup(false); }
        function onKeydown(e) {
            if (e.key === 'Escape') { cleanup(false); }
            if (e.key === 'Enter') {
                const active = document.activeElement;
                if (!active || active.tagName !== 'BUTTON') cleanup(true);
            }
        }

        // 5. Mostrar modal y configurar el foco
        previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden'; // Bloquear scroll

        // Mostrar con clases para la animación y accesibilidad
        overlay.classList.add('cr-visible');
        overlay.setAttribute('aria-hidden', 'false');

        // Enfocar el modal/botón para accesibilidad y manejo de Enter
        setTimeout(() => { try { yesBtn.focus(); } catch (e) { } }, 10);

        // 6. Adjuntar Event Listeners
        yesBtn.addEventListener('click', onYes);
        noBtn.addEventListener('click', onNo);
        overlay.addEventListener('click', onOverlayClick);
        document.addEventListener('keydown', onKeydown);
    });
}

// =========================== Gestion Tarjeta Membresia ===========================
// EVENT LISTENER PRINCIPAL (DELEGACIÓN)
botonAccion.addEventListener('click', async (event) => {
    event.preventDefault();

    // 1. Obtener datos y modo
    const formData = new FormData(formMembresia);

    // Leer el modo actual del botón. Si no existe, asume 'registrar'.
    const modo = botonAccion.getAttribute('data-modo') || 'registrar';
    const idRegistro = botonAccion.getAttribute('data-id-registro'); // ID solo existe en modo 'modificar'

    // 2. Delegar la acción basada en el modo
    if (modo === 'registrar') {
        // Lógica de Registro
        await manejarRegistroMembresia(formData, idAdmin);

    } else if (modo === 'modificar' && idRegistro) {
        // Lógica de Modificación
        await manejarModificacionMembresia(formData, idAdmin, idRegistro);

    } else {
        // Caso de error
        showCustomModal('error', '❌ Error de Acción', 'Modo de operación del botón inválido o ID de registro faltante.');
    }
});

// Registrar Nueva Membresia
function RegistrarNuevaMembresia() {
    // 1. Actualizar la Interfaz
    modoAccionSpan.textContent = 'Registrar Nueva Membresía';
    botonAccion.textContent = 'Registrar Membresía'; // Asegurar el texto

    // 2. Limpiar y deshabilitar
    formMembresia.reset(); // Limpia los campos del formulario
    botonEstado.disabled = true;

    // 3. Establecer el Modo de Acción (DATA ATTRIBUTES)
    botonAccion.setAttribute('data-modo', 'registrar');
    botonAccion.removeAttribute('data-id-registro'); // Eliminar cualquier ID anterior
}

async function manejarRegistroMembresia(formData, idAdmin) {
    showCustomModal('cargando', '⏳ Procesando...', 'Procesando la solicitud, por favor espere.');

    // ---- Pedir confirmación ----
    const confirmado = await ModalConfirmacionPlan(
        '⚠️ Confirmación',
        `¿Esta seguro que desea registrar la membresía ingresada?`
    );

    if (!confirmado) {
        closeCustomModal();
        return;
    }

    showCustomModal('cargando', '⏳ Registrando...', 'Registrando la membresía, por favor espere.');

    const data = Object.fromEntries(formData.entries());
    data.id_admin = idAdmin;

    try {
        const respuRegistrarMem = await fetch('../admin/Modelo/RegistrarTMem.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        if (!respuRegistrarMem.ok) {
            showCustomModal('error', '❌ Error del servidor', 'No se pudo registrar la membresía. Intente nuevamente más tarde.');
            return;
        }

        const resultRegistrarMem = await respuRegistrarMem.json();

        if (!resultRegistrarMem.success) {
            showCustomModal('error', '❌ Error al registrar la membresía', resultRegistrarMem.message || 'No se pudo registrar la membresía. Intente nuevamente más tarde.');
            return;
        }

        showCustomModal('success', `✅ Membresía Registrada`, resultRegistrarMem.message || 'La membresía se ha registrado correctamente.');
        setTimeout(() => {
            window.location.reload();
        }, 3000);

    } catch (error) {
        console.error("Error al registrar la membresía: ", error);
        showCustomModal('error', '❌ Error', 'No se pudo registrar la membresía. Intente nuevamente más tarde.');
    }
}

// Modificar Membresia 
function ModificarMembresia(idMembresia) {
    // 1. Cambiar modo en la interfaz y botón
    modoAccionSpan.textContent = 'Modificar Membresía';
    botonAccion.textContent = 'Guardar Cambios';

    // 2. Establecer el Modo de Acción (DATA ATTRIBUTES)
    botonAccion.setAttribute('data-modo', 'modificar');
    botonAccion.setAttribute('data-id-registro', idMembresia); // Guardamos el ID del registro a modificar

    // 3. Buscar el plan seleccionado
    const plan = dataPlanes.find(p => p.id_membresia == idMembresia);

    if (!plan) {
        showCustomModal('error', '❌ Error', 'No se encontró la membresía seleccionada.');
        console.error("No se encontró la membresía seleccionada.");
        return;
    }

    // 4. Autocompletar los datos
    document.getElementById("nombre").value = plan.nombre || "";
    document.getElementById("badge").value = plan.badge || "";
    document.getElementById("precio").value = plan.precio || "";
    document.getElementById("duracion_dias").value = plan.duracion_dias || "";
    document.getElementById("descripcion").value = plan.descripcion || "";
    document.getElementById("caracteristicas").value = plan.caracteristicas || "";
    document.getElementById("descripcion_larga").value = plan.descripcion_larga || "";

    // Checks 
    document.getElementById("activa").checked = plan.activa == 1;
    document.getElementById("especial").checked = plan.especial == 1;

    // 5. Habilitar el botón de eliminar
    botonEstado.disabled = false;
    document.getElementById("nombre").focus();
}

async function manejarModificacionMembresia(formData, idAdmin, idRegistro) {
    showCustomModal('cargando', '⏳ Actualizando...', 'Actualizando la solicitud, por favor espere.');

    // ---- Pedir confirmación ----
    const confirmado = await ModalConfirmacionPlan(
        '⚠️ Confirmación',
        `¿Esta seguro que desea actualizar la membresía seleccionada?`
    );

    if (!confirmado) {
        closeCustomModal();
        return;
    }

    showCustomModal('cargando', '⏳ Actualizando...', 'Actualizando la membresía, por favor espere.');
    const data = Object.fromEntries(formData.entries());
    data.id_admin = idAdmin;
    data.id_membresia_a_modificar = idRegistro; // Añade el ID de la membresía a modificar

    try {
        // Asegúrate de que este endpoint maneje la lógica de actualización
        const respuModificarMem = await fetch('../admin/Modelo/ModificarTMem.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        if (!respuModificarMem.ok) {
            showCustomModal('error', '❌ Error del servidor', 'No se pudo modificar la membresía. Intente nuevamente más tarde.');
            return;
        }

        const resultModificarMem = await respuModificarMem.json();

        if (!resultModificarMem.success) {
            showCustomModal('error', '❌ Error al modificar la membresía', resultModificarMem.message || 'No se pudo modificar la membresía.');
            return;
        }

        showCustomModal('success', `✅ Membresía Modificada `, resultModificarMem.message || 'La membresía se ha modificado correctamente.');
        setTimeout(() => {
            window.location.reload();
        }, 3000);

    } catch (error) {
        console.error("Error al modificar la membresía: ", error);
        showCustomModal('error', '❌ Error', 'No se pudo modificar la membresía. Intente nuevamente más tarde.');
    }
}

// Accion Btn Estado.
document.getElementById('btnEstadoMembresia').addEventListener('click', async (event) => {
    showCustomModal('cargando', '⏳ Desactivando...', 'Desactivando la membresía, por favor espere.');
    const selectMembresia = document.getElementById('id_membresia');
    const selectedValue = selectMembresia.value;
    const plan = dataPlanes.find(p => p.id_membresia == selectedValue);

    // ---- Pedir confirmación ----
    const confirmado = await ModalConfirmacionPlan(
        '⚠️ Confirmación',
        `¿  Está seguro que desea desactivar la membresía del plan ${plan.nombre}?`
    );

    if (!confirmado) {
        closeCustomModal();
        return;
    }
    // Creamos el arrays que contendran los datos de la membresia a desactivar 
    const data = {
        id_admin: idAdmin,
        id_membresia_a_desactivar: selectMembresia.value
    }

    try {
        // --------------- Procesando el cambio de estado --------------- 
        const respuDesactivarMem = await fetch('../admin/Modelo/DesactivarTMem.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        if (!respuDesactivarMem.ok) {
            showCustomModal('error', '❌ Error del servidor', 'No se pudo desactivar la membresía. Intente nuevamente más tarde.');
            return;
        }

        const resultDesactivarMem = await respuDesactivarMem.json();

        if (!resultDesactivarMem.success) {
            showCustomModal('error', '❌ Error al desactivar la membresía', resultDesactivarMem.message || 'No se pudo desactivar la membresía.');
            return;
        }

        showCustomModal('success', `✅ Membresía Desactivada `, resultDesactivarMem.message || 'La membresía se ha desactivado correctamente.');
        setTimeout(() => {
            window.location.reload();
        }, 3000);

    } catch (error) {
        console.error("Error al desactivar la membresía: ", error);
        showCustomModal('error', '❌ Error', 'No se pudo desactivar la membresía. Intente nuevamente más tarde.');
    }
});

// =========================== Ejecucion Principal ===========================
document.addEventListener("DOMContentLoaded", async () => {

    idAdmin = sessionStorage.getItem('idUser'); //Obengo el id del admin
    let FotoUserURL = sessionStorage.getItem('fotoUrl');
    const defaultUrl = '../img/user_default.png'; // Ruta por defecto

    showCustomModal('cargando', '⏳ Cargando la pagina', 'Espere por favor, mientras cargamos sus datos');
    if (!idAdmin || idAdmin === "" || idAdmin == null) {
        closeCustomModal();
        showCustomModal('error', '❌ Error al ingresar', 'No se encuentra disponible la pagina para este usuario, intente nuevamente mas tarde 😢');
        setTimeout(() => {
            window.location.href = '../login/login.php';
        }, 5000);
        return;
    }

    if (!FotoUserURL || FotoUserURL === "" || FotoUserURL == null) {
        FotoUserURL = defaultUrl;
        sessionStorage.setItem('fotoUrl', FotoUserURL);
    }

    CargarFotoUsuario(FotoUserURL);
    closeCustomModal();

    try {

        // ------------------ Carga membresia en el select ------------------
        const respuSelectMembresia = await fetch('../admin/Modelo/ObtenerPlanes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_admin: idAdmin })
        });

        if (!respuSelectMembresia.ok) {
            showCustomModal('error', '❌ Error', 'No se pudieron cargar las membresias. Intente nuevamente mas tarde.');
            return;
        }

        const resultMembresias = await respuSelectMembresia.json();

        if (!resultMembresias.success) {
            showCustomModal('error', '❌ Error', resultMembresias.message || 'No se pudieron cargar las membresias. Intente nuevamente mas tarde.');
            return;
        }

        // -------------------- Datos del servidor --------------------
        dataPlanes = resultMembresias.dataPlanes;
        //console.log("Membresias cargadas: ", resultMembresias.dataPlanes);

        // ------------------- Cargar el select ---------------------
        CargarMembresiasSelect();

    } catch (error) {
        console.error("Error al cargar membresias: ", error);
        showCustomModal('error', '❌ Error', 'No se pudieron cargar las membresias. Intente nuevamente mas tarde.');
    }
});