<?php
require_once __DIR__ . "/conexion.php";

class MIcono extends Conexion
{

    public function obtenerIconos()
    {
        try {
            // 1. Insertar usuario  PDO
            $sql = "SELECT id_icono as id,nombre,codigo FROM iconos";

            $stmt = $this->conexion->query($sql);

            return $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

        public function eliminarIcono($id = null){
            if ($id === null) $id = $_GET['id'] ?? 0;
            try{
                $sql = "DELETE FROM iconos WHERE id_icono = :id";
                $stmt = $this->conexion->prepare($sql);
                $stmt->execute([':id' => $id]);
                return $stmt;
            }catch(PDOException $e){
                echo "Error: ".$e->getMessage();
                return false;
            }
        }

        public function mostrarModificarIcono() {
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
            $sql = 'SELECT nombre, codigo FROM iconos WHERE id_icono = :id';
            try{
                $stmt = $this->conexion->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC); 
            } catch(PDOException $e){
                return false;
            }
        }

        public function modificarIcono($id, $nombre, $codigo){
            try{
                $sql = 'UPDATE iconos SET nombre = :nombre, codigo = :codigo WHERE id_icono = :id';
                $stmt = $this->conexion->prepare($sql);

                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindValue(':codigo', $codigo, PDO::PARAM_STR);

                $resultado = $stmt->execute();

                if($resultado){
                    return true;
                } else {
                    $mensaje = "Error al actualizar en la base de datos";
                    return false;
                }
                
            } catch(PDOException $e){
                $mensaje = "Error en la base de datos: " . $e->getMessage();
                return false;
            }
        }

        public function crearIcono($nombre, $codigo){
            try{
                $sql = 'INSERT INTO iconos (nombre, codigo) VALUES (:nombre, :codigo)';
                $stmt = $this->conexion->prepare($sql);

                $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindValue(':codigo', $codigo, PDO::PARAM_STR);

                $resultado = $stmt->execute();

                if($resultado){
                    return true;
                }else{
                    $mensaje = "Error al actualizar en la base de datos";
                    return false;
                }
            }catch(PDOException $e){
                $mensaje = "Error en la base de datos: ".$e->getMessage();
                return false;
            }
        }

    
}
?>