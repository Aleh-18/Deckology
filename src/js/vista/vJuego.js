/**
 * VistaJuego - Capa de presentacion del juego
 * 
 * Se encarga de TODO lo visual: renderizar cartas, eventos, paneles,
 * barra de vida, energia, efectos de particulas, animaciones, sonidos.
 * NO contiene logica de juego - solo lee el estado de MotorJuego
 * y lo dibuja en el DOM.
 * 
 * Flujo de render:
 *   actualizarInterfaz() -> leer estado -> renderMano() + renderEventos()
 *                              + actualizarVida() + actualizarEnergiaUI()
 *                              + actualizarPanelLateral()
 */
class VistaJuego {

    constructor() {
        // ── Paletas de colores por zona ──
        // Se aplican como CSS variables al root para theming dinamico
        // Definidas en prototype para compatibilidad con navegadores antiguos
        VistaJuego.prototype.ZONE_PALETTES = VistaJuego.prototype.ZONE_PALETTES || {
            'Bosque':    { '--zone-bg':'#0D3B2E','--zone-header':'rgba(13,59,46,0.95)','--zone-panel':'rgba(13,59,46,0.94)','--zone-card':'rgba(20,60,45,0.94)','--zone-actions':'rgba(10,35,25,0.92)','--zone-accent':'#289F5C','--zone-accent-light':'#6ECB8C' },
            'Ciudad':    { '--zone-bg':'#1A1428','--zone-header':'rgba(26,20,40,0.95)','--zone-panel':'rgba(26,20,40,0.94)','--zone-card':'rgba(38,30,55,0.94)','--zone-actions':'rgba(18,12,28,0.92)','--zone-accent':'#A06CCE','--zone-accent-light':'#C898E8' },
            'Mar':       { '--zone-bg':'#0A1E3A','--zone-header':'rgba(10,30,58,0.95)','--zone-panel':'rgba(10,30,58,0.94)','--zone-card':'rgba(16,42,75,0.94)','--zone-actions':'rgba(6,18,35,0.92)','--zone-accent':'#2A7FBF','--zone-accent-light':'#5AAFDF' },
            'Desierto':  { '--zone-bg':'#2A1A0A','--zone-header':'rgba(42,26,10,0.95)','--zone-panel':'rgba(42,26,10,0.94)','--zone-card':'rgba(60,38,16,0.94)','--zone-actions':'rgba(28,16,6,0.92)','--zone-accent':'#D48A3A','--zone-accent-light':'#ECA85A' },
            'Infinito':  { '--zone-bg':'#1A1608','--zone-header':'rgba(26,22,8,0.95)','--zone-panel':'rgba(26,22,8,0.94)','--zone-card':'rgba(40,34,14,0.94)','--zone-actions':'rgba(16,14,6,0.92)','--zone-accent':'#E8C84A','--zone-accent-light':'#F0DC80' }
        };

        // ── Referencias a elementos del DOM ──
        // Se cachean en el constructor para no buscarlos cada frame
        this.contenedorMano = document.querySelector('.lista-mano');
        this.contenedorEventos = document.getElementById('lista-eventos');
        this.panelSeleccion = document.getElementById('panel-seleccion');
        this.panelEventosLista = document.getElementById('panel-eventos-lista');
        this.vidaRestante = document.getElementById('vida-restante');
        this.textoVida = document.getElementById('vida-numero');
        this.zonaNombre = document.getElementById('nombre-zona');
        this.textoRonda = document.getElementById('texto-ronda');
        this.textoTurno = document.getElementById('texto-turno');
        this.comboDisplay = document.getElementById('combo-display');
        this.turnoInfo = document.getElementById('turno-info');
        this.gameBoard = document.getElementById('game-board');
        this.particles = document.getElementById('particles-container');
        this.bgOverlay = document.getElementById('bg-overlay');
        this.tituloEventos = document.getElementById('titulo-eventos');
        this.energiaDiamantes = document.getElementById('energia-diamantes');
        this.energiaTexto = document.getElementById('energia-texto');
        this.btnSacrificar = document.getElementById('btn-sacrificar');
        this.temaAplicado = false;

        // ── Estado de la vista ──
        this.cartasSeleccionadas = [];   // Cartas que el jugador ha seleccionado
        this.zonaObj = null;             // Objeto zona actual (para imagenes)
        this.catalogoEventosActual = []; // Catalogo de eventos de la zona
        this.eventosActivosRef = [];     // Referencia a eventos activos (para el panel)
        this.vidaAnterior = undefined;   // Vida del frame anterior (para animaciones)
        this.energiaActual = 3;          // Energia actual del jugador
        this.energiaMaxima = 3;          // Energia maxima
        this.modoSacrificio = false;     // Si esta en modo sacrifice (rojo)

        // Evento click del boton Sacrificar
        var self = this;
        if (this.btnSacrificar) {
            this.btnSacrificar.addEventListener('click', function() {
                self.toggleModoSacrificio();
            });
        }
    }

