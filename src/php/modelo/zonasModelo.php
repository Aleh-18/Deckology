<?php
require_once __DIR__ . "/conexion.php";

class Zona extends Conexion
{


    public function obtenerZona($id)
    {
        $sql = "SELECT * FROM zonas WHERE id_zona = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function obtenerZonas()
    {
        $sql = "SELECT z.id_zona, z.nombre,
                       z.imagenZona, z.imagenCartas, z.imagenEventos, z.fondoZona,
                       COUNT(DISTINCT c.id_carta) AS NumCartas,
                       COUNT(DISTINCT e.id_evento) AS NumEventos
                FROM zonas z
                LEFT JOIN cartas c ON z.id_zona = c.id_zona
                LEFT JOIN eventos e ON z.id_zona = e.id_zona
                GROUP BY z.id_zona, z.nombre";
        $resultado = $this->conexion->query($sql);
        return $resultado->fetchAll();
    }

    public function insertar($nombre, $imagenZona, $fondoZona, $imagenCarta, $imagenEvento)
    {
        $sql = "INSERT INTO zonas (nombre, imagenZona, fondoZona, imagenCartas, imagenEventos)
                VALUES (:nombre, :imagenZona, :fondoZona, :imagenCarta, :imagenEvento)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':imagenZona' => $imagenZona,
            ':fondoZona' => $fondoZona,
            ':imagenCarta' => $imagenCarta,
            ':imagenEvento' => $imagenEvento
        ]);
    }

    public function actualizar($id, $nombre, $imagenZona = null, $fondoZona = null, $imagenCarta = null, $imagenEvento = null)
    {
        $sql = "UPDATE zonas SET nombre = :nombre";
        $params = [':nombre' => $nombre, ':id' => $id];

        if ($imagenZona !== null) {
            $sql .= ", imagenZona = :imagenZona";
            $params[':imagenZona'] = $imagenZona;
        }
        if ($fondoZona !== null) {
            $sql .= ", fondoZona = :fondoZona";
            $params[':fondoZona'] = $fondoZona;
        }
        if ($imagenCarta !== null) {
            $sql .= ", imagenCartas = :imagenCarta";
            $params[':imagenCarta'] = $imagenCarta;
        }
        if ($imagenEvento !== null) {
            $sql .= ", imagenEventos = :imagenEvento";
            $params[':imagenEvento'] = $imagenEvento;
        }

        $sql .= " WHERE id_zona = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute($params);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM zonas WHERE id_zona = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function obtenerZonasTodas()
    {
        try {
            $sql = "SELECT id_zona as id, nombre, imagenZona, imagenCartas, imagenEventos, fondoZona FROM zonas";

            $stmt = $this->conexion->query($sql);

            return $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;

        }
    }
}
