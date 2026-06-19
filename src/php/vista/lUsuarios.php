<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509,05587">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-layout">
    <?php $currentSection = 'usuarios'; include 'sidebar_admin.php'; ?>
    <main class="admin-content">
        <div class="admin-wrap">
            <div class="page-header">
                <h1 class="page-title"><i class="bi bi-people-fill"></i> Usuarios</h1>
            </div>
            <div class="search-bar">
                <div class="search-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" id="busqueda" class="search-input" placeholder="Buscar usuario...">
                </div>
            </div>
            <div class="table-card">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th><i class="bi bi-person"></i> Nombre</th>
                            <th><i class="bi bi-envelope"></i> Correo</th>
                            <th><i class="bi bi-shield"></i> Rol</th>
                            <th><i class="bi bi-trophy"></i> Puntos</th>
                            <th><i class="bi bi-trash3"></i> Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($datos)): foreach($datos as $usuario): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td>
                                <?php if (($usuario['rol'] ?? '') === 'admin'): ?>
                                    <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(160,108,206,0.12);color:#A06CCE;padding:3px 10px;border-radius:50px;font-size:0.78rem;font-weight:700;"><i class="bi bi-shield-fill"></i> Admin</span>
                                <?php else: ?>
                                    <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(40,159,92,0.12);color:#289F5C;padding:3px 10px;border-radius:50px;font-size:0.78rem;font-weight:700;"><i class="bi bi-person-fill"></i> Jugador</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($usuario['puntuacion'] ?? 0); ?></strong></td>
                            <td>
                                <?php if (($usuario['rol'] ?? '') !== 'admin'): ?>
                                    <a href="?c=Usuario&m=eliminarUsuario&idTarget=<?php echo $usuario['id_usuario']; ?>" onclick="return confirm('¿Eliminar este usuario?')">
                                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></button>
                                    </a>
                                <?php else: ?>
                                    <span style="color:var(--admin-text-muted);">--</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="5" style="text-align:center;color:var(--admin-text-muted);">No hay usuarios registrados</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="../js/vista/admin_busqueda.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