    /**
     * Aplica la paleta de colores de una zona al DOM.
     * Modifica las CSS variables del :root para que todo el tablero
     * se adapte visualmente a la zona seleccionada.
     */
    aplicarTemaZona(nombreZona) {
        var paleta = (VistaJuego.prototype.ZONE_PALETTES || {})[nombreZona];
        if (!paleta) return;
        var root = document.documentElement;
        var keys = Object.keys(paleta);
        for (var i = 0; i < keys.length; i++) {
            root.style.setProperty(keys[i], paleta[keys[i]]);
        }
        this.temaAplicado = true;
    }

    /**
     * Metodo principal de render. Recibe el estado completo del juego
     * y actualiza TODOS los elementos visuales.
     * 
     * @param {Object} estado - Estado de MotorJuego (vida, mano, eventos, etc.)
     * @param {Array} catalogoEventos - Array de eventos de la zona (para buscar nombres)
     */
    actualizarInterfaz(estado, catalogoEventos) {
        this.catalogoEventosActual = catalogoEventos;
        var vidaActual = estado.vidaPlaneta;

        // Actualizar energia
        if (typeof estado.energia !== 'undefined') {
            this.energiaActual = estado.energia;
        }
        if (typeof estado.energiaMaxima !== 'undefined') {
            this.energiaMaxima = estado.energiaMaxima;
        }
        this.actualizarEnergiaUI();

        // Actualizar tema visual de zona (solo 1 vez)
        if (estado.zonaObj) {
            this.zonaObj = estado.zonaObj;
            this.zonaNombre.textContent = this.zonaObj.nombre || '';
            if (!this.temaAplicado) this.aplicarTemaZona(this.zonaObj.nombre);
            // Aplicar fondo de zona
            var rutaFondo = this.zonaObj.fondoZona || this.zonaObj.imagenZona;
            if (rutaFondo && this.bgOverlay) {
                this.bgOverlay.style.backgroundImage = "url('" + rutaFondo + "')";
            }
        }

        // Actualizar barra de vida (con animacion si cambio)
        this.actualizarVida(vidaActual, this.vidaAnterior);
        this.vidaAnterior = vidaActual;

        // Calcular ronda y turno actual (3 turnos por ronda)
        var ronda = Math.ceil(estado.turnoTotal / 3);
        var turnoDeRonda = (estado.turnoTotal - 1) % 3 + 1;
        this.textoRonda.textContent = "Ronda " + ronda;
        this.textoTurno.textContent = "Turno " + turnoDeRonda + "/3";

        // Mostrar/ocultar badge de combo
        if (typeof estado.combo !== 'undefined' && estado.combo > 0) {
            this.comboDisplay.style.display = '';
            this.comboDisplay.textContent = "x" + estado.comboMultiplier.toFixed(1);
            // Re-trigger animacion
            this.comboDisplay.style.animation = 'none';
            void this.comboDisplay.offsetWidth;
            this.comboDisplay.style.animation = 'comboPop 0.3s ease';
        } else if (typeof estado.combo !== 'undefined') {
            this.comboDisplay.style.display = 'none';
        }

        // Renderizar cartas y eventos
        this.eventosActivosRef = estado.eventosActivos || [];
        this.renderMano(estado.mano);
        this.renderEventos(estado.eventosActivos);
        this.actualizarPanelLateral();
    }

