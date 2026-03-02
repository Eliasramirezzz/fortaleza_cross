// ----------------- Variables Globales -------------------
let idAdmin = null;
let dataListPymesFiltrada = []; // Nueva lista para los pagos filtrados (opcional, pero más claro)
let dataPagosOriginal = [];    // ¡NUEVA! Lista completa de pagos
let dataListPymes = [];        // Lista de trabajo (la que se estaba usando, ahora será la lista completa)
let datalistPlanes = null;
let dataListAdmin = null;
let dataListClientes = null;
let dataClientesOriginal = [];
let idPagoActual = null;// Variable global para almacenar temporalmente el ID del pago
const BASE_DEFAULT_URL = '/Fortaleza_Cross/img/ImgUser/user_default.png';// Ruta que el login guarda

// ------------------Funcionalidad dinamica de la pagina -----------------------
//Funcion para exportar o realizar informe
// La función debe acceder a dataListClientes (la lista que ya está filtrada/ordenada)
/**
 * Genera una vista previa de informe en una nueva ventana basada en la lista de datos proporcionada.
 * @param {Array<Object>} dataToExport - La lista de datos (clientes o pagos) ya filtrada/ordenada.
 * @param {string} title - El título del informe (Ej: "Informe de Clientes" o "Informe de Pagos").
 * @param {Array<string>} headers - Un array de encabezados de la tabla (Ej: ['DNI', 'Nombre', 'Estado']).
 */
function exportarAInforme(dataToExport, title, headers) {
    if (dataToExport.length === 0) {
        // Asumo que showCustomModal está disponible
        showCustomModal('alerta', '⚠️ Sin Datos', `No hay datos que coincidan con los filtros actuales para generar el ${title}.`);
        return;
    }

    // 1. Crear las filas del cuerpo de la tabla
    let tablaBodyHTML = '';

    dataToExport.forEach((element, index) => {
        let rowData = '';

        // Asumo que la data de Pagos tiene ciertas claves y la de Clientes otras.
        // Adaptaremos las filas según el título:
        if (title.includes("Clientes")) {
            const nombreCompleto = `${element.apellido} ${element.nombre}`;
            rowData = `
                <td>${index + 1}</td>
                <td>${nombreCompleto}</td>
                <td>${element.dni}</td>
                <td>${element.telefono}</td>
                <td>${element.email}</td>
                <td>${element.fecha_alta}</td>
                <td>${element.estado}</td>
            `;
        } else if (title.includes("Pagos")) {
            let estado = "No definido";
            const hoy = new Date();
            const fechaVencimiento = new Date(element.fecha_vencimiento);

            // Lógica para determinar el estado Vencido
            if (element.estado === 0 && fechaVencimiento < hoy) {
                estado = "Vencido";
            } else if (element.estado === 0) {
                estado = "Pendiente";
            } else if (element.estado === 1) {
                estado = "Pagado";
            }

            // AJUSTA ESTAS CLAVES (`element.XXX`) PARA QUE COINCIDAN CON TU OBJETO DE PAGO
            rowData = `
                <td>${index + 1}</td>
                <td>${element.Nombre_Cliente + ' ' + element.apellido || 'N/A'}</td> 
                <td>${element.dni || 'N/A'}</td> 
                <td>${element.Nombre_Membresia || '0.00'}</td> 
                <td>$${element.precio || 'N/A'}</td> 
                <td>${element.fecha_vencimiento || 'N/A'}</td>
                <td>${element.fecha_pago || 'N/A'}</td>
                <td>${estado || 'N/A'}</td>
            `;
        }

        tablaBodyHTML += `<tr>${rowData}</tr>`;
    });

    // 2. Construir el HTML completo del documento
    const htmlContenido = `
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>${title} - ${new Date().toLocaleDateString('es-AR')}</title>
            <style>
                /* ... (Tus estilos de informe aquí, iguales a los que definimos antes) ... */
                body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
                h1 { text-align: center; color: #1a1a2e; border-bottom: 2px solid #4dc3ff; padding-bottom: 10px; }
                .report-header { margin-bottom: 20px; }
                .report-header p { margin: 5px 0; font-size: 0.9em; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 0.9em; }
                th { background-color: #f2f2f2; color: #1a1a2e; }
                tr:nth-child(even) { background-color: #f9f9f9; }
                .total { margin-top: 15px; font-weight: bold; }
                @media print { 
                    .no-print { 
                        display: none; 
                    }  
                    thead {
                        display: table-header-group; /* Hace que el encabezado se repita en cada página */
                    }
                } 
            </style>
        </head>
        <body>
            <h1>📋 ${title}</h1>
            <div class="report-header">
                <p><strong>Generado por:</strong> Administrador (ID: ${idAdmin || 'N/A'})</p>
                <p><strong>Fecha de Generación:</strong> ${new Date().toLocaleString('es-AR')}</p>
                <p><strong>Total de Registros:</strong> ${dataToExport.length}</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        ${headers.map(h => `<th>${h}</th>`).join('')}
                    </tr>
                </thead>
                <tbody>
                    ${tablaBodyHTML}
                </tbody>
            </table>
            
            <div class="total">Fin del Informe.</div>

            <div class="no-print" style="text-align: center; margin-top: 30px;">
                <button onclick="window.print()" style="padding: 10px 20px; background-color: #4dc3ff; color: white; border: none; border-radius: 5px; cursor: pointer;">Imprimir / Guardar como PDF</button>
                <button onclick="window.close()" style="padding: 10px 20px; background-color: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">Cerrar Vista Previa</button>
            </div>
        </body>
        </html>
    `;

    // 3. Abrir la nueva ventana y escribir el contenido
    const informeWindow = window.open('', '_blank');
    informeWindow.document.write(htmlContenido);
    informeWindow.document.close();
}

// CLAVES DE CLIENTES: idAdmin, dataListClientes
document.getElementById('exportClientData').addEventListener('click', () => {
    const clientHeaders = ['#', 'Nombre Completo', 'DNI', 'Teléfono', 'Email', 'F. Alta', 'Estado'];
    // Llama a la función genérica con los datos de clientes
    exportarAInforme(dataListClientes, 'Informe de Clientes', clientHeaders);
});

