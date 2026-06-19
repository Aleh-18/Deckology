<?php
require_once(__DIR__."/../config/configdb.php");

class Conexion{

    protected $conexion;

    public function __construct(){

        try {

            $dsn ="mysql:host=".SERVIDOR.";dbname=".BBDD.";charset=utf8mb4";
            $this->conexion= new PDO($dsn,USUARIO,PASSWORD);

        } catch (PDOException $e) {

            die("Error de conexion:".$e->getMessage());
        }
        
    }


    public function __destruct(){

        $this->conexion = null;

    }
}