<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil — Deckology</title>
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
            --dk-font: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        body { background: var(--dk-bg); font-family: var(--dk-font); }

        .deck-header { background: rgba(13,59,46,0.95); border-bottom: 1px solid var(--dk-border); }
        .deck-header .navbar-brand {
            font-weight: 900; font-size: 20px;
            background: linear-gradient(135deg, var(--dk-green-light), #E8C84A);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .deck-header .nav-link { color: rgba(255,255,255,0.45) !important; font-weight: 600; font-size: 14px; }
        .deck-header .nav-link:hover { color: #fff !important; }
        .deck-header .nav-link.text-danger { color: #D94A4A !important; }

        .form-card {
            background: var(--dk-surface);
            border: 1px solid var(--dk-border);
            border-radius: 16px;
        }
        .form-control-dark {
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.1);
            color: #fff;
            border-radius: 10px;
            transition: 0.2s ease;
        }
        .form-control-dark:focus {
            background: rgba(255,255,255,0.08);
            border-color: var(--dk-green-light);
            box-shadow: 0 0 0 3px rgba(110,203,140,0.15);
            color: #fff;
        }
        .form-control-dark::placeholder { color: rgba(255,255,255,0.25); }
        .form-label-dark { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; color: rgba(255,255,255,0.4); }

        .btn-deck-primary { background: linear-gradient(135deg, var(--dk-green), var(--dk-green-deep)); border: none; color: #fff; font-weight: 700; }
        .btn-deck-primary:hover { background: linear-gradient(135deg, var(--dk-green-deep), #145a35); color: #fff; }
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
                    <li class="nav-item"><a class="nav-link" href="?c=Usuario&m=perfilUsuario">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="?c=Usuario&m=cerrarSesion">Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4" style="max-width: 480px;">
        <?php if(isset($datos['mensaje']) && !empty($datos['mensaje'])): ?>
            <div class="alert alert-danger border-0 flash-msg" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?php echo htmlspecialchars($datos['mensaje']); ?>
            </div>
        <?php endif; ?>

        <div class="form-card p-4">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-pencil-square text-success fs-4"></i>
                <h4 class="text-white fw-bold mb-0">Editar perfil</h4>
            </div>
            <p class="text-secondary small mb-4">Actualiza tu nombre de usuario</p>

            <form method="POST" action="?c=Usuario&m=procesarEditarPerfil">
                <div class="mb-4">
                    <label class="form-label form-label-dark">Nombre de usuario</label>
                    <input type="text" name="nombre" class="form-control form-control-dark"
                           value="<?php echo htmlspecialchars($datos['filaUsuario']['nombre'] ?? ''); ?>"
                           required placeholder="Tu nombre de jugador" />
                </div>
                <div class="d-flex gap-2">
                    <a href="?c=Usuario&m=perfilUsuario" class="btn btn-deck-outline flex-fill rounded-3 py-2">Cancelar</a>
                    <button type="submit" class="btn btn-deck-primary flex-fill rounded-3 py-2">
                        <i class="bi bi-check-lg me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
