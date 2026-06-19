/**
 * MusicPlayer - Reproductor de musica de fondo para Deckology
 * 
 * Usa un unico archivo de audio con timestamps para cada pista.
 * Reproduce en bucle, muestra nombre de pista actual, y permite
 * saltar entre pistas. Guarda posicion en localStorage para
 * que la musica continue al refrescar la pagina.
 */
var MusicPlayer = (function() {
    var audio = null;
    var trackIndex = 0;
    var isPlaying = false;
    var isMuted = false;
    var volume = 0.25;
    var onTrackChange = null;
    var restoreKey = 'deckology_music';
    var autoplayHandler = null;

    var tracks = [
        { name: "Viridian City",          start: 0,        end: 72      },
        { name: "Bubblaine",              start: 73,       end: 298     },
        { name: "Town Theme",             start: 299,      end: 413     },
        { name: "Training Menu",          start: 414,      end: 563     },
        { name: "GracieGrace",            start: 564,      end: 644     },
        { name: "Salon Pokemon",          start: 645,      end: 757     },
        { name: "Bath Time",              start: 758,      end: 1000    },
        { name: "Garden Gridlock",        start: 1001,     end: 1178    },
        { name: "Basic U",                start: 1179,     end: 1307    },
        { name: "Interior Shop",          start: 1308,     end: 1375    },
        { name: "Retail",                 start: 1376,     end: 1462    },
        { name: "World 1 - Yoshi",        start: 1463,     end: 1619    },
        { name: "Have a Break",           start: 1620,     end: 1735    },
        { name: "Enid Chen",              start: 1736,     end: 1896    },
        { name: "Shopping in Wakeport",   start: 1897,     end: 2044    },
        { name: "Apartment Theme",        start: 2045,     end: 2231    },
        { name: "Mii Homes",              start: 2232,     end: 2314    },
        { name: "Training 1",             start: 2315,     end: 2519    },
        { name: "Rate Your Vid",          start: 2520,     end: 2613    },
        { name: "Rustboro City",          start: 2614,     end: 2742    },
        { name: "5pm Animal Crossing",    start: 2743,     end: 2895    },
        { name: "Gymnasium Results",      start: 2896,     end: 3042    },
        { name: "Mii Maker",              start: 3043,     end: 3182    },
        { name: "Body Measurements",      start: 3183,     end: 3297    },
        { name: "Progress Report",        start: 3298,     end: 3393    },
        { name: "Sponge Cave",            start: 3394,     end: 3575    },
        { name: "Beaumonde City",         start: 3576,     end: 3780    },
        { name: "Virbank City",           start: 3781,     end: 3888    },
        { name: "Dock Arrival",           start: 3889,     end: 4009    },
        { name: "Go with the Bros",       start: 4010,     end: 4122    },
        { name: "Soy Style",              start: 4123,     end: 4311    },
        { name: "Animal Tracker",         start: 4312,     end: 4528    },
        { name: "Cafe Petrov",            start: 4529,     end: 4762    },
        { name: "Bowling",                start: 4763,     end: 4919    },
        { name: "Hair Salon",             start: 4920,     end: 5028    },
        { name: "Ranking Board",          start: 5029,     end: 5144    },
        { name: "Chill Theme 3",          start: 5145,     end: 5436    },
        { name: "Your Words",             start: 5437,     end: 5586    },
        { name: "Main Menu",              start: 5587,     end: 5739    },
        { name: "Nintendo Shop",          start: 5740,     end: 5873    },
        { name: "Trophy Gallery",         start: 5874,     end: 6053    },
        { name: "Gate Pokemon X/Y",       start: 6054,     end: 6143    },
        { name: "Callie's Theme",         start: 6144,     end: 6346    },
        { name: "Bubblaine Underwater",   start: 6347,     end: 6581    }
    ];

    function saveState() {
        try {
            localStorage.setItem(restoreKey, JSON.stringify({
                track: trackIndex,
                time: audio ? audio.currentTime : 0,
                playing: isPlaying,
                muted: isMuted,
                volume: volume,
                savedAt: Date.now()
            }));
        } catch(e) {}
    }

    function loadState() {
        try {
            var raw = localStorage.getItem(restoreKey);
            return raw ? JSON.parse(raw) : null;
        } catch(e) { return null; }
    }

    function init() {
        audio = document.getElementById('bg-music');
        if (!audio) return;

        audio.volume = volume;
        audio.preload = 'auto';

        audio.addEventListener('timeupdate', onTimeUpdate);
        audio.addEventListener('ended', function() { next(); });
        audio.addEventListener('error', function(e) {
            console.warn('[MusicPlayer] Error de audio:', e);
        });

        // Restaurar estado anterior
        var state = loadState();
        if (state) {
            trackIndex = state.track || 0;
            isMuted = state.muted || false;
            if (typeof state.volume === 'number') volume = state.volume;
            audio.volume = isMuted ? 0 : volume;
        }

        updateUI();
    }

    function onTimeUpdate() {
        if (!audio || !tracks[trackIndex]) return;
        var current = audio.currentTime;
        var track = tracks[trackIndex];
        if (current >= track.end) {
            next();
        }
        // Guardar cada 2 segundos
        if (Math.floor(current) % 2 === 0) saveState();
    }

    var pendingAutoplay = false;

    function play() {
        if (!audio) return;

        // Restaurar pista guardada
        var state = loadState();
        if (state && typeof state.track === 'number' && state.track !== trackIndex) {
            trackIndex = state.track;
            updateUI();
        }

        var startPos = tracks[trackIndex].start;

        function doPlay() {
            audio.currentTime = startPos;
            // Estrategia: mutear primero para bypass de autoplay, luego restaurar volumen
            var wasMuted = isMuted;
            var wasVolume = audio.volume;
            audio.muted = true;
            audio.play().then(function() {
                audio.muted = wasMuted;
                audio.volume = wasMuted ? 0 : wasVolume;
                isPlaying = true;
                pendingAutoplay = false;
                saveState();
                updateUI();
                removeAutoplayListener();
            }).catch(function(e) {
                // Si incluso mutado falla, esperar interaccion del usuario
                console.warn('[MusicPlayer] Autoplay bloqueado, esperando click...');
                audio.muted = false;
                pendingAutoplay = true;
                waitForInteraction();
            });
        }

        function waitForInteraction() {
            if (autoplayHandler) return;
            autoplayHandler = function() {
                if (!pendingAutoplay) return;
                audio.play().then(function() {
                    isPlaying = true;
                    pendingAutoplay = false;
                    saveState();
                    updateUI();
                    removeAutoplayListener();
                }).catch(function() {});
            };
            document.addEventListener('click', autoplayHandler, { once: false });
            document.addEventListener('keydown', autoplayHandler, { once: false });
        }

        function removeAutoplayListener() {
            if (autoplayHandler) {
                document.removeEventListener('click', autoplayHandler);
                document.removeEventListener('keydown', autoplayHandler);
                autoplayHandler = null;
            }
        }

        if (audio.readyState >= 2) {
            doPlay();
        } else {
            var checkInterval = setInterval(function() {
                if (audio.readyState >= 2) {
                    clearInterval(checkInterval);
                    doPlay();
                }
            }, 100);
            setTimeout(function() {
                clearInterval(checkInterval);
                if (!isPlaying) doPlay();
            }, 4000);
        }
    }

    function pause() {
        if (!audio) return;
        audio.pause();
        isPlaying = false;
        saveState();
        updateUI();
    }

    function toggle() {
        if (isPlaying) pause();
        else play();
    }

    function next() {
        trackIndex = (trackIndex + 1) % tracks.length;
        seekToTrack();
    }

    function prev() {
        trackIndex = (trackIndex - 1 + tracks.length) % tracks.length;
        seekToTrack();
    }

    function seekToTrack() {
        if (!audio) return;
        audio.currentTime = tracks[trackIndex].start;
        if (isPlaying) {
            audio.play().catch(function() {});
        }
        saveState();
        updateUI();
    }

    function setVolume(v) {
        volume = Math.max(0, Math.min(1, v));
        if (audio && !isMuted) audio.volume = volume;
    }

    function toggleMute() {
        isMuted = !isMuted;
        if (audio) audio.volume = isMuted ? 0 : volume;
        saveState();
        updateUI();
        return isMuted;
    }

    function setMuted(muted) {
        isMuted = muted;
        if (audio) audio.volume = isMuted ? 0 : volume;
        saveState();
        updateUI();
    }

    function updateUI() {
        var nameEl = document.getElementById('track-name');
        if (nameEl && tracks[trackIndex]) {
            nameEl.textContent = tracks[trackIndex].name;
        }
        // Actualizar icono de play/pausa
        var toggleBtn = document.getElementById('btn-music-toggle');
        if (toggleBtn) {
            var icon = toggleBtn.querySelector('i');
            if (icon) {
                if (isPlaying) {
                    icon.className = 'bi bi-pause-fill';
                    toggleBtn.style.opacity = '1';
                } else {
                    icon.className = 'bi bi-play-fill';
                    toggleBtn.style.opacity = isMuted ? '0.35' : '0.7';
                }
            }
        }
        // Actualizar boton de mute de musica
        var muteBtn = document.getElementById('btn-mute-music');
        if (muteBtn) {
            var muteIcon = muteBtn.querySelector('i');
            if (muteIcon) {
                if (isMuted) {
                    muteIcon.className = 'bi bi-volume-mute';
                    muteBtn.style.color = '#D94A4A';
                } else {
                    muteIcon.className = 'bi bi-music-note-beamed';
                    muteBtn.style.color = '';
                }
            }
        }
        if (onTrackChange) onTrackChange(tracks[trackIndex], isPlaying);
    }

    return {
        init: init,
        play: play,
        pause: pause,
        toggle: toggle,
        next: next,
        prev: prev,
        setVolume: setVolume,
        toggleMute: toggleMute,
        setMuted: setMuted,
        getTrack: function() { return tracks[trackIndex]; },
        isPlaying: function() { return isPlaying; },
        getVolume: function() { return volume; },
        isMuted: function() { return isMuted; },
        onTrackChange: function(cb) { onTrackChange = cb; }
    };
})();
