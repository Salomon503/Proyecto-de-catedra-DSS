<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/Database.php';

$idEspecialidad = $_GET['id_especialidad'] ?? null;

$db = Database::getInstance()->getConnection();

if ($idEspecialidad) {
    // Médicos de una especialidad específica
    $query = "SELECT m.id_medico, u.primer_nombre, u.primer_apellido, e.nombre_especialidad
              FROM medicos m
              JOIN usuarios u ON m.id_usuario = u.id_usuario
              JOIN especialidades e ON m.id_especialidad = e.id_especialidad
              WHERE m.id_especialidad = :id_especialidad
              ORDER BY u.primer_apellido ASC";
    $stmt = $db->prepare($query);
    $stmt->execute([':id_especialidad' => $idEspecialidad]);
} else {
    // Todas las especialidades disponibles
    $query = "SELECT e.id_especialidad, e.nombre_especialidad, COUNT(m.id_medico) as total_medicos
              FROM especialidades e
              LEFT JOIN medicos m ON e.id_especialidad = m.id_especialidad
              GROUP BY e.id_especialidad
              HAVING total_medicos > 0
              ORDER BY e.nombre_especialidad ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
}

$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($resultado);
?>
