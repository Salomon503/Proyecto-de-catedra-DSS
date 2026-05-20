<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/Database.php';

$idMedico = $_GET['id_medico'] ?? null;
$fecha    = $_GET['fecha'] ?? null;

if (!$idMedico || !$fecha) {
    echo json_encode(['error' => 'Parámetros incompletos']);
    exit;
}

// Determinar el día de la semana en español
$diasSemana      = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
$diaSemanaIndice = (int)date('w', strtotime($fecha));
$diaSemanaStr    = $diasSemana[$diaSemanaIndice];

$db = Database::getInstance()->getConnection();

// Obtener el horario del médico para este día
$queryHorario = "SELECT * FROM horarios_medicos WHERE id_medico = :id_medico AND dia_semana = :dia_semana LIMIT 1";
$stmtHorario  = $db->prepare($queryHorario);
$stmtHorario->execute([':id_medico' => $idMedico, ':dia_semana' => $diaSemanaStr]);
$horario = $stmtHorario->fetch(PDO::FETCH_ASSOC);

// Si no tiene horario configurado para ese día o es día de descanso
if (!$horario) {
    echo json_encode(['dia_descanso' => true, 'mensaje' => "El médico no tiene horario configurado para los {$diaSemanaStr}."]);
    exit;
}

if ($horario['es_dia_descanso']) {
    echo json_encode(['dia_descanso' => true, 'mensaje' => "El médico descansa los {$diaSemanaStr}. Por favor seleccione otro día."]);
    exit;
}

$horaInicio    = strtotime($horario['hora_inicio']);
$horaFin       = strtotime($horario['hora_fin']);
$descansoInicio = !empty($horario['descanso_inicio']) ? strtotime($horario['descanso_inicio']) : null;
$descansoFin    = !empty($horario['descanso_fin'])    ? strtotime($horario['descanso_fin'])    : null;

// Obtener citas ya reservadas con su hora_fin
// Bloqueamos cualquier slot que caiga dentro del rango [hora, hora_fin) de una cita existente
$queryCitas = "SELECT hora, hora_fin FROM citas_medicas 
               WHERE id_medico = :id_medico 
               AND fecha = :fecha 
               AND estado NOT IN ('Cancelada')";
$stmtCitas  = $db->prepare($queryCitas);
$stmtCitas->execute([':id_medico' => $idMedico, ':fecha' => $fecha]);
$citasOcupadas = $stmtCitas->fetchAll(PDO::FETCH_ASSOC);

$horasDisponibles = [];
$intervalo = 30 * 60; // 30 minutos en segundos

$current = $horaInicio;
while ($current < $horaFin) {
    $slotFin = $current + (60 * 60); // la cita dura 1 hora

    // Si la cita de 1 hora no cabe antes del fin del horario, parar
    if ($slotFin > $horaFin) {
        break;
    }

    $horaFormat = date('H:i', $current);
    $valido = true;

    // Verificar si cae en hora de descanso
    if ($descansoInicio && $descansoFin) {
        // El slot es inválido si se superpone con el descanso
        if ($current < $descansoFin && $slotFin > $descansoInicio) {
            $valido = false;
        }
    }

    // Verificar si se superpone con alguna cita existente
    if ($valido) {
        foreach ($citasOcupadas as $cita) {
            $citaInicio = strtotime($cita['hora']);
            // hora_fin puede ser null si son citas antiguas; en ese caso asumimos 1 hora
            $citaFin = !empty($cita['hora_fin']) ? strtotime($cita['hora_fin']) : ($citaInicio + 3600);

            // Hay overlap si: slot_inicio < cita_fin Y slot_fin > cita_inicio
            if ($current < $citaFin && $slotFin > $citaInicio) {
                $valido = false;
                break;
            }
        }
    }

    if ($valido) {
        $horasDisponibles[] = [
            'value' => $horaFormat,
            'label' => date('g:i A', $current) . ' – ' . date('g:i A', $slotFin)
        ];
    }

    $current += $intervalo;
}

if (empty($horasDisponibles)) {
    echo json_encode(['sin_horas' => true, 'mensaje' => 'No hay horarios disponibles para este día. Todas las horas están ocupadas.']);
    exit;
}

echo json_encode($horasDisponibles);
?>
