<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deckology - Registro</title>
    <link rel="icon" type="image/png" href="../imagenes/Logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="pagina-publica">
        <header class="header-publico">
            <a href="?c=Usuario&m=mostrarAcceso" class="header-logo">Deckology</a>
            <nav class="header-nav">
                <a href="?c=Usuario&m=mostrarLogin" class="header-link">Volver</a>
            </nav>
        </header>

        <main class="main-publico">
            <form id="formRegistro" class="formulario-base" action="index.php?c=Usuario&m=registrar" method="post">
                <h2>Crear Cuenta</h2>
                <p>Unete a la comunidad Deckology</p>
                <label>Nombre:</label>
                <input type="text" name="usuario" id="nombre" placeholder="Tu nombre" required>
                <div id="errorNombre" class="errorFormulario"></div>

                <label>Correo Electronico:</label>
                <input type="email" name="email" id="email" placeholder="tu@email.com" required>
                <div id="errorEmail" class="errorFormulario"></div>

                <label>Contrasena:</label>
                <input type="password" name="password" id="password" placeholder="Minimo 6 caracteres" required>
                <div id="errorPassword" class="errorFormulario"></div>

                <label>Confirmar Contrasena:</label>
                <input type="password" name="password2" id="password2" placeholder="Repite tu contrasena" required>
                <div id="errorPassword2" class="errorFormulario"></div>
                <?php
                if (isset($datos['error'])) {
                    echo "<div class='errorFormulario'>" . $datos['error'] . "</div>";
                }
                ?>
                <div class="division-con-flex">
                    <a href="?c=Usuario&m=mostrarLogin" class="boton cancelar"><i class="bi bi-arrow-left"></i> Cancelar</a>
                    <button type="submit" class="boton enviar"><i class="bi bi-person-check"></i> Crear Cuenta</button>
                </div>

                <p class="texto-ayuda">
                    Ya tienes cuenta?
                    <a href="index.php?c=Usuario&m=mostrarLogin">Inicia sesion</a>
                </p>
            </form>
        </main>

        <footer class="footer-publico">
            <p class="footer-texto">Deckology es un proyecto educativo del ciclo de Desarrollo de Aplicaciones Web para concienciar sobre la proteccion del medioambiente a traves de un juego de cartas interactivo.</p>
            <p class="footer-copy">Proyecto educativo - DAW</p>
        </footer>
    </div>
    <script src="../js/vista/registro.js"></script>
</body>
</html>
