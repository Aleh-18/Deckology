<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Zona</title>
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
            <h1 class="page-title"><i class="bi bi-plus-circle"></i> Crear Zona</h1>
        </div>
        <div class="form-card">
            <form action="index.php?c=Zona&m=crear" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="bi bi-card-text"></i> Nombre</label>
                    <input id="nombre" type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-image"></i> Imagen Zona</label>
                    <input type="file" name="imagenZona" accept="image/*">
                </div>
                <div class="form-group">
                    <label><i class="bi bi-card-image"></i> Fondo Zona</label>
                    <input type="file" name="fondoZona" accept="image/*">
                </div>
                <div class="form-group">
                    <label><i class="bi bi-credit-card"></i> Imagen Cartas</label>
                    <input type="file" name="imagenCartas" accept="image/*">
                </div>
                <div class="form-group">
                    <label><i class="bi bi-exclamation-triangle"></i> Imagen Eventos</label>
                    <input type="file" name="imagenEventos" accept="image/*">
                </div>
                <div class="form-actions">
                    <a href="index.php?c=Zona&m=listar" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Guardar</button>
                </div>
            </form>
        </div>
            </div>
        </div>
    </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