    /**
     * Actualiza la barra de vida horizontal.
     * Calcula el porcentaje contra vidaMaxima (no contra 100).
     * En el primer frame (valorAnterior === undefined) no anima.
     * Si la vida bajo: shake de pantalla + particulas de dano.
     * Si la vida subio: flash verde + particulas de curacion.
     */
    actualizarVida(valor, valorAnterior) {
        var maxVida = window.juego ? window.juego.config.vidaMaxima : 50;
        var pct = Math.max(0, Math.min(100, (valor / maxVida) * 100));

        // Primer frame: saltar sin transicion
        if (valorAnterior === undefined) {
            this.vidaRestante.style.transition = 'none';
            this.vidaRestante.style.width = pct + '%';
            void this.vidaRestante.offsetWidth;
            this.vidaRestante.style.transition = '';
        } else {
            this.vidaRestante.style.width = pct + '%';
        }

        this.textoVida.textContent = valor;

        // Color de la barra segun porcentaje de vida
        this.vidaRestante.classList.remove('baja', 'media');
        if (pct < 30) {
            this.vidaRestante.classList.add('baja');
            this.textoVida.style.color = '#ff6b6b';
        } else if (pct < 60) {
            this.vidaRestante.classList.add('media');
            this.textoVida.style.color = '#E8C84A';
        } else {
            this.textoVida.style.color = '';
        }

        // Efectos visuales si la vida cambio
        if (valorAnterior !== undefined && valor !== valorAnterior) {
            if (valor < valorAnterior) {
                // Dano: shake + particulas rojas
                var dano = valorAnterior - valor;
                this.shakeScreen(dano > 20 ? 'fuerte' : 'suave');
                this.spawnDamageParticles(dano);
                this.flashDamage();
            } else {
                // Curacion: flash verde + particulas verdes
                var cura = valor - valorAnterior;
                this.spawnHealParticles(cura);
                this.flashHeal();
            }
        }
    }

    /**
     * Actualiza los diamantes de energia y el indicador de sacrificio.
     * Los diamantes se pintan activos o gastados segun energia actual.
     * El boton de sacrificio se deshabilita si ya se uso este turno.
     */
    actualizarEnergiaUI() {
        if (!this.energiaDiamantes || !this.energiaTexto) return;

        // Renderizar diamantes (activos = verdes, gastados = grises)
        var html = '';
        for (var i = 0; i < this.energiaMaxima; i++) {
            var clase = i < this.energiaActual ? 'diamante activo' : 'diamante gastado';
            html += '<span class="' + clase + '"></span>';
        }
        this.energiaDiamantes.innerHTML = html;
        this.energiaTexto.textContent = this.energiaActual + '/' + this.energiaMaxima;

        // Color del texto de energia segun cuanta queda
        if (this.energiaActual <= 1) {
            this.energiaTexto.style.color = '#ff6b6b'; // Rojo = peligro
        } else if (this.energiaActual === 2) {
            this.energiaTexto.style.color = '#E8C84A'; // Amarillo = bajo
        } else {
            this.energiaTexto.style.color = ''; // Verde = normal
        }

        // Estado del boton Sacrificar
        if (this.btnSacrificar) {
            if (window.juego && window.juego.sacrificiosRealizados >= window.juego.config.sacrificioMaxPorTurno) {
                this.btnSacrificar.classList.add('usado');
                this.btnSacrificar.disabled = true;
            } else {
                this.btnSacrificar.classList.remove('usado');
                this.btnSacrificar.disabled = false;
            }
        }

        // Actualizar texto del boton Jugar
        if (window.actualizarBotonJugar) window.actualizarBotonJugar();
    }

    /**
     * Activa o desactiva el modo Sacrificio.
     * El jugador luego hace click en la carta que quiere sacrificar.
     */
    toggleModoSacrificio() {
        if (window.juego && window.juego.sacrificiosRealizados >= window.juego.config.sacrificioMaxPorTurno) {
            if (window.DeckologySound) window.DeckologySound.noEnergy();
            this.showFloatingText('Ya sacrificaste este turno', '#ff6b6b');
            return;
        }
        if (!window.juego || !window.juego.mano || window.juego.mano.length === 0) {
            if (window.DeckologySound) window.DeckologySound.noEnergy();
            this.showFloatingText('Sin cartas para sacrificar', '#ff6b6b');
            return;
        }

        // Si ya esta en modo sacrificio, cancelar
        if (this.modoSacrificio) {
            this._salirModoSacrificio();
            return;
        }

        // Limpiar seleccion y entrar en modo sacrificio
        this.cartasSeleccionadas = [];
        this.modoSacrificio = true;
        this.btnSacrificar.textContent = 'Sacrificio (+1E)';
        this.btnSacrificar.style.background = 'rgba(200,60,60,0.4)';
        this.btnSacrificar.style.color = '#ff9999';
        this.btnSacrificar.style.borderColor = 'rgba(200,60,60,0.6)';
        if (this.contenedorMano) this.contenedorMano.classList.add('modo-sacrificio');
        this.renderMano(window.juego ? window.juego.mano : []);
        this.actualizarPanelLateral();
        if (window.actualizarBotonJugar) window.actualizarBotonJugar();
    }

