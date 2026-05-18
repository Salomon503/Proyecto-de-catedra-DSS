<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $usuarioModel = new Usuario();
        
        $db = Database::getInstance()->getConnection();
        $query = "SELECT u.id_usuario, u.primer_nombre, u.primer_apellido, u.correo, r.nombre_rol 
                  FROM usuarios u 
                  JOIN roles r ON u.id_rol = r.id_rol 
                  WHERE u.id_usuario != :current_id
                  ORDER BY u.id_usuario ASC";
        $stmt = $db->prepare($query);
        $stmt->execute([':current_id' => $_SESSION['user_id']]);
        $usuarios = $stmt->fetchAll();

        require_once __DIR__ . '/../views/admin/usuarios.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $db = Database::getInstance()->getConnection();
        
        $rolesQuery = "SELECT * FROM roles WHERE id_rol IN (1, 2)"; // Solo Admin y Doctor
        $rolesStmt = $db->prepare($rolesQuery);
        $rolesStmt->execute();
        $roles = $rolesStmt->fetchAll();

        require_once __DIR__ . '/../models/Especialidad.php';
        $especialidadModel = new Especialidad();
        $especialidades = $especialidadModel->obtenerTodas();

        require_once __DIR__ . '/../views/admin/crear_usuario.php';
    }

    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $primerNombre = $_POST['primer_nombre'];
            $segundoNombre = $_POST['segundo_nombre'] ?? null;
            $primerApellido = $_POST['primer_apellido'];
            $segundoApellido = $_POST['segundo_apellido'] ?? null;
            $correo = $_POST['correo'];
            $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
            $idRol = $_POST['id_rol'];

            $db = Database::getInstance()->getConnection();
            
            try {
                $db->beginTransaction();

                // Crear Usuario
                $queryUsuario = "INSERT INTO usuarios (id_rol, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo, contrasena) 
                                VALUES (:id_rol, :primer_nombre, :segundo_nombre, :primer_apellido, :segundo_apellido, :correo, :contrasena)";
                $stmtUsuario = $db->prepare($queryUsuario);
                $stmtUsuario->execute([
                    ':id_rol' => $idRol,
                    ':primer_nombre' => $primerNombre,
                    ':segundo_nombre' => $segundoNombre,
                    ':primer_apellido' => $primerApellido,
                    ':segundo_apellido' => $segundoApellido,
                    ':correo' => $correo,
                    ':contrasena' => $contrasena
                ]);
                $idUsuario = $db->lastInsertId();

                if ($idRol == 2) {
                    // Es Doctor
                    $idEspecialidad = $_POST['id_especialidad'];
                    $numeroColegiado = $_POST['numero_colegiado'];

                    $queryMedico = "INSERT INTO medicos (id_usuario, id_especialidad, numero_colegiado) VALUES (:id_usuario, :id_especialidad, :numero_colegiado)";
                    $stmtMedico = $db->prepare($queryMedico);
                    $stmtMedico->execute([
                        ':id_usuario' => $idUsuario,
                        ':id_especialidad' => $idEspecialidad,
                        ':numero_colegiado' => $numeroColegiado
                    ]);
                }

                $db->commit();
                header("Location: index.php?action=usuarios&success=created");
                exit;

            } catch (PDOException $e) {
                $db->rollBack();
                // Error manejable (ej. correo duplicado)
                header("Location: index.php?action=crear_usuario&error=duplicate");
                exit;
            }
        }
    }

    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $id = $_GET['id'] ?? 0;
        
        $db = Database::getInstance()->getConnection();
        $query = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute([':id_usuario' => $id]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            header("Location: index.php?action=usuarios");
            exit;
        }

        // Obtener roles para el select
        $rolesQuery = "SELECT * FROM roles";
        $rolesStmt = $db->prepare($rolesQuery);
        $rolesStmt->execute();
        $roles = $rolesStmt->fetchAll();

        require_once __DIR__ . '/../views/admin/editar_usuario.php';
    }

    public function update() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idUsuario = $_POST['id_usuario'];
            $primerNombre = $_POST['primer_nombre'];
            $segundoNombre = $_POST['segundo_nombre'];
            $primerApellido = $_POST['primer_apellido'];
            $segundoApellido = $_POST['segundo_apellido'];
            $idRol = $_POST['id_rol'];

            $db = Database::getInstance()->getConnection();
            $query = "UPDATE usuarios SET 
                      primer_nombre = :primer_nombre, 
                      segundo_nombre = :segundo_nombre, 
                      primer_apellido = :primer_apellido, 
                      segundo_apellido = :segundo_apellido,
                      id_rol = :id_rol
                      WHERE id_usuario = :id_usuario";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':primer_nombre' => $primerNombre,
                ':segundo_nombre' => $segundoNombre,
                ':primer_apellido' => $primerApellido,
                ':segundo_apellido' => $segundoApellido,
                ':id_rol' => $idRol,
                ':id_usuario' => $idUsuario
            ]);

            header("Location: index.php?action=usuarios&success=updated");
            exit;
        }
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $idUsuario = $_POST['id'];

            // Prevent deleting yourself
            if ($idUsuario == $_SESSION['user_id']) {
                header("Location: index.php?action=usuarios&error=self_delete");
                exit;
            }

            $db = Database::getInstance()->getConnection();
            
            try {
                // To avoid FK constraints, we would normally delete from dependants first or use ON DELETE CASCADE.
                // Assuming we just delete from usuarios and let DB handle CASCADE, or catch exception
                $query = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
                $stmt = $db->prepare($query);
                $stmt->execute([':id_usuario' => $idUsuario]);
                
                header("Location: index.php?action=usuarios&success=deleted");
            } catch (PDOException $e) {
                // FK violation likely
                header("Location: index.php?action=usuarios&error=fk_violation");
            }
            exit;
        }
        header("Location: index.php?action=usuarios");
        exit;
    }
}
?>
