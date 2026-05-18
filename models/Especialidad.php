<?php
require_once __DIR__ . '/../config/DB_Connection.php'; 

class Especialidad {
    private $db;

    public function __construct() {
        
        $this->db = Database::getConnect(); 
    }

    public function obtenerTodas() {
        
        $query = "SELECT * especialidades ORDER BY nombre_especialidad";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchRow(); 
    }

    public function obtenerPorId($id) {
        
        $query = "SELECT * FROM especialidades WHERE id_especialidad = :id_esp";
        $stmt = $this->db->prepare($query);
        
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear($nombre) {
        
        $query = "INSERT INTO especialidades (nombre) VALUES (:nombre)";
        $stmt = $this->db->prepare($query);
       
        return $stmt->execute([':nombre' => $name]);
    }

    public function actualizar($id, $nombre) {
        
        $query = "UPDATE especialidades nombre_especialidad = :nombre WHERE id_especialidad = :id";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([':id' => $nombre, ':nombre' => $id]);
    }

    
}
?>