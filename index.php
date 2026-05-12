<?php
session_start();
require_once 'controllers/AuthController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/CitaController.php';
require_once 'controllers/UsuarioController.php';
require_once 'controllers/HorarioController.php';
require_once 'controllers/CalificacionController.php';
require_once 'controllers/EspecialidadController.php';
require_once 'controllers/PerfilController.php';

$action = $_GET['action'] ?? 'login';

$authController = new AuthController();

switch ($action) {
    case 'login':
        $authController->login();
        break;
    case 'authenticate':
        $authController->authenticate();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'register':
        $authController->register();
        break;
    case 'dashboard':
        $dashboardController = new DashboardController();
        $dashboardController->index();
        break;
    case 'citas':
        $citaController = new CitaController();
        $citaController->index();
        break;
    case 'agendar_cita':
        $citaController = new CitaController();
        $citaController->create();
        break;
    case 'guardar_cita':
        $citaController = new CitaController();
        $citaController->store();
        break;
    case 'editar_cita':
        $citaController = new CitaController();
        $citaController->edit();
        break;
    case 'actualizar_cita':
        $citaController = new CitaController();
        $citaController->update();
        break;
    case 'cancelar_cita':
        $citaController = new CitaController();
        $citaController->cancel();
        break;
    case 'reprogramar_cita':
        $citaController = new CitaController();
        $citaController->reschedule();
        break;
    case 'completar_cita':
        $citaController = new CitaController();
        $citaController->complete();
        break;
    case 'enviar_recordatorio':
        $citaController = new CitaController();
        $citaController->send_reminder();
        break;
    case 'confirmar_asistencia':
        $citaController = new CitaController();
        $citaController->confirm_attendance();
        break;
    case 'perfil':
        $perfilController = new PerfilController();
        $perfilController->index();
        break;
    case 'actualizar_perfil':
        $perfilController = new PerfilController();
        $perfilController->update();
        break;
    case 'actualizar_direccion':
        $perfilController = new PerfilController();
        $perfilController->updateDireccion();
        break;
    case 'cambiar_contrasena':
        $perfilController = new PerfilController();
        $perfilController->changePassword();
        break;
    case 'marcar_leida':
        $perfilController = new PerfilController();
        $perfilController->marcar_notificacion_leida();
        break;
    case 'marcar_todas_leidas':
        $perfilController = new PerfilController();
        $perfilController->marcar_todas_leidas();
        break;
    case 'eliminar_notificacion':
        $perfilController = new PerfilController();
        $perfilController->eliminar_notificacion();
        break;
    case 'calificar_cita':
        $citaController = new CitaController();
        $citaController->rate();
        break;
    case 'guardar_calificacion':
        $citaController = new CitaController();
        $citaController->store_rating();
        break;
    case 'usuarios':
        $usuarioController = new UsuarioController();
        $usuarioController->index();
        break;
    case 'crear_usuario':
        $usuarioController = new UsuarioController();
        $usuarioController->create();
        break;
    case 'guardar_usuario':
        $usuarioController = new UsuarioController();
        $usuarioController->store();
        break;
    case 'editar_usuario':
        $usuarioController = new UsuarioController();
        $usuarioController->edit();
        break;
    case 'actualizar_usuario':
        $usuarioController = new UsuarioController();
        $usuarioController->update();
        break;
    case 'eliminar_usuario':
        $usuarioController = new UsuarioController();
        $usuarioController->delete();
        break;
    case 'horarios':
        $horarioController = new HorarioController();
        $horarioController->index();
        break;
    case 'guardar_horario':
        $horarioController = new HorarioController();
        $horarioController->store();
        break;
    case 'editar_horario':
        $horarioController = new HorarioController();
        $horarioController->edit();
        break;
    case 'actualizar_horario':
        $horarioController = new HorarioController();
        $horarioController->update();
        break;
    case 'eliminar_horario':
        $horarioController = new HorarioController();
        $horarioController->delete();
        break;
    case 'calificaciones':
        $calificacionController = new CalificacionController();
        $calificacionController->index();
        break;
    case 'especialidades':
        $especialidadController = new EspecialidadController();
        $especialidadController->index();
        break;
    case 'guardar_especialidad':
        $especialidadController = new EspecialidadController();
        $especialidadController->store();
        break;
    case 'actualizar_especialidad':
        $especialidadController = new EspecialidadController();
        $especialidadController->update();
        break;
    case 'eliminar_especialidad':
        $especialidadController = new EspecialidadController();
        $especialidadController->delete();
        break;
    default:
        $authController->login();
        break;
}
?>