// CLAVES DE PAGOS: idAdmin, dataListPymes (DEBES CONFIRMAR EL NOMBRE DE TU VARIABLE DE PAGOS FILTRADA)
document.getElementById('exportPagosData').addEventListener('click', () => {
    // Si dataListPymes está correctamente filtrada, aquí solo habrá 5 registros.
    const pagosHeaders = ['#', 'NOMBRE COMPLETO', 'DNI', 'NOMBRE PLAN', 'PRECIO', 'FECHA VTO', 'FECHA PAGO', 'ESTADO'];
    exportarAInforme(dataListPymesFiltrada, 'Informe de Pagos', pagosHeaders);
});

// =========================================================
// DELEGACIÓN DE EVENTOS EN EL DOCUMENTO GENERAL
// =========================================================
document.addEventListener('click', async (e) => {
    //  Esta delegacion es para verficar si quiere eliminar un registro de pagos.
    if (e.target.classList.contains('payment-table__btn--eliminar')) {

        const btn = e.target;
        const idPago = btn.getAttribute('data-id');

        if (!idPago) {
            showCustomModal('error', 'Error', 'ID de pago no encontrado.');
            return;
        }

        await EliminarPago(idPago,
            btn.getAttribute('data-nombre'),
            btn.getAttribute('data-plan-nombre')
        );
    }
    //Esta delegacion es para cambiar el estado de pendiente a pagado.
    if (e.target.classList.contains('payment-table__btn--pagar-accion')) {
        const btn = e.target;
        const idPago = btn.getAttribute('data-id');

        if (!idPago) {
            showCustomModal('error', 'Error', 'ID de pago no encontrado.');
            return;
        }
        await openPagoModal(idPago,
            btn.getAttribute('data-nombre'),
            btn.getAttribute('data-plan-nombre'));
    }
    //Esta delegacion es para cambiar el estado de debe a pendiente.
    if (e.target.classList.contains('payment-table__btn--debe')) {
        const btn = e.target;
        const idPago = btn.getAttribute('data-id');

        if (!idPago) {
            showCustomModal('error', 'Error', 'ID de pago no encontrado.');
            return;
        }
        await openPagoModal(idPago,
            btn.getAttribute('data-nombre'),
            btn.getAttribute('data-plan-nombre'));
    }

});

document.getElementById('logo').addEventListener('click', () => {
    //Subir al principio cuando se presione en el icono de la pagina. 
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
})

function CargarNombreHeader() {
    const NameAdmin = document.getElementById('NameAdmin');
    NameAdmin.textContent = `${dataListAdmin.nombre} ${dataListAdmin.apellido}`;
}

//Cargar la foto del usuario en el header
// La variable recibida es 'urlfoto'
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

// ------------------Seccion Gestion de pagos ---------------------
const searchPagos = document.getElementById('buscarPago');
const msjtotalpagos = document.getElementById('MsjtotalPagos');
const verMasBtn = document.getElementById('verMasBtn');
const verMenosBtn = document.getElementById('verMenosBtn');
const filtroPagoSelect = document.getElementById('filtroPago');

let registrosVisibles = 10; // cantidad de registros a mostrar por defecto

// Event Listener para el Filtro de Estado
filtroPagoSelect.addEventListener('change', () => {
    registrosVisibles = 10;
    renderPagos(searchPagos.value);
});

// Búsqueda en tiempo real 
searchPagos.addEventListener('input', () => {
    registrosVisibles = 10; // resetear siempre
    renderPagos(searchPagos.value);
});

// --- Función para renderizar con filtro ---
function renderPagos(filtro = "") {
    const tbodyPagosClientes = document.getElementById('tbodyPagosClientes');
    tbodyPagosClientes.innerHTML = "";

    if (!Array.isArray(dataListPymes) || dataListPymes.length === 0) {
        // Muestra mensaje si no hay datos
        tbodyPagosClientes.innerHTML = `<tr><td colspan="10" style="text-align: center; padding: 20px;">No hay datos de pagos para mostrar.</td></tr>`;
        msjtotalpagos.textContent = `Total de pagos: 0`;
        verMasBtn.classList.add("pagination-btn--hide");
        verMenosBtn.classList.add("pagination-btn--hide");
        return;
    }

    // ----------------------- Manejo del Filtro de Estado ----------------------
    // Obtener el valor del filtro de estado
    const filtroEstado = filtroPagoSelect.value;

    // 1. PRIMER FILTRADO: Por Estado
    let resultadosFiltradosPorEstado = dataListPymes.filter(element => {
        if (filtroEstado === 'todos') {
            return true;
        }

        const estadoReal = calcularEstadoTexto(element); // Usamos tu función existente

        if (filtroEstado === 'pendientes' && estadoReal === 'Pendiente') {
            return true;
        }
        if (filtroEstado === 'vencidos' && estadoReal === 'Vencido') {
            return true;
        }
        if (filtroEstado === 'pagados' && estadoReal === 'Pagado') {
            return true;
        }
        return false;
    });

    // 2. SEGUNDO FILTRADO: Por Texto (Nombre/DNI)
    let resultados = resultadosFiltradosPorEstado.filter(element => {
        // 1. Usar el parámetro 'filtro'
        if (filtro.trim() === "") return true;

        const nombreCompleto = `${element.Nombre_Cliente} ${element.apellido}`.toLowerCase();
        const dni = element.dni ? element.dni.toString().toLowerCase() : "";

        // 2. Crear filtroLimpio a partir del parámetro 'filtro'
        const filtroLimpio = filtro.toLowerCase().trim();

        return (
            nombreCompleto.includes(filtroLimpio) ||
            dni.includes(filtroLimpio)
        );
    });

    // ¡PASO CLAVE! ACTUALIZAR LA LISTA GLOBAL FILTRADA 
    dataListPymesFiltrada = resultados;

    // 3. Mensaje de Total
    if (filtro.trim() === "" && filtroEstado === 'todos') {
        msjtotalpagos.textContent = `Total de pagos: ${resultados.length}`;
    } else {
        msjtotalpagos.textContent = `Resultados encontrados: ${resultados.length}`;
    }

    // 4. Construcción de filas (usando el slice/límite)
    let filaPagos = "";
    // Solo iteramos hasta el límite de 'registrosVisibles'
    //const datosAMostrar = resultados.slice(0, registrosVisibles); borrar 
    const datosAMostrar = dataListPymesFiltrada.slice(0, registrosVisibles);

    if (datosAMostrar.length === 0) {
        filaPagos = `<tr><td colspan="10" style="text-align: center; padding: 20px;">No se encontraron registros de pagos.</td></tr>`;
    } else {
        datosAMostrar.forEach((element, index) => {
            const estado = calcularEstadoTexto(element); // Asumo que esta función existe y es correcta
            const fecha_pago = element.fecha_pago ? element.fecha_pago : 'No pagado';

            let estadoTexto = "";
            let accionBtn = "";

            if (estado === "Pagado") {
                estadoTexto = `<td class="payment-table__data payment-table__data--estado estado-pagado">Pagado</td>`;
                accionBtn = `<td class="payment-table__data payment-table__data--accion">
                                <button class="payment-table__btn payment-table__btn--al-dia">Al día</button>
                            </td>`;
            } else if (estado === "Pendiente") {
                estadoTexto = `<td class="payment-table__data payment-table__data--estado estado-pendiente">Pendiente</td>`;
                accionBtn = `<td class="payment-table__data payment-table__data--accion">
                                <button class="payment-table__btn payment-table__btn--pagar-accion" data-id='${element.id_pago}' data-nombre='${element.Nombre_Cliente}' data-plan-nombre='${element.Nombre_Membresia}'>Pagar</button>
                            </td>`;
            } else { // Vencido
                estadoTexto = `<td class="payment-table__data payment-table__data--estado estado-vencido">Vencido</td>`;
                accionBtn = `<td class="payment-table__data payment-table__data--accion">
                                <button class="payment-table__btn payment-table__btn--debe" data-id='${element.id_pago}' data-nombre='${element.Nombre_Cliente}' data-plan-nombre='${element.Nombre_Membresia}'>Saldar</button>
                            </td>`;
            }

            filaPagos += `
                <tr class="payment-table__row">
                    <td>${index + 1}</td>
                    <td>${element.Nombre_Cliente} ${element.apellido}</td>
                    <td>${element.dni}</td>
                    <td>${element.Nombre_Membresia}</td>
                    <td>${element.precio}</td>
                    <td>${element.fecha_vencimiento}</td>
                    <td>${fecha_pago}</td>
                    ${estadoTexto}
                    ${accionBtn}
                    <td class="payment-table__data payment-table__data--eliminar"> 
                        <button 
                            class="payment-table__btn payment-table__btn--eliminar" 
                            data-id='${element.id_pago}'
                            data-nombre='${element.Nombre_Cliente} ${element.apellido}'  
                            data-plan-nombre='${element.Nombre_Membresia}'
                        >
                            🗑️
                        </button>
                    </td>
                </tr>
            `;
        });
    }

    tbodyPagosClientes.innerHTML = filaPagos;

    /* 5. Controles de ver más/menos borrar 
    if (resultados.length > registrosVisibles) {
        verMasBtn.classList.remove("pagination-btn--hide");
    } else {
        verMasBtn.classList.add("pagination-btn--hide");
    }*/

    // 5. Controles de ver más/menos (Asegúrate de usar dataListPymesFiltrada.length)
    if (dataListPymesFiltrada.length > registrosVisibles) {
        verMasBtn.classList.remove("pagination-btn--hide");
    } else {
        verMasBtn.classList.add("pagination-btn--hide");
    }

    // Ver Menos: aparece si hemos mostrado más de 10 registros (el límite inicial)
    if (registrosVisibles > 10 && resultados.length > 10) {
        verMenosBtn.classList.remove("pagination-btn--hide");
    } else {
        verMenosBtn.classList.add("pagination-btn--hide");
    }
}


