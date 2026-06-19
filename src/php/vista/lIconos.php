<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar Iconos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509,05587">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-layout">
    <?php $currentSection = 'iconos'; include 'sidebar_admin.php'; ?>
    <main class="admin-content">
        <div class="admin-wrap">
            <div class="page-header">
                <h1 class="page-title"><i class="bi bi-emoji-smile-fill"></i> Iconos</h1>
                <a href="?c=Icono&m=vistaCrearIcono" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Crear Icono</a>
            </div>
            <div class="search-bar">
                <div class="search-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" id="busqueda" class="search-input" placeholder="Buscar icono...">
                </div>
            </div>
            <div class="table-card">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th><i class="bi bi-emoji-smile"></i> Icono</th>
                            <th><i class="bi bi-tag"></i> Nombre</th>
                            <th><i class="bi bi-code-slash"></i> Código</th>
                            <th><i class="bi bi-trash3"></i> Eliminar</th>
                            <th><i class="bi bi-pencil"></i> Modificar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $icono): ?>
                        <tr>
                            <td style="font-size:1.4em;"><?php echo htmlspecialchars($icono['codigo']); ?></td>
                            <td><strong><?php echo htmlspecialchars($icono['nombre']); ?></strong></td>
                            <td><code style="background:var(--admin-sand-light);padding:2px 8px;border-radius:6px;font-size:0.82rem;"><?php echo htmlspecialchars($icono['codigo']); ?></code></td>
                            <td>
                                <a href="?c=Icono&m=eliminarIcono&id=<?php echo $icono['id']; ?>" onclick="return confirm('¿Eliminar este icono?')">
                                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></button>
                                </a>
                            </td>
                            <td>
                                <a href="?c=Icono&m=mostrarModificarIcono&id=<?php echo $icono['id']; ?>">
                                    <button class="btn btn-info btn-sm"><i class="bi bi-pencil"></i></button>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
