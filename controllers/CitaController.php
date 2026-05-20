<?php
require_once __DIR__ . '/../models/Cita.php';
require_once __DIR__ . '/../config/Mailer.php';

class CitaController {

    // ─── Helpers privados ────────────────────────────────────────────
    private function getDatosCitaParaCorreo($idCita) {
        $db = Database::getInstance()->getConnection();
        $q  = "SELECT c.*,
                      CONCAT(pu.primer_nombre,' ',pu.primer_apellido) AS paciente_nombre,
                      pu.correo AS paciente_correo,
                      CONCAT(mu.primer_nombre,' ',mu.primer_apellido) AS medico_nombre,
                      mu.correo AS medico_correo,
                      e.nombre_especialidad
               FROM citas_medicas c
               JOIN pacientes pac ON c.id_paciente = pac.id_paciente
               JOIN usuarios   pu  ON pac.id_usuario = pu.id_usuario
               JOIN medicos    med ON c.id_medico    = med.id_medico
               JOIN usuarios   mu  ON med.id_usuario = mu.id_usuario
               JOIN especialidades e ON med.id_especialidad = e.id_especialidad
               WHERE c.id_cita = :id LIMIT 1";
        $s = $db->prepare($q);
        $s->execute([':id' => $idCita]);
        return $s->fetch(PDO::FETCH_ASSOC);
    }

    // ─── Listado ─────────────────────────────────────────────────────
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $citaModel = new Cita();
        $rol    = $_SESSION['rol'];
        $userId = $_SESSION['user_id'];

        $filtros = [];
        if (isset($_GET['estado'])) $filtros['estado'] = $_GET['estado'];
        if (isset($_GET['search'])) $filtros['search'] = $_GET['search'];

        if ($rol == 2) {
            require_once __DIR__ . '/../models/Medico.php';
            $medico = (new Medico())->obtenerMedicoPorUsuarioId($userId);
            $filtros['id_medico'] = $medico ? $medico['id_medico'] : 0;
        } elseif ($rol == 3) {
            require_once __DIR__ . '/../models/Paciente.php';
            $paciente = (new Paciente())->obtenerPacientePorUsuarioId($userId);
            $filtros['id_paciente'] = $paciente ? $paciente['id_paciente'] : 0;
        }

        $citas = $citaModel->obtenerCitasPorFiltro($filtros);

        $filtrosStats = $filtros;
        unset($filtrosStats['estado']);
        $todasCitas = $citaModel->obtenerCitasPorFiltro($filtrosStats);

        $stats = ['total' => count($todasCitas), 'programadas' => 0, 'completadas' => 0, 'canceladas' => 0, 'reprogramadas' => 0];
        foreach ($todasCitas as $c) {
            if ($c['estado'] == 'Programada')    $stats['programadas']++;
            if ($c['estado'] == 'Completada')    $stats['completadas']++;
            if ($c['estado'] == 'Cancelada')     $stats['canceladas']++;
            if ($c['estado'] == 'Reprogramada')  $stats['reprogramadas']++;
        }

