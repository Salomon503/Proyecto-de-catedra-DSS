<?php
require_once __DIR__ . '/../models/Especialidad.php';

class EspecialidadController {
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $especialidadModel = new Especialidad();
        $especialidades = $especialidadModel->obtenerTodas();

        require_once __DIR__ . '/../views/admin/especialidades.php';
    }

    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre_especialidad'];
            
            $especialidadModel = new Especialidad();
            $especialidadModel->crear($nombre);

            header("Location: index.php?action=especialidades&success=created");
            exit;
        }
    }

    public function update() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_especialidad'];
            $nombre = $_POST['nombre_especialidad'];
            
            $especialidadModel = new Especialidad();
            $especialidadModel->actualizar($id, $nombre);

            header("Location: index.php?action=especialidades&success=updated");
            exit;
        }
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $especialidadModel = new Especialidad();
            
            try {
                $especialidadModel->eliminar($id);
                header("Location: index.php?action=especialidades&success=deleted");
            } catch (PDOException $e) {
                header("Location: index.php?action=especialidades&error=fk_violation");
            }
            exit;
        }
    }
}
?>
