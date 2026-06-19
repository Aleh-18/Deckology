<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
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
            <h1 class="page-title"><i class="bi bi-pencil-square"></i> Editar Perfil</h1>
        </div>
        <div class="form-card">
            <div style="text-align:center; margin-bottom:20px;">
                <div style="width:80px; height:80px; background:var(--admin-bg); color:var(--admin-gold); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto;"><i class="bi bi-person-gear"></i></div>
            </div>
            <form method="POST" action="?c=Admin&m=procesarEditarPerfil">
                <?php if(isset($datos['mensaje']) && !empty($datos['mensaje'])): ?>
                    <p class="form-alert"><?php echo $datos['mensaje']; ?></p>
                <?php endif; ?>
                <div class="form-group">
                    <label><i class="bi bi-person"></i> Nombre de usuario</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($datos['filaAdmin']['nombre']); ?>">
                </div>
                <div class="form-actions">
                    <a href="?c=Admin&m=perfilAdministrador" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Guardar cambios</button>
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
