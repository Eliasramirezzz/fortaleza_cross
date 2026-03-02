// bin/modal_controller.js

// Variable global para almacenar el callback de confirmación
window.customModalCallback = null;

// =========================================================
// HTML TEMPLATES PARA CADA TIPO DE MODAL
// =========================================================
const modalTemplates = {
    success: `
        <div id="successModal" class="custom-modal custom-modal-success">
            <div class="modal-icon-wrapper">
                <svg class="modal-icon checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>
            <h3 class="modal-title"></h3>
            <p class="modal-message"></p>
            <button class="modal-button modal-button-success" data-action="close">Aceptar</button>
        </div>
    `,
    error: `
        <div id="errorModal" class="custom-modal custom-modal-error">
            <div class="modal-icon-wrapper">
                <svg class="modal-icon cross" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="cross__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="cross__path cross__path--right" fill="none" d="M16,16 l20,20" />
                    <path class="cross__path cross__path--left" fill="none" d="M16,36 l20,-20" />
                </svg>
            </div>
            <h3 class="modal-title"></h3>
            <p class="modal-message"></p>
            <button class="modal-button modal-button-error" data-action="close">Cerrar</button>
        </div>
    `,
    warning: `
        <div id="warningModal" class="custom-modal custom-modal-warning">
            <div class="modal-icon-wrapper">
                <svg class="modal-icon warning" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                    <circle class="warning__circle" cx="25" cy="25" r="23" fill="none"/>
                    <path class="warning__line" fill="none" d="M25 15L25 30"/>
                    <circle class="warning__dot" cx="25" cy="35" r="2"/>
                </svg>
            </div>
            <h3 class="modal-title"></h3>
            <p class="modal-message"></p>
            <button class="modal-button modal-button-warning" data-action="close">Entendido</button>
        </div>
    `,
    info: `
        <div id="infoModal" class="custom-modal custom-modal-info">
            <div class="modal-icon-wrapper">
                <svg class="modal-icon info" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="info__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="info__line" fill="none" d="M26 15L26 35"/>
                    <circle class="info__dot" cx="26" cy="40" r="2"/>
                </svg>
            </div>
            <h3 class="modal-title"></h3>
            <p class="modal-message"></p>
            <button class="modal-button modal-button-info" data-action="close">Aceptar</button>
        </div>
    `,
    confirm: `
        <div id="confirmModal" class="custom-modal custom-modal-confirm">
            <div class="modal-icon-wrapper">
                <svg class="modal-icon question" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="question__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="question__mark" fill="none" d="M26,16 c-4,0 -6,3 -6,6 c0,2 2,4 4,4 c3,0 4,-2 4,-4 M26,32 l0,2"/>
                </svg>
            </div>
            <h3 class="modal-title"></h3>
            <p class="modal-message"></p>
            <div class="modal-buttons">
                <button class="modal-button modal-button-confirm-yes" id="confirmYesBtn" data-action="confirm-yes">Sí</button>
                <button class="modal-button modal-button-confirm-no" data-action="close">No</button>
            </div>
        </div>
    `,
    // =========================================================
    // NUEVO TEMPLATE PARA MODAL DE CARGANDO
    // =========================================================
    cargando: `
        <div id="loadingModal" class="custom-modal custom-modal-loading">
            <h3 class="modal-title"></h3>
            <div class="loader-wrapper">
                <div class="loader"></div>
            </div>
            <p class="modal-message"></p>
            </div>
    `
};

/**
 * Función que asegura que el overlay del modal existe en el DOM.
 * Si no existe, lo crea y lo adjunta al body.
 * @returns {HTMLElement} El elemento del overlay.
 */
function ensureModalOverlayExists() {
    let overlay = document.getElementById('customModalOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'customModalOverlay';
        overlay.className = 'custom-modal-overlay';
        overlay.innerHTML = '<div class="custom-modal-content"></div>'; // Contenedor interno
        document.body.appendChild(overlay);
    }
    return overlay;
}

/**
 * Muestra un modal personalizado.
 * @param {string} type - Tipo de modal a mostrar ('success', 'error', 'warning', 'info', 'confirm', 'cargando').
 * @param {string} [title=''] - Título opcional para el modal. Si no se provee, usa un título por defecto.
 * @param {string} [message=''] - Mensaje opcional para el modal. Si no se provee, usa un mensaje por defecto.
 * @param {function} [callback=null] - Función de callback para el modal de 'confirm'. Se llama con true/false.
 */
