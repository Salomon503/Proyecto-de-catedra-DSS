<?php
require_once __DIR__ . '/../models/Cita.php';

class CalificacionController {
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $db = Database::getInstance()->getConnection();
        
        // Obtener citas con calificación
        $query = "SELECT cal.id_calificacion, cal.puntuacion, cal.comentario, cal.fecha as fecha_calificacion,
                  c.fecha as fecha_cita, c.hora as hora_cita, c.motivo,
                  CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS paciente_nombre,
                  CONCAT(m_u.primer_nombre, ' ', m_u.primer_apellido) AS medico_nombre,
                  e.nombre_especialidad
                  FROM calificaciones cal
                  JOIN citas_medicas c ON cal.id_calificacion = c.id_calificacion
                  JOIN pacientes pac ON c.id_paciente = pac.id_paciente
                  JOIN usuarios p ON pac.id_usuario = p.id_usuario
                  JOIN medicos med ON c.id_medico = med.id_medico
                  JOIN usuarios m_u ON med.id_usuario = m_u.id_usuario
                  JOIN especialidades e ON med.id_especialidad = e.id_especialidad
                  ORDER BY cal.fecha DESC";
                  
        $stmt = $db->prepare($query);
        $stmt->execute();
        $calificaciones = $stmt->fetchAll();

        // Estadísticas
        $stats = [
            'total' => count($calificaciones),
            'promedio' => 0,
            'excelentes' => 0,
            'malas' => 0
        ];

        $suma = 0;
        foreach($calificaciones as $c) {
            $suma += $c['puntuacion'];
            if($c['puntuacion'] >= 4) $stats['excelentes']++;
            if($c['puntuacion'] <= 2) $stats['malas']++;
        }
        if($stats['total'] > 0) {
            $stats['promedio'] = number_format($suma / $stats['total'], 1);
        }

        require_once __DIR__ . '/../views/admin/calificaciones.php';
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $id = $_POST['id'];
        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();
            
            // 1. Desvincular de citas_medicas
            $stmtCita = $db->prepare("UPDATE citas_medicas SET id_calificacion = NULL WHERE id_calificacion = :id");
            $stmtCita->execute([':id' => $id]);
            
            // 2. Eliminar calificacion
            $stmtCal = $db->prepare("DELETE FROM calificaciones WHERE id_calificacion = :id");
            $stmtCal->execute([':id' => $id]);
            
            $db->commit();
            header("Location: index.php?action=calificaciones&success=deleted");
        } catch (Exception $e) {
            $db->rollBack();
            header("Location: index.php?action=calificaciones&error=delete_failed");
        }
        exit;
    }
}
?>
