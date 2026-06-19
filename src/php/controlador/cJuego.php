<?php 
require_once __DIR__ . "/../modelo/conexion.php";

class CJuego extends Conexion {
    
    public $nombreVista;

    public function __construct()
    {
        parent::__construct();
    }

    public function mostrarJuego(){
        $zonaId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $base = '/Deckology/src';

        $sqlCartas = "SELECT c.*, i.codigo as codigo_icono, z.imagenCartas as fondo_carta 
                      FROM cartas c 
                      LEFT JOIN iconos i ON c.id_icono = i.id_icono
                      LEFT JOIN zonas z ON c.id_zona = z.id_zona";
        if ($zonaId != 0) {
            $sqlCartas .= " WHERE c.id_zona = :id_zona";
        }
        $stmt = $this->conexion->prepare($sqlCartas);
        if ($zonaId != 0) {
            $stmt->bindParam(':id_zona', $zonaId);
        }
        $stmt->execute();
        $cartas = $stmt->fetchAll();

        foreach ($cartas as &$c) {
            if (!empty($c['fondo_carta'])) {
                $c['fondo_carta'] = $base . '/' . ltrim($c['fondo_carta'], './');
            }
        }
        unset($c);

        $sqlEventos = "SELECT e.*, i.codigo as codigo_icono, z.imagenEventos as fondo_evento 
                       FROM eventos e 
                       LEFT JOIN iconos i ON e.id_icono = i.id_icono
                       LEFT JOIN zonas z ON e.id_zona = z.id_zona";
        if ($zonaId != 0) {
            $sqlEventos .= " WHERE e.id_zona = :id_zona";
        }
        $stmt2 = $this->conexion->prepare($sqlEventos);
        if ($zonaId != 0) {
            $stmt2->bindParam(':id_zona', $zonaId);
        }
        $stmt2->execute();
        $eventos = $stmt2->fetchAll();

        foreach ($eventos as &$e) {
            if (!empty($e['fondo_evento'])) {
                $e['fondo_evento'] = $base . '/' . ltrim($e['fondo_evento'], './');
            }
        }
        unset($e);

        $sqlZonas = "SELECT id_zona as id, nombre, imagenZona, imagenCartas, imagenEventos, fondoZona FROM zonas";
        $stmt3 = $this->conexion->query($sqlZonas);
        $zonas = $stmt3->fetchAll();

        foreach ($zonas as &$z) {
            foreach (['imagenZona', 'imagenCartas', 'imagenEventos', 'fondoZona'] as $campo) {
                if (!empty($z[$campo])) {
                    $z[$campo] = $base . '/' . ltrim($z[$campo], './');
                }
            }
        }
        unset($z);

        $this->nombreVista = "tablero";

        return [
            'zonaId' => $zonaId,
            'cartas' => $cartas,
            'eventos' => $eventos,
            'zonas' => $zonas
        ];
    }  
}
?>
