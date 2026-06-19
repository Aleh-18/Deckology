<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Deckology - Pantalla de Acceso</title>
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
        <a href="?c=Admin&m=vistaLoginAdmin" class="header-link">Administrador</a>
      </nav>
    </header>

    <main class="main-publico">
      <div class="seccion-logo">
        <img src="../imagenes/Logo.png" alt="Logo Deckology" class="logo">
        <p class="subtitulo">Aprende a proteger el planeta mientras juegas</p>
      </div>
      <div class="caja-login">
        <div class="botones-login">
          <a href="?c=Usuario&m=mostrarLogin" class="boton"><i class="bi bi-box-arrow-in-right"></i> Iniciar sesion</a>
          <a href="?c=Usuario&m=mostrarRegistro" class="boton"><i class="bi bi-person-plus"></i> Registrarse</a>
        </div>
        <a href="?c=Usuario&m=mostrarZonas">
          <button class="boton-invitado"><i class="bi bi-play-circle"></i> Entrar como Invitado</button>
        </a>
      </div>
    </main>

    <footer class="footer-publico">
      <p class="footer-texto">Deckology es un proyecto educativo del ciclo de Desarrollo de Aplicaciones Web para concienciar sobre la proteccion del medioambiente a traves de un juego de cartas interactivo.</p>
      <p class="footer-copy">Proyecto educativo - DAW</p>
    </footer>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const botonInvitado = document.querySelector('.boton-invitado');
      if (botonInvitado) {
        botonInvitado.addEventListener('click', () => {
          localStorage.setItem('estaLogueado', false);
        });
      }
    });
  </script>
</body>
</html>
