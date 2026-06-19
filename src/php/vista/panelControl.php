<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deckology - Panel de Control</title>
    <link rel="icon" type="image/png" href="../imagenes/Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #0a1f16;
            --surface: #112e22;
            --surface2: #163a2b;
            --border: rgba(255,255,255,0.08);
            --green: #289F5C;
            --green-light: #6ECB8C;
            --gold: #E8C84A;
            --red: #D94A4A;
        }
        body {
            background: var(--bg);
            color: #fff;
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
        }
        .navbar-custom {
            background: rgba(13,59,46,0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 10px 24px;
        }
        .navbar-custom .brand {
            font-size: 18px; font-weight: 800;
            background: linear-gradient(135deg, var(--green-light), var(--gold));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .container-main {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .card-dark {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px;
            margin-bottom: 24px;
        }
        .card-dark h3 {
            font-size: 16px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1px;
            color: var(--green-light);
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .form-floating-dark .form-control {
            background: var(--surface2);
            border: 1px solid var(--border);
            color: #fff;
            border-radius: 10px;
            padding: 14px 14px;
            font-size: 14px;
        }
        .form-floating-dark .form-control:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(40,159,92,0.2);
            background: var(--surface2);
            color: #fff;
        }
        .form-floating-dark .form-label {
            font-size: 12px; font-weight: 600;
            color: rgba(255,255,255,0.5);
            margin-bottom: 4px;
        }
        .form-floating-dark select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='rgba(255,255,255,0.5)' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }
        .form-floating-dark select.form-control option {
            background: var(--surface2);
            color: #fff;
        }
        .btn-create {
            background: linear-gradient(135deg, var(--green), var(--green-light));
            border: none;
            color: #fff;
            font-weight: 800;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40,159,92,0.35);
            color: #fff;
        }
        .alert-custom {
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 16px;
            border: none;
        }
        .alert-ok {
            background: rgba(40,159,92,0.15);
            color: var(--green-light);
            border: 1px solid rgba(40,159,92,0.2);
        }
        .alert-error {
            background: rgba(217,74,74,0.15);
            color: #ff8a8a;
            border: 1px solid rgba(217,74,74,0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            font-size: 10px; font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.35);
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        td {
            padding: 10px 12px;
            font-size: 13px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            vertical-align: middle;
        }
        tr:hover td { background: rgba(255,255,255,0.02); }
        .badge-admin {
            background: rgba(232,200,74,0.15);
            color: var(--gold);
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .badge-player {
            background: rgba(110,203,140,0.12);
            color: var(--green-light);
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .stats-row {
            display: flex; gap: 20px;
            margin-bottom: 20px;
        }
        .stat-box {
            flex: 1;
            background: var(--surface2);
            border-radius: 10px;
            padding: 16px;
            text-align: center;
            border: 1px solid var(--border);
        }
        .stat-box .stat-num {
            font-size: 28px; font-weight: 900;
            color: var(--green-light);
        }
        .stat-box .stat-label {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-top: 2px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .form-grid .full { grid-column: 1 / -1; }
        .icon-preview {
            font-size: 28px;
            margin-top: 6px;
        }
        .no-users {
            text-align: center;
            padding: 30px;
            color: rgba(255,255,255,0.25);
            font-size: 13px;
        }
    </style>
</head>
<body>
    <nav class="navbar-custom d-flex align-items-center justify-content-between">
        <span class="brand">Deckology</span>
        <a href="?c=Usuario&m=panelControl" class="text-white text-decoration-none" style="font-size:13px;">
            <i class="bi bi-gear-wide-connected"></i> Panel de Control
        </a>
    </nav>

    <div class="container-main">
        <div class="stats-row">
            <div class="stat-box">
                <div class="stat-num"><?php echo count(array_filter($usuarios, function($u) { return $u['rol'] === 'admin'; })); ?></div>
                <div class="stat-label">Administradores</div>
            </div>
            <div class="stat-box">
                <div class="stat-num"><?php echo count(array_filter($usuarios, function($u) { return $u['rol'] === 'player'; })); ?></div>
                <div class="stat-label">Jugadores</div>
            </div>
            <div class="stat-box">
                <div class="stat-num"><?php echo count($usuarios); ?></div>
                <div class="stat-label">Total Usuarios</div>
            </div>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="alert-custom <?php echo $tipo === 'ok' ? 'alert-ok' : 'alert-error'; ?>">
                <i class="bi <?php echo $tipo === 'ok' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?>"></i>
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <div class="card-dark">
            <h3><i class="bi bi-person-plus-fill"></i> Crear Nuevo Usuario</h3>
            <form method="POST" action="?c=Usuario&m=panelControl">
                <div class="form-grid">
                    <div>
                        <div class="form-floating-dark mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                        </div>
                    </div>
                    <div>
                        <div class="form-floating-dark mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@ejemplo.com" required>
                        </div>
                    </div>
                    <div>
                        <div class="form-floating-dark mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="text" name="contrasena" class="form-control" placeholder="Contraseña" required>
                        </div>
                    </div>
                    <div>
                        <div class="form-floating-dark mb-3">
                            <label class="form-label">Rol</label>
                            <select name="rol" class="form-control">
                                <option value="admin">Administrador</option>
                                <option value="player">Jugador</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-create">
                    <i class="bi bi-plus-circle-fill"></i> Crear Usuario
                </button>
            </form>
        </div>

        <div class="card-dark">
            <h3><i class="bi bi-people-fill"></i> Usuarios Existentes (<?php echo count($usuarios); ?>)</h3>
            <?php if (empty($usuarios)): ?>
                <div class="no-users">No hay usuarios registrados.</div>
            <?php else: ?>
                <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Puntos</th>
                            <th>Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td style="color:rgba(255,255,255,0.3);">#<?php echo $u['id_usuario']; ?></td>
                            <td style="font-weight:700;"><?php echo htmlspecialchars($u['nombre']); ?></td>
                            <td style="color:rgba(255,255,255,0.6);"><?php echo htmlspecialchars($u['email']); ?></td>
                            <td>
                                <span class="<?php echo $u['rol'] === 'admin' ? 'badge-admin' : 'badge-player'; ?>">
                                    <?php echo $u['rol']; ?>
                                </span>
                            </td>
                            <td style="font-weight:700;color:var(--green-light);"><?php echo $u['puntuacion']; ?></td>
                            <td style="color:rgba(255,255,255,0.4);font-size:12px;">
                                <?php echo date('d/m/Y H:i', strtotime($u['fecha_registro'])); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
