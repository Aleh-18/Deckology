<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Zona</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-layout">
    <?php $currentSection = 'zonas'; include 'sidebar_admin.php'; ?>
    <main class="admin-content">
        <div class="admin-wrap">
        <div class="page-header">
            <h1 class="page-title"><i class="bi bi-exclamation-triangle" style="color:var(--admin-red);"></i> Eliminar Zona</h1>
        </div>
        <div class="form-card" style="text-align:center;">
            <div style="margin-bottom:20px;">
                <i class="bi bi-trash3" style="font-size:48px;color:var(--admin-red);"></i>
            </div>
            <h2 style="color:var(--admin-bg);margin-bottom:8px;">¿Seguro que deseas eliminar esta zona?</h2>
            <p style="color:var(--admin-text-muted);font-size:1.1rem;margin-bottom:24px;">
                <strong><?php echo htmlspecialchars($datos['nombre']); ?></strong>
            </p>
            <div class="form-actions">
                <a class="btn btn-secondary" href="index.php?c=Zona&m=listar"><i class="bi bi-x-lg"></i> Cancelar</a>
                <a class="btn btn-danger" href="index.php?c=Zona&m=eliminar&id_zona=<?php echo $datos['id_zona']; ?>"><i class="bi bi-trash3"></i> Eliminar</a>
            </div>
        </div>
            </div>
        </div>
    </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
