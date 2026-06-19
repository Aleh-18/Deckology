<?php
require_once __DIR__ . "/../modelo/mEvento.php";

class CEvento
{


    private $evento;

    public function __construct()
    {
        $this->evento = new MEvento();
    }


    public function obtenerEventos()
    {


        $eventos = $this->evento->obtenerEventos();


        header("Content-Type: application/json");

        echo json_encode($eventos);

        exit();
    }

    /* ======== JUEGO - EVENTOS ======== */
    public function obtenerEventosJuego()
    {

        $this->evento->obtenerEventosJuego();

        exit();
    }


    private $mEventos;



    private function verificarAdmin()
    {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: index.php?c=Admin&m=vistaLoginAdmin");
            exit();
        }
    }

    public function vistaListarEventos()
    {
        $this->verificarAdmin();
        if (!isset($this->mEventos)) {
            $this->mEventos = new MEvento();
        }
        $this->nombreVista = "lEventos";
        return $this->mEventos->listarEventos();
    }

    public function vistaModificarEvento()
    {
        $this->verificarAdmin();
        if (!isset($this->mEventos)) {
            $this->mEventos = new MEvento();
        }
        $idEvento = $_GET['idEvento'] ?? $_POST['idEvento'] ?? null;
        $this->nombreVista = "modificarEvento";
        return [
            'evento' => $this->mEventos->obtenerEvento($idEvento),
            'zonas' => $this->mEventos->obtenerZonas(),
            'iconos' => $this->mEventos->obtenerIconos()
        ];
    }

    public function vistaCrearEvento()
    {
        $this->verificarAdmin();
        if (!isset($this->mEventos)) {
            $this->mEventos = new MEvento();
        }
        $this->nombreVista = "crearEvento";
        return [
            'zonas' => $this->mEventos->obtenerZonas(),
            'iconos' => $this->mEventos->obtenerIconos()
        ];
    }

    public function procesarModificarEvento()
    {
        $this->verificarAdmin();
        if (!isset($this->mEventos)) {
            $this->mEventos = new MEvento();
        }
        $idEvento = $_POST['idEvento'];

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $dano = $_POST['dano'];
        $turnos_duracion = $_POST['turnos_duracion'];
        $id_zona = $_POST['id_zona'];
        $id_icono = $_POST['id_icono'];

        $mensaje = "";

        if (empty($nombre) || empty($dano) || empty($turnos_duracion) || empty($id_zona)) {
            $this->nombreVista = "modificarEvento";
            return ['mensaje' => 'Error: Campos obligatorios vacíos'] + $this->vistaModificarEvento();
        }

        $resultado = $this->mEventos->modificarEvento($idEvento, $nombre, $descripcion, $dano, $turnos_duracion, $id_zona, $id_icono, $mensaje);

        if ($resultado) {
            header("Location: index.php?c=Evento&m=vistaListarEventos");
            exit();
        } else {
            $this->nombreVista = "modificarEvento";
            return ['mensaje' => $mensaje] + $this->vistaModificarEvento();
        }
    }
    public function procesarEliminarEvento()
    {
        $this->verificarAdmin();
        if (!isset($this->mEventos)) {
            $this->mEventos = new MEvento();
        }
        $idEvento = $_GET['idEvento'];

        if (!empty($idEvento)) {
            $this->mEventos->eliminarEvento($idEvento);
        }

        header("Location: index.php?c=Evento&m=vistaListarEventos");
        exit();
    }

    public function procesarCrearEvento()
    {
        $this->verificarAdmin();
        if (!isset($this->mEventos)) {
            $this->mEventos = new MEvento();
        }

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $dano = $_POST['dano'];
        $turnos_duracion = $_POST['turnos_duracion'];
        $id_zona = $_POST['id_zona'];
        $id_icono = $_POST['id_icono'];

        $mensaje = "";

        if (empty($nombre) || empty($dano) || empty($turnos_duracion) || empty($id_zona)) {
            $this->nombreVista = "crearEvento";
            return ['mensaje' => 'Error: Campos obligatorios vacíos'];
        }

        $resultado = $this->mEventos->crearEvento($nombre, $descripcion, $dano, $turnos_duracion, $id_zona, $id_icono, $mensaje);

        if ($resultado) {
            header("Location: index.php?c=Evento&m=vistaListarEventos");
            exit();
        } else {
            $this->nombreVista = "crearEvento";
            return ['mensaje' => $mensaje];
        }
    }

}
?>
