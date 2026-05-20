<?php
require_once __DIR__ . '/../models/Horario.php';
require_once __DIR__ . '/../models/Medico.php';

class HorarioController {
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 2) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $horarioModel = new Horario();
        $medicoModel = new Medico();
        
        $medico = $medicoModel->obtenerMedicoPorUsuarioId($_SESSION['user_id']);
        $idMedico = $medico ? $medico['id_medico'] : 0;
        
        $horarios = $horarioModel->obtenerPorMedico($idMedico);

        require_once __DIR__ . '/../views/doctor/horarios.php';
    }

    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 2) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $diaSemana = $_POST['dia_semana'];
            $esDiaDescanso = isset($_POST['es_dia_descanso']) ? 1 : 0;
            $horaInicio = $_POST['hora_inicio'] ?? null;
            $horaFin = $_POST['hora_fin'] ?? null;
            $descansoInicio = !empty($_POST['descanso_inicio']) ? $_POST['descanso_inicio'] : null;
            $descansoFin = !empty($_POST['descanso_fin']) ? $_POST['descanso_fin'] : null;

            $medicoModel = new Medico();
            $medico = $medicoModel->obtenerMedicoPorUsuarioId($_SESSION['user_id']);
            $idMedico = $medico ? $medico['id_medico'] : 0;

            if ($idMedico > 0) {
                $horarioModel = new Horario();
                $horarioModel->agregar($idMedico, $diaSemana, $horaInicio, $horaFin, $descansoInicio, $descansoFin, $esDiaDescanso);
            }

            header("Location: index.php?action=horarios&success=added");
            exit;
        }
    }

    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 2) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $horarioModel = new Horario();
        $horario = $horarioModel->obtenerPorId($id);

        if (!$horario) {
            header("Location: index.php?action=horarios");
            exit;
        }

        require_once __DIR__ . '/../views/doctor/editar_horario.php';
    }

    public function update() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 2) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idHorario = $_POST['id_horario'];
            $diaSemana = $_POST['dia_semana'];
            $esDiaDescanso = isset($_POST['es_dia_descanso']) ? 1 : 0;
            $horaInicio = $_POST['hora_inicio'] ?? null;
            $horaFin = $_POST['hora_fin'] ?? null;
            $descansoInicio = !empty($_POST['descanso_inicio']) ? $_POST['descanso_inicio'] : null;
            $descansoFin = !empty($_POST['descanso_fin']) ? $_POST['descanso_fin'] : null;

            $horarioModel = new Horario();
            $horarioModel->editar($idHorario, $diaSemana, $horaInicio, $horaFin, $descansoInicio, $descansoFin, $esDiaDescanso);

            header("Location: index.php?action=horarios&success=updated");
            exit;
        }
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 2) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $idHorario = $_POST['id'];
            $horarioModel = new Horario();
            $horarioModel->eliminar($idHorario);
            
            header("Location: index.php?action=horarios&success=deleted");
            exit;
        }
    }
}
?>
