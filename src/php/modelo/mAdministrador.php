<?php
require_once __DIR__ . '/conexion.php';
class MAdmin extends Conexion
{


    public function mostrarAdministradorPerfil($idAdministrador = null)
    {
        if ($idAdministrador === null) {
            $idAdministrador = $_GET['idAdministrador'] ?? $_POST['idAdministrador'] ?? null;
        }
        $sql = 'SELECT nombre, email FROM usuarios WHERE id_Usuario = :id';
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id', $idAdministrador);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function modificarContrasenaAdmin($usuario_id, $contrasena_actual, $nueva_contrasena, &$mensaje = "")
    {
        try {

            $sql_verificar = 'SELECT contrasena FROM usuarios WHERE id_Usuario = :id';
            $stmt = $this->conexion->prepare($sql_verificar);
            $stmt->bindParam(':id', $usuario_id);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                $mensaje = "Usuario no encontrado";
                return false;
            }

            if (!password_verify($contrasena_actual, $usuario['contrasena'])) {
                $mensaje = "Error: Contraseña actual incorrecta";
                return false;
            }

            $nueva_contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

            $sql_modificar = 'UPDATE usuarios SET contrasena = :nueva WHERE id_Usuario = :id';
            $stmt2 = $this->conexion->prepare($sql_modificar);
            $stmt2->bindParam(':nueva', $nueva_contrasena_hash);
            $stmt2->bindParam(':id', $usuario_id);

            if ($stmt2->execute()) {
                return true;
            } else {
                $mensaje = "Error al actualizar en la base de datos";
                return false;
            }

        } catch (PDOException $e) {
            $mensaje = "Error en la base de datos: " . $e->getMessage();
            return false;
        }
    }

    public function modificarPerfilAdmin($usuario_id, $nombre, &$mensaje = "")
    {
        try {
            $sql_modificar = 'UPDATE usuarios SET nombre = :nombre WHERE id_Usuario = :id';
            $stmt = $this->conexion->prepare($sql_modificar);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':id', $usuario_id);

            if ($stmt->execute()) {
                return true;
            } else {
                $mensaje = "Error al actualizar el perfil";
                return false;
            }

        } catch (PDOException $e) {
            $mensaje = "Error en la base de datos: " . $e->getMessage();
            return false;
        }
    }

    public function verificarCredenciales($email, $password, &$mensaje = "")
    {
        try {
            $sql = "SELECT id_Usuario, contrasena FROM usuarios WHERE email = :email AND rol = 'admin'";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['contrasena'])) {
                return $usuario['id_Usuario'];
            } else {
                $mensaje = "Usuario o contraseña incorrectos";
                return false;
            }

        } catch (PDOException $e) {
            $mensaje = "Error en la base de datos: " . $e->getMessage();
            return false;
        }
    }

}
?>