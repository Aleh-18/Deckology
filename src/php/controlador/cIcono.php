<?php
require_once __DIR__ . "/../modelo/mIcono.php";
class CIcono {

        private $modelo;
        public $nombreVista = "";

        public function __construct() {
            $this->modelo = new MIcono();
        }

        private function verificarAdmin()
        {
            if (!isset($_SESSION['idAdmin'])) {
                header("Location: index.php?c=Admin&m=vistaLoginAdmin");
                exit();
            }
        }

        public function listarIconos() {
            $this->verificarAdmin();
            $this->nombreVista = "lIconos";
            return $this->modelo->obtenerIconos();
        }

        public function eliminarIcono() {
            $this->verificarAdmin();
            $id = $_GET['id'] ?? 0;
            $this->modelo->eliminarIcono($id);
            $this->nombreVista = "lIconos";
            return $this->modelo->obtenerIconos();
        }
        
        public function mostrarModificarIcono(){
            $this->verificarAdmin();
            $id = $_GET['id'];
            $icono = $this->modelo->mostrarModificarIcono($id);
            $this->nombreVista = "modificarIcono";
            return ['icono' => $icono];
        }

        public function modificarIcono(){
            $this->verificarAdmin();
            $id = $_GET['id'];
            $nombre = $_POST['nombre'];
            $codigo = $_POST['codigo'];
            
            if(empty($nombre) || empty($codigo)) {
                $this->nombreVista = "modificarIcono";
                return ['error' => 'Error: Todos los campos son obligatorios', 'icono' => $this->modelo->mostrarModificarIcono($id)];
            }

            $resultado = $this->modelo->modificarIcono($id, $nombre, $codigo);
            
            if($resultado) {
                header("Location: index.php?c=Icono&m=listarIconos");
                exit();
            } else {
                $this->nombreVista = "modificarIcono";
                return ['error' => 'Error al modificar', 'icono' => $this->modelo->mostrarModificarIcono($id)];
            }
        }

        public function vistaCrearIcono() {
            $this->verificarAdmin();
            $this->nombreVista = "crearIcono";
        }

        public function crearIcono(){
            $this->verificarAdmin();
            $nombre = $_POST['nombre'];
            $codigo = $_POST['codigo'];

            if(empty($nombre) || empty($codigo)){
                $this->nombreVista = "crearIcono";
                return ['error' => 'Error: Todos los campos son obligatorios'];
            }

            $resultado = $this->modelo->crearIcono($nombre, $codigo);

            if($resultado){
                header("Location: index.php?c=Icono&m=listarIconos");
                exit();
            } else {
                $this->nombreVista = "crearIcono";
                return ['error' => 'Error al crear icono'];
            }
        }
    
}
?>