    /** Sale del modo sacrificio restaurando estilos */
    _salirModoSacrificio() {
        this.modoSacrificio = false;
        if (this.contenedorMano) this.contenedorMano.classList.remove('modo-sacrificio');
        if (this.btnSacrificar) {
            this.btnSacrificar.textContent = 'Sacrificio';
            this.btnSacrificar.style.background = '';
            this.btnSacrificar.style.color = '';
            this.btnSacrificar.style.borderColor = '';
        }
        if (window.actualizarBotonJugar) window.actualizarBotonJugar();
    }

    /**
     * Renderiza la mano del jugador.
     * Destruye y recrea todos los elementos DOM de las cartas.
     */
    renderMano(mano) {
        this.contenedorMano.innerHTML = '';
        var self = this;
        mano.forEach(function(carta, index) {
            self.crearCartaBuena(carta, index);
        });
    }

    /**
     * Crea el DOM de una carta de mano con todos sus elementos:
     * imagen de fondo, overlay, frame, contenido (nombre, icono, curacion,
     * evento que neutraliza, descripcion), badge de sinergia, check mark.
     * 
     * Estructura visual de una carta:
     *   .carta-mano
     *     .carta-img (fondo de zona)
     *     .carta-overlay (oscurece la imagen)
     *     .carta-frame (borde decorativo)
     *     .check-mark (check verde al seleccionar)
     *     .carta-content
     *       .titulo-carta (nombre)
     *       .icono-carta (emoji del icono)
     *       .valor-badge (+X HP)
     *       .match-tag (evento que neutraliza)
     *       .carta-desc (descripcion educativa)
     *     .sinergia-badge (S naranja si hay sinergia)
     */
    crearCartaBuena(carta, index) {
        var div = document.createElement('div');
        div.classList.add('carta-mano');
        div.setAttribute('data-id', carta.id_carta);

        // ── Imagen de fondo de la carta ──
        var fondo = carta.fondo_carta;
        if (!fondo && this.zonaObj && this.zonaObj.imagenCartas) {
            fondo = this.zonaObj.imagenCartas;
        }

        var img = document.createElement('div');
        img.className = 'carta-img';
        if (fondo) img.style.backgroundImage = "url('" + fondo + "')";
        div.appendChild(img);

        var overlay = document.createElement('div');
        overlay.className = 'carta-overlay';
        div.appendChild(overlay);

        var frame = document.createElement('div');
        frame.className = 'carta-frame';
        div.appendChild(frame);

        // ── Contenido de la carta ──
        var content = document.createElement('div');
        content.className = 'carta-content';

        // Check mark (se ve al seleccionar)
        var checkMark = document.createElement('div');
        checkMark.className = 'check-mark';
        checkMark.textContent = '\u2713';
        div.appendChild(checkMark);

        // Comprobar si ya estaba seleccionada
        var yaSeleccionada = this.cartasSeleccionadas.some(function(c) { return c.id_carta === carta.id_carta; });
        if (yaSeleccionada) div.classList.add('seleccionada');

        // ── Buscar que evento neutraliza esta carta ──
        var icono = carta.codigo_icono || '?';
        var curaVal = parseInt(carta.curacion) || 0;
        var neutraliza = '';
        if (carta.elimina_id_evento) {
            for (var i = 0; i < this.catalogoEventosActual.length; i++) {
                if (this.catalogoEventosActual[i].id_evento == carta.elimina_id_evento) {
                    neutraliza = this.catalogoEventosActual[i].nombre;
                    break;
                }
            }
        }

        // Nombre de la carta
        var titulo = document.createElement('div');
        titulo.className = 'titulo-carta';
        titulo.textContent = carta.nombre;

        // Icono del tipo
        var iconoEl = document.createElement('div');
        iconoEl.className = 'icono-carta';
        iconoEl.textContent = icono;

        // Badge de curacion
        var badge = document.createElement('div');
        badge.className = 'valor-badge';
        badge.textContent = '+' + curaVal + ' HP';

        content.appendChild(titulo);
        content.appendChild(iconoEl);
        content.appendChild(badge);

        // Tag del evento que neutraliza (si existe)
        if (neutraliza) {
            var matchTag = document.createElement('div');
            matchTag.className = 'match-tag';
            matchTag.textContent = neutraliza;
            content.appendChild(matchTag);
        }

        // Descripcion educativa de la carta
        if (carta.descripcion) {
            var desc = document.createElement('div');
            desc.className = 'carta-desc';
            desc.textContent = carta.descripcion;
            content.appendChild(desc);
        }

        div.appendChild(content);
        div.style.animationDelay = (index * 0.06) + 's';

        // ── Badge de sinergia ──
        // Si hay 2+ cartas con el mismo icono en mano, mostrar "S" naranja
        var iconosEnMano = {};
        if (window.juego && window.juego.mano) {
            for (var k = 0; k < window.juego.mano.length; k++) {
                var ic = window.juego.mano[k].codigo_icono || 'sin_icono';
                iconosEnMano[ic] = (iconosEnMano[ic] || 0) + 1;
            }
        }
        var miIcono = carta.codigo_icono || 'sin_icono';
        if (iconosEnMano[miIcono] >= 2) {
            var sinBadge = document.createElement('div');
            sinBadge.className = 'sinergia-badge';
            sinBadge.textContent = 'S';
            sinBadge.title = 'Sinergia: ' + miIcono + ' x' + iconosEnMano[miIcono];
            div.appendChild(sinBadge);
            div.classList.add('sinergia-activa');
        }

        // ── Evento click ──
        var self = this;
        div.addEventListener('click', function() {
            self.toggleCarta(carta, div);
        });

        this.contenedorMano.appendChild(div);
    }

