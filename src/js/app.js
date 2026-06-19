var juego = null;
var vista = null;
var zonaActualId = "0";
var _cargado = false;

function ocultarLoading() {
    if (_cargado) return;
    _cargado = true;
    var el = document.getElementById('loading-screen');
    if (el) {
        el.classList.add('fade-out');
        setTimeout(function() { el.style.display = 'none'; }, 400);
    }
}

function mostrarError(msg) {
    console.error('[Deckology]', msg);
    ocultarLoading();
    var zona = document.querySelector('.zona-mano');
    if (zona) {
        zona.innerHTML = '<div style="color:#ff8a8a;text-align:center;padding:40px 20px;">' +
            '<p style="font-size:40px;margin-bottom:12px;">&#9888;</p>' +
            '<p style="font-weight:700;font-size:16px;">' + msg + '</p>' +
            '<p style="font-size:13px;color:#888;margin-top:8px;">Recarga la pagina para intentar de nuevo.</p>' +
            '</div>';
    }
}

function fetchJSON(url) {
    console.log('[Deckology] Fetch:', url);
    return fetch(url).then(function(res) {
        console.log('[Deckology] Response:', url, 'status=' + res.status, 'type=' + res.headers.get('content-type'));
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.text();
    }).then(function(text) {
        try {
            return JSON.parse(text);
        } catch(e) {
            console.error('[Deckology] JSON parse error. Response text:', text.substring(0, 300));
            throw new Error('JSON invalido: ' + e.message);
        }
    });
}

