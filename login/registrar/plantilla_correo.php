<?php
// Carga el archivo de configuración del proyecto
$projectFolder = explode("/", $_SERVER['PHP_SELF'])[1]; // Ej: "FORTALEZA_CROSS"
require_once($_SERVER['DOCUMENT_ROOT'] . "/$projectFolder/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Confirma tu Dirección de Correo - Fortaleza Cross</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style type="text/css">
        /* Client-specific Styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }

        /* Resets */
        body { margin: 0; padding: 0; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* Responsive Styles */
        @media screen and (max-width: 600px) {
            .full-width-image img { width: 100% !important; max-width: 100% !important; height: auto !important; }
            .container-padding { padding-left: 15px !important; padding-right: 15px !important; }
            .content-padding { padding-top: 20px !important; padding-bottom: 20px !important; }
            .heading-font-size { font-size: 28px !important; line-height: 34px !important; }
            .text-font-size { font-size: 15px !important; line-height: 24px !important; }
            .button-cell { padding-left: 15px !important; padding-right: 15px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #0d1a26; font-family: 'Poppins', sans-serif;">
    <center>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; background-color: #0d1a26;">
            <tr>
                <td align="center" style="padding: 40px 0;">
                    <table border="0" cellpadding="0" cellspacing="0" width="600" class="container-padding" style="border-collapse: collapse; border-radius: 12px; overflow: hidden; background: linear-gradient(135deg, #1A2A3A, #0F1C24); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);">
                        
                        <tr>
                            <td align="center" style="padding: 30px 0 20px 0;">
                                <img src="../../img/icono.ico" alt="Fortaleza Cross Logo" width="80" height="80" style="display: block; border: 0; max-width: 80px; border-radius: 50%; outline: none; -ms-interpolation-mode: bicubic;">
                            </td>
                        </tr>

                        <tr>
                            <td class="content-padding" style="padding: 20px 40px 40px 40px; text-align: center;">
                                <h1 class="heading-font-size" style="font-family: 'Poppins', sans-serif; font-size: 32px; line-height: 38px; color: #21d4fd; margin: 0 0 20px 0; font-weight: 700; text-shadow: 0 0 8px rgba(33, 212, 253, 0.6);">
                                    ¡Confirma tu Dirección de Correo!
                                </h1>
                                <p class="text-font-size" style="font-family: 'Poppins', sans-serif; font-size: 16px; line-height: 26px; color: #e0e0e0; margin: 0 0 30px 0;">
                                    Hola,
                                </p>
                                <p class="text-font-size" style="font-family: 'Poppins', sans-serif; font-size: 16px; line-height: 26px; color: #e0e0e0; margin: 0 0 30px 0;">
                                    Gracias por registrarte en Fortaleza Cross. Para completar tu registro y activar tu cuenta, por favor haz clic en el botón de abajo.
                                </p>

                                <table border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse: collapse;">
                                    <tr>
                                        <td align="center" class="button-cell" style="border-radius: 8px; background-color: #4CAF50; box-shadow: 0 4px 10px rgba(76, 175, 80, 0.4);">
                                            <a href="{{URL_CONFIRMACION}}" target="_blank" 
                                            style="font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; color: #ffffff; text-decoration: none; padding: 15px 30px; display: inline-block; border-radius: 8px; -webkit-text-size-adjust: none; mso-hide: all;">
                                            Confirmar Mi Correo
                                            </a>
                                        </td>
                                    </tr>
                                </table>

                                <p class="text-font-size" style="font-family: 'Poppins', sans-serif; font-size: 14px; line-height: 22px; color: #cccccc; margin: 40px 0 0 0;">
                                    Si no te registraste en Fortaleza Cross, puedes ignorar este correo de forma segura.
                                    ¡¡¡ESTE ENLACE DE CONFIRMACION EXPIRA EN 1 MINUTOS!!!
                                </p>
                                <p class="text-font-size" style="font-family: 'Poppins', sans-serif; font-size: 14px; line-height: 22px; color: #cccccc; margin: 10px 0 0 0;">
                                    Saludos cordiales,<br>
                                    El equipo de Fortaleza Cross
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <td align="center" style="padding: 20px 40px 30px 40px; font-family: 'Poppins', sans-serif; font-size: 12px; color: #888888;">
                                <p style="margin: 0;">&copy; 2024 Fortaleza Cross. Todos los derechos reservados.</p>
                                <p style="margin: 5px 0 0 0;">Prci Roque Saenz Peña - Chaco</p>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>