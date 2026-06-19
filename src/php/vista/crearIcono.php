<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Icono</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-layout">
    <?php $currentSection = 'iconos'; include 'sidebar_admin.php'; ?>
    <main class="admin-content">
        <div class="admin-wrap">
        <div class="page-header">
            <h1 class="page-title"><i class="bi bi-plus-circle"></i> Crear Icono</h1>
        </div>
        <div class="form-card">
            <form action="?c=Icono&m=crearIcono" method="POST">
                <div class="form-group">
                    <label><i class="bi bi-card-text"></i> Nombre</label>
                    <input type="text" name="nombre" placeholder="Ej: Arbol" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-code-slash"></i> Codigo (emoji)</label>
                    <input type="text" name="codigo" placeholder="Ej: 🌳" required>
                </div>
                <?php if(isset($datos['error']) && !empty($datos['error'])): ?>
                    <p class="form-alert"><?php echo $datos['error']; ?></p>
                <?php endif; ?>
                <div class="form-actions">
                    <a href="?c=Icono&m=listarIconos" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Crear</button>
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
