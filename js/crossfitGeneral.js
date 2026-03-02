
// Seleccionamos todos los botones de cada tarjeta
const botonesWOD = document.querySelectorAll('.btn-info-wod');

botonesWOD.forEach((boton) => {
    boton.addEventListener('click', (e) => {
        e.preventDefault();

        // Tomamos la tarjeta correspondiente
        const tarjeta = boton.closest('.tarjeta-wod');
        const extraDetalle = tarjeta.querySelector('.extra-detalle');
        const contTarget = tarjeta.querySelector('.extra-wod');

        // Si está vacío => mostrar
        if (extraDetalle.innerHTML === '' && contTarget.innerHTML === '') {
            switch (tarjeta.id) {
                case 'wod-lunes':
                    extraDetalle.innerHTML = `
                        <p class="etiqueta-detalle">WOD (Trabajo del día) For time: 5 rondas</p>
                        <ul class="lista-ejercicios">
                            <li>Ejercicio 1: 50 saltos simples (soga)</li>
                            <li>Ejercicio 2: 15 hang squat clean (H: 45kg / M: 35kg)</li>
                            <li>Ejercicio 3: 10 push press (H: 45kg / M: 35kg)</li>
                        </ul>`;
                    contTarget.innerHTML = `
                        <p class="notas-wod">Notas del Coach: Priorizar técnica en hang squat clean y push press.</p>
                        <p class="duracion-wod">Duración: 60 minutos </p>
                        <p class="equipo-wod">Equipo: discos, kettlebell, barras, soga</p>`;
                    break;

                case 'wod-martes':
                    extraDetalle.innerHTML = `<div class="detalles-wod">
                            <p class="etiqueta-detalle">WOD EMON 25 minutos:(trabajo por tiempo de 1 minuto )</p>
                            <ul class="lista-ejercicios">
                                <li>10 front squat</li>
                                <li>10 push jerks</li>
                                <li>10 hang power clean</li>
                                <li>7 burpe bar(saltando la barra de costado)</li>
                            </ul>
                        </div>`;

                    contTarget.innerHTML = `<p class="notas-wod">Notas del Coach: Profundidad y repticiones controladas en los back squat </p>
                                <p class="duracion-wod">Duración Estimada de la clase 60 minutos</p>
                                <p class="equipo-wod">Equipo Necesario: barra con peso y discos</p>`;
                    break;

                case 'wod-miercoles':
                    extraDetalle.innerHTML = `<div class="detalles-wod">
                            <p class="etiqueta-detalle">Ejercicios:</p>
                            <ul class="lista-ejercicios">
                                <li>Ejercicio 1:hollow rock </li>
                                <li>Ejercicio 2:superman rock</li>
                            </ul>
                            <p class="tipo-wod"> WOD for time:21.15.9</p>
                            <div class="detalles-wod">
                            <p class="etiqueta-detalle">Ejercicios:</p>
                            <ul class="lista-ejercicios">
                                <li>Ejercicio 1:thruster </li>
                                <li>Ejercicio 2: wall ball(medbal: mujeres 6 kg, hombres:9 kg)</li>
                                <li>Ejercicio 3: burpe bar</li>
                                <li>Ejercicio 3: 200 mts run </li>
                            </ul>`;
                    contTarget.innerHTML = `
                            <p class="notas-wod">Notas del Coach: ...</p>
                            <p class="duracion-wod">Duración Estimada: 60 minutos</p>
                            <p class="equipo-wod">Equipo Necesario: barra con peso y medballs</p>`;
                    break;

                case 'wod-jueves':
                    extraDetalle.innerHTML = `<p class="tipo-wod"> WOD 1 :Complex 3 rondas (barra con peso: mujeres:40 kg,hombres 60kg)</p>
                            <div class="detalles-wod">
                                <p class="etiqueta-detalle">Ejercicios:</p>
                                <ul class="lista-ejercicios">
                                    <li>Ejercicio 1: 7 hang squat clean</li>
                                    <li>Ejercicio 2:7 front sqaut</li>
                                    <li>Ejercicio 3:7 push jerks</li>
                                </ul>
                            <p class="tipo-wod"> WOD 2 : for time 3 rondas (tiempo captura 20 minutos) </p>
                            <div class="detalles-wod">
                                <p class="etiqueta-detalle">Ejercicios:</p>
                                <ul class="lista-ejercicios">
                                    <li>Ejercicio 1: 15 cluster</li>
                                    <li>Ejercicio 2:12 front lungues</li>
                                    <li>Ejercicio 3: 9 burpes facing bar(saltando de frente a la barra)</li>
                                </ul>`;
                    contTarget.innerHTML = `<p class="notas-wod">Notas del Coach: ...</p>
                            <p class="duracion-wod">Duración Estimada: 60 minutos</p>
                            <p class="equipo-wod">Equipo Necesario: Barra con peso y discos</p>`;
                    break;

                case 'wod-viernes':
                    extraDetalle.innerHTML = `<p class="tipo-wod">WOD CHIPPERS 35 minutos(a         completar ) </p>
                        <div class="detalles-wod">
                            <p class="etiqueta-detalle">Ejercicios:</p>
                            <ul class="lista-ejercicios">
                                <li>Ejercicio 1:2000 mts run</li>
                                <li>Ejercicio 2: 30 burpe box jump over</li>
                                <li>Ejercicio 3: 30 wall ball</li>
                                <li>Ejercicio 4:30 power clean</li>
                                <li>Ejercicio 5: 30 burpe box jump over</li>
                                <li>Ejercicio 6: 30 wall ball </li>
                                <li>Ejercicio 7:2000 mts run</li>
                            </ul>
                        </div>`;
                    contTarget.innerHTML = `<p class="notas-wod">Notas del Coach: ...</p>
                    <p class="duracion-wod">Duración Estimada: 60 minutos</p>
                    <p class="equipo-wod">Equipo Necesario: barra con peso, medballs y cajones</p>`;
                    break;

            }

            boton.textContent = "Ocultar Detalles";

        } else {
            // Si ya estaba abierto => limpiar
            extraDetalle.innerHTML = '';
            contTarget.innerHTML = '';
            boton.textContent = "Ver Detalles Completos";
        }
    });
});
