/**
 * MotorJuego - Motor de logica del juego Deckology
 * 
 * Se encarga de toda la logica de estado: vida, energia, cartas, eventos,
 * combos, sinergias, sacrificio, escalado de dificultad y puntuacion.
 * NO toca el DOM - solo calcula y devuelve resultados.
 * 
 * Flujo de un turno:
 *   1. Jugador selecciona cartas y pulsa "Jugar" (o "Pasar Turno")
 *   2. Se ejecutan las cartas (usarCarta por cada una)
 *   3. Se llama a avanzarTurno() que:
 *      a. Aplica dano de todos los eventos activos
 *      b. Reduce duracion de eventos y elimina los expirados
 *      c. Genera un nuevo evento cada N turnos
 *      d. Roba una carta cada N turnos
 *      e. Actualiza combo (decrementa si no hubo neutralizacion)
 *      f. Resetea energia y sacrificios del turno
 */
class MotorJuego {

    /**
     * @param {Object} datosDelJuego - Datos embebidos desde PHP via DATOS_JUEGO
     * @param {Object} datosDelJuego.cartas - Cartas de la zona (objeto indexado por id_zona)
     * @param {Object} datosDelJuego.eventos - Eventos de la zona
     * @param {string|number} datosDelJuego.zonaInicial - ID de zona seleccionada ("0" = Infinito)
     * @param {Object} datosDelJuego.zonaObj - Objeto zona con imagenes, nombre, etc.
     */
    constructor(datosDelJuego) {
        // Copiar catalogos de cartas y eventos (indexados por zona)
        this.catalogoCartas = datosDelJuego.cartas;
        this.catalogoEventos = datosDelJuego.eventos;
        this.zonaActual = datosDelJuego.zonaInicial;
        this.zonaObj = datosDelJuego.zonaObj;

        // ── Configuracion del juego ──
        // Todos los valores balanceados para dificultad progresiva
        this.config = {
            vidaMaxima: 100,          // HP iniciales del planeta
            cartasManoMax: 5,         // Maximo de cartas en mano
            cartasIniciales: 3,       // Cartas con las que empiezas
            turnosParaRobar: 1,       // Robas 1 carta cada N turnos
            turnosEvento: 1,          // Aparece 1 evento nuevo cada N turnos
            limiteRondas: 5,          // Rondas para ganar en modo normal (15 turnos total)
            turnosParaAumentarDificultad: 3, // Cada N turnos sube dificultad (Infinito)
            cantidadAumentoDano: 3,   // Dano extra que suma la dificultad
            energiaMaxima: 3,         // Energia maxima por turno
            energiaPorTurno: 3,       // Energia que recuperas al inicio de turno
            costoCarta: 1,            // Coste de energia por carta jugada
            sacrificioMaxPorTurno: 1, // Maximo de sacrificios por turno
            bonusSinergia: 0.3,       // +30% curacion si 2+ cartas mismo icono
            escaladoDanoAmenaza: 1    // +1 dano por turno que un evento sobrevive
        };

        // Detectar modo Infinito (zona 0 usa todas las cartas/ev de las 4 zonas)
        this.esInfinito = (this.zonaActual == 0 || this.zonaActual == "0");

        // Dano extra acumulado en modo Infinito (sube cada N turnos)
        this.danoExtra = 0;

        // ── Estado del juego ──
        this.turnoTotal = 1;          // Turno global (no se resetea)
        this.vidaPlaneta = this.config.vidaMaxima;
        this.mano = [];               // Cartas en la mano del jugador
        this.eventosActivos = [];     // Eventos que el jugador debe resolver
        this.eventosSuperados = 0;    // Eventos neutralizados con exito
        this.cartasJugadas = 0;       // Total de cartas jugadas en la partida
        this.curacionTurno = 0;       // Curacion acumulada del turno actual

        // ── Sistema de combo ──
        // Cada neutralizacion seguida sube el combo (+0.5x cada 3 combos)
        // Si juegas una carta que NO neutraliza, el combo baja 1
        this.combo = 0;
        this.maxCombo = 0;
        this.comboMultiplier = 1;     // Multiplicador de curacion por combo

        // Puntuacion final (se calcula al terminar la partida)
        this.score = 0;

        // ── Sistema de energia y sacrificio ──
        this.energia = this.config.energiaMaxima;
        this.energiaMaxima = this.config.energiaMaxima;
        this.sacrificiosRealizados = 0;  // Contador de sacrificios este turno
        this.cartasJugadasEsteTurno = []; // Para calcular sinergia
    }

