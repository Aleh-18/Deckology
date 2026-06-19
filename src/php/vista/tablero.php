<?php
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$jsonJuego = isset($datos) ? json_encode($datos) : '{}';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deckology - Juego</title>
    <link rel="icon" type="image/png" href="../imagenes/Logo.png">
    <link rel="stylesheet" href="../css/estiloTablero.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body id="tablero">

    <div id="loading-screen">
        <div class="loading-content">
            <div class="loading-logo">Deckology</div>
            <div class="loading-spinner"></div>
            <p class="loading-text">Preparando el ecosistema...</p>
        </div>
    </div>

    <header class="barra-juego">
        <div class="barra-izquierda">
            <a href="index.php?c=Usuario&m=mostrarZonas" class="btn-volver"><i class="bi bi-arrow-left"></i> Salir</a>
            <span class="logo-texto">Deckology</span>
        </div>
        <div class="barra-centro">
            <span id="nombre-zona" class="zona-actual"></span>
        </div>
        <div class="barra-derecha">
            <div class="music-control">
                <button type="button" class="volumen-btn" id="btn-music-prev" title="Anterior"><i class="bi bi-skip-start"></i></button>
                <span id="track-name" class="track-name"></span>
                <button type="button" class="volumen-btn" id="btn-music-toggle" title="Play/Pausa"><i class="bi bi-play-fill"></i></button>
                <button type="button" class="volumen-btn" id="btn-music-next" title="Siguiente"><i class="bi bi-skip-end"></i></button>
                <div class="volumen-control" style="margin-left:6px;border-left:1px solid rgba(255,255,255,0.06);padding-left:6px;">
                    <button type="button" class="volumen-btn" id="btn-mute-music" title="Silenciar musica"><i class="bi bi-music-note-beamed"></i></button>
                    <input type="range" id="volumen-slider-music" class="volumen-slider" min="0" max="100" value="25">
                    <span class="vol-label">Musica</span>
                </div>
            </div>
            <div class="volumen-control">
                <button type="button" class="volumen-btn" id="btn-mute-sfx" title="Silenciar sonidos"><i class="bi bi-soundwave"></i></button>
                <input type="range" id="volumen-slider-sfx" class="volumen-slider" min="0" max="100" value="25">
                <span class="vol-label">SFX</span>
            </div>
            <span id="texto-ronda" class="badge-ronda">Ronda 1</span>
            <span id="texto-turno" class="badge-turno">Turno 1/3</span>
            <span id="combo-display" class="badge-combo" style="display:none;">x1</span>
        </div>
    </header>

    <div class="barra-vida-h">
        <div class="vida-h-info">
            <span class="vida-h-icono">&#127793;</span>
            <span id="vida-numero" class="vida-h-numero">100</span>
            <span class="vida-h-label">HP</span>
        </div>
        <div class="vida-h-track">
            <div class="vida-h-restante" id="vida-restante"></div>
        </div>
    </div>

    <div class="contenedor" id="game-board">
        <div id="bg-overlay"></div>
        <div id="particles-container"></div>

        <div class="zona-superior">
            <h3 class="seccion-titulo" id="titulo-eventos">Amenazas</h3>
            <div class="fila-eventos" id="lista-eventos"></div>
        </div>

        <div class="zona-centro">
            <div class="zona-mano">
                <h3 class="seccion-titulo">Tu Mano</h3>
                <div class="lista-mano"></div>
            </div>

            <div class="panel-lateral" id="panel-info">
                <div class="panel-seccion">
                    <h4 class="panel-seccion-titulo">Selección</h4>
                    <div id="panel-seleccion"><p class="panel-vacio">Sin seleccion</p></div>
                </div>
                <div class="panel-seccion">
                    <h4 class="panel-seccion-titulo">Eventos Activos</h4>
                    <div id="panel-eventos-lista"><p class="panel-vacio">Sin eventos</p></div>
                </div>
            </div>
        </div>

        <div class="barra-acciones">
            <div class="acciones-izquierda">
                <div class="energia-display" id="energia-display">
                    <span class="energia-label">Energia</span>
                    <div class="energia-diamantes" id="energia-diamantes">
                        <span class="diamante activo"></span>
                        <span class="diamante activo"></span>
                        <span class="diamante activo"></span>
                    </div>
                    <span class="energia-texto" id="energia-texto">3/3</span>
                </div>
                <button type="button" class="btn-accion btn-sacrificio" id="btn-sacrificar" title="Descartar 1 carta para +1 energia"><i class="bi bi-recycle"></i> Sacrificio</button>
            </div>
            <div class="turno-info" id="turno-info"></div>
            <div class="acciones-derecha">
                <button type="button" class="btn-accion btn-pasar" id="btn-pasar"><i class="bi bi-skip-forward"></i> Pasar Turno</button>
                <button type="button" class="btn-accion btn-jugar" id="btn-jugar"><i class="bi bi-play-fill"></i> Jugar Carta</button>
            </div>
        </div>
    </div>

    <div id="overlay-resultado" class="overlay-juego">
        <div class="modal-resultado">
            <div id="icono-resultado" class="modal-icono"></div>
            <h2 id="titulo-resultado" class="modal-titulo">RESULTADO</h2>
            <p id="mensaje-resultado" class="modal-mensaje">Mensaje</p>
            <p id="puntuacion-resultado" class="modal-puntuacion" style="display:none;">0 Puntos</p>
            <div class="modal-botones">
                <button onclick="location.reload()" class="btn-accion btn-jugar"><i class="bi bi-arrow-repeat"></i> Jugar de Nuevo</button>
                <a href="index.php?c=Usuario&m=mostrarZonas" class="btn-accion btn-salir"><i class="bi bi-house"></i> Menu</a>
            </div>
        </div>
    </div>

    <audio id="bg-music" src="../sonidos/bgmusic.wav" preload="auto"></audio>

    <script>var DATOS_JUEGO = <?php echo $jsonJuego; ?>;</script>
    <!-- Cache busting en scripts: ?v=6 fuerza al navegador a recargar versiones actualizadas -->
    <script src="../js/sonidos.js?v=<?php echo time(); ?>"></script>
    <script src="../js/api.js?v=<?php echo time(); ?>"></script>
    <script src="../js/musica.js?v=<?php echo time(); ?>"></script>
    <script src="../js/modelo/motor.js?v=<?php echo time(); ?>"></script>
    <script src="../js/vista/vJuego.js?v=<?php echo time(); ?>"></script>
    <script>
    /**
     * Inicializacion del juego Deckology.
     * Se ejecuta en un IIFE (Immediately Invoked Function Expression)
     * para no contaminar el scope global. Todo esta envuelto en try-catch
     * para mostrar errores en pantalla si algo falla.
     * 
     * Flujo:
     *   1. Leer datos embebidos de PHP (DATOS_JUEGO)
     *   2. Crear MotorJuego (logica) y VistaJuego (render)
     *   3. Repartir cartas iniciales + generar primer evento
     *   4. Renderizar el tablero
     *   5. Configurar event listeners (botones, teclado)
     *   6. Ocultar pantalla de carga
     */
    (function() {
        try {
        var datos = window.DATOS_JUEGO;

        // Validar que los datos del juego existen
        if (!datos || !datos.cartas || !datos.eventos) {
            document.getElementById('loading-screen').innerHTML =
                '<div class="loading-content"><p class="loading-text" style="color:#ff8a8a;">Error: No se pudieron cargar los datos del juego.</p></div>';
            return;
        }

        // Obtener ID de zona (Infinito = "0", otras = "1","2","3","4")
        var zonaId = String(datos.zonaId || 0);

        // ── Crear MotorJuego (logica del juego) ──
        window.juego = new MotorJuego({
            zonaInicial: zonaId,
            zonaObj: (function() {
                for (var i = 0; i < (datos.zonas || []).length; i++) {
                    if (String(datos.zonas[i].id) === zonaId) return datos.zonas[i];
                }
                return null;
            })(),
            cartas: {},
            eventos: {}
        });
        // Cargar cartas y eventos de la zona en el catalogo del motor
        window.juego.catalogoCartas[zonaId] = datos.cartas;
        window.juego.catalogoEventos[zonaId] = datos.eventos;

        // ── Crear VistaJuego (render y efectos visuales) ──
        window.vista = new VistaJuego();

        // ── Repartir cartas iniciales y primer evento ──
        for (var c = 0; c < window.juego.config.cartasIniciales; c++) { window.juego.robarCarta(); }
        window.juego.generarEvento();
        // Segundo evento si ya estamos en turno 2+
        if (window.juego.turnoTotal >= 2) window.juego.generarEvento();
        // Render inicial del tablero
        window.vista.actualizarInterfaz(window.juego, datos.eventos);

        // ── Ocultar pantalla de carga con transicion ──
        var loadingEl = document.getElementById('loading-screen');
        loadingEl.style.transition = 'opacity 0.3s ease';
        loadingEl.style.opacity = '0';
        loadingEl.style.pointerEvents = 'none';
        setTimeout(function() { loadingEl.style.display = 'none'; }, 300);

        // ── Inicializar reproductor de musica ──
        MusicPlayer.init();
        MusicPlayer.play();
        document.getElementById('btn-music-toggle').addEventListener('click', function() { MusicPlayer.toggle(); });
        document.getElementById('btn-music-next').addEventListener('click', function() { MusicPlayer.next(); });
        document.getElementById('btn-music-prev').addEventListener('click', function() { MusicPlayer.prev(); });
        // Control de volumen de musica
        var musicSlider = document.getElementById('volumen-slider-music');
        if (musicSlider) {
            musicSlider.value = Math.round((MusicPlayer.getVolume() || 0.25) * 100);
            musicSlider.addEventListener('input', function() { MusicPlayer.setVolume(this.value / 100); });
        }
        document.getElementById('btn-mute-music').addEventListener('click', function() { MusicPlayer.toggleMute(); });

        // ── Control de volumen de efectos de sonido ──
        if (window.DeckologySound) window.DeckologySound.setupVolumeControl();

        // ── Boton "Jugar Carta" ──
        // Juega todas las cartas seleccionadas, avanza turno, comprueba fin
        document.getElementById('btn-jugar').addEventListener('click', function() {
            if (!window.juego || !window.vista) return;
            if (window.DeckologySound) window.DeckologySound.uiClick();
            var sel = window.vista.obtenerCartasSeleccionadas();
            if (sel.length === 0) {
                window.vista.showFloatingText('Selecciona una carta', '#E8883A');
                return;
            }

            // Ejecutar cada carta seleccionada en el motor
            var res = [];
            for (var i = 0; i < sel.length; i++) {
                var r = window.juego.usarCarta(sel[i]);
                if (r.exito !== false) res.push(r);
            }

            if (res.length === 0) {
                window.vista.showFloatingText('Sin energia', '#ff6b6b');
                if (window.DeckologySound) window.DeckologySound.noEnergy();
                return;
            }

            // Animaciones y sonidos segun resultado
            var hayNeutralizacion = res.some(function(r) { return r.neutralizo; });
            var hayCuracion = res.some(function(r) { return r.curacion > 0; });

            window.vista.mostrarJugarAnimacion(res);

            if (window.DeckologySound) {
                window.DeckologySound.playCard();
                if (hayNeutralizacion) setTimeout(function() { window.DeckologySound.neutralize(); }, 150);
                else if (hayCuracion) setTimeout(function() { window.DeckologySound.heal(); }, 150);
            }

            // Avanzar turno y actualizar interfaz
            var st = window.juego.avanzarTurno();
            window.vista.mostrarTurnoInfo(st.dano || 0, st.curacion || 0, st.balance || 0);
            window.vista.limpiarSeleccion();
            window.vista.actualizarInterfaz(window.juego, datos.eventos);
            verificarFin(st);
        });

        // ── Boton "Pasar Turno" ──
        // Avanza turno sin jugar cartas
        document.getElementById('btn-pasar').addEventListener('click', function() {
            if (!window.juego) return;
            if (window.DeckologySound) window.DeckologySound.uiClick();
            var st = window.juego.avanzarTurno();
            if (window.DeckologySound) {
                window.DeckologySound.turnAdvance();
                if (st.dano > 0) setTimeout(function() { window.DeckologySound.damage(); }, 200);
            }
            window.vista.mostrarTurnoInfo(st.dano || 0, st.curacion || 0, st.balance || 0);
            window.vista.actualizarInterfaz(window.juego, datos.eventos);
            verificarFin(st);
        });

        // ── Atajos de teclado ──
        // Enter = jugar carta, E = pasar turno
        document.addEventListener('keydown', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            if (e.key === 'Enter') document.getElementById('btn-jugar').click();
            if (e.key === 'e' || e.key === 'E') document.getElementById('btn-pasar').click();
        });

        // ── Actualizar texto del boton Jugar ──
        // Muestra "Jugar 2 cartas (2/3E)" segun cartas seleccionadas y energia
        window.actualizarBotonJugar = function() {
            var b = document.getElementById('btn-jugar');
            if (!window.vista || !b) return;
            var sel = window.vista.obtenerCartasSeleccionadas();
            var energia = window.juego ? window.juego.energia : 0;
            var n = sel.length;
            if (n === 0) {
                b.textContent = 'Jugar Carta';
                b.classList.remove('alerta');
                b.disabled = false;
            } else {
                var usar = Math.min(n, energia);
                b.textContent = 'Jugar ' + usar + ' carta' + (usar > 1 ? 's' : '') + ' (' + usar + '/' + energia + 'E)';
                b.classList.add('alerta');
                b.disabled = usar === 0;
            }
        };

        // ── Comprobar fin de partida ──
        function verificarFin(st) {
            if (!window.juego) return;
            // Game Over: vida <= 0
            if (window.juego.vidaPlaneta <= 0) {
                var pf = window.juego.esInfinito ? window.juego.calcularPuntuacionFinal() : null;
                if (pf !== null && window.api) api.guardarPuntuacion(pf).catch(function() {});
                window.vista.mostrarGameOver("GAME OVER", "El ecosistema ha colapsado. Intenta de nuevo.", false, pf);
            // Victoria: todas las rondas completadas
            } else if (st && st.victoria) {
                var pv = window.juego.calcularPuntuacionFinal();
                if (window.api) api.guardarPuntuacion(pv).catch(function() {});
                window.vista.mostrarGameOver("VICTORIA!", st.mensaje, true, pv);
            }
        }

        } catch(e) {
            // Capturar errores de inicializacion y mostrarlos en pantalla
            console.error('[Deckology] INIT ERROR:', e);
            var el = document.getElementById('loading-screen');
            if (el) el.innerHTML = '<div class="loading-content"><p class="loading-text" style="color:#ff8a8a;">Error: ' + e.message + '</p></div>';
        }
    })();
    </script>
</body>
</html>
