<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    public function login() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';

            $usuarioModel = new Usuario();
            $user = $usuarioModel->iniciarSesion($correo, $contrasena);

            if ($user) {
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['rol'] = $user['id_rol'];
                $_SESSION['user_role'] = $user['nombre_rol']; // Asumiendo que el query trae el nombre del rol
                $_SESSION['user_name'] = $user['primer_nombre'] . ' ' . $user['primer_apellido'];
                header("Location: index.php?action=dashboard");
                exit;
            } else {
                $error = "Credenciales incorrectas.";
                require_once __DIR__ . '/../views/auth/login.php';
            }
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validaciones
            $correo = $_POST['correo'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            $confirmar = $_POST['confirmar_contrasena'] ?? '';

            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $error = "El correo no es válido.";
                require_once __DIR__ . '/../views/auth/registro.php';
                return;
            }

            if (strlen($contrasena) < 8) {
                $error = "La contraseña debe tener al menos 8 caracteres.";
                require_once __DIR__ . '/../views/auth/registro.php';
                return;
            }

            if ($contrasena !== $confirmar) {
                $error = "Las contraseñas no coinciden.";
                require_once __DIR__ . '/../views/auth/registro.php';
                return;
            }

            // 1. Crear Dirección
            require_once __DIR__ . '/../models/Direccion.php';
            $dirModel = new Direccion();
            $idDireccion = $dirModel->crearDireccion([
                'departamento' => $_POST['departamento'],
                'municipio' => $_POST['municipio'],
                'distrito' => $_POST['distrito'] ?? null,
                'residencia_detalle' => $_POST['residencia_detalle'] ?? null
            ]);

            if ($idDireccion) {
                // 2. Crear Usuario
                $usuarioModel = new Usuario();
                $idUsuario = $usuarioModel->registrarUsuario([
                    'id_rol' => 3, // 3 = Paciente
                    'primer_nombre' => $_POST['primer_nombre'],
                    'segundo_nombre' => $_POST['segundo_nombre'] ?? null,
                    'primer_apellido' => $_POST['primer_apellido'],
                    'segundo_apellido' => $_POST['segundo_apellido'] ?? null,
                    'correo' => $correo,
                    'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT),
                    'telefono' => $_POST['telefono'] ?? null
                ]);

                if ($idUsuario) {
                    // 3. Crear Paciente
                    require_once __DIR__ . '/../models/Paciente.php';
                    $pacienteModel = new Paciente();
                    $pacienteModel->registrarPaciente([
                        'id_usuario' => $idUsuario,
                        'id_direccion' => $idDireccion,
                        'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                        'genero' => $_POST['genero'] ?? null
                    ]);

                    // Login automático
                    $_SESSION['user_id'] = $idUsuario;
                    $_SESSION['rol'] = 3;
                    $_SESSION['user_name'] = $_POST['primer_nombre'] . ' ' . $_POST['primer_apellido'];
                    
                    header("Location: index.php?action=dashboard");
                    exit;
                }
            }
            $error = "Error al registrar el usuario.";
            require_once __DIR__ . '/../views/auth/registro.php';
        } else {
            require_once __DIR__ . '/../views/auth/registro.php';
        }
    }

    public function logout() {
        $usuarioModel = new Usuario();
        $usuarioModel->cerrarSesion();
        header("Location: index.php?action=login");
        exit;
    }
}
?>
