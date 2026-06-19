/**
 * DeckologySound - Sistema de sonido basado en Web Audio API
 * 
 * Genera todos los sonidos del juego sinteticamente (sin archivos de audio).
 * Cada sonido es una combinacion de osciladores con diferentes frecuencias,
 * tipos de onda (sine, square, triangle, sawtooth) y duraciones.
 * 
 * Arquitectura:
 *   AudioContext -> DynamicsCompressor -> GainNode(master) -> destination
 * 
 * El compressor evita picos de volumen molestos.
 * El GainNode master controla el volumen global y el mute.
 * 
 * El AudioContext requiere interaccion del usuario para activarse
 * (politica de autoplay del navegador). La funcion arm() escucha
 * el primer click/touch/keydown para inicializar el contexto.
 */
(function () {
  // ── Estado interno ──
  var audioContext = null;   // AudioContext de Web Audio API
  var master = null;         // GainNode para controlar volumen global
  var compressor = null;     // DynamicsCompressor para evitar clipping
  var volume = 0.25;         // Volumen actual (0 a 1)
  var muted = false;         // Si esta silenciado
  var sfxRestoreKey = 'deckology_sfx';

  /**
   * Inicializa el AudioContext si no existe.
   * Crea la cadena: compressor -> master gain -> destination.
   * Se llama automaticamente en el primer click del usuario.
   */
  function ensureContext() {
    if (!audioContext) {
      var Ctx = window.AudioContext || window.webkitAudioContext;
      if (!Ctx) return null;
      audioContext = new Ctx();

      // Configurar compressor para evitar distorsion
      compressor = audioContext.createDynamicsCompressor();
      compressor.threshold.setValueAtTime(-18, audioContext.currentTime);
      compressor.knee.setValueAtTime(24, audioContext.currentTime);
      compressor.ratio.setValueAtTime(6, audioContext.currentTime);
      compressor.attack.setValueAtTime(0.003, audioContext.currentTime);
      compressor.release.setValueAtTime(0.18, audioContext.currentTime);

      // Gain node para controlar volumen global
      master = audioContext.createGain();
      master.gain.setValueAtTime(volume, audioContext.currentTime);

      compressor.connect(master);
      master.connect(audioContext.destination);
    }
    // Reanudar si esta suspendido (navegadores lo hacen por politica de autoplay)
    if (audioContext.state === 'suspended') {
      audioContext.resume().catch(function() {});
    }
    return audioContext;
  }

  /**
   * Funcion base para generar un sonido (beep).
   * Crea un oscilador, lo conecta al compressor y lo reproduce.
   * 
   * @param {Object} opts - Opciones del beep
   * @param {number} opts.frequency - Frecuencia en Hz (default 440)
   * @param {number} opts.duration - Duracion en segundos (default 0.1)
   * @param {string} opts.type - Tipo de onda: sine|square|triangle|sawtooth
   * @param {number} opts.gain - Volumen del beep (default 0.08)
   * @param {number} [opts.freqEnd] - Frecuencia final para rampa exponencial
   */
  function beep(opts) {
    var freq = opts.frequency || 440;
    var dur = opts.duration || 0.1;
    var type = opts.type || 'sine';
    var gain = opts.gain || 0.08;

    var ctx = ensureContext();
    if (!ctx || muted) return;

    var now = ctx.currentTime;
    var osc = ctx.createOscillator();
    var gn = ctx.createGain();

    osc.type = type;
    osc.frequency.setValueAtTime(freq, now);
    // Rampa de frecuencia (para efectos como "power up" o "damage")
    if (opts.freqEnd) osc.frequency.exponentialRampToValueAtTime(opts.freqEnd, now + dur);

    // Envolvente de volumen: ataque rapido + decay exponencial
    gn.gain.setValueAtTime(0.0001, now);
    gn.gain.exponentialRampToValueAtTime(gain, now + 0.01);
    gn.gain.exponentialRampToValueAtTime(0.0001, now + dur);

    osc.connect(gn);
    gn.connect(compressor || ctx.destination);

    osc.start(now);
    osc.stop(now + dur);
  }

  /**
   * Arma el sistema de audio escuchando el primer evento de interaccion.
   * Esto es necesario porque los navegadores bloquean el AudioContext
   * hasta que el usuario interactua con la pagina.
   */
  function arm() {
    var handler = function() {
      ensureContext();
      // Despues del primer click, remover todos los listeners
      document.removeEventListener('pointerdown', handler, true);
      document.removeEventListener('touchstart', handler, true);
      document.removeEventListener('keydown', handler, true);
      document.removeEventListener('click', handler, true);
    };
    document.addEventListener('pointerdown', handler, true);
    document.addEventListener('touchstart', handler, true);
    document.addEventListener('keydown', handler, true);
    document.addEventListener('click', handler, true);
  }

  function saveSfxState() {
    try {
      localStorage.setItem(sfxRestoreKey, JSON.stringify({ volume: volume, muted: muted }));
    } catch(e) {}
  }

  function loadSfxState() {
    try {
      var raw = localStorage.getItem(sfxRestoreKey);
      if (raw) {
        var state = JSON.parse(raw);
        if (typeof state.volume === 'number') volume = state.volume;
        if (typeof state.muted === 'boolean') muted = state.muted;
      }
    } catch(e) {}
  }

  /**
   * Configura los controles de volumen (slider + boton mute).
   * Se busca el slider y el boton por ID en el DOM.
   * El slider va de 0 a 100, se divide entre 100 para obtener 0-1.
   */
  function setupVolumeControl() {
    var slider = document.getElementById('volumen-slider-sfx');
    var btn = document.getElementById('btn-mute-sfx');
    if (!slider || !btn) return;

    loadSfxState();
    slider.value = Math.round(volume * 100);
    if (master) master.gain.setValueAtTime(muted ? 0 : volume, audioContext.currentTime);

    slider.addEventListener('input', function() {
      volume = parseInt(this.value) / 100;
      muted = (volume === 0);
      if (master) master.gain.setValueAtTime(muted ? 0 : volume, audioContext.currentTime);
      saveSfxState();
      updateMuteIcon();
    });

    btn.addEventListener('click', function() {
      muted = !muted;
      if (master) master.gain.setValueAtTime(muted ? 0 : volume, audioContext.currentTime);
      if (!muted && volume === 0) {
        volume = 0.25;
        slider.value = 25;
      }
      saveSfxState();
      updateMuteIcon();
    });

    function updateMuteIcon() {
      if (muted || volume === 0) {
        btn.innerHTML = '<i class="bi bi-volume-mute"></i>';
        btn.style.color = '#D94A4A';
      } else if (volume < 0.15) {
        btn.innerHTML = '<i class="bi bi-soundwave"></i>';
        btn.style.color = '';
      } else {
        btn.innerHTML = '<i class="bi bi-soundwave"></i>';
        btn.style.color = '';
      }
    }

    updateMuteIcon();
  }

  // ══════════════════════════════════════════════
  //  SONIDOS DEL JUEGO
  //  Cada funcion es un beep o secuencia de beeps
  // ══════════════════════════════════════════════

  window.DeckologySound = {
    arm: arm,
    setupVolumeControl: setupVolumeControl,

    /** Click generico de UI (botones, navegacion) */
    uiClick: function() { beep({ frequency: 520, duration: 0.07, type: 'triangle', gain: 0.07 }); },

    /** Seleccionar una carta (agudo, positivo) */
    select: function() { beep({ frequency: 760, duration: 0.08, type: 'sine', gain: 0.08 }); },

    /** Deseleccionar carta o cancelar (medio, neutro) */
    deselect: function() { beep({ frequency: 480, duration: 0.08, type: 'sine', gain: 0.06 }); },

    /** Jugar carta (dos tonos ascendentes) */
    playCard: function() {
      beep({ frequency: 440, duration: 0.06, type: 'triangle', gain: 0.06 });
      setTimeout(function() { beep({ frequency: 660, duration: 0.08, type: 'triangle', gain: 0.06 }); }, 60);
    },

    /** Recibir dano (grave, distorted) */
    damage: function() {
      beep({ frequency: 200, duration: 0.12, type: 'sawtooth', gain: 0.05 });
      beep({ frequency: 150, duration: 0.15, type: 'square', gain: 0.04 });
    },

    /** Curar (dos tonos ascendentes suaves) */
    heal: function() {
      beep({ frequency: 520, duration: 0.08, type: 'sine', gain: 0.06 });
      setTimeout(function() { beep({ frequency: 780, duration: 0.1, type: 'sine', gain: 0.06 }); }, 80);
    },

    /** Neutralizar evento (tres tonos ascendentes, satisfactorio) */
    neutralize: function() {
      beep({ frequency: 600, duration: 0.06, type: 'sine', gain: 0.07 });
      setTimeout(function() { beep({ frequency: 900, duration: 0.08, type: 'sine', gain: 0.07 }); }, 70);
      setTimeout(function() { beep({ frequency: 1200, duration: 0.1, type: 'sine', gain: 0.06 }); }, 140);
    },

    /** Sinergia activada (cuatro tonos ascendentes brillantes) */
    synergy: function() {
      beep({ frequency: 800, duration: 0.05, type: 'sine', gain: 0.06 });
      setTimeout(function() { beep({ frequency: 1000, duration: 0.06, type: 'sine', gain: 0.06 }); }, 60);
      setTimeout(function() { beep({ frequency: 1200, duration: 0.08, type: 'sine', gain: 0.06 }); }, 120);
      setTimeout(function() { beep({ frequency: 1500, duration: 0.1, type: 'sine', gain: 0.05 }); }, 180);
    },

    /** Avanzar turno (click corto) */
    turnAdvance: function() { beep({ frequency: 400, duration: 0.05, type: 'triangle', gain: 0.04 }); },

    /** Sin energia (grave, error) */
    noEnergy: function() { beep({ frequency: 180, duration: 0.1, type: 'square', gain: 0.04 }); },

    /** Nuevo evento aparece (dos tonos graves descendentes) */
    newEvent: function() {
      beep({ frequency: 300, duration: 0.1, type: 'square', gain: 0.04 });
      setTimeout(function() { beep({ frequency: 250, duration: 0.12, type: 'square', gain: 0.03 }); }, 100);
    },

    /** Victoria (fanfarria ascendente) */
    victory: function() {
      beep({ frequency: 520, duration: 0.12, type: 'square', gain: 0.06 });
      setTimeout(function() { beep({ frequency: 660, duration: 0.12, type: 'square', gain: 0.06 }); }, 120);
      setTimeout(function() { beep({ frequency: 880, duration: 0.18, type: 'square', gain: 0.06 }); }, 240);
    },

    /** Derrota (dos tonos descendentes tristes) */
    defeat: function() {
      beep({ frequency: 220, duration: 0.16, type: 'sawtooth', gain: 0.06 });
      setTimeout(function() { beep({ frequency: 160, duration: 0.2, type: 'sawtooth', gain: 0.06 }); }, 160);
    }
  };

  // Armar el sistema de audio en el arranque
  arm();
})();