    /**
     * Avanza un turno completo. Se llama despues de que el jugador
     * juega cartas o pulsa "Pasar Turno".
     * 
     * @returns {Object} Resultado del turno con dano, curacion, eventos, etc.
     */
    avanzarTurno() {
        this.turnoTotal++;

        // Comprobar victoria en modo normal (5 rondas x 3 turnos = 15 turnos)
        var ronda = Math.ceil(this.turnoTotal / 3);
        if (!this.esInfinito && ronda > this.config.limiteRondas) {
            return {
                victoria: true,
                mensaje: "Has completado todas las rondas. El planeta esta a salvo."
            };
        }

        // En modo Infinito, subir dificultad cada N turnos
        if (this.esInfinito && this.turnoTotal % this.config.turnosParaAumentarDificultad === 0) {
            this.danoExtra += this.config.cantidadAumentoDano;
        }

        // 1. Aplicar dano de todos los eventos activos
        var danoResultado = this.aplicarDaños();

        // 2. Incrementar contador de turnos sin neutralizar por cada evento
        for (var i = 0; i < this.eventosActivos.length; i++) {
            this.eventosActivos[i].turnosSinNeutralizar = (this.eventosActivos[i].turnosSinNeutralizar || 0) + 1;
        }

        // 3. Generar evento nuevo segun frecuencia configurada
        if (this.turnoTotal % this.config.turnosEvento === 0) {
            this.generarEvento();
        }

        // 4. Robar carta segun frecuencia configurada
        if (this.turnoTotal % this.config.turnosParaRobar === 0) {
            this.robarCarta();
        }

        // 4b. Si la mano quedo vacia, robar 1 carta de garantia
        if (this.mano.length === 0) {
            this.robarCarta();
        }

        // 5. Reducir duracion de eventos y eliminar expirados
        this.eventosActivos.forEach(function(evento) {
            evento.duracionTurnos--;
        });
        var eliminados = this.eventosActivos.filter(function(e) { return e.duracionTurnos <= 0; });
        this.eventosActivos = this.eventosActivos.filter(function(e) { return e.duracionTurnos > 0; });

        // 6. Actualizar combo (decrementa si no hubo neutralizacion)
        this.actualizarCombo();

        // 7. Resetear estado del turno
        this.energia = this.config.energiaPorTurno;
        this.sacrificiosRealizados = 0;
        this.cartasJugadasEsteTurno = [];

        return {
            turno: this.turnoTotal,
            vida: this.vidaPlaneta,
            eventos: this.eventosActivos.length,
            esInfinito: this.esInfinito,
            danoExtra: this.danoExtra,
            eventosExpirados: eliminados.length,
            dano: danoResultado.dano,
            curacion: danoResultado.curacion,
            balance: danoResultado.balance,
            energia: this.energia,
            energiaMaxima: this.energiaMaxima
        };
    }

    /**
     * Roba una carta aleatoria del catalogo de la zona actual.
     * Intenta evitar duplicados, pero si no queda otra opcion,
     * permite duplicados (el mazo es infinito).
     * 
     * @param {number} intentos - Contador interno para evitar bucle infinito
     * @returns {boolean} true si robo carta, false si la mano esta llena
     */
    robarCarta(intentos) {
        if (typeof intentos === 'undefined') intentos = 0;
        if (this.mano.length >= this.config.cartasManoMax) return false;

        var listaPosible = this.catalogoCartas[this.zonaActual];
        if (!listaPosible || listaPosible.length === 0) return false;

        // Intentar carta aleatoria sin duplicado
        var cartaAleatoria = listaPosible[Math.floor(Math.random() * listaPosible.length)];
        var duplicado = this.mano.find(function(c) { return c.id_carta === cartaAleatoria.id_carta; });

        if (!duplicado) {
            this.mano.push(JSON.parse(JSON.stringify(cartaAleatoria)));
            return true;
        } else if (intentos < listaPosible.length) {
            // Seguir intentando si quedan cartas por probar
            return this.robarCarta(intentos + 1);
        } else {
            // Si ya probamos todas, aceptar duplicado
            this.mano.push(JSON.parse(JSON.stringify(cartaAleatoria)));
            return true;
        }
    }

    /**
     * Comprueba si el jugador tiene energia suficiente para jugar una carta.
     * @returns {boolean}
     */
    puedeUsarCarta() {
        return this.energia >= this.config.costoCarta;
    }

