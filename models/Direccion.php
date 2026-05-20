<?php
require_once __DIR__ . '/../config/Database.php';

class Direccion {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function crearDireccion($datos) {
        $query = "INSERT INTO direcciones (departamento, municipio, distrito, residencia_detalle) 
                  VALUES (:departamento, :municipio, :distrito, :residencia_detalle)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':departamento' => $datos['departamento'],
            ':municipio' => $datos['municipio'],
            ':distrito' => $datos['distrito'] ?? null,
            ':residencia_detalle' => $datos['residencia_detalle'] ?? null
        ]);

        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }
}
?>
