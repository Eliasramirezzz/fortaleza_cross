<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/icono.ico" type="image/x-icon">
    <title>Recuperación de Contraseña</title>
</head>
<body style="font-family: Arial, sans-serif;background-color: #ffffff ; margin: 0; padding: 0;">

    <div style="width: 100%; max-width: 600px; margin: 20px auto;background: linear-gradient(135deg, #272727 0%, rgb(1, 1, 31) 50%, #272727 100%); padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        
        <div style="text-align: center; padding-bottom: 20px; border-bottom: 1px solid #eeeeee;">
            <img src="../img/icono.ico" alt="Fortaleza Cross Logo" style="max-width: 150px;">
        </div>

        <div style="padding: 20px 0;">
            <h2 style="color: #f8deccff; text-align: center;">Solicitud de Recuperación de Contraseña</h2>
            <p style="color: #fae8e8ff; line-height: 1.6;">Hola,</p>
            <p style="color: #fae8e8ff; line-height: 1.6;">Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Si no fuiste tú quien solicitó esto, puedes ignorar este correo.</p>
            <p style="color: #e0eff4ff; line-height: 1.6;">Para continuar con el proceso, haz clic en el siguiente botón:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{URL_RECUPERACION}}" style="background-color: #007bff; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Restablecer Contraseña</a>
            </div>
            
            <p style="color: #555555; line-height: 1.6;">Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:</p>
            <p style="word-break: break-all; font-size: 0.9em; color: #888888;">{{URL_RECUPERACION}}</p>
        </div>

        <div style="text-align: center; padding-top: 20px; border-top: 1px solid #eeeeee; font-size: 0.8em; color: #999999;">
            <p>&copy; 2025 Fortaleza Cross. Todos los derechos reservados.</p>
        </div>

    </div>

</body>
</html>