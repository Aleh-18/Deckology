<?php
require_once __DIR__ . "/../modelo/mCarta.php";
require_once __DIR__ . "/../modelo/zonasModelo.php";
require_once __DIR__ . "/../modelo/mIcono.php";
require_once __DIR__ . "/../modelo/mEvento.php";

class CCarta
{
    private $carta;
    private $zona;
    private $icono;
    private $evento;
    public $nombreVista;

    public function __construct()
    {


        $this->carta = new MCarta();
        $this->evento = new MEvento();
        $this->zona = new Zona();
        $this->icono = new MIcono();

    }

    public function mostrarCrearCarta()
    {
        $this->verificarAdmin();
        $zonas = $this->zona->obtenerZonasTodas();
        $iconos = $this->icono->obtenerIconos();
        $this->nombreVista = "crearCartas";
        return [
            "zonas" => $zonas,
            "iconos" => $iconos
        ];
    }

    public function mostrarModificarCarta()
    {
        $this->verificarAdmin();
        $idCarta = $_GET['id'] ?? 0;
        $zonas = $this->zona->obtenerZonasTodas();
        $iconos = $this->icono->obtenerIconos();
        $carta = $this->carta->buscarCarta($idCarta);
        $this->nombreVista = "modificarCartas";
        return [
            "zonas" => $zonas,
            "iconos" => $iconos,
            "carta" => $carta
        ];
    }

    public function listarCartas()
    {
        $this->verificarAdmin();

        $this->nombreVista = "lCartas";
        return $this->carta->obtenerCartas();
    }

    public function crearCarta()
    {
        $this->verificarAdmin();

        if (!$this->validarCarta()) {
            header("Location:index.php?c=Carta&m=mostrarCrearCarta");
            return;
        }

        $id = null;

        if ($_POST["creandoEventoNuevo"] === "1") {

            if ($this->validarEvento()) {
                $id = $this->evento->crearEventoId();
            } else {
                header("Location:index.php?c=Carta&m=mostrarCrearCarta");
                return;
            }
        } else {
            $id = $_POST["evento"];
            $this->evento->modificarEstado($id);
        }
        $this->carta->crearCarta($id);

        $this->nombreVista = "lCartas";
        return $this->carta->obtenerCartas();
    }

    public function modificarCarta()
    {
        $this->verificarAdmin();

        $idCarta = $_GET['id'] ?? $_POST['id_carta'] ?? 0;
        $id_evento_antiguo = $_POST['idEventoAntiguo'] ?? null;
        $id_evento_nuevo = null;

        if (!$this->validarCarta()) {
            header('Location: index.php?c=Carta&m=mostrarModificarCarta&id=' . $idCarta);
            return;
        }

        if ($_POST["creandoEventoNuevo"] === "1") {
            if ($this->validarEvento()) {
                $id_evento_nuevo = $this->evento->crearEventoId();
            } else {
                header('Location: index.php?c=Carta&m=mostrarModificarCarta&id=' . $idCarta);
                return;
            }
        } else {
            $id_evento_nuevo = $_POST["evento"] !== '' ? $_POST["evento"] : null;
        }

        if (!empty($id_evento_antiguo) && $id_evento_antiguo != $id_evento_nuevo) {
            $this->evento->modificarEstado($id_evento_antiguo);
        }

        $resultado = $this->carta->modificarCarta($id_evento_nuevo);

        if (!empty($id_evento_nuevo)) {
            $this->evento->modificarEstado($id_evento_nuevo);
        }

        if ($resultado > 0) {
            $this->nombreVista = "lCartas";
            return $this->carta->obtenerCartas();
        } else {
            header('Location: index.php?c=Carta&m=mostrarModificarCarta&id=' . $idCarta);
            return;
        }
    }

    public function eliminarCarta()
    {
        $this->verificarAdmin();
        $idCarta = $_GET['id'] ?? 0;
        $this->carta->eliminarCarta($idCarta);
        header("Location: index.php?c=Carta&m=listarCartas");
    }

    /* ======== JUEGO - CARTAS ======== */
    public function obtenerCartasJuego()
    {

        $this->carta->obtenerCartasJuego();

        exit();
    }
    /* ======== VALIDACIONES ======== */

    private function validarCarta()
    {

        $camposRequeridos = [
            'nombre',
            'descripcion',
            'curacion',
            'emoticono'
        ];
        $hayError = false;

        foreach ($camposRequeridos as $campo) {

            // Puede venir como array (p. ej. multi-select)
            $valor = isset($_POST[$campo]) ? $_POST[$campo] : '';

            if (is_array($valor)) {

                if (empty($valor)) {
                    $hayError = true;
                }

            } else {

                if (empty(trim($valor))) {
                    $hayError = true;
                }
            }
        }

        return !$hayError;
    }

    private function validarEvento()
    {

        $camposRequeridos = [
            'nombreEvento',
            'descripcionEvento',
            'danoEvento',
            'emoticonoEvento'
        ];
        $hayError = false;

        foreach ($camposRequeridos as $campo) {

            // Puede venir como array (p. ej. multi-select)
            $valor = isset($_POST[$campo]) ? $_POST[$campo] : '';

            if (is_array($valor)) {

                if (empty($valor)) {
                    $hayError = true;
                }

            } else {

                if (empty(trim($valor))) {
                    $hayError = true;
                }
            }
        }

        return !$hayError;
    }

    private function verificarAdmin()
    {
        if (!isset($_SESSION['idAdmin'])) {
            header("Location: index.php?c=Admin&m=vistaLoginAdmin");
            exit();
        }
    }

}
