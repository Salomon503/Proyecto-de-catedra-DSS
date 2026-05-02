<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private static $smtp_email = 'medcare693@gmail.com';
    private static $smtp_pass  = 'xamw wjft mtlt fure';
    private static $smtp_host  = 'smtp.gmail.com';
    private static $smtp_port  = 587;

    private static function crearMail() {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = self::$smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = self::$smtp_email;
        $mail->Password   = self::$smtp_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = self::$smtp_port;
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom(self::$smtp_email, 'MedCare Sistema');
        // Silenciar errores de SSL en localhost
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];
        return $mail;
    }

    private static function plantillaBase($titulo, $cuerpo, $color = '#2563EB') {
        return "
<!DOCTYPE html>
<html lang='es'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>{$titulo}</title>
</head>
<body style='margin:0;padding:0;background-color:#f1f5f9;font-family:Arial,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#f1f5f9;padding:32px 16px;'>
  <tr><td align='center'>
    <table width='600' cellpadding='0' cellspacing='0' style='max-width:600px;width:100%;'>
      <!-- Header -->
      <tr>
        <td style='background-color:{$color};border-radius:16px 16px 0 0;padding:32px;text-align:center;'>
          <div style='display:inline-flex;align-items:center;gap:12px;'>
            <div style='width:40px;height:40px;background-color:rgba(255,255,255,0.2);border-radius:10px;display:inline-block;text-align:center;line-height:40px;'>
              <span style='color:white;font-size:20px;'>&#10084;</span>
            </div>
            <span style='color:white;font-size:24px;font-weight:700;letter-spacing:-0.5px;'>MedCare</span>
          </div>
          <p style='color:rgba(255,255,255,0.8);margin:8px 0 0;font-size:14px;'>Sistema de Gestión Médica</p>
        </td>
      </tr>
      <!-- Body -->
      <tr>
        <td style='background-color:#ffffff;padding:40px 40px 32px;'>
          {$cuerpo}
        </td>
      </tr>
      <!-- Footer -->
      <tr>
        <td style='background-color:#f8fafc;border-radius:0 0 16px 16px;padding:24px 40px;border-top:1px solid #e2e8f0;text-align:center;'>
          <p style='color:#94a3b8;font-size:12px;margin:0;'>Este correo fue enviado automáticamente por MedCare. Por favor no responda a este mensaje.</p>
          <p style='color:#94a3b8;font-size:12px;margin:8px 0 0;'>© " . date('Y') . " MedCare — Todos los derechos reservados.</p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body>
</html>";
    }

    private static function enviar($mail, $asunto, $cuerpoHtml) {
        try {
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpoHtml;
            $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $cuerpoHtml));
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("[MedCare Mailer] Error al enviar '{$asunto}': " . $mail->ErrorInfo);
            return false;
        }
    }

    // ─── 1. Confirmación de cita agendada ───────────────────────────
    public static function enviarConfirmacionCita($correoDestino, $nombrePaciente, $fecha, $hora, $doctor, $especialidad = '', $motivo = '') {
        try {
            $mail = self::crearMail();
            $mail->addAddress($correoDestino, $nombrePaciente);

            $fechaFormato = date('d/m/Y', strtotime($fecha));
            $horaFormato  = date('g:i A', strtotime($hora));

            $cuerpo = "
            <h2 style='color:#1e293b;font-size:22px;margin:0 0 8px;'>¡Cita Confirmada! ✅</h2>
            <p style='color:#64748b;font-size:15px;margin:0 0 28px;'>Hola <strong>{$nombrePaciente}</strong>, su cita médica ha sido programada exitosamente.</p>
            
            <div style='background-color:#f0f9ff;border:1px solid #bae6fd;border-radius:12px;padding:24px;margin-bottom:28px;'>
              <table width='100%' cellpadding='6'>
                <tr>
                  <td style='color:#0369a1;font-size:13px;font-weight:600;width:140px;'>🩺 Doctor</td>
                  <td style='color:#0f172a;font-size:14px;font-weight:500;'>Dr. {$doctor}</td>
                </tr>
                " . (!empty($especialidad) ? "<tr><td style='color:#0369a1;font-size:13px;font-weight:600;'>🏥 Especialidad</td><td style='color:#0f172a;font-size:14px;'>{$especialidad}</td></tr>" : "") . "
                <tr>
                  <td style='color:#0369a1;font-size:13px;font-weight:600;'>📅 Fecha</td>
                  <td style='color:#0f172a;font-size:14px;'>{$fechaFormato}</td>
                </tr>
                <tr>
                  <td style='color:#0369a1;font-size:13px;font-weight:600;'>⏰ Hora</td>
                  <td style='color:#0f172a;font-size:14px;'>{$horaFormato} (1 hora de duración)</td>
                </tr>
                " . (!empty($motivo) ? "<tr><td style='color:#0369a1;font-size:13px;font-weight:600;'>📋 Motivo</td><td style='color:#0f172a;font-size:14px;'>{$motivo}</td></tr>" : "") . "
              </table>
            </div>
            
            <p style='color:#64748b;font-size:14px;'>Por favor llegue con <strong>10 minutos de anticipación</strong> y traiga su documento de identificación.</p>
            <p style='color:#94a3b8;font-size:13px;margin-top:20px;'>Si necesita cancelar o reprogramar, acceda al sistema con anticipación.</p>";

            $html = self::plantillaBase('Confirmación de Cita', $cuerpo, '#2563EB');
            return self::enviar($mail, '✅ Confirmación de Cita Médica — MedCare', $html);
        } catch (Exception $e) {
            error_log("[MedCare Mailer] Error crearMail confirmación: " . $e->getMessage());
            return false;
        }
    }

    // ─── 2. Recordatorio de cita (doctor lo envía manualmente) ──────
    public static function enviarRecordatorioCita($correoDestino, $nombrePaciente, $fecha, $hora, $doctor) {
        try {
            $mail = self::crearMail();
            $mail->addAddress($correoDestino, $nombrePaciente);

            $fechaFormato = date('d/m/Y', strtotime($fecha));
            $horaFormato  = date('g:i A', strtotime($hora));

            $cuerpo = "
            <h2 style='color:#1e293b;font-size:22px;margin:0 0 8px;'>Recordatorio de Cita 🔔</h2>
            <p style='color:#64748b;font-size:15px;margin:0 0 28px;'>Hola <strong>{$nombrePaciente}</strong>, le recordamos que tiene una cita médica próximamente.</p>
            
            <div style='background-color:#fefce8;border:1px solid #fde68a;border-radius:12px;padding:24px;margin-bottom:28px;'>
              <table width='100%' cellpadding='6'>
                <tr><td style='color:#92400e;font-size:13px;font-weight:600;width:140px;'>🩺 Doctor</td><td style='color:#0f172a;font-size:14px;font-weight:500;'>Dr. {$doctor}</td></tr>
                <tr><td style='color:#92400e;font-size:13px;font-weight:600;'>📅 Fecha</td><td style='color:#0f172a;font-size:14px;'>{$fechaFormato}</td></tr>
                <tr><td style='color:#92400e;font-size:13px;font-weight:600;'>⏰ Hora</td><td style='color:#0f172a;font-size:14px;'>{$horaFormato}</td></tr>
              </table>
            </div>

            <p style='color:#64748b;font-size:14px;'>Le pedimos confirmar su asistencia a través del sistema MedCare.</p>";

            $html = self::plantillaBase('Recordatorio de Cita', $cuerpo, '#D97706');
            return self::enviar($mail, '🔔 Recordatorio de su Cita Médica — MedCare', $html);
        } catch (Exception $e) {
            error_log("[MedCare Mailer] Error recordatorio: " . $e->getMessage());
            return false;
        }
    }

    // ─── 3. Notificación de reprogramación ──────────────────────────
    public static function enviarReprogramacion($correoDestino, $nombreDestino, $nuevaFecha, $nuevaHora, $doctor, $reprogramadoPor, $motivo = '') {
        try {
            $mail = self::crearMail();
            $mail->addAddress($correoDestino, $nombreDestino);

            $fechaFormato = date('d/m/Y', strtotime($nuevaFecha));
            $horaFormato  = date('g:i A', strtotime($nuevaHora));
            $quien = ($reprogramadoPor == 'doctor') ? "el Dr. {$doctor}" : "el paciente";

            $cuerpo = "
            <h2 style='color:#1e293b;font-size:22px;margin:0 0 8px;'>Cita Reprogramada 🔄</h2>
            <p style='color:#64748b;font-size:15px;margin:0 0 28px;'>Hola <strong>{$nombreDestino}</strong>, su cita ha sido reprogramada por {$quien}.</p>

            <div style='background-color:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:24px;margin-bottom:28px;'>
              <p style='color:#166534;font-size:13px;font-weight:700;margin:0 0 16px;'>NUEVA FECHA Y HORA</p>
              <table width='100%' cellpadding='6'>
                <tr><td style='color:#166534;font-size:13px;font-weight:600;width:140px;'>📅 Nueva Fecha</td><td style='color:#0f172a;font-size:14px;font-weight:600;'>{$fechaFormato}</td></tr>
                <tr><td style='color:#166534;font-size:13px;font-weight:600;'>⏰ Nueva Hora</td><td style='color:#0f172a;font-size:14px;font-weight:600;'>{$horaFormato}</td></tr>
              </table>
            </div>
            
            " . (!empty($motivo) ? "<div style='background-color:#faf5ff;border:1px solid #e9d5ff;border-radius:12px;padding:16px;margin-bottom:28px;'><p style='color:#7c3aed;font-size:13px;font-weight:600;margin:0 0 8px;'>📝 Motivo del cambio</p><p style='color:#4c1d95;font-size:14px;margin:0;'>{$motivo}</p></div>" : "") . "
            
            <p style='color:#64748b;font-size:14px;'>Puede revisar los detalles accediendo al sistema MedCare.</p>";

            $html = self::plantillaBase('Cita Reprogramada', $cuerpo, '#059669');
            return self::enviar($mail, '🔄 Su Cita ha sido Reprogramada — MedCare', $html);
        } catch (Exception $e) {
            error_log("[MedCare Mailer] Error reprogramación: " . $e->getMessage());
            return false;
        }
    }

    // ─── 4. Notificación de cancelación ─────────────────────────────
    public static function enviarCancelacion($correoDestino, $nombreDestino, $fecha, $hora, $doctor, $motivo = '') {
        try {
            $mail = self::crearMail();
            $mail->addAddress($correoDestino, $nombreDestino);

            $fechaFormato = date('d/m/Y', strtotime($fecha));
            $horaFormato  = date('g:i A', strtotime($hora));

            $cuerpo = "
            <h2 style='color:#1e293b;font-size:22px;margin:0 0 8px;'>Cita Cancelada ❌</h2>
            <p style='color:#64748b;font-size:15px;margin:0 0 28px;'>Hola <strong>{$nombreDestino}</strong>, le informamos que su cita médica ha sido cancelada.</p>

            <div style='background-color:#fff1f2;border:1px solid #fecdd3;border-radius:12px;padding:24px;margin-bottom:28px;'>
              <table width='100%' cellpadding='6'>
                <tr><td style='color:#9f1239;font-size:13px;font-weight:600;width:140px;'>🩺 Doctor</td><td style='color:#0f172a;font-size:14px;'>Dr. {$doctor}</td></tr>
                <tr><td style='color:#9f1239;font-size:13px;font-weight:600;'>📅 Fecha</td><td style='color:#0f172a;font-size:14px;'>{$fechaFormato}</td></tr>
                <tr><td style='color:#9f1239;font-size:13px;font-weight:600;'>⏰ Hora</td><td style='color:#0f172a;font-size:14px;'>{$horaFormato}</td></tr>
              </table>
            </div>
            
            " . (!empty($motivo) ? "<div style='background-color:#faf5ff;border:1px solid #e9d5ff;border-radius:12px;padding:16px;margin-bottom:28px;'><p style='color:#7c3aed;font-size:13px;font-weight:600;margin:0 0 8px;'>📝 Motivo de cancelación</p><p style='color:#4c1d95;font-size:14px;margin:0;'>{$motivo}</p></div>" : "") . "
            
            <p style='color:#64748b;font-size:14px;'>Si desea reagendar, puede hacerlo desde el sistema MedCare.</p>";

            $html = self::plantillaBase('Cita Cancelada', $cuerpo, '#DC2626');
            return self::enviar($mail, '❌ Cita Médica Cancelada — MedCare', $html);
        } catch (Exception $e) {
            error_log("[MedCare Mailer] Error cancelación: " . $e->getMessage());
            return false;
        }
    }

    // ─── 5. Cita completada — invitar a calificar ────────────────────
    public static function enviarCitaCompletada($correoDestino, $nombrePaciente, $fecha, $doctor, $urlCalificar = '') {
        try {
            $mail = self::crearMail();
            $mail->addAddress($correoDestino, $nombrePaciente);

            $fechaFormato = date('d/m/Y', strtotime($fecha));

            $botonCalificar = !empty($urlCalificar)
                ? "<div style='text-align:center;margin:28px 0;'><a href='{$urlCalificar}' style='background-color:#f59e0b;color:white;text-decoration:none;padding:14px 32px;border-radius:10px;font-weight:700;font-size:15px;display:inline-block;'>⭐ Calificar Atención</a></div>"
                : "<p style='color:#64748b;font-size:14px;text-align:center;'>Ingrese al sistema MedCare para calificar su atención.</p>";

            $cuerpo = "
            <h2 style='color:#1e293b;font-size:22px;margin:0 0 8px;'>¡Consulta Completada! 🎉</h2>
            <p style='color:#64748b;font-size:15px;margin:0 0 28px;'>Hola <strong>{$nombrePaciente}</strong>, su consulta médica del <strong>{$fechaFormato}</strong> con el <strong>Dr. {$doctor}</strong> ha finalizado exitosamente.</p>

            <div style='background-color:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:24px;margin-bottom:24px;text-align:center;'>
              <p style='color:#92400e;font-size:22px;margin:0 0 8px;'>⭐ ⭐ ⭐ ⭐ ⭐</p>
              <p style='color:#78350f;font-size:15px;font-weight:600;margin:0 0 4px;'>¿Cómo fue su experiencia?</p>
              <p style='color:#92400e;font-size:13px;margin:0;'>Su opinión nos ayuda a mejorar el servicio para todos los pacientes.</p>
            </div>

            {$botonCalificar}

            <p style='color:#94a3b8;font-size:13px;text-align:center;'>Calificar toma menos de 1 minuto. ¡Gracias por su tiempo!</p>";

            $html = self::plantillaBase('Consulta Completada', $cuerpo, '#D97706');
            return self::enviar($mail, '🎉 Consulta Completada — Califique su Atención en MedCare', $html);
        } catch (Exception $e) {
            error_log("[MedCare Mailer] Error cita completada: " . $e->getMessage());
            return false;
        }
    }
    // ─── 6. Confirmación de asistencia (al doctor) ──────────────────
    public static function enviarConfirmacionAsistencia($correoDestino, $nombreDoctor, $nombrePaciente, $fecha, $hora) {
        try {
            $mail = self::crearMail();
            $mail->addAddress($correoDestino, 'Dr. ' . $nombreDoctor);
            $fechaFormato = date('d/m/Y', strtotime($fecha));
            $horaFormato  = date('g:i A', strtotime($hora));
            $cuerpo = "
            <h2 style='color:#1e293b;font-size:22px;margin:0 0 8px;'>Asistencia Confirmada ✅</h2>
            <p style='color:#64748b;font-size:15px;margin:0 0 28px;'>Hola <strong>Dr. {$nombreDoctor}</strong>, el paciente ha confirmado su asistencia a la siguiente cita:</p>
            <div style='background-color:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:24px;margin-bottom:28px;'>
              <table width='100%' cellpadding='6'>
                <tr><td style='color:#166534;font-size:13px;font-weight:600;width:140px;'>👤 Paciente</td><td style='color:#0f172a;font-size:14px;font-weight:600;'>{$nombrePaciente}</td></tr>
                <tr><td style='color:#166534;font-size:13px;font-weight:600;'>📅 Fecha</td><td style='color:#0f172a;font-size:14px;'>{$fechaFormato}</td></tr>
                <tr><td style='color:#166534;font-size:13px;font-weight:600;'>⏰ Hora</td><td style='color:#0f172a;font-size:14px;'>{$horaFormato}</td></tr>
              </table>
            </div>
            <p style='color:#64748b;font-size:14px;'>El paciente estará presente a la hora indicada. Puede revisar los detalles en el sistema MedCare.</p>";
            $html = self::plantillaBase('Asistencia Confirmada', $cuerpo, '#059669');
            return self::enviar($mail, '✅ Paciente Confirmó Asistencia — MedCare', $html);
        } catch (Exception $e) {
            error_log("[MedCare Mailer] Error confirmación asistencia: " . $e->getMessage());
            return false;
        }
    }

    // ─── 7. Alerta de cambio de contraseña ──────────────────────────
    public static function enviarCambioContrasena($correoDestino, $nombre) {
        try {
            $mail = self::crearMail();
            $mail->addAddress($correoDestino, $nombre);
            $fecha = date('d/m/Y H:i');
            $cuerpo = "
            <h2 style='color:#1e293b;font-size:22px;margin:0 0 8px;'>Contraseña Actualizada 🔐</h2>
            <p style='color:#64748b;font-size:15px;margin:0 0 28px;'>Hola <strong>{$nombre}</strong>, le informamos que la contraseña de su cuenta en MedCare fue modificada exitosamente.</p>
            <div style='background-color:#fef3c7;border:1px solid #fde68a;border-radius:12px;padding:20px;margin-bottom:24px;'>
              <p style='color:#92400e;font-size:14px;margin:0 0 8px;font-weight:600;'>⚠️ Información de seguridad</p>
              <p style='color:#78350f;font-size:13px;margin:0;'>Este cambio fue realizado el <strong>{$fecha}</strong>. Si usted no realizó este cambio, contacte al administrador de inmediato.</p>
            </div>
            <p style='color:#64748b;font-size:14px;'>Si usted realizó este cambio, puede ignorar este mensaje con seguridad.</p>";
            $html = self::plantillaBase('Contraseña Actualizada', $cuerpo, '#7C3AED');
            return self::enviar($mail, '🔐 Su Contraseña fue Actualizada — MedCare', $html);
        } catch (Exception $e) {
            error_log("[MedCare Mailer] Error cambio contraseña: " . $e->getMessage());
            return false;
        }
    }
}
?>