    /**
     * Juega una carta de la mano. Efectos:
     * 1. Cuesta 1 energia
     * 2. Si la carta neutraliza un evento activo, lo elimina y sube combo
     * 3. Si NO neutraliza, baja combo 1 punto
     * 4. Aplica curacion (multiplicada por combo y sinergia si activa)
     * 5. Elimina la carta de la mano
     * 
     * @param {Object} carta - Carta a jugar (debe estar en this.mano)
     * @returns {Object} Resultado con exito, curacion, neutralizo, combo, etc.
     */
    usarCarta(carta) {
        // Comprobar energia
        if (this.energia < this.config.costoCarta) {
            return { exito: false, razon: "Sin energia suficiente" };
        }

        this.energia -= this.config.costoCarta;
        var puntosCura = parseInt(carta.curacion) || 0;
        this.cartasJugadas++;
        this.cartasJugadasEsteTurno.push(carta);

        // ── Comprobar si neutraliza un evento activo ──
        var neutralizoEvento = false;
        var idEvento = carta.elimina_id_evento || carta.efecto;

        if (idEvento) {
            // Buscar el evento activo que coincide con esta carta
            var idx = -1;
            for (var i = 0; i < this.eventosActivos.length; i++) {
                var ev = this.eventosActivos[i];
                if ((ev.id_evento || ev.id) == idEvento) {
                    idx = i;
                    break;
                }
            }
            if (idx !== -1) {
                // Eliminar evento y subir combo
                this.eventosActivos.splice(idx, 1);
                this.eventosSuperados++;
                neutralizoEvento = true;
                this.combo++;
                if (this.combo > this.maxCombo) this.maxCombo = this.combo;
                this.comboMultiplier = 1 + Math.floor(this.combo / 3) * 0.5;
            }
        }

        // ── Bajar combo si no neutralizo nada ──
        if (!neutralizoEvento && this.combo > 0) {
            this.combo = Math.max(0, this.combo - 1);
            this.comboMultiplier = 1 + Math.floor(this.combo / 3) * 0.5;
        }

        // ── Calcular curacion final ──
        var curaFinal = Math.round(puntosCura * this.comboMultiplier);

        // Aplicar bonus de sinergia si hay 2+ cartas con mismo icono este turno
        var sinergia = this.calcularSinergia();
        if (sinergia.activa) {
            curaFinal = Math.round(curaFinal * (1 + this.config.bonusSinergia));
        }

        // Acumular curacion para el balance del turno
        this.curacionTurno += curaFinal;

        // Eliminar carta de la mano
        this.mano = this.mano.filter(function(c) {
            return (c.id_carta || c.id) !== (carta.id_carta || carta.id);
        });

        return {
            exito: true,
            curacion: curaFinal,
            neutralizo: neutralizoEvento,
            combo: this.combo,
            multiplier: this.comboMultiplier,
            sinergia: sinergia,
            energia: this.energia
        };
    }

    /**
     * Calcula si hay sinergia entre las cartas jugadas este turno.
     * Sinergia = 2+ cartas con el mismo codigo_icono.
     * 
     * @returns {Object} { activa: boolean, icono: string, cantidad: number }
     */
    calcularSinergia() {
        // Contar repeticiones de cada icono entre las cartas jugadas
        var iconos = {};
        for (var i = 0; i < this.cartasJugadasEsteTurno.length; i++) {
            var icono = this.cartasJugadasEsteTurno[i].codigo_icono || 'sin_icono';
            iconos[icono] = (iconos[icono] || 0) + 1;
        }

        // Encontrar el icono mas repetido
        var maxRepeticiones = 0;
        var iconoDominante = null;
        var keys = Object.keys(iconos);
        for (var j = 0; j < keys.length; j++) {
            if (iconos[keys[j]] > maxRepeticiones) {
                maxRepeticiones = iconos[keys[j]];
                iconoDominante = keys[j];
            }
        }

        return {
            activa: maxRepeticiones >= 2, // Activa si 2+ cartas mismo icono
            icono: iconoDominante,
            cantidad: maxRepeticiones
        };
    }

    /**
     * Sacrifica una carta de la mano para recuperar 1 energia.
     * Maximo 1 sacrificio por turno.
     * La energia puede superar el maximo en 1 punto (overflow).
     * 
     * @param {Object} carta - Carta a sacrificar
     * @returns {Object} { exito, energia, cartaSacrificada } o { exito, razon }
     */
    sacrificarCarta(carta) {
        if (this.sacrificiosRealizados >= this.config.sacrificioMaxPorTurno) {
            return { exito: false, razon: "Ya sacrificaste una carta este turno" };
        }
        this.sacrificiosRealizados++;
        // Permitir 1 punto de energia por encima del maximo
        this.energia = Math.min(this.energia + 1, this.energiaMaxima + 1);

        // Eliminar carta de la mano
        this.mano = this.mano.filter(function(c) {
            return (c.id_carta || c.id) !== (carta.id_carta || carta.id);
        });

        return {
            exito: true,
            energia: this.energia,
            cartaSacrificada: carta.nombre
        };
    }

    /**
     * Decrementa el combo si es necesario.
     * Se llama al final de cada turno para mantener el combo fresco.
     */
    actualizarCombo() {
        if (this.combo > 0) {
            this.combo = Math.max(0, this.combo - 1);
            this.comboMultiplier = 1 + Math.floor(this.combo / 3) * 0.5;
        }
    }