    /**
     * Maneja el click en una carta.
     * 
     * Si esta en modo sacrificio -> sacrificar la carta directamente.
     * Si no -> toggle de seleccion (con limite de energia).
     */
    toggleCarta(carta, div) {
        // ── Modo sacrificio: sacrificar carta al click ──
        if (this.modoSacrificio) {
            if (window.juego) {
                var resultado = window.juego.sacrificarCarta(carta);
                if (resultado.exito) {
                    if (window.DeckologySound) window.DeckologySound.deselect();
                    this.energiaActual = resultado.energia;
                    this._salirModoSacrificio();
                    this.renderMano(window.juego.mano);
                    this.actualizarEnergiaUI();
                    this.actualizarPanelLateral();
                    this.showFloatingText('Sacrificado +1E', '#E8883A');
                } else {
                    if (window.DeckologySound) window.DeckologySound.noEnergy();
                    this.showFloatingText(resultado.razon, '#ff6b6b');
                }
            }
            return;
        }

        // ── Modo normal: toggle seleccion ──
        var idx = -1;
        for (var i = 0; i < this.cartasSeleccionadas.length; i++) {
            if (this.cartasSeleccionadas[i].id_carta === carta.id_carta) { idx = i; break; }
        }

        if (idx !== -1) {
            // Deseleccionar
            this.cartasSeleccionadas.splice(idx, 1);
            div.classList.remove('seleccionada');
            if (window.DeckologySound) window.DeckologySound.deselect();
        } else {
            // Comprobar energia antes de seleccionar
            var energiaDisponible = window.juego ? window.juego.energia : 3;
            if (this.cartasSeleccionadas.length >= energiaDisponible) {
                if (window.DeckologySound) window.DeckologySound.noEnergy();
                this.showFloatingText('Sin energia para mas cartas', '#ff6b6b');
                return;
            }
            // Seleccionar
            this.cartasSeleccionadas.push(carta);
            div.classList.add('seleccionada');
            if (window.DeckologySound) window.DeckologySound.select();
        }
        this.actualizarPanelLateral();
        if (window.actualizarBotonJugar) window.actualizarBotonJugar();
    }

