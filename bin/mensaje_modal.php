
    <div id="customModalOverlay" class="custom-modal-overlay">
        <div class="custom-modal-content">

            <div id="successModal" class="custom-modal custom-modal-success" style="display: none;">
                <div class="modal-icon-wrapper">
                    <svg class="modal-icon checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>
                <h3 class="modal-title">¡Éxito!</h3>
                <p class="modal-message">Tu operación se completó correctamente.</p>
                <button class="modal-button modal-button-success" onclick="closeCustomModal()">Aceptar</button>
            </div>

            <div id="errorModal" class="custom-modal custom-modal-error" style="display: none;">
                <div class="modal-icon-wrapper">
                    <svg class="modal-icon cross" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="cross__circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="cross__path cross__path--right" fill="none" d="M16,16 l20,20" />
                        <path class="cross__path cross__path--left" fill="none" d="M16,36 l20,-20" />
                    </svg>
                </div>
                <h3 class="modal-title">¡Error!</h3>
                <p class="modal-message">Ha ocurrido un error inesperado. Por favor, inténtalo de nuevo.</p>
                <button class="modal-button modal-button-error" onclick="closeCustomModal()">Cerrar</button>
            </div>

            <div id="warningModal" class="custom-modal custom-modal-warning" style="display: none;">
                <div class="modal-icon-wrapper">
                    <svg class="modal-icon warning" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                        <circle class="warning__circle" cx="25" cy="25" r="23" fill="none"/>
                        <path class="warning__line" fill="none" d="M25 15L25 30"/>
                        <circle class="warning__dot" cx="25" cy="35" r="2"/>
                    </svg>
                </div>
                <h3 class="modal-title">Advertencia</h3>
                <p class="modal-message">Por favor, revisa la información antes de continuar.</p>
                <button class="modal-button modal-button-warning" onclick="closeCustomModal()">Entendido</button>
            </div>

            <div id="infoModal" class="custom-modal custom-modal-info" style="display: none;">
                <div class="modal-icon-wrapper">
                    <svg class="modal-icon info" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="info__circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="info__line" fill="none" d="M26 15L26 35"/>
                        <circle class="info__dot" cx="26" cy="40" r="2"/>
                    </svg>
                </div>
                <h3 class="modal-title">Información</h3>
                <p class="modal-message">Esta es una notificación informativa.</p>
                <button class="modal-button modal-button-info" onclick="closeCustomModal()">Aceptar</button>
            </div>

            <div id="confirmModal" class="custom-modal custom-modal-confirm" style="display: none;">
                <div class="modal-icon-wrapper">
                    <svg class="modal-icon question" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="question__circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="question__mark" fill="none" d="M26,16 c-4,0 -6,3 -6,6 c0,2 2,4 4,4 c3,0 4,-2 4,-4 M26,32 l0,2"/>
                    </svg>
                </div>
                <h3 class="modal-title">¿Estás seguro?</h3>
                <p class="modal-message">Esta acción no se puede deshacer.</p>
                <div class="modal-buttons">
                    <button class="modal-button modal-button-confirm-yes" id="confirmYesBtn">Sí</button>
                    <button class="modal-button modal-button-confirm-no" onclick="closeCustomModal()">No</button>
                </div>
            </div>

            <div id="modalCargando" class="modal-container">
                <div class="modal-content loading-modal-content">
                    <h3 id="modalCargandoTitle" class="modal-title">Espere por favor</h3>
                    <div class="loader-wrapper">
                        <div class="loader"></div>
                    </div>
                    <p id="modalCargandoMessage" class="modal-message">Cargando...</p>
                </div>
            </div>

        </div> 
    </div> 
   