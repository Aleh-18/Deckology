<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Deckology</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../imagenes/Logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="pagina-publica">
        <header class="header-publico">
            <a href="?c=Usuario&m=mostrarZonas" class="header-logo">Deckology</a>
            <nav class="header-nav">
                <a href="?c=Usuario&m=perfilUsuario" class="header-link" id="link-perfil"><i class="bi bi-person"></i> Perfil</a>
                <a href="?c=Usuario&m=obtenerPuntuaciones" class="header-link"><i class="bi bi-trophy"></i> Puntuaciones</a>
                <a href="#" class="header-link" id="boton-sesion"></a>
            </nav>
        </header>

        <main class="main-publico main-zonas">
            <h2 id="titulo-seleccion">Modos de Juego</h2>
            <p class="subtitulo-zonas">Elige una zona para comenzar a proteger el planeta. Cada zona tiene sus propias cartas y amenazas.</p>
            <div id="boton-como-jugar"><i class="bi bi-question-circle"></i> ¿Cómo jugar?</div>

            <div id="contenedor-tarjetas">
                <?php
                $gridPos = [1 => 'norte', 2 => 'oeste', 3 => 'este', 4 => 'sur'];
                foreach ($datos as $zona):
                    $id = $zona["id"];
                    if ($id == 0) continue;
                    $pos = $gridPos[$id] ?? '';
                ?>
                    <a href="?c=Juego&m=mostrarJuego&id=<?php echo $id; ?>" class="tarjeta pos-<?php echo $pos; ?> tarjeta-modo-normal" data-zona="<?php echo $zona["nombre"]; ?>">
                        <img class="fondo-zona" src="<?php echo $zona["fondoZona"] ?? $zona["imagenZona"]; ?>" alt="" loading="lazy">
                        <div class="tarjeta-contenido">
                            <img class="icono-zona" src="<?php echo $zona["imagenZona"]; ?>" alt="<?php echo $zona["nombre"]; ?>" loading="lazy">
                            <h3 class="titulo-tarjeta"><?php echo $zona["nombre"]; ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>

                <?php foreach ($datos as $zona):
                    if ($zona["id"] != 0) continue;
                ?>
                    <a href="?c=Juego&m=mostrarJuego&id=<?php echo $zona["id"]; ?>" class="tarjeta tarjeta-infinito pos-centro" data-zona="<?php echo $zona["nombre"]; ?>">
                        <img class="fondo-zona" src="<?php echo $zona["fondoZona"] ?? $zona["imagenZona"]; ?>" alt="" loading="lazy">
                        <div class="tarjeta-contenido">
                            <img class="icono-zona" src="<?php echo $zona["imagenZona"]; ?>" alt="<?php echo $zona["nombre"]; ?>" loading="lazy">
                            <h3 class="titulo-tarjeta"><?php echo $zona["nombre"]; ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div id="popup-reglas">
                <div id="popup-contenido">
                    <p class="cerrar-popup"><i class="bi bi-x-lg"></i></p>
                    <h2>¿Cómo se juega?</h2>
                    <p><strong>Objetivo:</strong> Sobrevivir la mayor cantidad de rondas.</p>
                    <h3>&#9881; Eventos</h3>
                    <p>Un nuevo evento aparece <strong>cada turno</strong>.<br>Restan <strong>dano fijo +2 por turno</strong> que sobreviven.<br>Si sobreviven 3+ turnos, reciben <strong>dano extra acumulado</strong>.</p>
                    <h3>&#127183; Cartas</h3>
                    <p>Cada carta cuesta <strong>1 energia</strong>. Tienes <strong>3 energia por turno</strong>.<br>Cura maxima por carta: <strong>15 HP</strong> (el resto se pierde).<br>Mano: <strong>3 iniciales</strong>, maximo <strong>5</strong>. Robas 1 cada 2 turnos.</p>
                    <h3>&#9889; Sacrificio</h3>
                    <p>Descarta una carta para <strong>recuperar 1 energia</strong>.<br>Maximo <strong>1 sacrificio por turno</strong>.</p>
                    <h3>&#128260; Sinergias</h3>
                    <p>2+ cartas con el <strong>mismo icono</strong> en un turno = <strong>+30% curacion</strong>.</p>
                    <h3>&#128260; Turnos y Rondas</h3>
                    <p><strong>3 turnos por ronda</strong>, 5 rondas maximo.<br>El planeta empieza con <strong>50 HP</strong>.</p>
                    <h3>&#128161; Consejos</h3>
                    <p>&#8226; No dejes eventos vivir mas de 2 turnos.<br>&#8226; Sacrifica cartas que no sirven para recuperar energia.<br>&#8226; Aprovecha sinergias para curar mas.<br>&#8226; Cada carta cuenta: el maximo de curacion es 15.</p>
                </div>
            </div>
        </main>

        <footer class="footer-publico">
            <p class="footer-texto">Deckology es un proyecto educativo del ciclo de Desarrollo de Aplicaciones Web para concienciar sobre la proteccion del medioambiente a traves de un juego de cartas interactivo.</p>
            <p class="footer-copy">Proyecto educativo - DAW</p>
        </footer>
    </div>
    <script>
        window.estaLogueado = <?php echo (isset($_SESSION['idUsuario'])) ? 'true' : 'false'; ?>;
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const botonComoJugar = document.getElementById('boton-como-jugar');
            const popup = document.getElementById('popup-reglas');
            const botonCerrar = popup.querySelector('.cerrar-popup');
            botonComoJugar.addEventListener('click', () => { popup.style.display = 'block'; });
            if (botonCerrar) { botonCerrar.addEventListener('click', () => { popup.style.display = 'none'; }); }
            const enlacePerfil = document.getElementById('link-perfil');
            const botonSesion = document.getElementById('boton-sesion');
            const logueado = window.estaLogueado;
            if (logueado) {
                botonSesion.textContent = 'Cerrar Sesión';
                botonSesion.addEventListener('click', (e) => { e.preventDefault(); window.location.href = '?c=Usuario&m=cerrarSesion'; });
            } else {
                if (enlacePerfil) enlacePerfil.style.display = 'none';
                botonSesion.textContent = 'Iniciar Sesión';
                botonSesion.addEventListener('click', (e) => { e.preventDefault(); window.location.href = '?c=Usuario&m=mostrarLogin'; });
            }
        });
    </script>
</body>
</html>
