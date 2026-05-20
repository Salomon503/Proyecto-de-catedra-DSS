<?php
require_once __DIR__ . '/Usuario.php';

class Paciente extends Usuario {
    public $idPaciente;
    public $idDireccion;
    public $fechaNacimiento;
    public $genero;
    public $edad;

    public function __construct() {
        parent::__construct();
    }

    public function reservarCita($datosCita) {
        // Delegaremos esto al modelo Cita
    }

    public function modificarCita($idCita, $datos) {
        // Delegaremos esto al modelo Cita
    }

    public function cancelarCita($idCita) {
        // Delegaremos esto al modelo Cita
    }

    public function registrarPaciente($datos) {
        $query = "INSERT INTO pacientes (id_usuario, id_direccion, fecha_nacimiento, genero) 
                  VALUES (:id_usuario, :id_direccion, :fecha_nacimiento, :genero)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':id_usuario' => $datos['id_usuario'],
            ':id_direccion' => $datos['id_direccion'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':genero' => $datos['genero'] ?? null
        ]);

        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function obtenerPacientesRecientes($limit = 5) {
        $query = "SELECT u.primer_nombre, u.primer_apellido, u.correo, p.id_paciente
                  FROM pacientes p 
                  JOIN usuarios u ON p.id_usuario = u.id_usuario 
                  ORDER BY p.id_paciente DESC LIMIT :limit";
        $stmt = $this->db->prepare($query);
        // Bind limit securely since execute() treats all as strings
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPacientePorUsuarioId($idUsuario) {
        $query = "SELECT * FROM pacientes WHERE id_usuario = :id_usuario LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_usuario' => $idUsuario]);
        return $stmt->fetch();
    }
}
?>
