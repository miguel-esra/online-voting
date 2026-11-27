<?php

/**
 * @var array{
 *     actionLink: string,
 *     user: object{name?: string, email?: string}
 * } $mail_data
 */

$userName   = esc($mail_data['user']->name ?? 'usuario(a)');
$userEmail  = esc($mail_data['user']->email ?? '');
$actionLink = esc($mail_data['actionLink'] ?? '#');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña</title>
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
                                Hemos recibido una solicitud para restablecer la contraseña de tu cuenta asociada al correo electrónico
                                <span style="color:#1b365d; font-weight:600;"><?= $userEmail ?></span>.
                            </p>

                            <p style="margin:0 0 24px 0;">
                                Para crear una nueva contraseña, haz clic en el siguiente botón:
                            </p>

                            <!-- Botón CTA -->
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 28px auto;">
                                <tr>
                                    <td align="center" bgcolor="#198754" style="border-radius:6px; background-color:#198754;">
                                        <a href="<?= $actionLink ?>" target="_blank" style="display:inline-block; padding:13px 28px; font-size:15px; font-weight:600; color:#ffffff; text-decoration:none; border-radius:6px;">
                                            Restablecer contraseña
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Aviso de vigencia -->
                            <div style="background-color:#fff8e6; border-left:4px solid #e0a800; padding:12px 16px; border-radius:4px; margin-bottom:20px; font-size:14px; color:#5c4400;">
                                <strong>Importante:</strong> Este enlace es válido durante los próximos <strong>15 minutos</strong>.
                            </div>

                            <p style="margin:0; font-size:14px; color:#6b7280;">
                                Si no has solicitado restablecer tu contraseña, puedes ignorar este mensaje de forma segura.
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