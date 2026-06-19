<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil — Deckology</title>
    <link rel="icon" type="image/png" href="../imagenes/Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --dk-bg: #0A1F17;
            --dk-surface: rgba(255,255,255,0.04);
            --dk-border: rgba(255,255,255,0.08);
            --dk-green: #289F5C;
            --dk-green-deep: #1A6B42;
            --dk-green-light: #6ECB8C;
            --dk-gold: #E8C84A;
            --dk-orange: #E8883A;
            --dk-red: #D94A4A;
            --dk-blue: #3B82C4;
            --dk-font: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        body { background: var(--dk-bg); font-family: var(--dk-font); }

        .deck-header {
            background: rgba(13,59,46,0.95);
            border-bottom: 1px solid var(--dk-border);
        }
        .deck-header .navbar-brand {
            font-weight: 900; font-size: 20px;
            background: linear-gradient(135deg, var(--dk-green-light), var(--dk-gold));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .deck-header .nav-link { color: rgba(255,255,255,0.45) !important; font-weight: 600; font-size: 14px; }
        .deck-header .nav-link:hover { color: #fff !important; }
        .deck-header .nav-link.active { color: var(--dk-green-light) !important; }
        .deck-header .nav-link.text-danger { color: var(--dk-red) !important; }

        .profile-avatar {
            width: 96px; height: 96px; border-radius: 50%;
            background: linear-gradient(135deg, var(--dk-green-deep), var(--dk-green));
            border: 3px solid rgba(110,203,140,0.25);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 40px rgba(40,159,92,0.15);
        }
        .profile-avatar i { font-size: 40px; color: rgba(255,255,255,0.85); }

        .rank-badge {
            background: rgba(232,200,74,0.1);
            border: 1px solid rgba(232,200,74,0.2);
            color: var(--dk-gold);
        }

        .stat-card {
            background: var(--dk-surface);
            border: 1px solid var(--dk-border);
            border-radius: 14px;
            transition: 0.2s ease;
        }
        .stat-card:hover { background: rgba(255,255,255,0.06); transform: translateY(-2px); }
        .stat-card .stat-value { font-size: 1.5rem; font-weight: 900; }
        .stat-card .stat-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
        .stat-card.score .stat-value { color: var(--dk-gold); }
        .stat-card.rank .stat-value { color: var(--dk-green-light); }
        .stat-card.days .stat-value { color: var(--dk-orange); }
        .stat-card.players .stat-value { color: var(--dk-blue); }
        .stat-card.score i, .stat-card.rank i, .stat-card.days i, .stat-card.players i {
            font-size: 1.6rem; margin-bottom: 0.5rem; opacity: 0.8;
        }

        .info-section {
            background: var(--dk-surface);
            border: 1px solid var(--dk-border);
            border-radius: 14px;
        }
        .info-section .section-title {
            font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;
            font-weight: 800; color: rgba(255,255,255,0.3);
        }
        .info-row {
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .info-row:last-child { border-bottom: none; }

        .btn-deck-primary {
            background: linear-gradient(135deg, var(--dk-green), var(--dk-green-deep));
            border: none; color: #fff; font-weight: 700;
        }
        .btn-deck-primary:hover { background: linear-gradient(135deg, var(--dk-green-deep), #145a35); color: #fff; }
        .btn-deck-outline {
            background: transparent; border: 1px solid var(--dk-border);
            color: rgba(255,255,255,0.6);
        }
        .btn-deck-outline:hover { background: rgba(255,255,255,0.06); color: #fff; }

        .flash-msg { animation: flashIn 0.3s ease; }
        @keyframes flashIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg deck-header">
        <div class="container">
            <a class="navbar-brand" href="?c=Usuario&m=mostrarZonas">Deckology</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list text-white fs-4"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-3">
                    <li class="nav-item"><a class="nav-link" href="?c=Usuario&m=mostrarZonas">Zonas</a></li>
                    <li class="nav-item"><a class="nav-link" href="?c=Usuario&m=obtenerPuntuaciones">Puntuaciones</a></li>
                    <li class="nav-item"><a class="nav-link active" href="?c=Usuario&m=perfilUsuario">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="?c=Usuario&m=cerrarSesion">Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4" style="max-width: 620px;">
        <?php if(isset($datos['mensaje']) && !empty($datos['mensaje'])): ?>
            <div class="flash-msg alert <?php echo strpos($datos['mensaje'], 'Error') !== false ? 'alert-danger' : 'alert-success'; ?> border-0" role="alert">
                <i class="bi bi-<?php echo strpos($datos['mensaje'], 'Error') !== false ? 'exclamation-triangle' : 'check-circle'; ?> me-2"></i>
                <?php echo htmlspecialchars($datos['mensaje']); ?>
            </div>
        <?php endif; ?>

        <!-- Profile header -->
        <div class="text-center mb-4">
            <div class="profile-avatar mx-auto mb-3">
                <i class="bi bi-person-fill"></i>
            </div>
            <h2 class="text-white fw-bold mb-1"><?php echo htmlspecialchars($datos['filaUsuario']['nombre'] ?? ''); ?></h2>
            <p class="text-secondary small mb-2"><?php echo htmlspecialchars($datos['filaUsuario']['email'] ?? ''); ?></p>
            <span class="badge rank-badge rounded-pill px-3 py-2">
                <i class="bi bi-shield-fill-check me-1"></i>
                <?php echo htmlspecialchars($datos['stats']['nivel'] ?? 'Novato'); ?>
            </span>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="stat-card score p-3 text-center rounded-4">
                    <i class="bi bi-trophy-fill d-block"></i>
                    <div class="stat-value"><?php echo number_format($datos['filaUsuario']['puntuacion'] ?? 0); ?></div>
                    <div class="stat-label text-secondary">Puntuación</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card rank p-3 text-center rounded-4">
                    <i class="bi bi-bar-chart-fill d-block"></i>
                    <div class="stat-value">#<?php echo $datos['stats']['rank'] ?? '—'; ?> / <?php echo $datos['stats']['total_jugadores'] ?? '0'; ?></div>
                    <div class="stat-label text-secondary">Ranking</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card days p-3 text-center rounded-4">
                    <i class="bi bi-calendar-check d-block"></i>
                    <div class="stat-value"><?php echo $datos['stats']['dias_jugador'] ?? '0'; ?></div>
                    <div class="stat-label text-secondary">Dias Jugando</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card players p-3 text-center rounded-4">
                    <i class="bi bi-people-fill d-block"></i>
                    <div class="stat-value"><?php echo $datos['stats']['total_jugadores'] ?? '0'; ?></div>
                    <div class="stat-label text-secondary">Jugadores</div>
                </div>
            </div>
        </div>

        <!-- Account info -->
        <div class="info-section p-4 mb-4">
            <h6 class="section-title mb-3">Información de la cuenta</h6>
            <div class="d-flex justify-content-between align-items-center info-row py-2">
                <span class="text-secondary">Nombre</span>
                <span class="text-white fw-bold"><?php echo htmlspecialchars($datos['filaUsuario']['nombre'] ?? ''); ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center info-row py-2">
                <span class="text-secondary">Email</span>
                <span class="text-white fw-bold"><?php echo htmlspecialchars($datos['filaUsuario']['email'] ?? ''); ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center info-row py-2">
                <span class="text-secondary">Rol</span>
                <span class="text-white fw-bold"><?php echo ucfirst(htmlspecialchars($datos['filaUsuario']['rol'] ?? 'player')); ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2">
                <span class="text-secondary">Registro</span>
                <span class="text-white fw-bold">
                    <?php
                    $fecha = $datos['fecha_registro'] ?? null;
                    if ($fecha) {
                        $dt = new DateTime($fecha);
                        echo $dt->format('d/m/Y');
                    } else {
                        echo '—';
                    }
                    ?>
                </span>
            </div>
        </div>

        <!-- Actions -->
        <div class="d-grid gap-2">
            <a href="?c=Usuario&m=vistaEditarPerfil" class="btn btn-deck-primary rounded-3 py-2">
                <i class="bi bi-pencil-square me-2"></i>Editar nombre
            </a>
            <a href="?c=Usuario&m=vistaModificarcontrasena" class="btn btn-deck-outline rounded-3 py-2">
                <i class="bi bi-lock me-2"></i>Cambiar contrasena
            </a>
            <a href="?c=Usuario&m=mostrarZonas" class="btn btn-deck-outline rounded-3 py-2">
                <i class="bi bi-arrow-left me-2"></i>Volver a zonas
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
