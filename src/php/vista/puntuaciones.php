<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntuaciones — Deckology</title>
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
            --dk-font: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        body { background: var(--dk-bg); font-family: var(--dk-font); }

        .deck-header { background: rgba(13,59,46,0.95); border-bottom: 1px solid var(--dk-border); }
        .deck-header .navbar-brand {
            font-weight: 900; font-size: 20px;
            background: linear-gradient(135deg, var(--dk-green-light), var(--dk-gold));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .deck-header .nav-link { color: rgba(255,255,255,0.45) !important; font-weight: 600; font-size: 14px; }
        .deck-header .nav-link:hover { color: #fff !important; }
        .deck-header .nav-link.active { color: var(--dk-green-light) !important; }
        .deck-header .nav-link.text-danger { color: #D94A4A !important; }

        .table-deck {
            background: var(--dk-surface);
            border: 1px solid var(--dk-border);
            border-radius: 14px;
            overflow: hidden;
        }
        .table-deck thead th {
            background: rgba(255,255,255,0.04);
            border-bottom: 1px solid var(--dk-border);
            font-size: 0.7rem; text-transform: uppercase;
            letter-spacing: 1px; font-weight: 800;
            color: rgba(255,255,255,0.3);
            padding: 14px 18px;
        }
        .table-deck tbody td {
            padding: 13px 18px; color: #fff;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            vertical-align: middle;
        }
        .table-deck tbody tr:last-child td { border-bottom: none; }
        .table-deck tbody tr:hover td { background: rgba(255,255,255,0.03); }

        .rank-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 50%;
            font-size: 0.8rem; font-weight: 900;
        }
        .rank-1 { background: rgba(232,200,74,0.15); color: var(--dk-gold); border: 1px solid rgba(232,200,74,0.3); }
        .rank-2 { background: rgba(192,192,192,0.12); color: #C0C0C0; border: 1px solid rgba(192,192,192,0.2); }
        .rank-3 { background: rgba(205,127,50,0.12); color: #CD7F32; border: 1px solid rgba(205,127,50,0.2); }
        .rank-other { background: rgba(255,255,255,0.04); color: rgba(255,255,255,0.25); }

        .form-control-dark {
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.1);
            color: #fff; border-radius: 10px;
        }
        .form-control-dark:focus {
            background: rgba(255,255,255,0.08);
            border-color: var(--dk-green-light);
            box-shadow: 0 0 0 3px rgba(110,203,140,0.15);
            color: #fff;
        }
        .form-control-dark::placeholder { color: rgba(255,255,255,0.25); }
        .btn-deck-outline { background: transparent; border: 1px solid var(--dk-border); color: rgba(255,255,255,0.6); }
        .btn-deck-outline:hover { background: rgba(255,255,255,0.06); color: #fff; }
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
                    <li class="nav-item"><a class="nav-link active" href="?c=Usuario&m=obtenerPuntuaciones">Puntuaciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="?c=Usuario&m=perfilUsuario">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="?c=Usuario&m=cerrarSesion">Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4" style="max-width: 650px;">
        <div class="text-center mb-4">
            <h2 class="text-white fw-bold"><i class="bi bi-trophy me-2"></i>Tabla de Puntuaciones</h2>
            <p class="text-secondary small">Los mejores jugadores de Deckology</p>
        </div>

        <div class="d-flex gap-2 mb-3">
            <input type="text" id="filtroNombre" class="form-control form-control-dark" placeholder="Buscar jugador..." />
            <button id="botonOrdenar" class="btn btn-deck-outline rounded-3 px-3">
                <i class="bi bi-arrow-down-up"></i>
            </button>
        </div>

        <div class="table-deck">
            <table class="table mb-0" id="tablaPuntuaciones">
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Jugador</th>
                        <th style="text-align:right; width:100px;">Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pos = 1;
                    foreach ($datos as $fila) {
                        $rankClass = 'rank-other';
                        if ($pos == 1) $rankClass = 'rank-1';
                        elseif ($pos == 2) $rankClass = 'rank-2';
                        elseif ($pos == 3) $rankClass = 'rank-3';
                        echo '<tr>';
                        echo '<td><span class="rank-badge ' . $rankClass . '">' . $pos . '</span></td>';
                        echo '<td class="fw-bold">' . htmlspecialchars($fila['nombre']) . '</td>';
                        echo '<td style="text-align:right;"><span class="fw-bold" style="color:var(--dk-gold);">' . number_format($fila['puntuacion']) . '</span></td>';
                        echo '</tr>';
                        $pos++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/vista/puntuaciones.js"></script>
</body>
</html>
