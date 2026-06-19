<?php
// instalar.php
// Script para instalar la base de datos y autoborrarse

// 1. Comprobamos si existe el archivo sql
$archivoSQL = __DIR__ . '/database.sql';

if (!file_exists($archivoSQL)) {
    die("<h1>Error: No se encuentra database.sql</h1><p>Por favor, coloca el archivo de exportación SQL en <code>" . $archivoSQL . "</code> y vuelve a intentarlo.</p><a href='index.php'>Volver</a>");
}

try {
    // Configuración por defecto
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbName = 'deckology';

    // Si hay archivo de config lo usamos
    $configFile = __DIR__ . '/../php/config/configdb.php';
    if (file_exists($configFile)) {
        include $configFile;

        if (defined('SERVIDOR'))
            $host = SERVIDOR;
        if (defined('USUARIO'))
            $user = USUARIO;
        if (defined('PASSWORD'))
            $pass = PASSWORD;
        if (defined('BBDD'))
            $dbName = BBDD;
    }

    // Conexión sin DB para poder crearla si no existe
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear la base de datos si no esta
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbName`");

    // Pillamos el archivo sql
    $sql = file_get_contents($archivoSQL);

    // Ejecutamos las querys
    $pdo->exec($sql);

    // --- Crear admin ---
    if (isset($_POST['nombre'], $_POST['email'], $_POST['contrasena'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $contrasenaAdmin = $_POST['contrasena'];

        // Encriptar contraseña 
        $passCifrada = password_hash($contrasenaAdmin, PASSWORD_DEFAULT);

        // Meter admin en la base de datos
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contrasena, rol, puntuacion) VALUES (:nombre, :email, :pass, 'admin', 0)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $passCifrada);
        $stmt->execute();

        echo "<p>Usuario administrador '$nombre' creado correctamente.</p>";
    }

    echo "<h1>Instalación Completada con Éxito</h1>";
    echo "<p>La base de datos se ha configurado correctamente.</p>";

    // Funcion para borrar la carpeta
    function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }

    $installDir = __DIR__;

    // Redirección para ir al inicio
    echo "<p>Borrando archivos de instalación...</p>";
    echo "<meta http-equiv='refresh' content='3;url=../php/index.php'>";

    // Forzar que salga todo
    flush();

    // Borrar todo
    deleteDirectory($installDir);

} catch (PDOException $e) {
    die("<h1>Error de Instalación</h1><p>" . $e->getMessage() . "</p><a href='index.php'>Volver</a>");
}
?>