    /**
     * Genera un evento nuevo aleatorio de la zona actual.
     * Intenta no repetir eventos ya activos, pero si no queda otra
     * opcion, permite repetidos (max 20 intentos).
     */
    generarEvento() {
        var listaEventos = this.catalogoEventos[this.zonaActual];
        if (!listaEventos || listaEventos.length === 0) return;

        var intentos = 0;
        this._generarEventoIntento(listaEventos, intentos);
    }

    _generarEventoIntento(listaEventos, intentos) {
        // Si ya intentamos 20 veces, aceptar cualquier evento
        if (intentos >= 20) {
            var ev = listaEventos[Math.floor(Math.random() * listaEventos.length)];
            this._anyadirEvento(ev);
            return;
        }

        var ev = listaEventos[Math.floor(Math.random() * listaEventos.length)];
        var yaExiste = this.eventosActivos.some(function(e) {
            return (e.id_evento || e.id) === (ev.id_evento || ev.id);
        });

        if (!yaExiste) {
            this._anyadirEvento(ev);
        } else if (this.eventosActivos.length < listaEventos.length) {
            // Todavia hay eventos no activos, reintentar
            this._generarEventoIntento(listaEventos, intentos + 1);
        }
    }

    /**
     * Anyade un evento al array de eventos activos.
     * Calcula el dano final sumando dano base + danoExtra del modo Infinito.
     */
    _anyadirEvento(ev) {
        var danoFinal = parseInt(ev.dano) + (this.danoExtra || 0);
        var copia = JSON.parse(JSON.stringify(ev));
        copia.duracionTurnos = parseInt(ev.turnos_duracion);
        copia.danoReal = danoFinal;
        copia.turnosSinNeutralizar = 0; // Turnos que lleva activo sin ser neutralizado
        this.eventosActivos.push(copia);
    }

    /**
     * Aplica el dano de todos los eventos activos al planeta.
     * El dano se escala con los turnos que lleva activo:
     *   - Base: dano del evento
     *   - +escaladoDanoAmenaza por turno sin neutralizar
     *   - +10 si lleva 3+ turnos (fatiga)
     *   - +15 adicionales si lleva 5+ turnos
     * 
     * La vida se calcula como: vida += (curacionTurno - danoTotal)
     * Y se respeta el rango [0, vidaMaxima].
     * 
     * @returns {Object} { dano, curacion, balance }
     */
    aplicarDaños() {
        var dañoTotalTurno = 0;

        for (var i = 0; i < this.eventosActivos.length; i++) {
            var ev = this.eventosActivos[i];
            var d = ev.danoReal;
            if (typeof d === 'undefined' || isNaN(d)) {
                d = parseInt(ev.dano) || 0;
                ev.danoReal = d;
            }

            var turnosExtra = ev.turnosSinNeutralizar || 0;

            // Escalado normal: +2 por turno que sobrevive
            var danoEscalado = d + (turnosExtra * this.config.escaladoDanoAmenaza);

            // Fatiga: bonus de dano si el evento lleva mucho tiempo activo
            if (turnosExtra >= 3) danoEscalado += 5;
            if (turnosExtra >= 5) danoEscalado += 8;

            dañoTotalTurno += danoEscalado;
        }

        // Balance del turno: curacion menos dano
        var balance = this.curacionTurno - dañoTotalTurno;
        this.vidaPlaneta += balance;

        var curacionUsada = this.curacionTurno;
        this.curacionTurno = 0; // Resetear para el proximo turno

        // Clamp de vida entre 0 y maximo
        if (this.vidaPlaneta > this.config.vidaMaxima) this.vidaPlaneta = this.config.vidaMaxima;
        if (this.vidaPlaneta < 0) this.vidaPlaneta = 0;

        return {
            dano: dañoTotalTurno,
            curacion: curacionUsada,
            balance: balance
        };
    }

    /**
     * Calcula la puntuacion final de la partida.
     * Formula: turnos*10 + eventosSuperados*50 + maxCombo*25 + cartasJugadas*5
     */
    calcularPuntuacionFinal() {
        var base = this.turnoTotal * 10 + this.eventosSuperados * 50;
        var bonusCombo = this.maxCombo * 25;
        var bonusCartas = this.cartasJugadas * 5;
        this.score = base + bonusCombo + bonusCartas;
        return this.score;
    }

    /**
     * Devuelve un resumen del estado actual para la interfaz.
     */
    getStats() {
        return {
            turnos: this.turnoTotal,
            eventosSuperados: this.eventosSuperados,
            cartasJugadas: this.cartasJugadas,
            maxCombo: this.maxCombo,
            score: this.score,
            vida: this.vidaPlaneta,
            energia: this.energia,
            energiaMaxima: this.energiaMaxima
        };
    }
}
