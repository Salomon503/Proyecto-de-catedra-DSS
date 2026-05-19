<?php
require_once __DIR__ . '/../config/Database.php';

class Especialidad {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerTodas() {
        $query = "SELECT * FROM especialidades ORDER BY nombre_especialidad ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM especialidades WHERE id_especialidad = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear($nombre) {
        $query = "INSERT INTO especialidades (nombre_especialidad) VALUES (:nombre)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':nombre' => $nombre]);
    }

    public function actualizar($id, $nombre) {
        $query = "UPDATE especialidades SET nombre_especialidad = :nombre WHERE id_especialidad = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':nombre' => $nombre, ':id' => $id]);
    }

    public function eliminar($id) {
        // En un caso real se debe verificar si hay medicos usándola
        // Pero para el alcance actual intentaremos eliminarla y atrapar error FK
        $query = "DELETE FROM especialidades WHERE id_especialidad = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
?>