    /**
     * Renderiza los eventos activos (amenazas) en la zona superior.
     * Cada evento muestra: icono, nombre, dano actual (con escalado),
     * turnos que quedan, descripcion, y warning si esta escalado.
     */
    renderEventos(eventos) {
        this.contenedorEventos.innerHTML = '';
        var self = this;

        // Sin eventos: mostrar placeholder
        if (!eventos || eventos.length === 0) {
            this.tituloEventos.textContent = 'Amenazas';
            var empty = document.createElement('div');
            empty.className = 'fila-vacia';
            empty.textContent = 'Sin amenazas activas';
            this.contenedorEventos.appendChild(empty);
            return;
        }

        this.tituloEventos.textContent = 'Amenazas (' + eventos.length + ')';

        eventos.forEach(function(evento, index) {
            var div = document.createElement('div');
            div.classList.add('carta-evento');
            div.setAttribute('data-id', evento.id_evento);

            // Imagen de fondo del evento
            var fondoEvento = evento.fondo_evento;
            if (!fondoEvento && self.zonaObj && self.zonaObj.imagenEventos) {
                fondoEvento = self.zonaObj.imagenEventos;
            }

            var img = document.createElement('div');
            img.className = 'carta-img';
            if (fondoEvento) img.style.backgroundImage = "url('" + fondoEvento + "')";
            div.appendChild(img);

            var overlay = document.createElement('div');
            overlay.className = 'carta-overlay';
            div.appendChild(overlay);

            var frame = document.createElement('div');
            frame.className = 'carta-frame';
            div.appendChild(frame);

            // Badge de duracion (turnos que quedan)
            var durBadge = document.createElement('div');
            durBadge.className = 'evento-duracion';
            durBadge.textContent = evento.duracionTurnos || '?';
            div.appendChild(durBadge);

            var content = document.createElement('div');
            content.className = 'carta-content';

            var icono = evento.codigo_icono || '!';

            // ── Calcular dano actual con escalado ──
            var danoReal = evento.danoReal;
            if (typeof danoReal === 'undefined' || isNaN(danoReal)) {
                danoReal = parseInt(evento.dano) || 0;
            }
            var danoBase = parseInt(evento.dano) || 0;
            var turnosExtra = evento.turnosSinNeutralizar || 0;
            var danoEscalado = danoBase + (turnosExtra * (window.juego ? window.juego.config.escaladoDanoAmenaza : 2));
            if (turnosExtra >= 3) danoEscalado += 10;
            if (turnosExtra >= 5) danoEscalado += 15;

            var iconoSpan = document.createElement('div');
            iconoSpan.className = 'evento-icono';
            iconoSpan.textContent = icono;

            var nombre = document.createElement('div');
            nombre.className = 'evento-nombre';
            nombre.textContent = evento.nombre;

            var dano = document.createElement('div');
            dano.className = 'evento-dano';
            dano.textContent = '-' + danoEscalado + '/t';

            // Warning de escalado si ha sobrevivido turnos
            if (turnosExtra > 0) {
                var warning = document.createElement('div');
                warning.className = 'escalado-warning';
                var warnText = '+' + (danoEscalado - danoBase) + ' esc.';
                if (turnosExtra >= 3) warnText += ' (!)';
                warning.textContent = warnText;
                dano.appendChild(warning);
            }

            content.appendChild(iconoSpan);
            content.appendChild(nombre);
            content.appendChild(dano);

            // Descripcion del evento
            if (evento.descripcion) {
                var desc = document.createElement('div');
                desc.className = 'evento-desc';
                desc.textContent = evento.descripcion;
                content.appendChild(desc);
            }

            div.appendChild(content);
            div.style.animationDelay = (index * 0.08) + 's';
            self.contenedorEventos.appendChild(div);
        });
    }

