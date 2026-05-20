<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../config/Mailer.php';

class PerfilController {

    // ─── Cargar perfil del usuario ───────────────────────────────────
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $db    = Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'];
        $rol    = $_SESSION['rol'];

        // Datos base del usuario
        $stmtU = $db->prepare("SELECT * FROM usuarios WHERE id_usuario = :id LIMIT 1");
        $stmtU->execute([':id' => $userId]);
        $usuario = $stmtU->fetch(PDO::FETCH_ASSOC);

        if ($rol == 3) { // Paciente
            require_once __DIR__ . '/../models/Paciente.php';
            $pacModel = new Paciente();
            $paciente = $pacModel->obtenerPacientePorUsuarioId($userId);

            // Obtener dirección
            $direccion = null;
            if ($paciente && $paciente['id_direccion']) {
                $stmtD = $db->prepare("SELECT * FROM direcciones WHERE id_direccion = :id LIMIT 1");
                $stmtD->execute([':id' => $paciente['id_direccion']]);
                $direccion = $stmtD->fetch(PDO::FETCH_ASSOC);
            }

            require_once __DIR__ . '/../views/paciente/perfil_paciente.php';

        } elseif ($rol == 2) { // Doctor
            require_once __DIR__ . '/../models/Medico.php';
            $medModel = new Medico();
            $medico   = $medModel->obtenerMedicoPorUsuarioId($userId);

            // Obtener especialidad
            $especialidad = null;
            if ($medico) {
                $stmtE = $db->prepare("SELECT nombre_especialidad FROM especialidades WHERE id_especialidad = :id LIMIT 1");
                $stmtE->execute([':id' => $medico['id_especialidad']]);
                $especialidad = $stmtE->fetch(PDO::FETCH_ASSOC);
            }

            require_once __DIR__ . '/../views/doctor/perfil_doctor.php';

        } else { // Admin
            header("Location: index.php?action=dashboard");
            exit;
        }
    }

    // ─── Actualizar datos personales ─────────────────────────────────
    public function update() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=perfil");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $db     = Database::getInstance()->getConnection();

        $db->prepare("UPDATE usuarios SET 
                      primer_nombre   = :pn,
                      segundo_nombre  = :sn,
                      primer_apellido = :pa,
                      segundo_apellido= :sa,
                      telefono        = :tel
                      WHERE id_usuario = :id")
           ->execute([
               ':pn'  => trim($_POST['primer_nombre']),
               ':sn'  => trim($_POST['segundo_nombre'] ?? ''),
               ':pa'  => trim($_POST['primer_apellido']),
               ':sa'  => trim($_POST['segundo_apellido'] ?? ''),
               ':tel' => trim($_POST['telefono'] ?? ''),
               ':id'  => $userId,
           ]);

        // Si es doctor, solo se puede editar teléfono (ya actualizado arriba)
        header("Location: index.php?action=perfil&success=profile_updated&tab=personal");
        exit;
    }

    // ─── Actualizar dirección (solo paciente) ─────────────────────────
    public function updateDireccion() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 3 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=perfil");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $db     = Database::getInstance()->getConnection();

        // Obtener id_direccion del paciente
        $stmtP = $db->prepare("SELECT id_direccion FROM pacientes WHERE id_usuario = :id LIMIT 1");
        $stmtP->execute([':id' => $userId]);
        $pac = $stmtP->fetch();

        if ($pac && $pac['id_direccion']) {
            // Actualizar dirección existente
            $db->prepare("UPDATE direcciones SET
                          departamento          = :dep,
                          municipio             = :mun,
                          distrito              = :dis,
                          residencia_detalle    = :res,
                          pasaje_poligono_calle = :pas,
                          numero_casa           = :num,
                          punto_referencia      = :ref
                          WHERE id_direccion = :id")
               ->execute([
                   ':dep' => trim($_POST['departamento']          ?? ''),
                   ':mun' => trim($_POST['municipio']             ?? ''),
                   ':dis' => trim($_POST['distrito']              ?? ''),
                   ':res' => trim($_POST['residencia_detalle']    ?? ''),
                   ':pas' => trim($_POST['pasaje_poligono_calle'] ?? ''),
                   ':num' => trim($_POST['numero_casa']           ?? ''),
                   ':ref' => trim($_POST['punto_referencia']      ?? ''),
                   ':id'  => $pac['id_direccion'],
               ]);
        } else {
            // Crear dirección nueva y vincular al paciente
            $db->prepare("INSERT INTO direcciones (departamento, municipio, distrito, residencia_detalle, pasaje_poligono_calle, numero_casa, punto_referencia)
                          VALUES (:dep, :mun, :dis, :res, :pas, :num, :ref)")
               ->execute([
                   ':dep' => trim($_POST['departamento']          ?? ''),
                   ':mun' => trim($_POST['municipio']             ?? ''),
                   ':dis' => trim($_POST['distrito']              ?? ''),
                   ':res' => trim($_POST['residencia_detalle']    ?? ''),
                   ':pas' => trim($_POST['pasaje_poligono_calle'] ?? ''),
                   ':num' => trim($_POST['numero_casa']           ?? ''),
                   ':ref' => trim($_POST['punto_referencia']      ?? ''),
               ]);
            $idDir = $db->lastInsertId();
            $db->prepare("UPDATE pacientes SET id_direccion = :id_dir WHERE id_usuario = :id_u")
               ->execute([':id_dir' => $idDir, ':id_u' => $userId]);
        }

        header("Location: index.php?action=perfil&success=address_updated&tab=direccion");
        exit;
    }

    // ─── Cambiar contraseña ──────────────────────────────────────────
    public function changePassword() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=perfil");
            exit;
        }

        $userId     = $_SESSION['user_id'];
        $actual     = $_POST['contrasena_actual']   ?? '';
        $nueva      = $_POST['contrasena_nueva']    ?? '';
        $confirmar  = $_POST['contrasena_confirmar'] ?? '';

        $db   = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT correo, primer_nombre, primer_apellido, contrasena FROM usuarios WHERE id_usuario = :id LIMIT 1");
        $stmt->execute([':id' => $userId]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validar contraseña actual
        if (!password_verify($actual, $usuario['contrasena'])) {
            header("Location: index.php?action=perfil&error=wrong_password&tab=contrasena");
            exit;
        }

        // Validar que nueva y confirmación coincidan
        if ($nueva !== $confirmar) {
            header("Location: index.php?action=perfil&error=password_mismatch&tab=contrasena");
            exit;
        }

        // Validar longitud mínima
        if (strlen($nueva) < 8) {
            header("Location: index.php?action=perfil&error=password_short&tab=contrasena");
            exit;
        }

        // Actualizar contraseña
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $db->prepare("UPDATE usuarios SET contrasena = :hash WHERE id_usuario = :id")
           ->execute([':hash' => $hash, ':id' => $userId]);

        // Enviar correo de alerta (silencioso si falla)
        $nombre = $usuario['primer_nombre'] . ' ' . $usuario['primer_apellido'];
        Mailer::enviarCambioContrasena($usuario['correo'], $nombre);

        header("Location: index.php?action=perfil&success=password_changed&tab=contrasena");
        exit;
    }

    // ─── Gestionar Notificaciones ────────────────────────────────────
    public function marcar_notificacion_leida() {
        if (!isset($_SESSION['user_id'])) exit;
        $id = $_GET['id'] ?? 0;
        require_once __DIR__ . '/../models/Notificacion.php';
        (new Notificacion())->marcarLeida($id);
        header("Location: index.php?action=perfil&tab=notificaciones");
        exit;
    }

    public function marcar_todas_leidas() {
        if (!isset($_SESSION['user_id'])) exit;
        require_once __DIR__ . '/../models/Notificacion.php';
        (new Notificacion())->marcarTodasLeidas($_SESSION['user_id']);
        header("Location: index.php?action=perfil&tab=notificaciones");
        exit;
    }

    public function eliminar_notificacion() {
        if (!isset($_SESSION['user_id'])) exit;
        $id = $_GET['id'] ?? 0;
        require_once __DIR__ . '/../models/Notificacion.php';
        (new Notificacion())->eliminar($id);
        header("Location: index.php?action=perfil&tab=notificaciones");
        exit;
    }
}
?>