// Ver más
verMasBtn.addEventListener('click', () => {
    registrosVisibles += 10;
    renderPagos(searchPagos.value);
});

// Ver menos
verMenosBtn.addEventListener('click', () => {
    registrosVisibles = 10;
    renderPagos(searchPagos.value);
});

//Para buscar el estado del pago por texto
function calcularEstadoTexto(element) {
    const hoy = new Date();
    const fechaVto = new Date(element.fecha_vencimiento);

    if (element.estado == 1) {
        return "Pagado";
    } else {
        if (fechaVto >= hoy) {
            return "Pendiente";
        } else {
            return "Vencido";
        }
    }
}
//Para calcular el estado del pago por botones
function calcularEstadoPago(element) {
    const hoy = new Date();
    const fechaVto = new Date(element.fecha_vencimiento);
    let estadoTexto = "";
    let botonClase = "";
    let botonTexto = "";

    if (element.estado == 1) {
        // Ya pagado
        estadoTexto = "Pagado";
        botonClase = "payment-table__btn payment-table__btn--al-dia";
        botonTexto = "Al día";
    } else {
        // No pagó todavía
        if (fechaVto >= hoy) {
            estadoTexto = "Pendiente";
            botonClase = "payment-table__btn payment-table__btn--pagar-accion";
            botonTexto = "Pagar";
        } else {
            estadoTexto = "Vencido";
            botonClase = "payment-table__btn payment-table__btn--debe";
            botonTexto = "Saldar";
        }
    }

    return {
        estadoTexto,
        botonClase,
        botonTexto
    };
}
//Cargar los pagos en la tabla de modo dinamico al iniciar
function CargarPagosEnTabla() {
    const tbodyPagosClientes = document.getElementById('tbodyPagosClientes');
    tbodyPagosClientes.innerHTML = '';

    let filaPagos = "";
    let contador = 0;

    dataListPymes.forEach(element => {
        contador++;

        const fecha_pago = element.fecha_pago ? element.fecha_pago : 'No pagado';
        const estadoInfo = calcularEstadoPago(element);

        filaPagos += `
            <tr class="payment-table__row">
                <td class="payment-table__data payment-table__data--nro" >${contador}</td>
                <td class="payment-table__data payment-table__data--nombre">${element.Nombre_Cliente} ${element.apellido}</td>
                <td class="payment-table__data payment-table__data--dni">${element.dni}</td>
                <td class="payment-table__data payment-table__data--plan">${element.Nombre_Membresia}</td>
                <td class="payment-table__data payment-table__data--precio">${element.precio}</td>
                <td class="payment-table__data payment-table__data--vto">${element.fecha_vencimiento}</td>
                <td class="payment-table__data payment-table__data--pago">${fecha_pago}</td>
                <td class="payment-table__data payment-table__data--estado">${estadoInfo.estadoTexto}</td>
                <td class="payment-table__data payment-table__data--accion">
                    <button class="${estadoInfo.botonClase}">
                        ${estadoInfo.botonTexto}
                    </button>
                </td>
                <td class="payment-table__data payment-table__data--eliminar">
                    <button class="payment-table__btn payment-table__btn--eliminar" data-id='${element.id_pago}'>🗑️</button>
                </td>
            </tr>
        `;
    });

    tbodyPagosClientes.innerHTML = filaPagos;
    // Inicio la funcion de busqueda de pagos en tiempo real
    renderPagos();
}

