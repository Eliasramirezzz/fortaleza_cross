// ../js/header_responsive.js

// Define el HTML del menú móvil y el botón de hamburguesa
const mobileMenuHtml = `
    <button class="menu-toggle" aria-label="Abrir menú">
        <span class="hamburger"></span>
    </button>

    <div class="mobile-menu" id="mobileMenu">
        <nav class="nav-mobile">
            <ul class="nav-list-mobile">
                <li class="submenu-personalizado-mobile">
                    <a href="#">PERSONALIZADO</a>
                    <ul class="dropdown-personalizado-mobile">
                        <li><a href="../home/secciones.php?seccion=gap">Gap</a></li>
                        <li><a href="../home/secciones.php?seccion=funcional">Funcional</a></li>
                        <li><a href="../home/secciones.php?seccion=musculacion">Musculacion</a></li>
                        <li><a href="../home/secciones.php?seccion=levantamiento">Levantamiento</a></li>
                    </ul>
                </li>
                <li><a href="../home/secciones.php?seccion=horarios">HORARIOS</a></li>
                <li><a href="../home/secciones.php?seccion=membresias">MEMBRESIAS</a></li>
                <li><a href="../home/secciones.php?seccion=ubicacion">UBICACION</a></li>
                <li><a href="../home/secciones.php?seccion=eventos">EVENTOS</a></li>
                <li><a href="../home/secciones.php?seccion=PF">PREGUNTAS FRECUENTES</a></li>
            </ul>
        </nav>
        <div class="header-icons-mobile">
            <a href="../login/login.php" title="iniciar sesion" class="login-link-mobile">
                <i class="fas fa-user-circle icon login-icon-mobile"></i> 
                <span class="login-text-mobile">Iniciar Sesión</span>
            </a>
            </div>
    </div>
    <div class="menu-overlay" id="menuOverlay"></div>
`;

