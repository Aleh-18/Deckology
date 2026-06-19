<?php
require_once __DIR__ . "/../modelo/zonasModelo.php";

class CZona
{
    public $nombreVista = "";
    private $modelo;
    public $datos = null;

    public function __construct()
    {

        $this->modelo = new Zona();
    }

    public function listar()
    {
        $this->verificarAdmin();
        $this->datos = $this->modelo->obtenerZonas();
        $this->nombreVista = "listarZonas";
        return $this->datos;
    }

    public function mostrarCrear()
    {
        $this->verificarAdmin();
        $this->nombreVista = "crearZonas";
    }

    private function guardarImagen($campo, $subdir)
    {
        if (empty($_FILES[$campo]['tmp_name'])) return null;
        $nombre = basename($_FILES[$campo]['name']);
        $rutaRel = "../imagenes/$subdir/$nombre";
        $rutaAbs = __DIR__ . "/../$rutaRel";
        $dir = dirname($rutaAbs);
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        move_uploaded_file($_FILES[$campo]['tmp_name'], $rutaAbs);
        return $rutaRel;
    }

    public function crear()
    {
        $this->verificarAdmin();
        $nombre = $_POST['nombre'];
        $imagenZona = $this->guardarImagen('imagenZona', 'zonas');
        $fondoZona = $this->guardarImagen('fondoZona', 'fondo');
        $imagenCarta = $this->guardarImagen('imagenCartas', 'cartas');
        $imagenEvento = $this->guardarImagen('imagenEventos', 'eventos');
        $this->modelo->insertar($nombre, $imagenZona, $fondoZona, $imagenCarta, $imagenEvento);
        header("Location: index.php?c=Zona&m=listar");
        exit;
    }

    public function mostrarEditar()
    {
        $this->verificarAdmin();
        $idZona = $_GET['id_zona'] ?? 0;
        if ((int)$idZona === 0) { header("Location: index.php?c=Zona&m=listar"); exit; }
        $this->datos = $this->modelo->obtenerZona($idZona);
        $this->nombreVista = "modificarZonas";
        return $this->datos;
    }

    public function editar()
    {
        $this->verificarAdmin();
        $idZona = $_GET['id_zona'] ?? 0;
        $nombre = $_POST['nombre'];

        $datosZona = $this->modelo->obtenerZona($idZona);

        $imagenZona = $this->guardarImagen('imagenZona', 'zonas');

        if (!empty($_POST['borrarFondo'])) {
            $fondoZona = "";
        } else {
            $fondoZona = $this->guardarImagen('fondoZona', 'fondo');
        }

        if (!empty($_POST['borrarCarta'])) {
            if (!empty($datosZona['imagenCartas'])) {
                $ruta = __DIR__ . '/../' . ltrim($datosZona['imagenCartas'], '/');
                if (file_exists($ruta)) unlink($ruta);
            }
            $imagenCarta = "";
        } else {
            $imagenCarta = $this->guardarImagen('imagenCartas', 'cartas');
        }

        if (!empty($_POST['borrarEvento'])) {
            if (!empty($datosZona['imagenEventos'])) {
                $ruta = __DIR__ . '/../' . ltrim($datosZona['imagenEventos'], '/');
                if (file_exists($ruta)) unlink($ruta);
            }
            $imagenEvento = "";
        } else {
            $imagenEvento = $this->guardarImagen('imagenEventos', 'eventos');
        }

        $this->modelo->actualizar($idZona, $nombre, $imagenZona, $fondoZona, $imagenCarta, $imagenEvento);
        header("Location: index.php?c=Zona&m=listar");
        exit;
    }

    public function confirmarEliminar()
    {
        $this->verificarAdmin();
        $idZona = $_GET['id_zona'] ?? 0;
        if ((int)$idZona === 0) { header("Location: index.php?c=Zona&m=listar"); exit; }
        $this->datos = $this->modelo->obtenerZona($idZona);
        $this->nombreVista = "confirmarEliminar";
        return $this->datos;
    }

    public function eliminar()
    {
        $this->verificarAdmin();
        $idZona = $_GET['id_zona'] ?? 0;
        if ((int)$idZona === 0) { header("Location: index.php?c=Zona&m=listar"); exit; }
        $datosZona = $this->modelo->obtenerZona($idZona);

        foreach (['imagenCartas', 'imagenEventos', 'imagenZona', 'fondoZona'] as $campo) {
            if (!empty($datosZona[$campo])) {
                $ruta = __DIR__ . '/../' . ltrim($datosZona[$campo], '/');
                if (file_exists($ruta)) unlink($ruta);
            }
        }

        $this->modelo->eliminar($idZona);
        header("Location: index.php?c=Zona&m=listar");
        exit;
    }
    public function listarFront()
    {
        $this->datos = $this->modelo->obtenerZonasTodas();
        $this->nombreVista = "zonas";
        return $this->datos;
    }

    public function obtenerZonas()
    {

        $eventos = $this->modelo->obtenerZonasTodas();

        header("Content-Type: application/json");
        echo json_encode($eventos);
        exit();

    }
    private function verificarAdmin()
    {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: index.php?c=Admin&m=vistaLoginAdmin");
            exit();
        }
    }
}