//Funcion para cerrar modal de registro pago
function closeModalPago() {
    modalPagoOverlay.classList.add('modal-overlay--oculto');
    // Reiniciar el formulario
    formRegistrarPago.reset();
    idPagoActual = null;
}
// Cerrar modal al hacer clic en Cancelar
btnCerrarPagoModal.addEventListener('click', closeModalPago);

// Cerrar modal al hacer clic fuera del contenido (en el overlay)
modalPagoOverlay.addEventListener('click', (e) => {
    if (e.target.id === 'modalPagoOverlay') {
        closeModalPago();
    }
});

//Eliminar un registro de pago
async function EliminarPago(idPago, Nombre_Cliente, Plan_Membresia) {
    //Pedir confirmación (usando tu función Promise)
    const confirmado = await ModalConfirmacionPlan(
        'Confirmar Eliminación',
        `¿Estás seguro de que deseas eliminar a (${Nombre_Cliente}) del registro de pagos (${Plan_Membresia})? Esta acción es irreversible, Verifique antes de confirmar.`
    );

    if (confirmado) {
        const datasDelete = {
            id_admin: idAdmin,
            id_pago: idPago
        };
        // Llamar a la API para eliminar
        try {
            // Muestra un mensaje temporal o un spinner antes del fetch si quieres
            showCustomModal('cargando', 'Procesando...', 'Eliminando registro, por favor espere.');

            const respuDeleteRegPago = await fetch('../admin/Modelo/DeleteRegPago.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datasDelete)
            });

            if (!respuDeleteRegPago.ok) {
                showCustomModal('error', 'Error del servidor', 'No se pudo eliminar el registro de pago, el servidor no esta respondiendo. Intente nuevamente mas tarde.');
                return;
            }

            const resultDeleteRegPago = await respuDeleteRegPago.json();

            if (!resultDeleteRegPago.success) {
                showCustomModal('error', 'Error al Eliminar', resultDeleteRegPago.message || 'No se pudo eliminar el registro de pago.');
                return;
            }

            showCustomModal('success', 'Eliminación Exitosa', 'El registro de pago ha sido eliminado correctamente.');
            setTimeout(() => {
                // recargar la pagina.
                window.location.reload();
            }, 2000);

        } catch (error) {
            console.error("Error en la solicitud de eliminación:", error);
            showCustomModal('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
        }
    }
}

async function openPagoModal(idPago, nombreCliente, nombrePlan) {
    // Encontrar el registro en la lista global
    const pago = dataListPymes.find(p => p.id_pago == idPago);

    if (!pago) {
        showCustomModal('error', 'Error de Datos', 'No se encontró el registro de pago en la lista actual. intente nuevamente mas tarde.');
        return;
    }

    // Llenar los campos de información
    idPagoActual = idPago;
    pagoIdHidden.value = idPago;
    pagoNombreCliente.textContent = nombreCliente;
    pagoNombrePlan.textContent = nombrePlan;
    // Asignar el monto de la membresía (asumiendo que viene en el campo 'precio' o 'monto')
    pagoMonto.textContent = `$${pago.precio}`;

    // Mostrar el modal
    modalPagoOverlay.classList.remove('modal-overlay--oculto');
    metodoPagoSelect.focus(); // Enfocar el select para mejor UX
}

formRegistrarPago.addEventListener('submit', async (e) => {
    e.preventDefault();

    const idPago = pagoIdHidden.value;
    const metodoPago = metodoPagoSelect.value;

    if (!idPago || !metodoPago) {
        showCustomModal('error', 'Error', 'Faltan datos de pago o método de pago.');
        return;
    }

    // Llama a la API para registrar el pago
    await registrarPago(idPago, metodoPago);
});

// Esta funcion realiza el registro del pago y se ejeucta cuando se la llame en el evento de envio de formulario 
async function registrarPago(idPago, metodoPago) {
    const confirmado = await ModalConfirmacionPlan(
        'Confirmar Pago',
        `¿Estás seguro que deseas confirmar el pago? Esta acción es irreversible, Verifique antes de confirmar.`
    );

    if (confirmado) {
        const datasUpdate = {
            id_admin: idAdmin,
            id_pago: idPago,
            metodo_pago: metodoPago
        };
        // Muestra un mensaje temporal o un spinner antes del fetch si quieres
        showCustomModal('cargando', 'Procesando...', 'Procesando su pago, por favor espere.');
        try {
            const respuProcesarPago = await fetch('../admin/Modelo/MarcarPagoComoPagado.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datasUpdate)
            });

            if (!respuProcesarPago.ok) {
                showCustomModal('error', 'Error del servidor', 'No se pudo realizar el pago, el servidor no esta respondiendo. Intente nuevamente mas tarde.');
                return;
            }

            const resultProcesarPago = await respuProcesarPago.json();

            if (!resultProcesarPago.success) {
                showCustomModal('error', 'Error al Procesar su pago', resultProcesarPago.message || 'No se pudo registrar su pago. Intente nuevamente mas tarde.');
                return;
            }

            console.log(resultProcesarPago.message);
            showCustomModal('success', 'Pago Exitoso', 'El pago ha sido registrado correctamente.');
            setTimeout(() => {
                window.location.reload();
            }, 3000);

        } catch (error) {
            console.error("Error en la solicitud de eliminación:", error);
            showCustomModal('error', 'Error al registrar el pago', 'No se pudo registrar el pago. intente nuevamente mas tarde.');
        }
    }
}

