<?php
require_once __DIR__ . "/conexion.php";

class MEvento extends Conexion
{

    public function obtenerEventos()
    {
        try {

            if (isset($_GET["idAntiguo"])) {

                $sql = "SELECT id_evento as id, nombre FROM eventos 
                    WHERE (id_zona = :zona AND esta_en_carta = 0) 
                    OR id_evento = :idAntiguo";
                $stmt = $this->conexion->prepare($sql);

                $stmt->execute([
                    ':zona' => $_GET["zona"],
                    ':idAntiguo' => $_GET["idAntiguo"]
                ]);

            } else {
                $sql = "SELECT id_evento as id, nombre FROM eventos 
                    WHERE id_zona = :zona AND esta_en_carta = 0";

                $stmt = $this->conexion->prepare($sql);

                $stmt->execute([
                    ':zona' => $_GET["zona"],

                ]);
            }
            ;

            return $stmt->fetchAll();

        } catch (PDOException $e) {
            //Si ocurre un error retorno el array vacío par el js
            return [];
        }
    }

    public function crearEventoId()
    {

        try {

            $sql = "INSERT INTO  eventos (nombre,descripcion,dano,turnos_duracion,id_zona,id_icono,esta_en_carta) 
                    VALUES (:nombre,:descripcion,:dano,:turnos_rondas,:id_zona,:id_icono,:esta_en_carta)";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                ':nombre' => $_POST["nombreEvento"],
                ':descripcion' => $_POST["descripcionEvento"],
                ':dano' => $_POST["danoEvento"],
                ':turnos_rondas' => $_POST["rondasEvento"],
                ':id_zona' => $_POST["zona"],
                ':id_icono' => $_POST["emoticonoEvento"],
                ':esta_en_carta' => 1
            ]);

            return $this->conexion->lastInsertID();

        } catch (PDOException $e) {

            echo "Error: " . $e->getMessage();
        }
    }

    public function modificarEstado($id)
    {
        //Cambiamos el estado de la carta  ejemplos: estado:1 -> 1 - 1 = 0 / estado:0 -> 1 - 0 = 1
        //Siempre cambia el estado
        $sql = "UPDATE eventos 
                SET esta_en_carta = 1 - esta_en_carta
                WHERE id_evento = :id_evento";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':id_evento' => $id
        ]);
    }

    public function obtenerEventosJuego()
    {
        try {
            $id_zona = isset($_GET["zona"]) ? $_GET["zona"] : 0;

            // Consulta unificada: Siempre traemos el fondo específico
            $sql = "SELECT e.*, i.codigo as codigo_icono, z.imagenEventos as fondo_evento 
                    FROM eventos e 
                    LEFT JOIN iconos i ON e.id_icono = i.id_icono
                    LEFT JOIN zonas z ON e.id_zona = z.id_zona";

            if ($id_zona != 0) {
                $sql .= " WHERE e.id_zona = :id_zona";
            }

            $stmt = $this->conexion->prepare($sql);

            if ($id_zona != 0) {
                $stmt->bindParam(':id_zona', $id_zona);
            }

            $stmt->execute();

            $eventos = $stmt->fetchAll();

            header("Content-Type: application/json");
            // 3. Envía el JSON limpio
            echo json_encode($eventos);
            // Para la ejecucion tras enviar la información para js
            exit();

        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }



    public function listarEventos()
    {
        // mostramos nombre, nombre icono, nombre zona, daño, turnos
        $sql = "SELECT eventos.id_evento, eventos.nombre, eventos.dano, eventos.turnos_duracion, 
                       zonas.nombre as nombre_zona, iconos.codigo as codigo_icono
                FROM eventos
                LEFT JOIN zonas ON eventos.id_zona = zonas.id_Zona
                LEFT JOIN iconos ON eventos.id_icono = iconos.id_icono";
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerEvento()
    {
        $idEvento = $_GET['idEvento'] ?? $_POST['idEvento'] ?? null;
        $sql = "SELECT * FROM eventos WHERE id_evento = :id";
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id', $idEvento);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerZonas()
    {
        $sql = "SELECT id_Zona, nombre FROM zonas";
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerIconos()
    {
        $sql = "SELECT id_icono, codigo FROM iconos";
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function modificarEvento($idEvento, $nombre, $descripcion, $dano, $turnos_duracion, $id_zona, $id_icono, &$mensaje)
    {
        try {
            $sql = "UPDATE eventos SET nombre = :nombre, descripcion = :descripcion, 
                    dano = :dano, turnos_duracion = :turnos_duracion, 
                    id_zona = :id_zona, id_icono = :id_icono 
                    WHERE id_evento = :id";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':dano', $dano);
            $stmt->bindParam(':turnos_duracion', $turnos_duracion);
            $stmt->bindParam(':id_zona', $id_zona);
            $stmt->bindParam(':id_icono', $id_icono);
            $stmt->bindParam(':id', $idEvento);

            if ($stmt->execute()) {
                return true;
            } else {
                $mensaje = "Error al actualizar evento.";
                return false;
            }

        } catch (PDOException $e) {
            $mensaje = "Error BD: " . $e->getMessage();
            return false;
        }
    }
    public function eliminarEvento()
    {
        $idEvento = $_GET['idEvento'] ?? $_POST['idEvento'] ?? null;
        try {
            $sql = "DELETE FROM eventos WHERE id_evento = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id', $idEvento);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    public function crearEvento($nombre, $descripcion, $dano, $turnos_duracion, $id_zona, $id_icono, &$mensaje)
    {
        try {
            $sql = "INSERT INTO eventos (nombre, descripcion, dano, turnos_duracion, id_zona, id_icono) 
                    VALUES (:nombre, :descripcion, :dano, :turnos_duracion, :id_zona, :id_icono)";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':dano', $dano);
            $stmt->bindParam(':turnos_duracion', $turnos_duracion);
            $stmt->bindParam(':id_zona', $id_zona);
            $stmt->bindParam(':id_icono', $id_icono);

            if ($stmt->execute()) {
                return true;
            } else {
                $mensaje = "Error al crear el evento.";
                return false;
            }

        } catch (PDOException $e) {
            $mensaje = "Error BD: " . $e->getMessage();
            return false;
        }
    }

}
?>