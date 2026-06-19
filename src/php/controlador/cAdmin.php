<?php
require_once __DIR__ . '/../modelo/mAdministrador.php';
class CAdmin
{

    private $mAdmin;
    public $nombreVista = "";

    public function __construct() {
        $this->mAdmin = new MAdmin();
    }

    public function vistaDashboardAdmin()
    {
        $this->nombreVista = "dashboardAdmin";
    }

    public function perfilAdministrador()
    {
        $this->nombreVista = "perfilAdmin";
        return ['filaAdmin' => $this->mAdmin->mostrarAdministradorPerfil($_SESSION['idAdmin'])];
    }

    public function vistaModificarcontrasena()
    {
        $this->nombreVista = "modificarContrasenaAdmin";
        return ['filaAdmin' => $this->mAdmin->mostrarAdministradorPerfil($_SESSION['idAdmin'])];
    }

    public function procesarCambioContrasena()
    {
        $idAdmin = $_SESSION['idAdmin'];

        $contrasena_actual = $_POST['contrasena_actual'];
        $nueva_contrasena = $_POST['nueva_contrasena'];
        $confirmar_contrasena = $_POST['confirmar_contrasena'];
        $mensaje = "";

        if (empty($contrasena_actual) || empty($nueva_contrasena) || empty($confirmar_contrasena)) {
            $this->nombreVista = "modificarContrasenaAdmin";
            return ['mensaje' => 'Error: Todos los campos son obligatorios', 'filaAdmin' => $this->mAdmin->mostrarAdministradorPerfil($idAdmin)];
        }

        if ($nueva_contrasena !== $confirmar_contrasena) {
            $this->nombreVista = "modificarContrasenaAdmin";
            return ['mensaje' => 'Error: Las contraseñas nuevas no coinciden', 'filaAdmin' => $this->mAdmin->mostrarAdministradorPerfil($idAdmin)];
        }

        $resultado = $this->mAdmin->modificarContrasenaAdmin($idAdmin, $contrasena_actual, $nueva_contrasena, $mensaje);

        if ($resultado) {
            $this->nombreVista = "perfilAdmin";
            return ['mensaje' => 'Contraseña cambiada correctamente', 'filaAdmin' => $this->mAdmin->mostrarAdministradorPerfil($idAdmin)];
        } else {
            $this->nombreVista = "modificarContrasenaAdmin";
            return ['mensaje' => $mensaje, 'filaAdmin' => $this->mAdmin->mostrarAdministradorPerfil($idAdmin)];
        }
    }

    public function vistaEditarPerfil()
    {
        $this->nombreVista = "editarPerfilAdmin";
        return ['filaAdmin' => $this->mAdmin->mostrarAdministradorPerfil($_SESSION['idAdmin'])];
    }

    public function procesarEditarPerfil()
    {
        $idAdmin = $_SESSION['idAdmin'];
        $nombre = $_POST['nombre'];
        $mensaje = "";

        if (empty($nombre)) {
            $this->nombreVista = "editarPerfilAdmin";
            return ['mensaje' => 'Error: No se han rellenado los campos', 'filaAdmin' => $this->mAdmin->mostrarAdministradorPerfil($idAdmin)];
        }

        $resultado = $this->mAdmin->modificarPerfilAdmin($idAdmin, $nombre, $mensaje);

        if ($resultado) {
            $this->nombreVista = "perfilAdmin";
            return ['mensaje' => 'Perfil actualizado correctamente', 'filaAdmin' => $this->mAdmin->mostrarAdministradorPerfil($idAdmin)];
        } else {
            if (empty($mensaje)) $mensaje = "Error al actualizar el perfil";
            $this->nombreVista = "editarPerfilAdmin";
            return ['mensaje' => $mensaje, 'filaAdmin' => $this->mAdmin->mostrarAdministradorPerfil($idAdmin)];
        }
    }

    public function vistaLoginAdmin()
    {
        $this->nombreVista = "admin_login";
    }

    public function procesarLoginAdmin()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $mensaje = "";

        if (empty($email) || empty($password)) {
            $this->nombreVista = "admin_login";
            return ['mensaje' => 'Por favor, complete todos los campos'];
        }

        $idAdmin = $this->mAdmin->verificarCredenciales($email, $password, $mensaje);

        if ($idAdmin) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['idAdmin'] = $idAdmin;

            $datosAdmin = $this->mAdmin->mostrarAdministradorPerfil($idAdmin);
            $_SESSION['nombreAdmin'] = $datosAdmin['nombre'];

            header("Location: index.php?c=Admin&m=vistaDashboardAdmin");
            exit();
        } else {
            $this->nombreVista = "admin_login";
            return ['mensaje' => $mensaje];
        }
    }

}
?>