// Define el CSS para el menú móvil y el botón de hamburguesa
const mobileMenuCss = `
    /* Estilos para el botón de hamburguesa */
    .menu-toggle {
        display: none; /* Se mostrará con JS/Media Query */
        background: none;
        border: none;
        cursor: pointer;
        padding: 10px;
        z-index: 1002;
    }

    .hamburger {
        width: 30px;
        height: 3px;
        background-color: white;
        position: relative;
        transition: background-color 0.3s ease;
    }

    .hamburger::before,
    .hamburger::after {
        content: '';
        width: 30px;
        height: 3px;
        background-color: white;
        position: absolute;
        transition: transform 0.3s ease;
    }

    .hamburger::before {
        top: -10px;
    }

    .hamburger::after {
        top: 10px;
    }

    /* Animación de la hamburguesa a la 'X' */
    .menu-toggle.active .hamburger {
        background-color: transparent;
    }

    .menu-toggle.active .hamburger::before {
        transform: translateY(10px) rotate(45deg);
    }

    .menu-toggle.active .hamburger::after {
        transform: translateY(-10px) rotate(-45deg);
    }

    /* Estilos para el menú lateral (o fullscreen) */
    .mobile-menu {
        position: fixed;
        top: 0;
        right: -100%; /* Empieza fuera de la pantalla */
        width: 80%;
        max-width: 300px;
        height: 100%;
        background-color: #0a0a23;
        padding-top: 80px;
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.5);
        z-index: 999;
        transition: right 0.3s ease;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start; /* Alinea los elementos arriba */
    }

    .mobile-menu.active {
        right: 0;
    }

    .mobile-menu .nav-list-mobile { /* Usar la clase específica para móvil */
        list-style: none;
        padding: 0;
        margin: 20px 0;
        display: flex;
        flex-direction: column;
        width: 100%;
        text-align: center;
    }

    .mobile-menu .nav-list-mobile li {
        margin: 15px 0;
    }

    .mobile-menu .nav-list-mobile a {
        padding: 15px 0;
        font-size: 1.2rem;
        width: 100%;
        display: block; /* Asegura que ocupe todo el ancho para el click */
        color: white; /* Color base */
        text-decoration: none;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .mobile-menu .nav-list-mobile a:hover {
        background-color: #1a1a3b;
        color: #00BFFF;
    }

    /* Submenú en móvil */
    .mobile-menu .submenu-personalizado-mobile .dropdown-personalizado-mobile {
        list-style: none;
        padding: 0;
        margin: 0;
        position: static; /* No flotar en móvil */
        opacity: 1; /* Siempre visible cuando el padre está abierto */
        visibility: visible;
        transform: none;
        box-shadow: none;
        border-top: none;
        background-color: #1a1a3b; /* Un poco más claro para diferenciar */
        width: 100%;
        border-radius: 0;
        display: none; /* Oculto por defecto, se abrirá con JS */
    }

    .mobile-menu .dropdown-personalizado-mobile li a {
        padding: 10px 0;
        font-size: 1rem;
        color: #cccccc;
    }

    .mobile-menu .header-icons-mobile {
        display: flex;
        flex-direction: column; /* Apila los iconos verticalmente en el menú móvil */
        margin-top: 30px;
        gap: 20px;
        width: 100%; /* Asegura que ocupe el ancho para centrar los elementos */
    }

    .mobile-menu .header-icons-mobile a {
        margin: 0;
    }
    
    .mobile-menu .header-icons-mobile .login-link-mobile {
        display: flex;
        align-items: center;
        justify-content: center; /* Centra el icono y el texto dentro del enlace */
        text-decoration: none;
        color: white;
        font-weight: 600;
        transition: color 0.2s ease, transform 0.2s ease;
        margin: 0; /* Elimina cualquier margen horizontal heredado */
        padding: 10px 0; /* Añade un poco de padding vertical */
    }

    .mobile-menu .header-icons-mobile .login-link-mobile:hover {
        color: #00BFFF;
        transform: scale(1.03); /* Ligerísimo efecto de escala al pasar el mouse */
    }
    
    .mobile-menu .header-icons-mobile .login-icon-mobile {
         /* Usar font-size para el icono de Font Awesome */
        font-size: 2.5rem; /* Tamaño del icono más grande para el menú móvil (aprox. 40px) */
        margin-right: 12px;
        color: inherit; /* Hereda el color del padre (blanco) */
    }

    .mobile-menu .header-icons-mobile .login-text-mobile {
        display: inline-block;
        font-size: 1.4rem; 
        line-height: 1;
        text-align: center;
        color: inherit; /* Hereda el color del padre (blanco) */
    }


    /* Overlay para oscurecer el fondo */
    .menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 998;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .menu-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Media query para ocultar elementos de escritorio y mostrar de móvil */
    @media (max-width: 800px) { /* Breakpoint para móvil */
        .header .nav,
        .header .header-icons {
            display: none !important; /* Ocultar nav e iconos de escritorio */
        }
        .menu-toggle {
            display: block !important; /* Mostrar botón de hamburguesa */
        }
    }

    @media (min-width: 801px) { /* Breakpoint para escritorio */
        .header .nav,
        .header .header-icons {
            display: flex !important; /* Asegurar que se muestren en escritorio */
        }
        .menu-toggle,
        .mobile-menu,
        .menu-overlay {
            display: none !important; /* Ocultar elementos de móvil en escritorio */
        }
    }
`;

