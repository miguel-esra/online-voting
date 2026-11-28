<?php

/**
 * @var array{
 *     user: object{name?: string, email?: string}
 * } $mail_data
 */

$userName  = esc($mail_data['user']->name ?? 'usuario(a)');
$userEmail = esc($mail_data['user']->email ?? '');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario Activado</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f9; font-family:Arial, Helvetica, sans-serif; color:#333333; -webkit-font-smoothing:antialiased;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f6f9; padding:30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.06); border:1px solid #e6eaf0;">
                    <!-- Encabezado -->
                    <tr>
                        <td style="background-color:#1b365d; padding:24px 32px; text-align:center;">
                            <h1 style="margin:0; font-size:20px; font-weight:600; color:#ffffff; letter-spacing:0.3px;">
                                Sistema de Votación Virtual
                            </h1>
                        </td>
                    </tr>

                    <!-- Cuerpo -->
                    <tr>
                        <td style="padding:32px; font-size:15px; line-height:1.6; color:#374151;">
                            <p style="margin:0 0 16px 0; font-size:16px; color:#111827;">
                                Estimado(a) <strong><?= $userName ?></strong>,
                            </p>

                            <p style="margin:0 0 20px 0;">
                                Te confirmamos que tu cuenta en el <strong>Sistema de Votación Virtual</strong> se ha activado correctamente.
                            </p>

                            <!-- Resumen de cuenta -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; margin-bottom:22px;">
                                <tr>
                                    <td style="padding:14px 18px; font-size:14px; color:#334155;">
                                        <strong>Cuenta:</strong> <?= $userEmail ?><br>
                                        <strong>Estado:</strong> Contraseña creada con éxito
                                    </td>
                                </tr>
                            </table>

                            <!-- Aviso de seguridad -->
                            <div style="background-color:#fff8e6; border-left:4px solid #e0a800; padding:12px 16px; border-radius:4px; margin-bottom:20px; font-size:14px; color:#5c4400;">
                                <strong>Aviso de seguridad:</strong> Mantén tus credenciales en confidencialidad y no las compartas con nadie. Si tú no realizaste este cambio, comunícate de inmediato con el administrador del proceso electoral.
                            </div>

                            <p style="margin:0; font-size:13px; color:#6b7280;">
                                El Sistema de Votación Virtual no se responsabiliza por el uso indebido de las credenciales por parte de terceros.
                            </p>
                        </td>
                    </tr>

                    <!-- Pie de página -->
                    <tr>
                        <td style="background-color:#f9fafb; padding:18px 32px; text-align:center; border-top:1px solid #e6eaf0; font-size:12px; color:#6b7280;">
                            Este es un correo automático, por favor no respondas a este mensaje.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>