function iniciarJuego() {
    console.log('[Deckology] iniciarJuego() called');
    var parametrosURL = new URLSearchParams(window.location.search);
    zonaActualId = parametrosURL.get('id') || '0';
    console.log('[Deckology] zonaActualId =', zonaActualId);

    var cartasData, eventosData, zonasData;

    fetchJSON('index.php?c=Carta&m=obtenerCartasJuego&zona=' + zonaActualId)
    .then(function(data) {
        console.log('[Deckology] Cartas cargadas:', data.length);
        cartasData = data;
        return fetchJSON('index.php?c=Evento&m=obtenerEventosJuego&zona=' + zonaActualId);
    })
    .then(function(data) {
        console.log('[Deckology] Eventos cargados:', data.length);
        eventosData = data;
        return fetchJSON('index.php?c=Zona&m=obtenerZonas');
    })
    .then(function(data) {
        console.log('[Deckology] Zonas cargadas:', data.length);
        zonasData = data;

        var zonaObj = null;
        for (var i = 0; i < zonasData.length; i++) {
            if (String(zonasData[i].id) === String(zonaActualId)) {
                zonaObj = zonasData[i];
                break;
            }
        }
        console.log('[Deckology] zonaObj:', zonaObj ? zonaObj.nombre : 'NOT FOUND');

        var datosIniciales = {
            zonaInicial: zonaActualId,
            zonaObj: zonaObj,
            cartas: {},
            eventos: {}
        };
        datosIniciales.cartas[zonaActualId] = cartasData;
        datosIniciales.eventos[zonaActualId] = eventosData;

        console.log('[Deckology] Creando MotorJuego...');
        juego = new MotorJuego(datosIniciales);

        console.log('[Deckology] Creando VistaJuego...');
        vista = new VistaJuego();

        console.log('[Deckology] Robando cartas iniciales...');
        for (var c = 0; c < 5; c++) {
            juego.robarCarta();
        }
        console.log('[Deckology] Mano inicial:', juego.mano.length, 'cartas');

        console.log('[Deckology] Generando evento inicial...');
        juego.generarEvento();
        console.log('[Deckology] Eventos activos:', juego.eventosActivos.length);

        vista.actualizarInterfaz(juego, eventosData);

        console.log('[Deckology] Juego iniciado correctamente');
        if (window.DeckologySound) window.DeckologySound.setupVolumeControl();
        ocultarLoading();
    })
    .catch(function(e) {
        console.error('[Deckology] Error en iniciarJuego:', e);
        mostrarError('Error al cargar el juego: ' + e.message);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('[Deckology] DOMContentLoaded fired');
    var btnJugar = document.getElementById('btn-jugar');
    var btnPasar = document.getElementById('btn-pasar');
    console.log('[Deckology] btnJugar:', btnJugar ? 'found' : 'NOT FOUND');
    console.log('[Deckology] btnPasar:', btnPasar ? 'found' : 'NOT FOUND');

    if (btnPasar) {
        btnPasar.addEventListener('click', function() {
            if (!juego) return;
            if (window.DeckologySound) window.DeckologySound.uiClick();

            var resultado = juego.avanzarTurno();

            if (window.DeckologySound) {
                window.DeckologySound.turnAdvance();
                if (resultado.dano > 0) setTimeout(function() { window.DeckologySound.damage(); }, 200);
                if (resultado.eventos > 0 && resultado.eventosExpirados === 0 && Math.random() < 0.5) {
                    setTimeout(function() { window.DeckologySound.newEvent(); }, 300);
                }
            }

            if (vista) {
                vista.mostrarTurnoInfo(
                    resultado.dano || 0,
                    resultado.curacion || 0,
                    resultado.balance || 0
                );
                vista.actualizarInterfaz(juego, juego.catalogoEventos[zonaActualId]);
            }
            verificarFinJuego(resultado);
        });
    }

    if (btnJugar) {
        btnJugar.addEventListener('click', function() {
            if (!juego || !vista) return;
            if (window.DeckologySound) window.DeckologySound.uiClick();

            var cartasAJugar = vista.obtenerCartasSeleccionadas();
            if (cartasAJugar.length === 0) {
                vista.showFloatingText('Selecciona una carta', '#E8883A');
                return;
            }

            var resultados = [];
            for (var i = 0; i < cartasAJugar.length; i++) {
                var r = juego.usarCarta(cartasAJugar[i]);
                if (r.exito !== false) resultados.push(r);
            }

            if (resultados.length === 0) {
                vista.showFloatingText('Sin energia', '#ff6b6b');
                if (window.DeckologySound) window.DeckologySound.noEnergy();
                return;
            }

            var hayNeutralizacion = resultados.some(function(r) { return r.neutralizo; });
            var hayCuracion = resultados.some(function(r) { return r.curacion > 0; });

            vista.mostrarJugarAnimacion(resultados);

            if (window.DeckologySound) {
                window.DeckologySound.playCard();
                if (hayNeutralizacion) setTimeout(function() { window.DeckologySound.neutralize(); }, 150);
                else if (hayCuracion) setTimeout(function() { window.DeckologySound.heal(); }, 150);
            }

            var estado = juego.avanzarTurno();
            vista.mostrarTurnoInfo(
                estado.dano || 0,
                estado.curacion || 0,
                estado.balance || 0
            );

            vista.limpiarSeleccion();
            vista.actualizarInterfaz(juego, juego.catalogoEventos[zonaActualId]);
            verificarFinJuego(estado);
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        if (e.key === 'Enter' && btnJugar) btnJugar.click();
        if ((e.key === 'e' || e.key === 'E') && btnPasar) btnPasar.click();
    });

    window.actualizarBotonJugar = function() {
        if (!vista || !btnJugar) return;
        var sel = vista.obtenerCartasSeleccionadas();
        var tieneEnergia = juego && juego.energia > 0;
        btnJugar.classList.toggle('alerta', sel.length > 0 && tieneEnergia);
        btnJugar.disabled = !tieneEnergia && sel.length > 0;
    };

    console.log('[Deckology] Starting iniciarJuego()...');
    iniciarJuego();
});

setTimeout(function() {
    if (!_cargado) {
        console.error('[Deckology] TIMEOUT: Loading screen still visible after 5s, forcing hide');
        ocultarLoading();
    }
}, 5000);

function verificarFinJuego(estado) {
    if (!juego) return;
    if (juego.vidaPlaneta <= 0) {
        var puntuacionFinal = null;
        if (juego.esInfinito) {
            puntuacionFinal = juego.calcularPuntuacionFinal();
            if (window.api) {
                api.guardarPuntuacion(puntuacionFinal)
                    .then(function(res) { console.log("Puntuacion guardada", res); })
                    .catch(function(err) { console.error("Error al guardar puntuacion", err); });
            }
        }
        vista.mostrarGameOver("GAME OVER", "El ecosistema ha colapsado. Intenta de nuevo.", false, puntuacionFinal);
        return;
    }
    if (estado && estado.victoria) {
        var puntuacionVictoria = juego.calcularPuntuacionFinal();
        if (window.api) {
            api.guardarPuntuacion(puntuacionVictoria)
                .then(function(res) { console.log("Puntuacion guardada", res); })
                .catch(function(err) { console.error("Error al guardar puntuacion", err); });
        }
        vista.mostrarGameOver("VICTORIA!", estado.mensaje, true, puntuacionVictoria);
    }
}