// Función para inicializar el header responsivo
function initializeHeaderResponsive() {
    const header = document.querySelector('.header');
    if (!header) {
        console.error('Header element not found. Cannot initialize responsive header.');
        return;
    }

    // Inyectar CSS si aún no está presente
    let styleTag = document.getElementById('mobileHeaderStyles');
    if (!styleTag) {
        styleTag = document.createElement('style');
        styleTag.id = 'mobileHeaderStyles';
        styleTag.textContent = mobileMenuCss;
        document.head.appendChild(styleTag);
    }

    // Inyectar HTML del menú móvil y botón de hamburguesa
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = mobileMenuHtml;

    // Añadir el botón de hamburguesa al header
    const menuToggle = tempDiv.querySelector('.menu-toggle');
    if (menuToggle) {
        header.appendChild(menuToggle);
    } else {
        console.warn('Menu toggle button not found in mobile menu HTML.');
    }

    // Añadir el menú móvil y el overlay al body (fuera del header para posicionamiento fijo)
    const mobileMenu = tempDiv.querySelector('#mobileMenu');
    const menuOverlay = tempDiv.querySelector('#menuOverlay');
    if (mobileMenu && menuOverlay) {
        document.body.appendChild(mobileMenu);
        document.body.appendChild(menuOverlay);
    } else {
        console.warn('Mobile menu or overlay not found in mobile menu HTML.');
    }

    // Obtener referencias a los elementos después de haber sido inyectados
    const currentMenuToggle = document.querySelector('.menu-toggle');
    const currentMobileMenu = document.getElementById('mobileMenu');
    const currentMenuOverlay = document.getElementById('menuOverlay');
    const desktopNav = document.querySelector('.header .nav');
    const desktopIcons = document.querySelector('.header .header-icons');
    const body = document.body;

    if (!currentMenuToggle || !currentMobileMenu || !currentMenuOverlay) {
        console.error('Failed to get references to mobile menu elements after injection.');
        return;
    }

    // Lógica para abrir/cerrar el menú
    currentMenuToggle.addEventListener('click', () => {
        currentMenuToggle.classList.toggle('active');
        currentMobileMenu.classList.toggle('active');
        currentMenuOverlay.classList.toggle('active');
        body.classList.toggle('no-scroll');
    });

    currentMenuOverlay.addEventListener('click', () => {
        currentMenuToggle.classList.remove('active');
        currentMobileMenu.classList.remove('active');
        currentMenuOverlay.classList.remove('active');
        body.classList.remove('no-scroll');
    });

    // Lógica para el submenú en móvil (PERSONALIZADO)
    const mobileSubmenuParent = currentMobileMenu.querySelector('.submenu-personalizado-mobile > a');
    if (mobileSubmenuParent) {
        mobileSubmenuParent.addEventListener('click', (e) => {
            // Si el href es '#' significa que es solo un toggle
            if (mobileSubmenuParent.getAttribute('href') === '#') {
                e.preventDefault();
            }
            const dropdown = mobileSubmenuParent.nextElementSibling;
            if (dropdown && dropdown.classList.contains('dropdown-personalizado-mobile')) {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            }
        });
    }

    // Cerrar menú móvil al redimensionar a desktop
    let mediaQuery = window.matchMedia('(min-width: 801px)');

    function handleMediaQueryChange(e) {
        if (e.matches) { // Si la pantalla es grande (escritorio)
            // Asegúrate de que los elementos de escritorio estén visibles
            if (desktopNav) desktopNav.style.display = 'flex';
            if (desktopIcons) desktopIcons.style.display = 'flex';

            // Oculta y limpia el menú móvil
            currentMenuToggle.classList.remove('active');
            currentMobileMenu.classList.remove('active');
            currentMenuOverlay.classList.remove('active');
            body.classList.remove('no-scroll');
            currentMenuToggle.style.display = 'none'; // Asegura que el botón de hamburguesa esté oculto
            currentMobileMenu.style.display = 'none'; // Asegura que el menú móvil esté oculto
            currentMenuOverlay.style.display = 'none'; // Asegura que el overlay esté oculto

        } else { // Si la pantalla es pequeña (móvil)
            // Asegúrate de que los elementos de escritorio estén ocultos
            if (desktopNav) desktopNav.style.display = 'none';
            if (desktopIcons) desktopIcons.style.display = 'none';

            // Asegura que los elementos de móvil estén visibles para ser manejados por clases
            currentMenuToggle.style.display = 'block';
            currentMobileMenu.style.display = 'flex';
            currentMenuOverlay.style.display = 'block';
        }
    }

    // Ejecuta la función al cargar y cuando la media query cambie
    handleMediaQueryChange(mediaQuery);
    mediaQuery.addListener(handleMediaQueryChange);

    // Opcional: Cerrar menú móvil si se hace clic en un enlace (excepto los padres del submenú)
    const mobileLinks = currentMobileMenu.querySelectorAll('.nav-list-mobile a');
    mobileLinks.forEach(link => {
        if (!link.closest('.submenu-personalizado-mobile')) { // No cerrar si es el padre del submenú
            link.addEventListener('click', () => {
                currentMenuToggle.classList.remove('active');
                currentMobileMenu.classList.remove('active');
                currentMenuOverlay.classList.remove('active');
                body.classList.remove('no-scroll');
            });
        }
    });
}

// Llama a la función de inicialización cuando el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', initializeHeaderResponsive);