
const contenedor = document.getElementById('contenedor-pf');

const preguntas = [
    {
        pregunta: "¿Cómo puedo registrarme en el gimnasio?",
        respuesta: "Puedes registrarte en línea desde nuestro sitio web o directamente en recepción."
    },
    {
        pregunta: "¿Qué medios de pago aceptan?",
        respuesta: "Aceptamos pagos con transferecias y pagos en efectivo en recepción"
    },
    {
        pregunta: "¿Qué incluye la suscripción mensual?",
        respuesta: "Acceso a todas las instalaciones, clases grupales y asesoramiento de los entrenadores."
    },
    {
        pregunta: "¿Puedo congelar mi membresía temporalmente?",
        respuesta: "No, no se puedes."
    },
    {
        pregunta: "¿Qué horarios maneja el gimnasio?",
        respuesta: "Abrimos de lunes a viernes de 14:00 a 21:00."
    },
    {
        pregunta: "¿Hay promociones por traer a un amigo?",
        respuesta: "Las promociónes por lo general se aplican en el mes del amigo."
    },
    {
        pregunta: "¿Puedo pagar en efectivo?",
        respuesta: "Sí, también aceptamos pagos en efectivo en recepción."
    },
    {
        pregunta: "¿Ofrecen planes para grupos o familias?",
        respuesta: "Por el momento no ofrecemos planes o desuentos familiares"
    },
    {
        pregunta: "¿Es necesario registrarse en otra clase en caso de no poder asistir a la que me registre?",
        respuesta: "No, no es necesario, solo se debe avisar el cambio de horario al entrenador."
    },
    {
        pregunta: "¿Qué pasa si pierdo mi tarjeta de acceso?",
        respuesta: "Debes informar en recepción para emitir una nueva tarjeta ."
    }
];

// Generador de acordeones dinámicos
preguntas.forEach((item, index) => {
    const acordeon = document.createElement('div');
    acordeon.className = 'acordeon-item';

    acordeon.innerHTML = `
        <div class="acordeon-header">
            <span class="acordeon-numero">${String(index + 1).padStart(2, '0')}</span>
            <h3>${item.pregunta}</h3>
            <span class="acordeon-toggle"></span>
        </div>
        <div class="acordeon-content">
            <p>${item.respuesta}</p>
        </div>
    `;

    contenedor.appendChild(acordeon);
});

// Acordeón funcionalidad
const activarAcordeon = () => {
    const headers = document.querySelectorAll('.acordeon-header');

    headers.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.closest('.acordeon-item');
            const content = item.querySelector('.acordeon-content');

            item.classList.toggle('active');

            if (item.classList.contains('active')) {
                content.style.maxHeight = content.scrollHeight + 'px';
            } else {
                content.style.maxHeight = '0';
            }
        });
    });
};

activarAcordeon();

// Envío de pregunta personalizada
const preguntaPersonalizada = document.getElementById('enviar-pregunta');
const mensajeUser = document.getElementById('pregunta-personal');
const NUMERO_ADMIN_WHATSAPP = '543644222296';

preguntaPersonalizada.addEventListener('click', async () => {


    const mensajeCodificado = encodeURIComponent(mensajeUser.value.trim());

    const urlWhatsApp = `https://api.whatsapp.com/send?phone=${NUMERO_ADMIN_WHATSAPP}&text=${mensajeCodificado}`;
    // Abrir y Cerrar
    window.open(urlWhatsApp, '_blank');
})




