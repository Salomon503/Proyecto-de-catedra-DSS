<?php
require_once __DIR__ . '/../config/Database.php';

class Notificacion {
    private $db;
    public $idNotificacion;
    public $idUsuario;
    public $mensajeRecordatorio;
    public $fecha;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function crearNotificacion($idUsuario, $mensaje) {
        $query = "INSERT INTO notificaciones (id_usuario, mensaje_recordatorio) VALUES (:id_usuario, :mensaje)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id_usuario' => $idUsuario,
            ':mensaje' => $mensaje
        ]);
    }

    public function obtenerPorUsuario($idUsuario) {
        $query = "SELECT * FROM notificaciones WHERE id_usuario = :id_usuario ORDER BY fecha DESC LIMIT 50";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_usuario' => $idUsuario]);
        return $stmt->fetchAll();
    }

    public function contarNoLeidas($idUsuario) {
        $query = "SELECT COUNT(*) as total FROM notificaciones 
                  WHERE id_usuario = :id_usuario AND leida = 0";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_usuario' => $idUsuario]);
        return $stmt->fetch()['total'] ?? 0;
    }

    public function marcarLeida($idNotificacion) {
        $query = "UPDATE notificaciones SET leida = 1 WHERE id_notificacion = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $idNotificacion]);
    }

    public function marcarTodasLeidas($idUsuario) {
        $query = "UPDATE notificaciones SET leida = 1 WHERE id_usuario = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $idUsuario]);
    }

    public function eliminar($idNotificacion) {
        $query = "DELETE FROM notificaciones WHERE id_notificacion = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $idNotificacion]);
    }
}
?>