        require_once __DIR__ . '/../views/doctor/citas.php';
    }

    // ─── Formulario agendar ──────────────────────────────────────────
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $db = Database::getInstance()->getConnection();

        // Todos los pacientes (para admin/doctor)
        $stmtP = $db->prepare("SELECT p.id_paciente, u.primer_nombre, u.primer_apellido, u.correo FROM pacientes p JOIN usuarios u ON p.id_usuario = u.id_usuario ORDER BY u.primer_apellido");
        $stmtP->execute();
        $pacientes = $stmtP->fetchAll();

        // Especialidades con al menos un médico
        require_once __DIR__ . '/../models/Especialidad.php';
        $especialidades = (new Especialidad())->obtenerTodas();

        require_once __DIR__ . '/../views/paciente/agendar_cita.php';
    }

    // ─── Guardar cita ────────────────────────────────────────────────
    public function store() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=citas");
            exit;
        }

        $hora       = $_POST['hora'];
        $horaFin    = date('H:i:s', strtotime($hora) + 3600); // +1 hora
        $notas      = $_POST['notas_adicionales'] ?? null;

        $datos = [
            'idPaciente'        => $_POST['id_paciente'],
            'idMedico'          => $_POST['id_medico'],
            'fecha'             => $_POST['fecha'],
            'hora'              => $hora,
            'hora_fin'          => $horaFin,
            'motivo'            => $_POST['motivo'],
            'notas_adicionales' => $notas,
        ];

        $citaModel = new Cita();
        $idCita    = $citaModel->reservarCita($datos);

        if ($idCita) {
            // Notificación BD
            require_once __DIR__ . '/../models/Notificacion.php';
            $notif = new Notificacion();
            $notif->crearNotificacion(
                $_SESSION['user_id'],
                "Su cita ha sido programada para el {$datos['fecha']} a las {$hora}"
            );

            // Correo de confirmación
            $datosCita = $this->getDatosCitaParaCorreo($idCita);
            if ($datosCita) {
                Mailer::enviarConfirmacionCita(
                    $datosCita['paciente_correo'],
                    $datosCita['paciente_nombre'],
                    $datosCita['fecha'],
                    $datosCita['hora'],
                    $datosCita['medico_nombre'],
                    $datosCita['nombre_especialidad'],
                    $datosCita['motivo']
                );
            }

            header("Location: index.php?action=citas&success=1");
            exit;
        }

        header("Location: index.php?action=agendar_cita&error=overbooking");
        exit;
    }

    // ─── Ver formulario editar ───────────────────────────────────────
    public function edit() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $id        = $_GET['id'] ?? 0;
        $citaModel = new Cita();
        $cita      = $citaModel->obtenerCitaPorId($id);

        if (!$cita) {
            header("Location: index.php?action=citas&error=not_found");
            exit;
        }

        require_once __DIR__ . '/../views/doctor/editar_cita.php';
    }

    // ─── Actualizar cita (edición simple) ───────────────────────────
    public function update() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=citas");
            exit;
        }

        $idCita  = $_POST['id_cita'];
        $hora    = $_POST['hora'];
        $horaFin = date('H:i:s', strtotime($hora) + 3600);

        $db = Database::getInstance()->getConnection();
        $q  = "UPDATE citas_medicas SET fecha=:fecha, hora=:hora, hora_fin=:hora_fin, motivo=:motivo, estado=:estado WHERE id_cita=:id_cita";
        $db->prepare($q)->execute([
            ':fecha'    => $_POST['fecha'],
            ':hora'     => $hora,
            ':hora_fin' => $horaFin,
            ':motivo'   => $_POST['motivo'],
            ':estado'   => $_POST['estado'],
            ':id_cita'  => $idCita,
        ]);

        header("Location: index.php?action=citas&success=updated");
        exit;
    }

    // ─── Cancelar cita con motivo ────────────────────────────────────
    public function cancel() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=citas");
            exit;
        }

        $idCita      = $_POST['id'];
        $motivoCamb  = $_POST['motivo_cancelacion'] ?? 'Sin motivo especificado';
        $citaModel   = new Cita();

        // Obtener datos antes de cancelar para correo
        $datosCita = $this->getDatosCitaParaCorreo($idCita);

        $citaModel->cancelarCita($idCita, $motivoCamb);

        // Notificar al otro party por correo (sin error visible)
        if ($datosCita) {
            $esPaciente = ($_SESSION['rol'] == 3);
            if ($esPaciente) {
                // Paciente cancela → notificar al doctor (silencioso si falla)
                Mailer::enviarCancelacion(
                    $datosCita['medico_correo'],
                    'Dr. ' . $datosCita['medico_nombre'],
                    $datosCita['fecha'],
                    $datosCita['hora'],
                    $datosCita['medico_nombre'],
                    $motivoCamb
                );
            } else {
                // Doctor/Admin cancela → notificar al paciente
                Mailer::enviarCancelacion(
                    $datosCita['paciente_correo'],
                    $datosCita['paciente_nombre'],
                    $datosCita['fecha'],
                    $datosCita['hora'],
                    $datosCita['medico_nombre'],
                    $motivoCamb
                );
            }
        }

        header("Location: index.php?action=citas&success=cancelled");
        exit;
    }

    // ─── Reprogramar cita ────────────────────────────────────────────
    public function reschedule() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=citas");
            exit;
        }

        $idCita        = $_POST['id_cita'];
        $nuevaFecha    = $_POST['nueva_fecha'];
        $nuevaHora     = $_POST['nueva_hora'];
        $nuevaHoraFin  = date('H:i:s', strtotime($nuevaHora) + 3600);
        $motivoCamb    = $_POST['motivo_reprogramacion'] ?? '';
        $reprogPor     = ($_SESSION['rol'] == 2) ? 'doctor' : 'paciente';

        $datosCita = $this->getDatosCitaParaCorreo($idCita);

        $citaModel = new Cita();
        $citaModel->reprogramarCita($idCita, $nuevaFecha, $nuevaHora, $nuevaHoraFin, $motivoCamb, $reprogPor);

        // Correo al otro party
        if ($datosCita) {
            if ($reprogPor == 'doctor') {
                // Doctor reprograma → mail al paciente
                Mailer::enviarReprogramacion(
                    $datosCita['paciente_correo'],
                    $datosCita['paciente_nombre'],
                    $nuevaFecha,
                    $nuevaHora,
                    $datosCita['medico_nombre'],
                    $reprogPor,
                    $motivoCamb
                );
            } else {
                // Paciente reprograma → mail al doctor
                Mailer::enviarReprogramacion(
                    $datosCita['medico_correo'],
                    'Dr. ' . $datosCita['medico_nombre'],
                    $nuevaFecha,
                    $nuevaHora,
                    $datosCita['medico_nombre'],
                    $reprogPor,
                    $motivoCamb
                );
            }
        }

        header("Location: index.php?action=citas&success=rescheduled");
        exit;
    }

    // ─── Completar cita ──────────────────────────────────────────────
    public function complete() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=citas");
            exit;
        }

        $idCita = $_POST['id'];
        $datosCita = $this->getDatosCitaParaCorreo($idCita);

        $db = Database::getInstance()->getConnection();
        $db->prepare("UPDATE citas_medicas SET estado='Completada' WHERE id_cita=:id")
           ->execute([':id' => $idCita]);

        // Correo al paciente para calificar
        if ($datosCita) {
            $urlCalificar = "http://localhost/ADS_PROYECTO/index.php?action=calificar_cita&id={$idCita}";
            Mailer::enviarCitaCompletada(
                $datosCita['paciente_correo'],
                $datosCita['paciente_nombre'],
                $datosCita['fecha'],
                $datosCita['medico_nombre'],
                $urlCalificar
            );
        }

        header("Location: index.php?action=citas&success=completed");
        exit;
    }

    // ─── Enviar recordatorio manualmente (doctor) ────────────────────
    public function send_reminder() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 2 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=citas");
            exit;
        }

        $idCita    = $_POST['id'];
        $datosCita = $this->getDatosCitaParaCorreo($idCita);

        $enviado = false;
        if ($datosCita) {
            $enviado = Mailer::enviarRecordatorioCita(
                $datosCita['paciente_correo'],
                $datosCita['paciente_nombre'],
                $datosCita['fecha'],
                $datosCita['hora'],
                $datosCita['medico_nombre']
            );
        }

        $msg = $enviado ? 'reminder_sent' : 'reminder_failed';
        header("Location: index.php?action=citas&success={$msg}");
        exit;
    }

    // ─── Confirmar asistencia (paciente) ─────────────────────────────
    public function confirm_attendance() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 3 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=citas");
            exit;
        }

        $idCita    = $_POST['id'];
        $datosCita = $this->getDatosCitaParaCorreo($idCita);

        if (!$datosCita) {
            header("Location: index.php?action=citas");
            exit;
        }

        // Actualizar confirmacion_asistencia en BD
        $db = Database::getInstance()->getConnection();
        $db->prepare("UPDATE citas_medicas SET confirmacion_asistencia = 1 WHERE id_cita = :id")
           ->execute([':id' => $idCita]);

        // Obtener id_usuario del doctor para notificación en sistema
        $stmtDoc = $db->prepare("SELECT u.id_usuario FROM medicos m JOIN usuarios u ON m.id_usuario = u.id_usuario WHERE m.id_medico = :id_medico");
        $stmtDoc->execute([':id_medico' => $datosCita['id_medico']]);
        $docUsuario = $stmtDoc->fetch();

        $fechaFormato   = date('d/m/Y', strtotime($datosCita['fecha']));
        $horaFormato    = date('g:i A', strtotime($datosCita['hora']));
        $mensajeSistema = "✅ El paciente {$datosCita['paciente_nombre']} confirmó su asistencia para la cita del {$fechaFormato} a las {$horaFormato}.";

        // Notificación en sistema (SIEMPRE se crea, aunque el correo falle)
        if ($docUsuario) {
            require_once __DIR__ . '/../models/Notificacion.php';
            (new Notificacion())->crearNotificacion($docUsuario['id_usuario'], $mensajeSistema);
        }

        // Intento de correo al doctor (silencioso si falla)
        Mailer::enviarConfirmacionAsistencia(
            $datosCita['medico_correo'],
            $datosCita['medico_nombre'],
            $datosCita['paciente_nombre'],
            $datosCita['fecha'],
            $datosCita['hora']
        );

        header("Location: index.php?action=citas&success=attendance_confirmed");
        exit;
    }

    // ─── Calificar cita ──────────────────────────────────────────────
    public function rate() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 3) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $id        = $_GET['id'] ?? 0;
        $citaModel = new Cita();
        $cita      = $citaModel->obtenerCitaPorId($id);

        if (!$cita || $cita['estado'] != 'Completada' || !empty($cita['id_calificacion'])) {
            header("Location: index.php?action=citas");
            exit;
        }

        require_once __DIR__ . '/../views/paciente/calificar_cita.php';
    }

    // ─── Guardar calificación ────────────────────────────────────────
    public function store_rating() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 3 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $idCita     = $_POST['id_cita'];
        $puntuacion = $_POST['puntuacion'];
        $comentario = $_POST['comentario'] ?? '';

        $db = Database::getInstance()->getConnection();

        $db->prepare("INSERT INTO calificaciones (puntuacion, comentario) VALUES (:p, :c)")
           ->execute([':p' => $puntuacion, ':c' => $comentario]);
        $idCal = $db->lastInsertId();

        $db->prepare("UPDATE citas_medicas SET id_calificacion=:ic WHERE id_cita=:id")
           ->execute([':ic' => $idCal, ':id' => $idCita]);

        header("Location: index.php?action=citas&success=rated");
        exit;
    }
}
?>
