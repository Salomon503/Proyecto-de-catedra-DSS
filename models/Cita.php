<?php
require_once __DIR__ . '/../config/Database.php';

class Cita {
    private $db;
    public $idCita;
    public $idPaciente;
    public $idMedico;
    public $motivo;
    public $fecha;
    public $hora;
    public $estado;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function reservarCita($datos) {
        // Prevenir overbooking considerando hora_fin
        $checkQuery = "SELECT COUNT(*) as count FROM citas_medicas 
                       WHERE id_medico = :id_medico 
                       AND fecha = :fecha 
                       AND estado NOT IN ('Cancelada')
                       AND (:hora < hora_fin OR hora_fin IS NULL)
                       AND (:hora_fin > hora)";
        $checkStmt = $this->db->prepare($checkQuery);
        $horaFin = date('H:i:s', strtotime($datos['hora']) + 3600);
        $checkStmt->execute([
            ':id_medico' => $datos['idMedico'],
            ':fecha'     => $datos['fecha'],
            ':hora'      => $datos['hora'],
            ':hora_fin'  => $horaFin
        ]);
        if ($checkStmt->fetch()['count'] > 0) {
            return false;
        }

        $query = "INSERT INTO citas_medicas (id_paciente, id_medico, motivo, notas_adicionales, fecha, hora, hora_fin, estado) 
                  VALUES (:id_paciente, :id_medico, :motivo, :notas, :fecha, :hora, :hora_fin, 'Programada')";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':id_paciente' => $datos['idPaciente'],
            ':id_medico'   => $datos['idMedico'],
            ':motivo'      => $datos['motivo'],
            ':notas'       => $datos['notas_adicionales'] ?? null,
            ':fecha'       => $datos['fecha'],
            ':hora'        => $datos['hora'],
            ':hora_fin'    => $datos['hora_fin'] ?? $horaFin,
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    public function modificarCita($idCita, $datos) {
        $query = "UPDATE citas_medicas SET fecha = :fecha, hora = :hora, estado = :estado, motivo = :motivo WHERE id_cita = :id_cita";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':fecha' => $datos['fecha'],
            ':hora' => $datos['hora'],
            ':estado' => $datos['estado'],
            ':motivo' => $datos['motivo'],
            ':id_cita' => $idCita
        ]);
    }

    public function cancelarCita($idCita, $motivoCambio = null) {
        $query = "UPDATE citas_medicas SET estado = 'Cancelada', motivo_cambio = :motivo WHERE id_cita = :id_cita";
        $stmt  = $this->db->prepare($query);
        return $stmt->execute([':motivo' => $motivoCambio, ':id_cita' => $idCita]);
    }

    public function reprogramarCita($idCita, $nuevaFecha, $nuevaHora, $nuevaHoraFin, $motivoCambio, $reprogramadaPor) {
        $query = "UPDATE citas_medicas SET 
                  fecha = :fecha, hora = :hora, hora_fin = :hora_fin,
                  estado = 'Reprogramada', motivo_cambio = :motivo, reprogramada_por = :por
                  WHERE id_cita = :id_cita";
        $stmt  = $this->db->prepare($query);
        return $stmt->execute([
            ':fecha'    => $nuevaFecha,
            ':hora'     => $nuevaHora,
            ':hora_fin' => $nuevaHoraFin,
            ':motivo'   => $motivoCambio,
            ':por'      => $reprogramadaPor,
            ':id_cita'  => $idCita
        ]);
    }

    public function obtenerCitasPorFiltro($filtros = []) {
        $query = "SELECT c.*, 
                  CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS paciente_nombre,
                  p.correo AS paciente_correo,
                  CONCAT(m_u.primer_nombre, ' ', m_u.primer_apellido) AS medico_nombre,
                  e.nombre_especialidad,
                  cal.puntuacion,
                  cal.comentario as comentario_calificacion
                  FROM citas_medicas c
                  JOIN pacientes pac ON c.id_paciente = pac.id_paciente
                  JOIN usuarios p ON pac.id_usuario = p.id_usuario
                  JOIN medicos med ON c.id_medico = med.id_medico
                  JOIN usuarios m_u ON med.id_usuario = m_u.id_usuario
                  JOIN especialidades e ON med.id_especialidad = e.id_especialidad
                  LEFT JOIN calificaciones cal ON c.id_calificacion = cal.id_calificacion";
        
        $condiciones = [];
        $params = [];

        if (isset($filtros['estado']) && $filtros['estado'] !== 'Todas' && $filtros['estado'] !== '') {
            $condiciones[] = "c.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        if (!empty($filtros['search'])) {
            $condiciones[] = "(p.primer_nombre LIKE :search OR p.primer_apellido LIKE :search OR c.motivo LIKE :search)";
            $params[':search'] = "%" . $filtros['search'] . "%";
        }

        if (!empty($filtros['id_paciente'])) {
            $condiciones[] = "c.id_paciente = :id_paciente";
            $params[':id_paciente'] = $filtros['id_paciente'];
        }

        if (!empty($filtros['id_medico'])) {
            $condiciones[] = "c.id_medico = :id_medico";
            $params[':id_medico'] = $filtros['id_medico'];
        }

        if (count($condiciones) > 0) {
            $query .= " WHERE " . implode(" AND ", $condiciones);
        }

        $query .= " ORDER BY c.fecha ASC, c.hora ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function obtenerCitaPorId($idCita) {
        $query = "SELECT c.*, 
                  CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS paciente_nombre,
                  p.correo AS paciente_correo,
                  CONCAT(m_u.primer_nombre, ' ', m_u.primer_apellido) AS medico_nombre,
                  e.nombre_especialidad,
                  cal.puntuacion,
                  cal.comentario as comentario_calificacion
                  FROM citas_medicas c
                  JOIN pacientes pac ON c.id_paciente = pac.id_paciente
                  JOIN usuarios p ON pac.id_usuario = p.id_usuario
                  JOIN medicos med ON c.id_medico = med.id_medico
                  JOIN usuarios m_u ON med.id_usuario = m_u.id_usuario
                  JOIN especialidades e ON med.id_especialidad = e.id_especialidad
                  LEFT JOIN calificaciones cal ON c.id_calificacion = cal.id_calificacion
                  WHERE c.id_cita = :id_cita LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_cita' => $idCita]);
        return $stmt->fetch();
    }

    public function contarCitasPorEstado($estado = null, $idMedico = null, $idPaciente = null) {
        $query = "SELECT COUNT(*) FROM citas_medicas WHERE 1=1";
        $params = [];
        
        if ($estado) {
            $query .= " AND estado = :estado";
            $params[':estado'] = $estado;
        }
        if ($idMedico) {
            $query .= " AND id_medico = :id_medico";
            $params[':id_medico'] = $idMedico;
        }
        if ($idPaciente) {
            $query .= " AND id_paciente = :id_paciente";
            $params[':id_paciente'] = $idPaciente;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function contarCitasHoy($idMedico = null) {
        $query = "SELECT COUNT(*) FROM citas_medicas WHERE fecha = CURDATE() AND estado != 'Cancelada'";
        $params = [];
        if ($idMedico) {
            $query .= " AND id_medico = :id_medico";
            $params[':id_medico'] = $idMedico;
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function contarPacientesUnicos($idMedico) {
        $query = "SELECT COUNT(DISTINCT id_paciente) FROM citas_medicas WHERE id_medico = :id_medico";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_medico' => $idMedico]);
        return $stmt->fetchColumn();
    }

    public function contarCitasPorEstadoPaciente($estado, $idPaciente) {
        $query = "SELECT COUNT(*) FROM citas_medicas WHERE estado = :estado AND id_paciente = :id_paciente";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':estado' => $estado, ':id_paciente' => $idPaciente]);
        return $stmt->fetchColumn();
    }
}
?>
