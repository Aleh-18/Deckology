<?php
if (is_dir('instalador')) {
    header("Location: instalador/index.php");
    exit();
}
header("Location: php/index.php");
?>