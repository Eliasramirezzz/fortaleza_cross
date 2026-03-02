# fortaleza_cross

# 🏋️‍♂️ Sistema de Gestión para Gimnasio – Fortaleza Cross

Sistema web completo desarrollado para la administración de un gimnasio.

Permite gestionar:

* Socios

* Pagos

* Planes

* Registro de usuarios

* Recuperación de contraseña

* Envío de emails automáticos

* Integración con mapa de ubicación

El sistema fue diseñado como solución real lista para implementación comercial.


# 🚀 Tecnologías Utilizadas

Tecnología	Uso

* HTML5	      Estructura

* CSS3	      Estilos

* JavaScript	Interactividad

* PHP	        Backend

* MySQL	      Base de datos

* PHPMailer	  Envío de correos

* API de Mapa	Ubicación del gimnasio


# ⚙️ Requisitos del Sistema

Antes de ejecutar el proyecto, asegurarse de contar con:

* Servidor local (XAMPP, WAMP, Laragon, etc.)

* PHP 7 o superior

* MySQL

* phpMyAdmin

* Conexión a internet (para APIs externas)


# 🗄️ Instalación de la Base de Datos

Crear una base de datos en phpMyAdmin

Importar el archivo:

* fortaleza_cross.sql

Este archivo se encuentra en la raíz del proyecto.

🔌 Configuración de Conexión


Ir al archivo:

/conexion/conexion.php

Completar con tus datos locales:

*  $host = "localhost";

*  $user = "TU_USUARIO";

*  $pass = "TU_PASSWORD";

*  $db   = "TU_BASE_DE_DATOS";


# ✉️ Configuración de Envío de Emails

El sistema utiliza PHPMailer para:

* Registro de usuarios

* Recuperación de contraseña

* Notificaciones

Buscar los archivos donde se configure el envío de correo y completar:

* $mail->Host = 'smtp.tuservidor.com';

* $mail->Username = 'tu_email';

* $mail->Password = 'tu_password';

* $mail->Port = 587;

⚠️ Importante: Usar credenciales propias.

