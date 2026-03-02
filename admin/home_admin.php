
<?php
//Seccion valida para el cliente hasta que cierre el navegador
session_set_cookie_params([
    'lifetime' => 0,        // 0 = se borra cuando se cierra el navegador
    'path' => '/',
    'domain' => '',         // dejalo vacío para localhost
    'secure' => false,      // true si usás HTTPS
    'httponly' => true,     // seguridad extra
    'samesite' => 'Lax'     // evita CSRF
]);
//Necesario para que mientrs tenga abierto el navegador pueda aceder al sistema.
session_start();
$_SESSION['acceso_permitido'] = true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Fortaleza Cross Saenz Peña Chaco">
    <meta name="description" content="Pagina del administrador de Fortaleza Cross">
    <link rel="shortcut icon" href="../img/fortaleza.png" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Enlaces a Css-->
    <link rel="stylesheet" href="../bin/mensaje_modal.css"> 
    <link rel="stylesheet" href="home_admin.css">
    <link rel="stylesheet" href="../css/home_admin/tabla_pagos.css">
    <link rel="stylesheet" href="../css/home_admin/tablaSocios.css">
    <link rel="stylesheet" href="../css/home_admin/seccionRegistrarPlanCliente.css">
    <link rel="stylesheet" href="../css/home_admin/modalConfirmar.css">
    <link rel="stylesheet" href="../css/home_admin/modaleditarCliente.css">
    <link rel="stylesheet" href="../css/home_admin/modalRegistrarPago.css">
    <link rel="stylesheet" href="../css/home_admin/seccionRegistrarCliente.css">
    <title>Fortaleza Cross - administrador/Entrenador </title>
</head>