// Trabaja sobre dataPagosOriginal y actualiza dataListPymes (la lista de trabajo).
function aplicarFiltrosYOrdenamientoPagos() {
    // 1. Obtener valores de los controles de Pagos
    // **¡AJUSTA ESTOS IDs!** (searchPago y filterEstadoPago son nombres sugeridos)
    const buscadorPagos = document.getElementById('searchPago');
    const selectorEstadoPagos = document.getElementById('filterEstadoPago');

    if (!buscadorPagos || !selectorEstadoPagos) {
        // Salir si los elementos del DOM aún no existen (o el ID es incorrecto)
        return;
    }

    const searchTerm = buscadorPagos.value.toLowerCase().trim();
    const filterValue = selectorEstadoPagos.value;

    // Clonamos la lista original (dataPagosOriginal) para empezar el procesamiento
    let filteredList = [...dataPagosOriginal];

    // 2. Aplicar Búsqueda
    if (searchTerm) {
        filteredList = filteredList.filter(pago => {
            const cliente = `${pago.Nombre_Cliente} ${pago.apellido}`.toLowerCase();
            const plan = pago.Nombre_Membresia.toLowerCase();
            const dni = String(pago.dni).toLowerCase();

            // Buscar coincidencia
            return cliente.includes(searchTerm) ||
                plan.includes(searchTerm) ||
                dni.includes(searchTerm);
        });
    }

    // 3. Aplicar Filtro de Estado (y Ordenamiento si aplica)
    if (filterValue !== 'todos') {
        if (filterValue === 'pendiente') {
            filteredList = filteredList.filter(pago => pago.estado === 0);
        } else if (filterValue === 'pagado') {
            filteredList = filteredList.filter(pago => pago.estado === 1);
        } else if (filterValue === 'vencido') {
            // Lógica para vencido: el estado es 0 (Pendiente) Y la fecha de vencimiento es pasada
            const hoy = new Date();
            filteredList = filteredList.filter(pago => {
                const fechaVencimiento = new Date(pago.fecha_vencimiento);
                return pago.estado === 0 && fechaVencimiento < hoy;
            });
        }
        // Puedes agregar más lógica de ordenamiento (ej: por fecha_pago_z_a) aquí si lo necesitas
    }

    // 4. Actualizar la lista que usa la tabla y la exportación (¡EL PASO CLAVE!)
    dataListPymes = filteredList;

    // 5. Reiniciar paginación y recargar la tabla de Pagos (DEBES LLAMAR A TU FUNCIÓN DE RENDERIZADO DE PAGOS AQUÍ)
    // Ejemplo:
    // currentPagePagos = 1; 
    // CargarPagosEnTabla(); 
}
// ------------------Seccion Gestion de clientes ---------------------
// Variables globales para manejar paginación
let currentPage = 1;
const rowsPerPage = 10;

// Función para renderizar los clientes en la tabla con paginación
function CargarClientesEnTabla() {
    const tbodyClientes = document.getElementById('clientTableBody');
    const MjsTotal = document.getElementById('MsjtotalClients');
    const btnAnterior = document.getElementById('prevPageBtn');
    const btnSiguiente = document.getElementById('nextPageBtn');
    const busquedaActiva = document.getElementById('searchClient').value.trim() !== "";

    tbodyClientes.innerHTML = '';
    let filaClientes = "";

    // Calcular los índices de inicio y fin
    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;

    // Cortar el array para mostrar solo los de la página actual
    const clientesPagina = dataListClientes.slice(startIndex, endIndex); // <--- Usa la lista ya filtrada/ordenada

    // Renderizar filas (tu código existente)
    clientesPagina.forEach(element => {
        const estadoClase = (element.estado === "Activo") ? "status-active" : "status-inactive";
        const nombreCompleto = `${element.apellido} ${element.nombre}`;
        filaClientes += `
            <tr class="client-table-row"> 
                <td>${nombreCompleto}</td>
                <td>${element.dni}</td>
                <td>${element.telefono}</td>
                <td>${element.email}</td>
                <td>${element.fecha_alta}</td>
                <td class="${estadoClase}">${element.estado}</td>
                <td>
                    <button class="btn-client-action btn-client-edit" data-id="${element.id_cliente}"  title="Ver/Editar cliente">✏️</button>
                    <button class="btn-client-action btn-client-delete" data-id="${element.id_cliente}" data-nombre="${nombreCompleto}" title="Eliminar cliente">🗑️</button>
                </td>
            </tr>
        `;
    });

    tbodyClientes.innerHTML = filaClientes;

    // Mensaje de total (Ajustado)
    if (busquedaActiva) {
        MjsTotal.textContent = `Se encontraron ${dataListClientes.length} cliente(s) que coinciden con el filtro/búsqueda. (Página ${currentPage} de ${Math.ceil(dataListClientes.length / rowsPerPage)})`;
    } else {
        MjsTotal.textContent = `Se están mostrando ${clientesPagina.length} clientes (página ${currentPage}) de ${dataListClientes.length} en total.`;
    }

    // Habilitar o deshabilitar botones
    const totalPages = Math.ceil(dataListClientes.length / rowsPerPage);
    btnAnterior.disabled = currentPage === 1;
    btnSiguiente.disabled = currentPage >= totalPages;
}

// Función para aplicar filtros y ordenamiento
function aplicarFiltrosYOrdenamiento() {
    // 1. Obtener valores de los controles
    const searchTerm = document.getElementById('searchClient').value.toLowerCase().trim();
    const filterValue = document.getElementById('filterEstado').value;

    // 2. FILTRADO DE DATOS (Búsqueda General)
    // Clonamos la lista original para empezar el procesamiento
    let filteredList = [...dataClientesOriginal];

    if (searchTerm) {
        filteredList = filteredList.filter(cliente => {
            const nombreCompleto = `${cliente.nombre} ${cliente.apellido}`.toLowerCase();
            const dni = String(cliente.dni).toLowerCase();
            const email = String(cliente.email).toLowerCase();

            // Buscar coincidencia en cualquiera de los campos
            return nombreCompleto.includes(searchTerm) ||
                dni.includes(searchTerm) ||
                email.includes(searchTerm);
        });
    }

    // 3. FILTRADO Y ORDENAMIENTO POR SELECTOR DE ESTADO
    if (filterValue !== 'todos') {
        if (filterValue === 'activo' || filterValue === 'inactivo') {
            // Lógica de FILTRADO por Estado
            const estadoDeseado = filterValue.charAt(0).toUpperCase() + filterValue.slice(1); // "Activo" o "Inactivo"
            filteredList = filteredList.filter(cliente => cliente.estado === estadoDeseado);

        } else if (filterValue === 'fecha_alta_a_z') {
            // Lógica de ORDENAMIENTO por Fecha de Alta (Ascendente: Menor a Mayor)
            // Esto ordena la lista resultante del filtrado (si hubo búsqueda)
            filteredList.sort((a, b) => new Date(a.fecha_alta) - new Date(b.fecha_alta));

        } else if (filterValue === 'fecha_alta_z_a') {
            // Lógica de ORDENAMIENTO por Fecha de Alta (Descendente: Mayor a Menor)
            filteredList.sort((a, b) => new Date(b.fecha_alta) - new Date(a.fecha_alta));
        }
    }

    // 4. Actualizar la lista que usa la paginación
    dataListClientes = filteredList;

    // 5. Reiniciar a la primera página y recargar la tabla
    currentPage = 1;
    CargarClientesEnTabla();
}