    /**
     * Actualiza el panel lateral derecho con:
     * - Contador de cartas seleccionadas vs energia disponible
     * - Detalle de cartas seleccionadas (nombre, curacion, neutraliza)
     * - Lista de eventos activos con dano y duracion
     */
    actualizarPanelLateral() {
        var htmlSel = '';
        var htmlEv = '';
        var energia = window.juego ? window.juego.energia : 3;
        var seleccionadas = this.cartasSeleccionadas.length;

        // ── Panel de seleccion ──
        htmlSel += '<div class="panel-energia-info">' +
            '<span class="pei-label">Cartas: </span>' +
            '<span class="pei-numero">' + seleccionadas + '/' + energia + '</span>' +
        '</div>';

        if (seleccionadas >= energia && energia > 0) {
            htmlSel += '<div class="panel-energia-full">Maximo alcanzado</div>';
        }

        // Detalle de cada carta seleccionada
        if (this.cartasSeleccionadas.length > 0) {
            for (var i = 0; i < this.cartasSeleccionadas.length; i++) {
                var carta = this.cartasSeleccionadas[i];
                var neutraliza = 'Nada';
                var curaVal = parseInt(carta.curacion) || 0;
                if (carta.elimina_id_evento) {
                    for (var j = 0; j < this.catalogoEventosActual.length; j++) {
                        if (this.catalogoEventosActual[j].id_evento == carta.elimina_id_evento) {
                            neutraliza = this.catalogoEventosActual[j].nombre;
                            break;
                        }
                    }
                }
                htmlSel += '<div class="panel-item-seleccion">' +
                    '<div class="pis-nombre">' + carta.nombre + '</div>' +
                    '<div class="pis-desc">' + (carta.descripcion || '') + '</div>' +
                    '<div class="pis-dato">Cura: <b class="cura">+' + curaVal + ' HP</b></div>' +
                    '<div class="pis-dato">Neutraliza: <b class="neut">' + neutraliza + '</b></div>' +
                '</div>';
            }
        }

        // ── Panel de eventos activos ──
        if (this.eventosActivosRef.length > 0) {
            for (var k = 0; k < this.eventosActivosRef.length; k++) {
                var ev = this.eventosActivosRef[k];
                var danoR = ev.danoReal || parseInt(ev.dano) || 0;
                htmlEv += '<div class="panel-item-evento">' +
                    '<div class="pie-icono">' + (ev.codigo_icono || '!') + '</div>' +
                    '<div class="pie-datos">' +
                        '<div class="pie-nombre">' + ev.nombre + '</div>' +
                        '<div class="pie-stats">-' + danoR + '/t &middot; ' + (ev.duracionTurnos || '?') + ' turnos</div>' +
                    '</div>' +
                '</div>';
            }
        }

        this.panelSeleccion.innerHTML = htmlSel || '<p class="panel-vacio">Toca una carta</p>';
        this.panelEventosLista.innerHTML = htmlEv || '<p class="panel-vacio">Sin eventos</p>';
    }

    /** Devuelve las cartas actualmente seleccionadas */
    obtenerCartasSeleccionadas() { return this.cartasSeleccionadas; }

    /** Limpia la seleccion de cartas */
    limpiarSeleccion() {
        this.cartasSeleccionadas = [];
        this.actualizarPanelLateral();
    }

    /**
     * Muestra animaciones al jugar cartas:
     * - "EVENTO ELIMINADO" si neutralizo algo
     * - "+X HP" por cada carta que curo
     */
    mostrarJugarAnimacion(resultadoCartas) {
        var self = this;
        resultadoCartas.forEach(function(r) {
            if (r.neutralizo) {
                self.spawnNeutralizeParticles();
                self.showFloatingText('EVENTO ELIMINADO', '#6ECB8C');
            }
            if (r.curacion > 0) {
                self.showFloatingText('+' + r.curacion + ' HP', '#6ECB8C');
            }
        });
    }

    /**
     * Muestra el resultado del turno en la barra de acciones:
     * balance (+X HP o -X HP), dano total, curacion total.
     */
    mostrarTurnoInfo(dano, curacion, balance) {
        var html = '';
        if (balance >= 0) {
            html = '<span style="color:#6ECB8C;">+' + balance + ' HP</span>';
        } else {
            html = '<span style="color:#ff8a8a;">-' + Math.abs(balance) + ' HP</span>';
        }
        if (dano > 0 && curacion > 0) {
            html += ' <span style="color:#888;font-size:11px;">(Da\u00f1o: ' + dano + ' | Cura: ' + curacion + ')</span>';
        }
        this.turnoInfo.innerHTML = html;
        this.turnoInfo.style.animation = 'none';
        void this.turnoInfo.offsetWidth;
        this.turnoInfo.style.animation = 'fadeSlideUp 0.4s ease';
    }

