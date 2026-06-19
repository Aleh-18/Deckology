<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Zonas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509,05587">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-layout">
    <?php $currentSection = 'zonas'; include 'sidebar_admin.php'; ?>
    <main class="admin-content">
        <div class="admin-wrap">
            <div class="page-header">
                <h1 class="page-title"><i class="bi bi-globe-americas"></i> Zonas</h1>
                <a href="index.php?c=Zona&m=mostrarCrear" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Crear Zona</a>
            </div>
            <div class="search-bar">
                <div class="search-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" id="busqueda" class="search-input" placeholder="Buscar zona...">
                </div>
            </div>
            <div class="table-card">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th><i class="bi bi-globe"></i> Nombre</th>
                            <th><i class="bi bi-image"></i> Imagen</th>
                            <th><i class="bi bi-card-image"></i> Fondo</th>
                            <th><i class="bi bi-credit-card"></i> Cartas</th>
                            <th><i class="bi bi-exclamation-triangle"></i> Eventos</th>
                            <th colspan="2"><i class="bi bi-gear"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($datos)):
                            foreach ($datos as $fila):
                                $esInfinito = (int)($fila['id_zona'] ?? 0) === 0 || strtolower($fila['nombre'] ?? '') === 'infinito';
                            ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($fila['nombre']); ?></strong></td>
                            <td>
                                <?php if (!empty($fila['imagenZona'])): ?>
                                    <img src="<?php echo $fila['imagenZona']; ?>" width="56" height="56" style="border-radius:8px;object-fit:contain;">
                                <?php else: ?>
                                    <span style="color:var(--admin-text-muted);">Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($fila['fondoZona'])): ?>
                                    <img src="<?php echo $fila['fondoZona']; ?>" width="56" height="56" style="border-radius:8px;object-fit:contain;">
                                <?php else: ?>
                                    <span style="color:var(--admin-text-muted);">Sin fondo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($fila['imagenCartas'])): ?>
                                    <img src="<?php echo $fila['imagenCartas']; ?>" width="56" height="56" style="border-radius:8px;object-fit:contain;">
                                <?php else: ?>
                                    <span style="color:var(--admin-text-muted);">Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($fila['imagenEventos'])): ?>
                                    <img src="<?php echo $fila['imagenEventos']; ?>" width="56" height="56" style="border-radius:8px;object-fit:contain;">
                                <?php else: ?>
                                    <span style="color:var(--admin-text-muted);">Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($esInfinito): ?>
                                    <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(232,200,74,0.12);color:#B8960A;padding:3px 10px;border-radius:50px;font-size:0.78rem;font-weight:700;"><i class="bi bi-infinity"></i> Especial</span>
                                <?php else: ?>
                                    <a href="index.php?c=Zona&m=confirmarEliminar&id_zona=<?php echo $fila['id_zona']; ?>">
                                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></button>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($esInfinito): ?>
                                    <span style="color:var(--admin-text-muted);">--</span>
                                <?php else: ?>
                                    <a href="index.php?c=Zona&m=mostrarEditar&id_zona=<?php echo $fila['id_zona']; ?>">
                                        <button class="btn btn-info btn-sm"><i class="bi bi-pencil"></i></button>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="7" style="text-align:center;color:var(--admin-text-muted);">No hay zonas registradas</td></tr>
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
