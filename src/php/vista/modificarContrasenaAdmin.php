<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Contrasena</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-layout">
    <?php $currentSection = 'dashboard'; include 'sidebar_admin.php'; ?>
    <main class="admin-content">
        <div class="admin-wrap">
        <div class="page-header">
            <h1 class="page-title"><i class="bi bi-key-fill"></i> Cambiar Contrasena</h1>
        </div>
        <div class="form-card">
            <form action="?c=Admin&m=procesarCambioContrasena" method="POST">
                <?php if(isset($datos['mensaje']) && !empty($datos['mensaje'])): ?>
                    <p class="form-alert"><?php echo $datos['mensaje']; ?></p>
                <?php endif; ?>
                <div class="form-group">
                    <label><i class="bi bi-lock"></i> Contrasena actual</label>
                    <input type="password" name="contrasena_actual" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-lock-fill"></i> Nueva contrasena</label>
                    <input type="password" name="nueva_contrasena" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-lock-fill"></i> Confirmar contrasena</label>
                    <input type="password" name="confirmar_contrasena" required>
                </div>
                <div class="form-actions">
                    <a href="?c=Admin&m=perfilAdministrador" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Cancelar</a>
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