<body>
    <header class="header">
        <div class="logo-container" id="logo-container">
            <img src="../img/fortaleza.png" alt="Logo Fortaleza" class="logo" id="logo">
        </div>

        <button class="menu-toggle" id="menuToggle">&#9776;</button>

        <nav class="nav" id="navMenu">
            <ul class="nav-list">
                <li class="nav-item"><a href="#pagos-section" class="nav-link">Pagos</a></li>
                <li class="nav-item"><a href="#clientes-section" class="nav-link">Clientes</a></li>
                <li class="nav-item"><a href="#reg-cli-section" class="nav-link">Registrar Cliente</a></li>
                <li class="nav-item"><a href="#section-registro-cliente" class="nav-link">Registrar Plan</a></li>
                <li class="nav-item"><a href="configuracion.php" class="nav-link">Configuracion </a></li>
            </ul>
        </nav>

        <div class="header-icons">
            <div class="user-menu">
                <a href="#" class="login-link" id="userMenuToggle">
                    <span id="userIconContainer" class="user-icon-container">
                        <i class="fas fa-user-circle icon login-icon" id="userIcon"></i>
                    </span>
                    <span class="login-text" id="NameAdmin"></span>
                </a>
                <ul class="dropdown-user" id="dropdownUser">
                    <li><a href="../login/login.php">Cambiar Usuario</a></li>
                    <li><a href="../home/index.php">Volver al Home Público</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main class="admin-main-content">
        <div class="main-header">
            <h1 id="TitlePanel">Panel de administrador</h1>
        </div>
    
        <!-- seccion Gestion Pagos -->
        <section class="section-pagos" id="pagos-section">
            <h2 class="section-pagos__title">Gestión de Pagos Pendientes y Vencidos 💰</h2>
            
            <div class="section-pagos__controls">
                <div class="filter-container">
                    <label for="filtroPago" class="filter-label">Filtrar por Estado:</label>
                    <select id="filtroPago" class="filter-select">
                        <option value="todos">Todos</option>
                        <option value="pagados">Pagados</option>
                        <option value="pendientes">Pendientes</option>
                        <option value="vencidos">Vencidos</option>
                    </select>
                </div>
                <div>
                    <label for="buscarPago" class="filter-label">Buscar:</label>
                    <input type="text" id="buscarPago" class="filter-input-search" placeholder="Buscar por Nombre o DNI">
                </div>
                <div>
                    <img src="../img/iconoExportar.png" alt="Icono para Exportar Datos" class="export-icon" id="exportPagosData">
                </div>
            </div>

            <div class="payment-table-container">
                <table class="payment-table" id="paymentTable">
                    <thead>
                        <tr>
                            <th class="payment-table__header payment-table__header--nro">NRO</th>
                            <th class="payment-table__header payment-table__header--nombre">Nombre y Apellido</th>
                            <th class="payment-table__header payment-table__header--dni">DNI</th>
                            <th class="payment-table__header payment-table__header--plan">Nombre Plan</th>
                            <th class="payment-table__header payment-table__header--precio">Precio</th>
                            <th class="payment-table__header payment-table__header--vto">Fecha Vto</th>
                            <th class="payment-table__header payment-table__header--pago">Fecha Pago</th> 
                            <th class="payment-table__header payment-table__header--estado">Estado</th>
                            <th class="payment-table__header payment-table__header--accion">Acción</th>
                            <th class="payment-table__header payment-table__header--eliminar">
                                Eliminar
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbodyPagosClientes" class="payment-table__body"></tbody> 
                </table>
            </div>
            
            <div class="pagination-controls">
                <button id="verMenosBtn" class="pagination-btn pagination-btn--hide">Ver menos</button>
                <button id="verMasBtn" class="pagination-btn">Ver más</button>
            </div>
            <span id="MsjtotalPagos"></span>
        </section>

        <!-- seccion Gestion Cliente -->
        <section class="section-clientes" id="clientes-section">
            <h2>Listado y Gestión de Clientes 👥</h2>
            
            <div class="client-controls">
                <label for="searchClient" class="client-search-label">Buscar: </label>
                <input type="text" id="searchClient" placeholder="Buscar cliente por nombre - DNI / Email">
                
                <div class="client-filter-controls">
                    <div class="filter-group">
                        <label for="filterEstado">Filtrar por Estado:</label>
                        <select id="filterEstado" name="filterEstado">
                            <option value="todos" selected>Todos</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="fecha_alta_a_z">Fecha de Alta: Ultimos</option>
                            <option value="fecha_alta_z_a">Fecha de Alta: Primeros</option>
                        </select>
                    </div>
                </div>
                <div>
                    <img src="../img/iconoExportar.png" alt="Icono para Exportar Datos" class="export-icon" id="exportClientData">
                </div>
            </div>

            <div class="client-table-container">
                <table id="clientTable" class="client-data-table">
                    <thead>
                        <tr>
                            <th data-sort="nombre">Nombre ⬆️</th>
                            <th data-sort="dni">DNI</th>
                            <th data-sort="telefono">Teléfono</th>
                            <th data-sort="plan">Email</th>
                            <th data-sort="inicio">Fecha Inicio</th>
                            <th data-sort="estado">Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="clientTableBody"></tbody>
                </table>
            </div>

            <div class="pagination-controls client-pagination-controls">
                <button id="prevPageBtn">Anterior</button>
                <button id="nextPageBtn">Siguiente</button>
            </div>
            <span id="MsjtotalClients"></span>
        </section>

        <!-- Modal Editar Cliente -->
        <div id="modalEditarCliente" class="modal-overlay modal-overlay--oculto">
            <div class="modal-content">
                <h2>✏️ Editar Cliente</h2>

                <form id="formEditarCliente">
                    <input type="hidden" id="editId" name="id_cliente">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="editNombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" id="editApellido" name="apellido" required>
                    </div>
                    <div class="form-group">
                        <label>DNI</label>
                        <input type="text" id="editDni" name="dni" required>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" id="editTelefono" name="telefono" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="editEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha Alta</label>
                        <input type="text" id="editFechaAlta" name="fecha_alta" disabled>
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <select id="editEstado" name="estado">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="modal-actions">
                        <button type="button" id="btnCancelarEdicion">Cancelar</button>
                        <button type="submit" id="btnConfirmarEdicion">Confirmar Cambios</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Seccion Registrar Cliente -->
        <section class="reg-cli-section" id="reg-cli-section">
            <div class="cont-reg-cliente-title">
                <h2 class="reg-cli-title">Registrar Nuevo Cliente 📝</h2>
            </div>
            
            <form id="formRegistroCliente" class="reg-cli-form" autocomplete="off">
                <div class="cont-sect-form">
                    <div class="reg-cli-group datos-personales">
                        <h3 class="reg-cli-subtitle">Datos del Cliente 👤</h3>

                        <div class="reg-cli-input-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" required placeholder="Ingrese el nombre">
                        </div>

                        <div class="reg-cli-input-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" id="apellido" name="apellido" required placeholder="Ingrese el apellido">
                        </div>

                        <div class="reg-cli-input-group">
                            <label for="dni">DNI:</label>
                            <input type="number" id="dni" name="dni" required placeholder="Ingrese el DNI">
                        </div>

                        <div class="reg-cli-input-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="tel" id="telefono" name="telefono" required placeholder="Ej: 3644-000000">
                        </div>

                        <div class="reg-cli-input-group">
                            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                        </div>

                        <div class="reg-cli-input-group">
                            <label for="genero">Género:</label>
                            <select id="genero" name="genero" required>
                                <option value="">Seleccione...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="reg-cli-group datos-cuenta">
                        <h3 class="reg-cli-subtitle">Datos de la Cuenta 🔐</h3>

                        <div class="reg-cli-input-group">
                            <label for="usuario">Usuario:</label>
                            <input type="text" id="usuario" name="usuario" required placeholder="Nombre de usuario">
                        </div>

                        <div class="reg-cli-input-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required placeholder="Correo electrónico">
                        </div>

                        <div class="reg-cli-input-group">
                            <label for="password">Contraseña:</label>
                            <input type="password" id="password" name="password" required placeholder="Ingrese la contraseña">
                        </div>

                        <div class="reg-cli-input-group">
                            <label for="confirpassword">Confirmar Contraseña:</label>
                            <input type="password" id="confirpassword" name="confirpassword" required placeholder="Confirmar lacontraseña">
                        </div>

                        <div class="reg-cli-input-group">
                            <label for="rol">Rol:</label>
                            <select id="rol" name="rol">
                                <option value="cliente" selected>Cliente</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="reg-cli-actions">
                    <button type="submit" class="btn-registrar">Registrar Cliente</button>
                    <button type="reset" class="btn-limpiar">Limpiar</button>
                </div>
            </form>

            <div id="mensajeRegistroCliente" class="mensaje-resultado"></div>
        </section>

        <!-- Seccion Registrar Cliente a plan-->
        <section class="section-registro-cliente client-reg-section" id="section-registro-cliente">
            <h2 class="client-reg-title">Asignación de Plan al cliente 📝</h2>

            <div class="registration-form-container client-reg-form-container">
                <form id="newClientForm" class="client-reg-form-unified">
                    
                    <div class="client-reg-group-email">
                        <div class="client-reg-input-group">
                            <label for="clientEmail" class="client-reg-label">Email del Cliente (Obligatorio):</label>
                            <input type="email" id="clientEmail" name="clientEmail" class="client-reg-input" placeholder="ejemplo@correo.com" required>
                            <div id="emailStatusMessage" class="status-message"></div>
                        </div>
                    </div>

                    <div class="client-reg-group-plan">
                        <div class="client-reg-input-group">
                            <label for="clientPlan" class="client-reg-label">Plan / Membresía:</label>
                            <select id="clientPlan" name="clientPlan" class="client-reg-select" required>
                                <option value="" disabled selected>Seleccione un Plan</option>
                            </select>
                            <div id="planStatusMessage" class="status-message"></div>
                        </div>

                        <div class="client-reg-input-group">
                            <label for="paymentStatus" class="client-reg-label">Estado de Pago Inicial:</label>
                            <select id="paymentStatus" name="paymentStatus" class="client-reg-select" required>
                                <option value="">Seleccione el estado</option>
                                <option value="pendiente">Pendiente (Paga después)</option>
                                <option value="pagado">Pagado</option>
                            </select>
                            <div id="paymentStatusMessage" class="status-message"></div>
                        </div>
                        <div class="client-reg-input-group">
                            <label for="clientStartDate" class="client-reg-label">Fecha de Inicio del Plan:</label>
                            <input type="date" id="clientStartDate" name="clientStartDate" readonly>
                            <div id="dateStatusMessage" class="status-message"></div>
                        </div>

                    </div>
                    
                    <button type="submit" id="registerClientBtn" class="client-reg-submit-btn">
                        Registrar Cliente al Plan
                    </button>
                </form>
            </div>
            <p class="client-reg-description"><strong>Descripción:</strong> Ingrese el Email para verificar la existencia del cliente y asigne un plan con la fecha de inicio y el estado de pago.</p>
        </section>

        <!-- Este modal de confirmación se muestra cuando se registra un socio al plan-->
        <div id="confirmRegisterOverlay" class="cr-overlay" role="dialog" aria-modal="true" aria-hidden="true">
            <div class="cr-modal" tabindex="-1">
                <div class="cr-header">
                    <div class="cr-icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="white" fill-opacity="0.15"/>
                            <path d="M11 17h2v2h-2zM12 7a3 3 0 00-3 3h2a1 1 0 112 0c0 1-1 1.5-1.8 2.1C10.6 13 10 13.7 10 15h2c0-.5.2-1 .6-1.4.7-.5 1.8-1.2 1.8-2.6A3 3 0 0012 7z" fill="white"/>
                        </svg>
                    </div>
                    <h3 id="cr-title" class="cr-title"></h3>
                </div>
                <div id="cr-msg" class="cr-message"></div>
                <div class="cr-actions">
                    <button type="button" class="cr-btn cr-no" id="cr-noBtn">No</button>
                    <button type="button" class="cr-btn cr-yes" id="cr-yesBtn">Sí</button>
                </div>
            </div>
        </div>

    </main>

    <footer>
        <div class="footer-contenido">
            <div class="footer-columna footer-marca">
                <a href="#TitlePanel" class="footer-logo-inferior">
                    <img src="../img/fortaleza.png" alt="Logo Fortaleza Cross"> </a>
                <p>Tu viaje hacia una vida más fuerte y saludable.</p>
                <div class="redes-sociales-footer">
                    <a href="https://www.facebook.com/fortalezacross?rdid=l8JKr2LQ3xDXak9E#" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/fortaleza_box/?hl=es" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-columna footer-contacto">
                <h3>Contáctanos</h3>
                <p><i class="fas fa-map-marker-alt"></i> Dirección:  Guemes Calle 8 entre 1 y 3 del centro.</p>
                <p><i class="fas fa-phone-alt"></i> Teléfono: +54 36-44  2222-96</p>
                <p><i class="fas fa-envelope"></i> Email: fortalezacross@gmail.com</p>
            </div>

            <div class="footer-columna footer-enlaces-rapidos">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="../data/politica_privacidad.html" target="_blank">Política de Privacidad</a></li>
                    <li><a href="../data/terminos_condiciones.html" target="_blank">Términos y Condiciones</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-copyright">
            <p>&copy; 2025 Fortaleza Cross. Todos los derechos reservados.</p>
            <span class="desarrollador"> <!--Desarrollado por
                <a href="https://github.com/Eliasramirezzz" target="_blank">Ramirez Elias</a>  -->
                <img src="../img/LogoERH.png" alt="Mi marca" width="100px">
            </span>
        </div>
    </footer>

    <script src="../bin/modal_controller.js"></script>
    <script src="../admin/registrarCliente.js"></script>
    <script src="../admin/exportPDF.js"></script>
    <script src="../admin/home_admin.js" defer></script>
    </body>

    <!-- Este modal es para ingresar el metodo de pago del paogo que se desea registrar.-->
    <div id="modalPagoOverlay" class="modal-overlay modal-overlay--oculto" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalPagoTitle">
        <div class="modal-pago modal-pago--content">
            <h2 id="modalPagoTitle" class="modal-pago__title">Registrar Pago</h2>
            
            <div class="modal-pago__info">
                <p><strong>Cliente:</strong> <span id="pagoNombreCliente"></span></p>
                <p><strong>Plan:</strong> <span id="pagoNombrePlan"></span></p>
                <p><strong>Monto a pagar:</strong> <span id="pagoMonto" class="modal-pago__monto"></span></p>
            </div>

            <form id="formRegistrarPago" class="modal-pago__form">
                <input type="hidden" id="pagoIdHidden" name="id_pago">
                
                <div class="modal-pago__field">
                    <label for="metodoPagoSelect" class="modal-pago__label">Método de Pago:</label>
                    <select id="metodoPagoSelect" name="metodo_pago" class="modal-pago__select" required>
                        <option value="" disabled selected>Seleccione el método</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                </div>
                
                <div class="modal-pago__actions">
                    <button type="button" id="btnCerrarPagoModal" class="modal-pago__btn modal-pago__btn--cancelar">Cancelar</button>
                    <button type="submit" id="btnConfirmarPago" class="modal-pago__btn modal-pago__btn--confirmar">Confirmar Pago</button>
                </div>
            </form>
        </div>
    </div>
    
</html>