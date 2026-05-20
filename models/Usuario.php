<?php
require_once __DIR__ . '/../config/Database.php';

class Usuario {
    protected $db;
    public $idUsuario;
    public $idRol;
    public $primerNombre;
    public $segundoNombre;
    public $primerApellido;
    public $segundoApellido;
    public $correo;
    public $contrasena;
    public $telefono;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function iniciarSesion($correo, $contrasenaIngresada) {
        $query = "SELECT u.*, r.nombre_rol FROM usuarios u JOIN roles r ON u.id_rol = r.id_rol WHERE u.correo = :correo LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            if (password_verify($contrasenaIngresada, $row['contrasena'])) {
                $this->mapearObjeto($row);
                return $row;
            }
        }
        return false;
    }

    public function cerrarSesion() {
        session_start();
        session_unset();
        session_destroy();
    }

    private function mapearObjeto($row) {
        $this->idUsuario = $row['id_usuario'];
        $this->idRol = $row['id_rol'];
        $this->primerNombre = $row['primer_nombre'];
        $this->segundoNombre = $row['segundo_nombre'];
        $this->primerApellido = $row['primer_apellido'];
        $this->segundoApellido = $row['segundo_apellido'];
        $this->correo = $row['correo'];
        $this->telefono = $row['telefono'];
    }

    // Método genérico para obtener todos los usuarios (para admin)
    public function obtenerTodos() {
        $query = "SELECT u.*, r.nombre_rol FROM usuarios u JOIN roles r ON u.id_rol = r.id_rol";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function registrarUsuario($datos) {
        $query = "INSERT INTO usuarios (id_rol, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo, contrasena, telefono) 
                  VALUES (:id_rol, :primer_nombre, :segundo_nombre, :primer_apellido, :segundo_apellido, :correo, :contrasena, :telefono)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':id_rol' => $datos['id_rol'],
            ':primer_nombre' => $datos['primer_nombre'],
            ':segundo_nombre' => $datos['segundo_nombre'] ?? null,
            ':primer_apellido' => $datos['primer_apellido'],
            ':segundo_apellido' => $datos['segundo_apellido'] ?? null,
            ':correo' => $datos['correo'],
            ':contrasena' => $datos['contrasena'], // Debe venir ya hasheada con password_hash
            ':telefono' => $datos['telefono'] ?? null
        ]);

        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function contarPorRol($idRol) {
        $query = "SELECT COUNT(*) FROM usuarios WHERE id_rol = :id_rol";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_rol' => $idRol]);
        return $stmt->fetchColumn();
    }
}
?>