// Conexión de Eventos para Filtro y Búsqueda (Debe ir DESPUÉS de DOMContentLoaded)
function conectarEventosFiltro() {
    const buscador = document.getElementById('searchClient');
    const filterSelect = document.getElementById('filterEstado');

    // Reemplazamos los listeners viejos que usaban style.display
    buscador.addEventListener('input', aplicarFiltrosYOrdenamiento);
    filterSelect.addEventListener('change', aplicarFiltrosYOrdenamiento);

    // Opcional: También conecta los botones de paginación para usar la nueva lógica
    document.getElementById('prevPageBtn').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            CargarClientesEnTabla();
        }
    });

    document.getElementById('nextPageBtn').addEventListener('click', () => {
        const totalPages = Math.ceil(dataListClientes.length / rowsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            CargarClientesEnTabla();
        }
    });
}

// Eventos para cambiar de página
document.getElementById('prevPageBtn').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        CargarClientesEnTabla();
    }
}); //Siguientes paginas

document.getElementById('nextPageBtn').addEventListener('click', () => {
    if (currentPage * rowsPerPage < dataListClientes.length) {
        currentPage++;
        CargarClientesEnTabla();
    }
}); //Anteriores paginas

// Cerrar modal al hacer clic en "Cancelar"
document.getElementById('btnCancelarEdicion').addEventListener('click', () => {
    document.getElementById('modalEditarCliente').classList.add('modal-overlay--oculto'); // Usamos la clase para ocultar
});

// Cerrar modal al hacer clic fuera de ella en el overlay de editar
window.document.addEventListener('click', (e) => {
    const modal = document.getElementById('modalEditarCliente');
    // Verifica si se hizo click en el overlay (modalEditarCliente) o en el botón Cancelar
    if (e.target.id === 'modalEditarCliente' || e.target.id === 'btnCancelarEdicion') {
        modal.classList.add('modal-overlay--oculto'); // Usamos la clase para ocultar
    }
});

// Capturar confirmación de edición
document.getElementById('formEditarCliente').addEventListener('submit', async (e) => {
    e.preventDefault();

    //  Aquí mandás los datos al backend
    const datosActualizados = {
        id_admin: idAdmin,
        id_cliente: document.getElementById('editId').value,
        nombre: document.getElementById('editNombre').value,
        apellido: document.getElementById('editApellido').value,
        dni: document.getElementById('editDni').value,
        telefono: document.getElementById('editTelefono').value,
        email: document.getElementById('editEmail').value,
        fecha_alta: document.getElementById('editFechaAlta').value,
        estado: document.getElementById('editEstado').value
    };

    console.log("Datos a actualizar:", datosActualizados);

    // ---- Pedir confirmación ----
    const confirmado = await ModalConfirmacionPlan(
        'Confirmación',
        `¿Está seguro que desea actualizar los datos del cliente: ${datosActualizados.nombre} ${datosActualizados.apellido} ?`
    );

    if (!confirmado) {
        return;
    }
    //Procesamos los datos en el servidor de modo seguro.
    try {
        //Pasamos al servidor a actualizar los datos
        const respuUpdateCliente = await fetch('../admin/Modelo/UpdateCliente.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datosActualizados)
        })

        if (!respuUpdateCliente.ok) {
            showCustomModal('error', '❌ Error del servidor', 'Intente nuevamente mas tarde 😢');
            return;
        }

        const resultUpdateCliente = await respuUpdateCliente.json();

        if (!resultUpdateCliente.success) {
            showCustomModal('error', '❌ Error al editar el cliente', resultUpdateCliente.message);
            return;
        }

        showCustomModal('success', '✅ Cliente editado', resultUpdateCliente.message);
        // Cerrar modal luego de confirmar
        document.getElementById('modalEditarCliente').classList.add('modal-overlay--oculto');

        // Recargar la pagina despues de 3 segundo
        setTimeout(() => {
            window.location.reload();
        }, 3000);
    } catch (error) {
        console.log(error);
        showCustomModal('error', '❌ Error al editar el cliente', 'Intente nuevamente mas tarde 😢');
    }
});

// Capturar delegacion de evento del boton de editar o eliminar
document.getElementById('clientTableBody').addEventListener('click', async (e) => {
    // EDITAR
    if (e.target.classList.contains('btn-client-edit')) {
        const fila = e.target.closest('tr');
        const celdas = fila.querySelectorAll('td');
        const idCliente = e.target.dataset.id;

        document.getElementById('editId').value = idCliente;
        document.getElementById('editApellido').value = celdas[0].textContent.split(" ")[0];
        document.getElementById('editNombre').value = celdas[0].textContent.split(" ")[1] || "";
        document.getElementById('editDni').value = celdas[1].textContent;
        document.getElementById('editTelefono').value = celdas[2].textContent;
        document.getElementById('editEmail').value = celdas[3].textContent;
        document.getElementById('editFechaAlta').value = celdas[4].textContent;
        document.getElementById('editEstado').value = celdas[5].textContent;

        document.getElementById('modalEditarCliente').classList.remove('modal-overlay--oculto');
        return;
    }

    // ELIMINAR
    if (e.target.classList.contains('btn-client-delete')) {
        const idCliente = e.target.getAttribute('data-id');
        const NameClient = e.target.getAttribute('data-nombre');

        // ---- Pedir confirmación ----
        const confirmado = await ModalConfirmacionPlan(
            'Confirmación',
            `¿Está seguro que desea eliminar a ${NameClient} (Su ID es ${idCliente})?`
        );
        if (!confirmado) return;

        try {
            const datasDelete = {
                id_cliente: idCliente,
                id_admin: idAdmin
            };

            const respuDeleteCliente = await fetch('../admin/Modelo/DeleteCliente.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datasDelete)
            });

            const resultDeleteCliente = await respuDeleteCliente.json();

            if (!resultDeleteCliente.success) {
                showCustomModal('error', '❌ Error al eliminar el cliente', resultDeleteCliente.message);
                return;
            }

            showCustomModal('success', '✅ Cliente eliminado', resultDeleteCliente.message);

            setTimeout(() => {
                window.location.reload();
            }, 3000);

        } catch (error) {
            console.log(error);
            showCustomModal('error', '❌ Error al eliminar el cliente', 'Intente nuevamente más tarde 😢');
        }
    }
});

