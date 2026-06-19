<?php
require_once __DIR__ . "/conexion.php";

class Usuario extends Conexion
{


    public function insertar($nombre, $email, $pass)
    {
        $sql = "INSERT INTO usuarios (nombre, contrasena, email, rol, puntuacion, fecha_registro)
                VALUES (:nombre, :contrasena, :email, 'player', 0, NOW())";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':contrasena' => $pass,
            ':email' => $email
        ]);
    }

    public function login($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function obtenerPuntuacion()
    {
        try {
            $sql = "SELECT nombre, puntuacion FROM usuarios WHERE puntuacion > 0 ORDER BY puntuacion DESC";
            $resultado = $this->conexion->query($sql);
            return $resultado->fetchAll();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function actualizarPuntuacion($id, $puntos)
    {
        try {
            // Solo actualizamos si la nueva puntuacion es MAYOR a la antigua
            $sql = "UPDATE usuarios SET puntuacion = :puntos WHERE id_usuario = :id AND puntuacion < :puntos";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':puntos', $puntos);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function guardarTokenRecuperacion($email, $tokenHash, $expiresAt)
    {
        $sql = "UPDATE usuarios
                SET reset_token = :token, reset_expires = :expires
                WHERE email = :email";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':token' => $tokenHash,
            ':expires' => $expiresAt,
            ':email' => $email
        ]);
    }

    public function buscarPorTokenRecuperacion($tokenHash)
    {
        $sql = "SELECT id_usuario, nombre, email, reset_expires
                FROM usuarios
                WHERE reset_token = :token
                LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':token' => $tokenHash]);
        return $stmt->fetch();
    }

    public function actualizarContrasenaPorId($idUsuario, $passHash)
    {
        $sql = "UPDATE usuarios
                SET contrasena = :pass, reset_token = NULL, reset_expires = NULL
                WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':pass' => $passHash,
            ':id' => $idUsuario
        ]);
    }

    public function mostrarUsuarioPerfil($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function listarUsuarios()
    {
        $sql = "SELECT id_usuario, nombre, email, rol, puntuacion FROM usuarios ORDER BY nombre";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerEstadisticas($id)
    {
        $resultado = [
            'rank' => 0,
            'total_jugadores' => 0,
            'dias_jugador' => 0,
            'nivel' => 'Novato',
            'emblema' => '🌱',
        ];

        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE puntuacion > 0";
            $stmt = $this->conexion->query($sql);
            $row = $stmt->fetch();
            $resultado['total_jugadores'] = (int)$row['total'];

            $sql2 = "SELECT COUNT(*) as rank FROM usuarios WHERE puntuacion > (SELECT puntuacion FROM usuarios WHERE id_usuario = :id)";
            $stmt2 = $this->conexion->prepare($sql2);
            $stmt2->execute([':id' => $id]);
            $row2 = $stmt2->fetch();
            $resultado['rank'] = (int)$row2['rank'] + 1;

            $sql3 = "SELECT fecha_registro FROM usuarios WHERE id_usuario = :id";
            $stmt3 = $this->conexion->prepare($sql3);
            $stmt3->execute([':id' => $id]);
            $row3 = $stmt3->fetch();
            if ($row3 && $row3['fecha_registro']) {
                $fecha = new DateTime($row3['fecha_registro']);
                $ahora = new DateTime();
                $resultado['dias_jugador'] = $fecha->diff($ahora)->days;
            }

            $puntuacion = $this->obtenerPuntuacionDe($id);
            if ($puntuacion >= 5000) {
                $resultado['nivel'] = 'Leyenda Verde';
                $resultado['emblema'] = '🏆';
            } elseif ($puntuacion >= 3000) {
                $resultado['nivel'] = 'Guardián del Bosque';
                $resultado['emblema'] = '🌳';
            } elseif ($puntuacion >= 1500) {
                $resultado['nivel'] = 'Defensor Activo';
                $resultado['emblema'] = '⚔️';
            } elseif ($puntuacion >= 500) {
                $resultado['nivel'] = 'Explorador';
                $resultado['emblema'] = '🧭';
            } else {
                $resultado['nivel'] = 'Novato';
                $resultado['emblema'] = '🌱';
            }
        } catch (PDOException $e) {
        }

        return $resultado;
    }

    public function obtenerPuntuacionDe($id)
    {
        $sql = "SELECT puntuacion FROM usuarios WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? (int)$row['puntuacion'] : 0;
    }

    public function eliminarUsuario($id)
    {
        $sql = "DELETE FROM usuarios WHERE id_usuario = :id AND rol != 'admin'";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function modificarContrasenaUsuario($usuario_id, $contrasena_actual, $nueva_contrasena, &$mensaje = "")
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

    public function modificarPerfilUsuario($usuario_id, $nombre, $apellido, $email, $telefono, &$mensaje = "")
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
}
