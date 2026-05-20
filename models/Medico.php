<?php
require_once __DIR__ . '/Usuario.php';

class Medico extends Usuario {
    public $idMedico;
    public $idEspecialidad;
    public $numeroColegiado;
    public $nombreEspecialidad;

    public function __construct() {
        parent::__construct();
    }

    public function consultarAgenda($fecha) {
        // Delegaremos esto al modelo Cita
    }

    public function actualizarEstadoCita($idCita, $estado) {
        // Delegaremos esto al modelo Cita
    }

    public function obtenerMedicoPorUsuarioId($idUsuario) {
        $query = "SELECT * FROM medicos WHERE id_usuario = :id_usuario LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_usuario' => $idUsuario]);
        return $stmt->fetch();
    }
}
?>
