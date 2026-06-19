<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Instalación Deckology</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="card">
        <h1>Instalación</h1>
        <img src="../imagenes/Logo.png" alt="Logo" class="logo">
        <p>Bienvenido al asistente de instalación de la aplicación Deckology<br>Rellene los datos para generar el primer
            administrador</p>
        <form action="instalar.php" method="post">
            <h3>Crear Administrador</h3>

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" required>
            </div>

            <button type="submit" class="btn">Instalar y Crear Admin</button>
        </form>
        <div class="status">
            <p><span class="warning">Atención : </span>Se borrará esta carpeta al finalizar.</p>
        </div>
    </div>
</body>

</html>