function showCustomModal(type, title = '', message = '', callback = null) {
    const overlay = ensureModalOverlayExists();
    const modalContentContainer = overlay.querySelector('.custom-modal-content');

    // Limpiar contenido previo para asegurar que solo haya un modal a la vez
    modalContentContainer.innerHTML = '';

    // Obtener la plantilla HTML para el tipo de modal solicitado
    const modalHtml = modalTemplates[type];
    if (!modalHtml) {
        console.warn('Tipo de modal desconocido o plantilla no definida:', type);
        return;
    }

    // Insertar el HTML del modal en el contenedor
    modalContentContainer.innerHTML = modalHtml;

    // Obtener el modal recién insertado
    const targetModal = modalContentContainer.querySelector('.custom-modal');
    
    // 1. Determinar y actualizar el título y mensaje
    let defaultTitle = '';
    let defaultMessage = '';

    switch (type) {
        case 'success':
            defaultTitle = '¡Éxito!';
            defaultMessage = 'Tu operación se completó correctamente.';
            break;
        case 'error':
            defaultTitle = '¡Error!';
            defaultMessage = 'Ha ocurrido un error inesperado. Por favor, inténtalo de nuevo.';
            break;
        case 'warning':
            defaultTitle = 'Advertencia';
            defaultMessage = 'Por favor, revisa la información antes de continuar.';
            break;
        case 'info':
            defaultTitle = 'Información';
            defaultMessage = 'Esta es una notificación informativa.';
            break;
        case 'confirm':
            defaultTitle = '¿Estás seguro?';
            defaultMessage = 'Esta acción no se puede deshacer.';
            // Guardar el callback para este tipo de modal
            window.customModalCallback = callback;
            break;
        case 'cargando': // <--- TU NUEVO CASE AQUÍ
            defaultTitle = 'Espere por favor';
            defaultMessage = 'Cargando...';
            break;
    }

    targetModal.querySelector('.modal-title').textContent = title || defaultTitle;
    targetModal.querySelector('.modal-message').textContent = message || defaultMessage;

    // 2. Mostrar el modal objetivo (ya es display:block por defecto al insertarlo)
    targetModal.style.display = 'block';

    // 3. Asignar event listeners para cerrar (todos los botones con data-action="close")
    // Ojo: el modal de cargando NO tiene botón de cierre, así que este bloque no le afectará.
    const closeButtons = targetModal.querySelectorAll('[data-action="close"]');
    closeButtons.forEach(button => {
        if (button._currentListener) {
            button.removeEventListener('click', button._currentListener);
        }
        const newListener = closeCustomModal;
        button.addEventListener('click', newListener);
        button._currentListener = newListener;
    });

    // 4. Asignar event listener para el botón 'Sí' del modal de confirmación
    if (type === 'confirm') {
        const confirmYesBtn = targetModal.querySelector('#confirmYesBtn');
        if (confirmYesBtn) {
            if (confirmYesBtn._currentListener) {
                confirmYesBtn.removeEventListener('click', confirmYesBtn._currentListener);
            }
            const newListener = function() {
                closeCustomModal();
                if (window.customModalCallback && typeof window.customModalCallback === 'function') {
                    window.customModalCallback(true);
                    window.customModalCallback = null;
                }
            };
            confirmYesBtn.addEventListener('click', newListener);
            confirmYesBtn._currentListener = newListener;
        }
    }

    // 5. Mostrar el overlay principal con la clase 'active' para animaciones
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';

    // 6. Permitir cerrar haciendo clic fuera del modal (overlay)
    // Para el modal de 'cargando', usualmente NO se cierra haciendo clic fuera.
    // Solo se debe añadir el listener si el tipo NO es 'cargando'.
    if (type !== 'cargando') {
        if (overlay._currentOverlayClickListener) {
            overlay.removeEventListener('click', overlay._currentOverlayClickListener);
        }
        const overlayClickListener = function(event) {
            if (event.target === overlay) {
                closeCustomModal();
                if (type === 'confirm' && window.customModalCallback && typeof window.customModalCallback === 'function') {
                    window.customModalCallback(false);
                    window.customModalCallback = null;
                }
            }
        };
        overlay.addEventListener('click', overlayClickListener);
        overlay._currentOverlayClickListener = overlayClickListener;
    }
}

/**
 * Cierra el modal activo y el overlay.
 */
function closeCustomModal() {
    const overlay = document.getElementById('customModalOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        document.body.style.overflow = '';

        const modalContentContainer = overlay.querySelector('.custom-modal-content');
        if (modalContentContainer) {
            modalContentContainer.innerHTML = '';
        }
        
        window.customModalCallback = null;
        if (overlay._currentOverlayClickListener) {
            overlay.removeEventListener('click', overlay._currentOverlayClickListener);
            overlay._currentOverlayClickListener = null;
        }
    }
}