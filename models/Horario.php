<?php
require_once __DIR__ . '/../config/Database.php';

class Horario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerPorMedico($idMedico) {
        $query = "SELECT * FROM horarios_medicos WHERE id_medico = :id_medico ORDER BY FIELD(dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'), hora_inicio ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_medico' => $idMedico]);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($idHorario) {
        $query = "SELECT * FROM horarios_medicos WHERE id_horario = :id_horario";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_horario' => $idHorario]);
        return $stmt->fetch();
    }

    public function agregar($idMedico, $diaSemana, $horaInicio, $horaFin, $descansoInicio = null, $descansoFin = null, $esDiaDescanso = 0) {
        $query = "INSERT INTO horarios_medicos (id_medico, dia_semana, hora_inicio, hora_fin, descanso_inicio, descanso_fin, es_dia_descanso) 
                  VALUES (:id_medico, :dia_semana, :hora_inicio, :hora_fin, :descanso_inicio, :descanso_fin, :es_dia_descanso)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id_medico' => $idMedico,
            ':dia_semana' => $diaSemana,
            ':hora_inicio' => $esDiaDescanso ? null : $horaInicio,
            ':hora_fin' => $esDiaDescanso ? null : $horaFin,
            ':descanso_inicio' => $esDiaDescanso ? null : $descansoInicio,
            ':descanso_fin' => $esDiaDescanso ? null : $descansoFin,
            ':es_dia_descanso' => $esDiaDescanso
        ]);
    }

    public function editar($idHorario, $diaSemana, $horaInicio, $horaFin, $descansoInicio = null, $descansoFin = null, $esDiaDescanso = 0) {
        $query = "UPDATE horarios_medicos SET 
                  dia_semana = :dia_semana, 
                  hora_inicio = :hora_inicio, 
                  hora_fin = :hora_fin, 
                  descanso_inicio = :descanso_inicio, 
                  descanso_fin = :descanso_fin, 
                  es_dia_descanso = :es_dia_descanso 
                  WHERE id_horario = :id_horario";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':dia_semana' => $diaSemana,
            ':hora_inicio' => $esDiaDescanso ? null : $horaInicio,
            ':hora_fin' => $esDiaDescanso ? null : $horaFin,
            ':descanso_inicio' => $esDiaDescanso ? null : $descansoInicio,
            ':descanso_fin' => $esDiaDescanso ? null : $descansoFin,
            ':es_dia_descanso' => $esDiaDescanso,
            ':id_horario' => $idHorario
        ]);
    }

    public function eliminar($idHorario) {
        $query = "DELETE FROM horarios_medicos WHERE id_horario = :id_horario";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id_horario' => $idHorario]);
    }
}
?>
