

    const titulo = document.querySelector('.titulo-seccion-membresias');
    const letras = document.querySelectorAll('.titulo-seccion-membresias .letra');

    const animarTitulo = () => {
        // Reiniciar estado para la animación de entrada
        letras.forEach(letra => {
            letra.style.opacity = '0';
            letra.style.transform = 'translateY(100%)';
            letra.style.animation = 'none'; // Eliminar cualquier animación activa
        });
        // Asegurar que el contenedor del título esté visible
        titulo.style.opacity = '1';

        // Animación de entrada letra por letra
        letras.forEach((letra, index) => {
            setTimeout(() => {
                letra.style.animation = `letraEntra 0.6s ease-out forwards`;
            }, index * 50); // Retraso escalonado para cada letra
        });

        // Después de 5 segundos, iniciar la animación de salida
        setTimeout(() => {
            // Animación de salida letra por letra
            letras.forEach((letra, index) => {
                setTimeout(() => {
                    letra.style.animation = `letraSale 0.6s ease-in forwards`;
                }, index * 50); // Retraso escalonado para cada letra
            });

            // Una vez que todas las letras se hayan ido, reiniciar el ciclo
            // Calculamos el tiempo total que tarda la última letra en salir
            const duracionSalidaTotal = (letras.length - 1) * 50 + 600; // (último index * delay) + duración de animación
            setTimeout(() => {
                // Ocultar el contenedor del título mientras no hay letras
                titulo.style.opacity = '0';
                // Llamar a la función nuevamente para reiniciar el ciclo
                animarTitulo();
            }, duracionSalidaTotal);

        }, 5000); // 5 segundos después de que el título apareció completamente
    };

    // Iniciar la animación cuando la página carga
    animarTitulo();