// Buscar cliente por nombre o dni en tiempo real en la tabla (evento por teclado)
const buscador = document.getElementById('searchClient');
const filterSelect = document.getElementById('filterEstado');

buscador.addEventListener('input', (e) => {
    const busqueda = e.target.value;
    const filas = document.querySelectorAll('#clientTableBody tr');
    const MjsTotal = document.getElementById('MsjtotalClients'); //Informar encontrados
    let encontrados = 0;

    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        const nombre = celdas[0].textContent;
        const dni = celdas[1].textContent;
        const email = celdas[3].textContent.toLowerCase();

        if (nombre.toLowerCase().includes(busqueda.toLowerCase())
            || dni.toLowerCase().includes(busqueda.toLowerCase())
            || email.includes(busqueda)) {
            fila.style.display = 'table-row';
            encontrados++; // sumamos solo las visibles
        } else {
            fila.style.display = 'none';
        }
    });
    // Mensaje de total
    if (busqueda.trim() === "") {
        // Si no hay búsqueda, mostrar mensaje normal con paginación
        MjsTotal.textContent = `Se están mostrando ${filas.length} clientes (página ${currentPage}) de ${dataListClientes.length} en total.`;
    } else {
        // Si hay búsqueda, mostrar encontrados
        MjsTotal.textContent = `Se encontraron ${encontrados} cliente(s) que coinciden con la búsqueda.`;
    }
})

// ------------------Seccion Registrar cliente a plan -----------------------
const selectPlan = document.getElementById('clientPlan');
//Funciones 
function cargarPlanesEnRegistrar() {
    selectPlan.innerHTML = '';
    let filaplanes = "";

    datalistPlanes.filter(p => p.activa == 1).forEach(element => {
        filaplanes += `
        <option value="${element.id_membresia}">${element.nombre}</option>
        `;
    });
    selectPlan.innerHTML = filaplanes;
}

// --- Validaciones ---
function validateEmail(input, messageEl) {
    const value = input.value.trim();
    const regex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/; // solo gmail
    if (!value) {
        setError(input, messageEl, "El email es obligatorio");
        return false;
    } else if (!regex.test(value)) {
        setError(input, messageEl, "Debe ser un correo válido de Gmail");
        return false;
    } else {
        setSuccess(input, messageEl, "Email válido ✅");
        return true;
    }
}
function validatePlan(select, messageEl) {
    if (!select.value) {
        setError(select, messageEl, "Debe seleccionar un plan");
        return false;
    } else {
        setSuccess(select, messageEl, "Plan seleccionado ✅");
        return true;
    }
}

function validatePayment(select, messageEl) {
    if (!select.value) {
        setError(select, messageEl, "Debe seleccionar el estado de pago");
        return false;
    } else {
        setSuccess(select, messageEl, "Estado de pago válido ✅");
        return true;
    }
}
// --- Helpers ---
function setError(input, messageEl, message) {
    input.classList.add("input-error");
    input.classList.remove("input-success");
    messageEl.textContent = message;
    messageEl.classList.add("error-message");
    messageEl.classList.remove("success-message");
}
function setSuccess(input, messageEl, message) {
    input.classList.add("input-success");
    input.classList.remove("input-error");
    messageEl.textContent = message;
    messageEl.classList.add("success-message");
    messageEl.classList.remove("error-message");
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

// ConfirmacionRegistrarPlan — modal con HTML y CSS externos
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
//Evento para registrar socio al plan
const btnregistrar = document.getElementById('registerClientBtn');
btnregistrar.addEventListener('click', async (event) => {
    event.preventDefault(); // siempre cancelo envío primero
    // obtengo referencias ahora (asegura que existen)
    const formRegistrar = document.getElementById('newClientForm');
    const fromRegistrar = new FormData(formRegistrar);

    const email = document.getElementById("clientEmail");
    const emailMsg = document.getElementById("emailStatusMessage");

    const plan = document.getElementById("clientPlan");
    const planMsg = document.getElementById("planStatusMessage");

    const payment = document.getElementById("paymentStatus");
    const paymentMsg = document.getElementById("paymentStatusMessage");

    // correr todas las validaciones (usar && para cortar en falso)
    const valid = validateEmail(email, emailMsg) &&
        validatePlan(plan, planMsg) &&
        validatePayment(payment, paymentMsg);

    if (!valid) {
        closeCustomModal();
        showCustomModal('error', '❌ Error al registrar', 'Existen errores en el formulario, verifique los campos e intente nuevamente.');
        return; // corto acá, no sigue al backend
    }

    //Si todo es valido pasamos al backen a registrar al socio al plan 
    const datas = {
        id_admin: idAdmin,
        email: fromRegistrar.get('clientEmail'),
        plan: fromRegistrar.get('clientPlan'),
        fecha_alta: fromRegistrar.get('clientStartDate'),
        estado_pago: fromRegistrar.get('paymentStatus'),
    };
    // ---- Pedir confirmación ----
    const confirmado = await ModalConfirmacionPlan(
        '⚠️ Confirmación',
        `¿Está seguro que desea registrar el cliente con el email ${datas.email} al plan seleccionado?`
    );

    if (!confirmado) {
        return;
    }

    // ------------------ Procesamos el Registro de socio al plan -----------
    try {
        showCustomModal('cargando', '⏳ Registrando al socio', 'Espere por favor...');
        console.log(datas);
        const respuRegistro = await fetch('../admin/Modelo/RegistrarSocioPlan.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datas)
        });

        if (!respuRegistro.ok) {
            closeCustomModal();
            showCustomModal('error', '❌ Error del servidor', 'Error al registrar al socio.\nEl servidor no esta respondiendo, intente nuevamente mas tarde 😢');
            setTimeout(() => {
                window.location.href = '../login/login.php';
            }, 3000);
            return;
        }

        const resulRegistro = await respuRegistro.json();

        if (!resulRegistro.success) {
            closeCustomModal();
            showCustomModal('error', '❌ Error al registrar al socio', resulRegistro.message);
            //Insertar el mensje en la validacion del email
            emailMsg.textContent = resulRegistro.message;
            return;
        }

        showCustomModal('success', '✅ Registro exitoso', resulRegistro.message);
        setTimeout(() => {
            window.location.reload();
        }, 3000);

    } catch (error) {
        console.error(error);
        closeCustomModal();
        showCustomModal('error', '❌ Error al registrar al socio', 'Intente nuevamente mas tarde');
        setTimeout(() => {
            window.location.href = '../login/login.php';
        }, 3000);
        return;
    }
})

