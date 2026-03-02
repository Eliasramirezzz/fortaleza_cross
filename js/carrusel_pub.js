const carruselContainer = document.querySelector('.carousel');
const carruselItems = document.querySelectorAll('.carousel-item');
const btnPrev = document.querySelector('.carousel-btn.prev');
const btnNext = document.querySelector('.carousel-btn.next');

let carruselIndex = 0;
const totalCarruselItems = carruselItems.length;

function updateCarrusel() {
  const translateX = -carruselIndex * 100;
  carruselContainer.style.transform = `translateX(${translateX}%)`;
}

btnPrev.addEventListener('click', () => {
  carruselIndex = (carruselIndex - 1 + totalCarruselItems) % totalCarruselItems;
  updateCarrusel();
});

btnNext.addEventListener('click', () => {
  carruselIndex = (carruselIndex + 1) % totalCarruselItems;
  updateCarrusel();
});

updateCarrusel();
