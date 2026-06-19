<?php
require_once __DIR__ . "/conexion.php";

class MCarta extends Conexion
{

    public function buscarCarta($id = null)
    {
        try {
            if ($id === null) $id = $_GET['id'] ?? 0;
            $sql = "SELECT * FROM cartas WHERE id_carta = :id";

            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([':id' => $id]);

            return $stmt->fetch();

        } catch (PDOException $e) {
            echo "Error al buscar la carta: " . $e->getMessage();
            return false;
        }
    }

    public function crearCarta($id)
    {

        try {

            $sql = "INSERT INTO  cartas (nombre,curacion,elimina_id_evento,descripcion,id_zona,id_icono) 
                    VALUES (:nombre,:curacion,:elimina_id_evento,:descripcion,:id_zona,:id_icono)";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([

                ':nombre' => $_POST["nombre"],
                ':curacion' => $_POST["curacion"],
                ':elimina_id_evento' => $id,
                ':descripcion' => $_POST["descripcion"],
                ':id_zona' => $_POST["zona"],
                ':id_icono' => $_POST["emoticono"]
            ]);

            return $stmt;

        } catch (PDOException $e) {

            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function modificarCarta($id_evento)
    {
        try {
            $idCarta = $_GET['id'] ?? $_POST['id_carta'] ?? 0;
            $sql = "UPDATE cartas
                    SET nombre = :nombre, descripcion = :descripcion, curacion = :curacion,
                        id_zona = :id_zona, elimina_id_evento = :elimina_id_evento, id_icono = :id_icono
                    WHERE id_carta = :id_carta";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                ':nombre' => $_POST["nombre"],
                ':descripcion' => $_POST["descripcion"],
                ':curacion' => $_POST["curacion"],
                ':id_zona' => $_POST["zona"],
                ':elimina_id_evento' => $id_evento,
                ':id_icono' => $_POST["emoticono"],
                ':id_carta' => $idCarta
            ]);
            return $stmt;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function eliminarCarta($id = null)
    {
        if ($id === null) $id = $_GET['id'] ?? 0;
        $sql = "DELETE FROM cartas WHERE id_carta = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    public function obtenerCartas()
    {

        try {

            $sql = "SELECT c.id_carta, c.nombre, c.curacion, z.nombre as zona, e.nombre as evento, i.codigo as icono
                    FROM cartas c
                    INNER JOIN zonas z on c.id_zona = z.id_zona 
                    LEFT JOIN eventos e on c.elimina_id_evento = e.id_evento
                    INNER JOIN iconos i on c.id_icono = i.id_icono";

            $stmt = $this->conexion->query($sql);

            return $stmt->fetchAll();

        } catch (PDOException $e) {

            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerCartasJuego()
    {
        try {
            $id_zona = isset($_GET["zona"]) ? $_GET["zona"] : 0;

            // Consulta unificada: Siempre traemos el fondo específico
            $sql = "SELECT c.*, i.codigo as codigo_icono, z.imagenCartas as fondo_carta 
                    FROM cartas c 
                    LEFT JOIN iconos i ON c.id_icono = i.id_icono
                    LEFT JOIN zonas z ON c.id_zona = z.id_zona";

            if ($id_zona != 0) {
                $sql .= " WHERE c.id_zona = :id_zona";
            }

            $stmt = $this->conexion->prepare($sql);

            if ($id_zona != 0) {
                $stmt->bindParam(':id_zona', $id_zona);
            }

            $stmt->execute();

            $cartas = $stmt->fetchAll();
            //Describir el tipo de archivo
            header("Content-Type: application/json");
            // 3. Envía el JSON limpio
            echo json_encode($cartas);
            // Para la ejecucion tras enviar la información para js
            exit();

        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
?>