// =========================== Ejecucion Principal ===========================
document.addEventListener('DOMContentLoaded', async (event) => {
    //Elementos del DOM
    idAdmin = sessionStorage.getItem('idUser'); //Obengo el id del admin
    let FotoUserURL = sessionStorage.getItem('fotoUrl');
    const defaultUrl = '../img/user_default.png'; // Ruta por defecto

    showCustomModal('cargando', '⏳ Cargando la pagina', 'Espere por favor, mientras cargamos sus datos');
    //Verificar si hay un id
    if (!idAdmin || idAdmin === "" || idAdmin == null) {
        closeCustomModal();
        showCustomModal('error', '❌ Error al ingresar', 'No se encuentra disponible la pagina para este usuario, intente nuevamente mas tarde 😢');
        setTimeout(() => {
            window.location.href = '../login/login.php';
        }, 5000);
        return;
    }
    // 1. Verificación y asignación de ruta por defecto
    if (!FotoUserURL || FotoUserURL === "" || FotoUserURL == null) {
        FotoUserURL = defaultUrl;
        sessionStorage.setItem('fotoUrl', FotoUserURL);
    }
    // 2. Cargar la foto del usuario en el header
    // Llamamos a la función SIEMPRE que tengamos una URL válida (ya sea la personalizada o la por defecto).
    CargarFotoUsuario(FotoUserURL);

    // ------------------ Procesamiento de modo seguro y sincronico con el servidor -----------
    try {
        //Obtener datos del administrador
        const respuDataAdmin = await fetch('../admin/Modelo/ObtenerDatosAdmin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_admin: idAdmin })
        });

        //Obtener los planes u membresia disponible.
        const respuAllPlanes = await fetch('../admin/Modelo/ObtenerPlanes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_admin: idAdmin })
        });

        //Obtener todos los planes con los clientes asociados
        const respuAllClientes = await fetch('../admin/Modelo/ObtenerAllClientes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_admin: idAdmin })
        })

        //Obtener todos los pagos asociado al cliente
        const respuAllPagosCliente = await fetch('../admin/Modelo/ObtenerPagosCliente.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_admin: idAdmin })
        })

        //Verficar problemas con el servidor 
        if (!respuAllPlanes.ok || !respuDataAdmin.ok || !respuAllClientes.ok || !respuAllPagosCliente.ok) {
            closeCustomModal();
            showCustomModal('error', '❌ Error del servidor', 'Error al procesar los planes.\nEl servidor no esta respondiendo, intente nuevamente mas tarde 😢');
            //volver al login
            setTimeout(() => {
                window.location.href = '../login/login.php';
            }, 3000);
            return;
        }

        //Procesar la respuesta del servidor
        const resultAllPlanes = await respuAllPlanes.json();
        const resultDataAdmin = await respuDataAdmin.json();
        const resultClientes = await respuAllClientes.json();
        const resultPagosCliente = await respuAllPagosCliente.json();

        if (!resultAllPlanes.success) {
            closeCustomModal();
            showCustomModal('warning', '⚠️ Advertencia', 'No se encontraron planes disponibles, intente nuevamente mas tarde 😢');
        }
        if (!resultDataAdmin.success) {
            closeCustomModal();
            showCustomModal('error', '❌ Error', respuDataAdmin.message);
            return;
        }
        if (!resultClientes.success) {
            closeCustomModal();
            showCustomModal('error', '❌ Error', respuAllPlanesAsociados.message);
            return;
        }
        if (!resultPagosCliente.success) {
            closeCustomModal();
            showCustomModal('error', '❌ Error', respuAllPlanesAsociados.message);
            return;
        }

        // ----------- Asignacion de valor a las variables -------------------
        dataListAdmin = resultDataAdmin.dataAdmin
        datalistPlanes = resultAllPlanes.dataPlanes
        dataClientesOriginal = resultClientes.dataClientes
        dataListClientes = resultClientes.dataClientes
        dataListPymes = resultPagosCliente.dataPagos
        dataPagosOriginal = resultPagosCliente.dataPagos;  // Clonamos para la lista original

        // ----------- Estado de depuracion -----------
        /*console.log(dataListAdmin);
        console.log(dataListClientes);
        console.log(dataListPymes);
        */
        console.log(datalistPlanes);

        // ================== Carga dinamica de la pagina ==================
        CargarNombreHeader();

        // ================== Carga pagos de clientes en secion de pagos ==================
        CargarPagosEnTabla();

        // ================== Seccion listar socio-plan ===================
        CargarClientesEnTabla(); // Función que se encarga de cargar los clientes en la tabla
        conectarEventosFiltro(); // Función que se encarga de conectar los eventos de filtrado de la tabla

        // ================== Seccion de registrar socio al plan ==================
        //Cargar los planes disponibles en la seccion de registrar cliente al plan
        cargarPlanesEnRegistrar(datalistPlanes);

        // ------------ Validaciones en tiempo real al registrar un socio -----------------
        const email = document.getElementById("clientEmail");
        const emailMsg = document.getElementById("emailStatusMessage");

        const plan = document.getElementById("clientPlan");
        const planMsg = document.getElementById("planStatusMessage");

        const payment = document.getElementById("paymentStatus");
        const paymentMsg = document.getElementById("paymentStatusMessage");

        // listeners en tiempo real
        email.addEventListener("input", () => validateEmail(email, emailMsg));
        plan.addEventListener("change", () => validatePlan(plan, planMsg));
        payment.addEventListener("change", () => validatePayment(payment, paymentMsg));

        // --- Autocompletar fecha con hoy y bloquear edición (readonly, y se envía en el form) --
        const dateInput = document.getElementById("clientStartDate");
        const today = new Date();

        // Usar los métodos locales para construir el formato YYYY-MM-DD
        const year = today.getFullYear();
        // Se añade 1 porque getMonth() devuelve 0 (Enero) a 11 (Diciembre)
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');

        const todayLocalISO = `${year}-${month}-${day}`;

        dateInput.value = todayLocalISO;
        dateInput.min = todayLocalISO;
        dateInput.readOnly = true; // bloquea escritura manual
        dateInput.disabled = false; // debe quedar false para que el valor llegue en FormData

        closeCustomModal();
    } catch (error) {
        console.log(error);
        closeCustomModal();
        showCustomModal('error', '❌ Error al cargar la pagina', 'Intente nuevamente mas tarde 😢');
        setTimeout(() => {
            window.location.href = '../login/login.php';
        }, 3000);
    }
})