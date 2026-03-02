// Variables
const carousel = document.getElementById('carousel-gifs');
const indicatorsContainer = document.getElementById('indicators-gifs');
const images = document.querySelectorAll('.carousel-gifs img');
const totalImages = images.length;

let currentIndex = 0;
// ======================= LÓGICA RESPONSIVA DE IMÁGENES ======================
// Define el punto de quiebre (breakpoint) para móvil (debe coincidir con tu CSS @media 768px)
const MOBILE_BREAKPOINT = 768;
// Define el sufijo de los archivos de imagen para móvil
const MOBILE_SUFFIX = '-mobile';
// Define el path base de las imágenes (asumimos que es "../img/")
const BASE_PATH = '../img/carrusel/';

// Función que actualiza la fuente (src) de las imágenes basado en el ancho de la ventana.
function updateImageSources() {
    const isMobileView = window.innerWidth <= MOBILE_BREAKPOINT;

    images.forEach((img, index) => {
        // Obtenemos el nombre base (ej: 'carr1') a partir del atributo alt o un data-attribute
        // Para simplificar, asumiremos que el alt es el nombre base
        const baseName = img.dataset.name;
        // const baseName = img.alt.toLowerCase().replace('imagen ', 'carr'); // Ej: 'IMAGEN 1' -> 'carr1'

        let newSrc = BASE_PATH + baseName;

        if (isMobileView) {
            newSrc += MOBILE_SUFFIX + '.png'; // ej: '../img/carrusel/carr1-mobile.png'
        } else {
            newSrc += '.png'; // ej: '../img/carrusel/carr1.png'
        }

        // Solo actualizamos si la fuente es diferente
        if (!img.src.endsWith(newSrc)) { // <-- ¡NOT / NEGACIÓN!
            img.src = newSrc;
        }
    });
}

// Crear indicadores dinámicos
for (let i = 0; i < totalImages; i++) {
    const indicator = document.createElement('div');
    indicator.classList.add('indicator-gifs');
    if (i === 0) indicator.classList.add('active');
    indicator.dataset.index = i;
    indicatorsContainer.appendChild(indicator);
}

const indicators = document.querySelectorAll('.indicator-gifs');

// Función para actualizar el carrusel
function updateCarousel(index) {
    const translateX = -index * 100; // Mover el carrusel horizontalmente
    carousel.style.transform = `translateX(${translateX}%)`;

    // Actualizar indicadores activos
    indicators.forEach((indicator, i) => {
        indicator.classList.toggle('active', i === index);
    });
}

// Cambio automático de imágenes
function startCarousel() {
    setInterval(() => {
        currentIndex = (currentIndex + 1) % totalImages; // Siguiente imagen (circular)
        updateCarousel(currentIndex);
    }, 5000); // Cambiar cada 5 segundos
}

// Navegación manual (opcional)
indicators.forEach(indicator => {
    indicator.addEventListener('click', () => {
        currentIndex = Number(indicator.dataset.index);
        updateCarousel(currentIndex);
    });
});


// 1. Carga las imágenes correctas al inicio
updateImageSources();
// 2. Vuelve a cargar las imágenes si el usuario redimensiona la ventana
let resizeTimeout;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(updateImageSources, 200); // Debounce para evitar sobrecargar
});
// 3. Iniciar el carrusel
startCarousel();