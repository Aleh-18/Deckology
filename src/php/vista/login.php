<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deckology - Iniciar Sesión</title>
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
                <a href="?c=Usuario&m=mostrarAcceso" class="header-link">Volver</a>
            </nav>
        </header>

        <main class="main-publico">
            <form id="formLogin" class="formulario-base" action="index.php?c=Usuario&m=login" method="post">
                <h2>Iniciar Sesión</h2>
                <p>Accede a tu cuenta de Deckology</p>
                <label for="email">Correo Electronico:</label>
                <input type="email" name="email" id="email" placeholder="tu@email.com" required>
                <div id="errorEmail" class="errorFormulario"></div>

                <label for="password">Contrasena:</label>
                <div class="contenedor-password">
                    <input type="password" name="password" id="password" placeholder="Tu contrasena" required>
                    <span id="iconoContrasena"></span>
                </div>
                <div id="errorPassword" class="errorFormulario"></div>
                <?php
                if (isset($datos['error'])) {
                    echo "<div class='errorFormulario'>" . $datos['error'] . "</div>";
                }
                ?>
                <?php
                if (!empty($_SESSION['oauth_error'])) {
                    echo "<div class='errorFormulario'>" . $_SESSION['oauth_error'] . "</div>";
                    unset($_SESSION['oauth_error']);
                }
                ?>

                <a class="boton-login-google" href="index.php?c=Usuario&m=iniciarSesionGoogle">
                    <img class="logo-google" src="../imagenes/google-logo.svg" alt="Google">
                    Continuar con Google
                </a>
                <div class="division-con-flex">
                    <a href="?c=Usuario&m=mostrarAcceso" class="boton cancelar"><i class="bi bi-arrow-left"></i> Cancelar</a>
                    <button type="submit" class="boton enviar" id="enviar"><i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión</button>
                </div>
                <a href="index.php?c=Usuario&m=mostrarRecuperarContrasena" class="enlace-olvido">Olvidaste tu contrasena?</a>
                <p class="texto-ayuda">
                    No tienes una cuenta?
                    <a href="index.php?c=Usuario&m=mostrarRegistro">Registrate aqui</a>
                </p>
            </form>
        </main>

        <footer class="footer-publico">
            <p class="footer-texto">Deckology es un proyecto educativo del ciclo de Desarrollo de Aplicaciones Web para concienciar sobre la proteccion del medioambiente a traves de un juego de cartas interactivo.</p>
            <p class="footer-copy">Proyecto educativo - DAW</p>
        </footer>
    </div>
    <script src="../js/vista/login.js"></script>
</body>
</html>