    // ── EFECTOS VISUALES ──

    /** Shake de pantalla segun intensidad (suave o fuerte) */
    shakeScreen(intensidad) {
        var el = this.gameBoard;
        el.classList.remove('shake-fuerte', 'shake-suave');
        void el.offsetWidth;
        el.classList.add('shake-' + intensidad);
        setTimeout(function() { el.classList.remove('shake-fuerte', 'shake-suave'); }, 400);
    }

    /** Flash rojo al recibir dano */
    flashDamage() {
        this.gameBoard.classList.remove('flash-damage');
        void this.gameBoard.offsetWidth;
        this.gameBoard.classList.add('flash-damage');
        var self = this;
        setTimeout(function() { self.gameBoard.classList.remove('flash-damage'); }, 500);
    }

    /** Flash verde al curar */
    flashHeal() {
        this.gameBoard.classList.remove('flash-heal');
        void this.gameBoard.offsetWidth;
        this.gameBoard.classList.add('flash-heal');
        var self = this;
        setTimeout(function() { self.gameBoard.classList.remove('flash-heal'); }, 500);
    }

    /** Genera particulas de dano (rojas) */
    spawnDamageParticles(dano) {
        var count = Math.min(dano / 3, 12);
        for (var i = 0; i < count; i++) this.spawnParticle('particle-damage');
    }

    /** Genera particulas de curacion (verdes) */
    spawnHealParticles(cura) {
        var count = Math.min(cura / 3, 8);
        for (var i = 0; i < count; i++) this.spawnParticle('particle-heal');
    }

    /** Genera particulas de neutralizacion (blancas) */
    spawnNeutralizeParticles() {
        for (var i = 0; i < 10; i++) this.spawnParticle('particle-neutralize');
    }

    /**
     * Crea una particula individual con movimiento aleatorio.
     * Se auto-elimina despues de 1 segundo.
     */
    spawnParticle(className) {
        var p = document.createElement('div');
        p.className = 'game-particle ' + className;
        p.style.left = (20 + Math.random() * 60) + '%';
        p.style.top = (30 + Math.random() * 40) + '%';
        p.style.setProperty('--dx', (Math.random() * 100 - 50) + 'px');
        p.style.setProperty('--dy', -(30 + Math.random() * 60) + 'px');
        this.particles.appendChild(p);
        setTimeout(function() { if (p.parentNode) p.parentNode.removeChild(p); }, 1000);
    }

    /**
     * Muestra texto flotante (como "+10 HP" o "Sin energia").
     * Se posiciona aleatoriamente y se auto-elimina.
     */
    showFloatingText(text, color) {
        var el = document.createElement('div');
        el.className = 'floating-text';
        el.textContent = text;
        el.style.color = color;
        el.style.left = (30 + Math.random() * 40) + '%';
        el.style.top = '40%';
        this.particles.appendChild(el);
        setTimeout(function() { if (el.parentNode) el.parentNode.removeChild(el); }, 1200);
    }

    /**
     * Muestra el overlay de Game Over o Victoria.
     * Muestra titulo, mensaje, icono (trofeo o calavera) y puntuacion.
     */
    mostrarGameOver(titulo, mensaje, esVictoria, puntuacion) {
        var overlay = document.getElementById('overlay-resultado');
        document.getElementById('titulo-resultado').textContent = titulo;
        document.getElementById('mensaje-resultado').textContent = mensaje;
        document.getElementById('icono-resultado').textContent = esVictoria ? '\uD83C\uDFC6' : '\uD83D\uDC80';
        document.getElementById('titulo-resultado').style.color = esVictoria ? '#289F5C' : '#D94A4A';

        var pEl = document.getElementById('puntuacion-resultado');
        if (puntuacion !== null && puntuacion !== undefined) {
            pEl.textContent = "Puntuaci\u00f3n: " + puntuacion;
            pEl.style.display = 'block';
        } else {
            pEl.style.display = 'none';
        }

        overlay.style.display = 'flex';
        if (esVictoria && window.DeckologySound) window.DeckologySound.victory();
        else if (window.DeckologySound) window.DeckologySound.defeat();
    }
}
