<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Cita.php';

class DashboardController {
    public function index() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol'])) {
            header("Location: index.php?action=logout");
            exit;
        }

        $usuarioModel = new Usuario();
        $citaModel = new Cita();
        $rol = $_SESSION['rol'];
        $userId = $_SESSION['user_id'];

        if ($rol == 1) { // Administrador
            $totalCitas = $citaModel->contarCitasPorEstado();
            $citasCompletadas = $citaModel->contarCitasPorEstado('Completada');
            $stats = [
                'total_pacientes' => $usuarioModel->contarPorRol(3),
                'pacientes_mes_pasado_pct' => 0,
                'total_doctores' => $usuarioModel->contarPorRol(2),
                'doctores_nuevos_mes' => 0,
                'citas_programadas' => $citaModel->contarCitasPorEstado('Programada'),
                'citas_semana_pasada_pct' => 0,
                'citas_completadas' => $citasCompletadas,
                'tasa_finalizacion' => ($totalCitas > 0) ? round(($citasCompletadas / $totalCitas) * 100) : 0
            ];
            
            $proximasCitas = $citaModel->obtenerCitasPorFiltro(['estado' => 'Programada']);
            $proximasCitas = array_slice($proximasCitas, 0, 5);

            // Fetch pacientes recientes (crearemos este método en Paciente.php)
            require_once __DIR__ . '/../models/Paciente.php';
            $pacienteModel = new Paciente();
            $pacientesRecientes = $pacienteModel->obtenerPacientesRecientes(5);

            require_once __DIR__ . '/../views/admin/dashboard.php';

        } else if ($rol == 2) { // Doctor
            // TODO: Crear lógica específica para doctor
            require_once __DIR__ . '/../models/Medico.php';
            $medicoModel = new Medico();
            // Obtener ID del médico a partir del usuario (Agregaremos método a Medico.php)
            $medico = $medicoModel->obtenerMedicoPorUsuarioId($userId);
            $idMedico = $medico ? $medico['id_medico'] : 0;

            $proximasCitas = $citaModel->obtenerCitasPorFiltro(['estado' => 'Programada', 'id_medico' => $idMedico]);
            $proximasCitas = array_slice($proximasCitas, 0, 5);
            
            $stats = [
                'citas_hoy' => $citaModel->contarCitasHoy($idMedico),
                'citas_programadas' => $citaModel->contarCitasPorEstado('Programada', $idMedico),
                'citas_completadas' => $citaModel->contarCitasPorEstado('Completada', $idMedico),
                'pacientes_atendidos' => $citaModel->contarPacientesUnicos($idMedico)
            ];

            // Calcular puntuación promedio del médico
            $db = Database::getInstance()->getConnection();
            $queryProm = "SELECT AVG(cal.puntuacion) as promedio, COUNT(cal.id_calificacion) as total_cal
                          FROM calificaciones cal
                          JOIN citas_medicas c ON cal.id_calificacion = c.id_calificacion
                          WHERE c.id_medico = :id_medico";
            $stmtProm = $db->prepare($queryProm);
            $stmtProm->execute([':id_medico' => $idMedico]);
            $promResult = $stmtProm->fetch();
            $stats['puntuacion_promedio'] = $promResult['promedio'] ? number_format($promResult['promedio'], 1) : null;
            $stats['total_calificaciones'] = $promResult['total_cal'] ?? 0;

            require_once __DIR__ . '/../views/doctor/dashboard_doctor.php';

        } else { // Paciente
            require_once __DIR__ . '/../models/Paciente.php';
            $pacienteModel = new Paciente();
            $paciente = $pacienteModel->obtenerPacientePorUsuarioId($userId);
            $idPaciente = $paciente ? $paciente['id_paciente'] : 0;

            $proximasCitas = $citaModel->obtenerCitasPorFiltro(['estado' => 'Programada', 'id_paciente' => $idPaciente]);
            $proximasCitas = array_slice($proximasCitas, 0, 5);

            $stats = [
                'citas_programadas' => count($citaModel->obtenerCitasPorFiltro(['estado' => 'Programada', 'id_paciente' => $idPaciente])),
                'citas_completadas' => count($citaModel->obtenerCitasPorFiltro(['estado' => 'Completada', 'id_paciente' => $idPaciente]))
            ];

            require_once __DIR__ . '/../views/paciente/dashboard_paciente.php';
        }
    }
}
?>
