<?php
require_once __DIR__ . '/Usuario.php';

class Administrador extends Usuario {
    public function __construct() {
        parent::__construct();
    }

    public function gestionarUsuarios() {
        // Lógica de gestión
    }

    public function gestionarMedicos() {
        // Lógica de gestión
    }

    public function gestionarEspecialidades() {
        // Lógica de gestión
    }

    public function gestionarCitas() {
        // Lógica de gestión
    }
}
?>
