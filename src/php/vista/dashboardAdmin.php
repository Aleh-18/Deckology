<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Deckology - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .dash-hero {
            background: linear-gradient(135deg, var(--admin-bg) 0%, #1A6B42 100%);
            border-radius: var(--admin-radius);
            padding: 36px 40px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 28px;
            margin-bottom: 32px;
            box-shadow: 0 6px 24px rgba(13,59,46,0.3);
        }
        .dash-hero-logo {
            width: 90px; height: 90px;
            border-radius: 20px;
            object-fit: contain;
            background: rgba(255,255,255,0.12);
            padding: 8px;
            flex-shrink: 0;
        }
        .dash-hero-text h1 { font-size: 1.6rem; font-weight: 800; margin-bottom: 4px; }
        .dash-hero-text p { color: rgba(255,255,255,0.7); font-size: 0.95rem; }
        .dash-hero-text .badge-admin {
            display: inline-block;
            background: var(--admin-gold);
            color: var(--admin-bg);
            padding: 3px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 8px;
        }

        .dash-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 32px;
        }
        .dash-stat {
            background: var(--admin-white);
            border-radius: var(--admin-radius-sm);
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            border: 1px solid var(--admin-sand-light);
            box-shadow: var(--admin-shadow);
            text-decoration: none;
            color: var(--admin-text);
            transition: all 0.2s ease;
        }
        .dash-stat:hover { transform: translateY(-2px); box-shadow: var(--admin-shadow-hover); }
        .dash-stat-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .dash-stat-num { font-size: 1.6rem; font-weight: 900; line-height: 1; }
        .dash-stat-label { font-size: 0.78rem; color: var(--admin-text-muted); font-weight: 600; }

        .dash-section-title {
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--admin-text-muted);
            margin-bottom: 18px;
            text-align: center;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--admin-sand);
        }

        .dash-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 36px;
        }

        .dash-zone-card {
            position: relative;
            border-radius: var(--admin-radius);
            overflow: hidden;
            aspect-ratio: 16 / 11;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--admin-shadow);
            background: #1a2a20;
        }
        .dash-zone-card:hover { transform: translateY(-4px); box-shadow: var(--admin-shadow-hover); }
        .dash-zone-card img {
            width: 100%; height: 100%;
            object-fit: contain;
            transition: transform 0.4s ease;
        }
        .dash-zone-card:hover img { transform: scale(1.04); }
        .dash-zone-card .zone-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,0,0,0.7) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 16px 18px;
        }
        .dash-zone-card .zone-name {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 800;
            text-shadow: 0 2px 6px rgba(0,0,0,0.5);
        }
        .dash-zone-card .zone-sub {
            color: rgba(255,255,255,0.7);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .dash-action-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }
        .dash-action {
            background: var(--admin-white);
            border-radius: var(--admin-radius-sm);
            padding: 20px 16px;
            text-align: center;
            text-decoration: none;
            color: var(--admin-text);
            border: 1px solid var(--admin-sand-light);
            box-shadow: var(--admin-shadow);
            transition: all 0.25s ease;
        }
        .dash-action:hover {
            transform: translateY(-3px);
            box-shadow: var(--admin-shadow-hover);
            border-color: var(--admin-accent-light);
        }
        .dash-action i {
            font-size: 24px;
            margin-bottom: 10px;
            display: block;
        }
        .dash-action span {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
        }
        .dash-action small {
            display: block;
            font-size: 0.72rem;
            color: var(--admin-text-muted);
            margin-top: 2px;
        }

        @media (max-width: 900px) {
            .dash-hero { flex-direction: column; text-align: center; padding: 24px; }
            .dash-stats { grid-template-columns: 1fr; }
            .dash-grid { grid-template-columns: repeat(2, 1fr); }
            .dash-action-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 550px) {
            .dash-grid { grid-template-columns: 1fr; }
            .dash-action-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>
<div class="admin-layout">
    <?php $currentSection = 'dashboard'; include 'sidebar_admin.php'; ?>
    <main class="admin-content">
        <div class="admin-wrap">

        <div class="dash-hero">
            <img src="../imagenes/Logo.png" alt="Deckology" class="dash-hero-logo">
            <div class="dash-hero-text">
                <h1>Hola, <?php echo htmlspecialchars($_SESSION['nombreAdmin']); ?></h1>
                <p>Bienvenido al panel de administracion de Deckology</p>
                <span class="badge-admin"><i class="bi bi-shield-check"></i> Administrador</span>
            </div>
        </div>

        <div class="dash-stats">
            <a href="?c=Usuario&m=listarUsuarios" class="dash-stat">
                <div class="dash-stat-icon" style="background:rgba(59,130,196,0.12);color:#3B82C4;"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="dash-stat-num" style="color:#3B82C4;"><?php
                        try {
                            $pdo = new PDO('mysql:host=localhost;dbname=deckology;charset=utf8mb4', 'root', '');
                            echo $pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
                        } catch(Exception $e) { echo '?'; }
                    ?></div>
                    <div class="dash-stat-label">Usuarios</div>
                </div>
            </a>
            <a href="?c=Carta&m=listarCartas" class="dash-stat">
                <div class="dash-stat-icon" style="background:rgba(232,136,58,0.12);color:#E8883A;"><i class="bi bi-credit-card-2-front-fill"></i></div>
                <div>
                    <div class="dash-stat-num" style="color:#E8883A;"><?php
                        try {
                            echo $pdo->query('SELECT COUNT(*) FROM cartas')->fetchColumn();
                        } catch(Exception $e) { echo '?'; }
                    ?></div>
                    <div class="dash-stat-label">Cartas</div>
                </div>
            </a>
            <a href="?c=Evento&m=vistaListarEventos" class="dash-stat">
                <div class="dash-stat-icon" style="background:rgba(217,74,74,0.12);color:#D94A4A;"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div>
                    <div class="dash-stat-num" style="color:#D94A4A;"><?php
                        try {
                            echo $pdo->query('SELECT COUNT(*) FROM eventos')->fetchColumn();
                        } catch(Exception $e) { echo '?'; }
                    ?></div>
                    <div class="dash-stat-label">Eventos</div>
                </div>
            </a>
        </div>

        <div class="dash-section-title"><i class="bi bi-globe-americas"></i> Zonas del Juego</div>
        <div class="dash-grid">
            <a href="?c=Zona&m=listar" class="dash-zone-card">
                <img src="../imagenes/zonas/Bosque.png" alt="Bosque">
                <div class="zone-overlay">
                    <div class="zone-name">Bosque</div>
                    <div class="zone-sub">10 cartas - 10 eventos</div>
                </div>
            </a>
            <a href="?c=Zona&m=listar" class="dash-zone-card">
                <img src="../imagenes/zonas/ciudad.png" alt="Ciudad">
                <div class="zone-overlay">
                    <div class="zone-name">Ciudad</div>
                    <div class="zone-sub">10 cartas - 10 eventos</div>
                </div>
            </a>
            <a href="?c=Zona&m=listar" class="dash-zone-card">
                <img src="../imagenes/zonas/Mar.png" alt="Mar">
                <div class="zone-overlay">
                    <div class="zone-name">Mar</div>
                    <div class="zone-sub">10 cartas - 10 eventos</div>
                </div>
            </a>
            <a href="?c=Zona&m=listar" class="dash-zone-card">
                <img src="../imagenes/zonas/Desierto.png" alt="Desierto">
                <div class="zone-overlay">
                    <div class="zone-name">Desierto</div>
                    <div class="zone-sub">10 cartas - 10 eventos</div>
                </div>
            </a>
            <a href="?c=Zona&m=listar" class="dash-zone-card" style="grid-column: span 2;aspect-ratio:2/1;">
                <img src="../imagenes/zonas/Infinito.png" alt="Infinito">
                <div class="zone-overlay">
                    <div class="zone-name">Infinito</div>
                    <div class="zone-sub">Todas las zonas combinadas - Modo supervivencia</div>
                </div>
            </a>
        </div>

        <div class="dash-section-title"><i class="bi bi-lightning-fill"></i> Acciones Rapidas</div>
        <div class="dash-action-grid">
            <a href="?c=Carta&m=mostrarCrearCarta" class="dash-action" style="color:#E8883A;">
                <i class="bi bi-plus-circle"></i>
                <span>Crear Carta</span>
                <small>Añadir nueva carta</small>
            </a>
            <a href="?c=Evento&m=vistaCrearEvento" class="dash-action" style="color:#D94A4A;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Crear Evento</span>
                <small>Añadir nuevo evento</small>
            </a>
            <a href="?c=Icono&m=vistaCrearIcono" class="dash-action" style="color:#B8960A;">
                <i class="bi bi-emoji-smile"></i>
                <span>Crear Icono</span>
                <small>Añadir nuevo icono</small>
            </a>
            <a href="?c=Admin&m=perfilAdministrador" class="dash-action" style="color:#3B82C4;">
                <i class="bi bi-person-gear"></i>
                <span>Mi Perfil</span>
                <small>Editar perfil admin</small>
            </a>
        </div>

        </div>

    </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
