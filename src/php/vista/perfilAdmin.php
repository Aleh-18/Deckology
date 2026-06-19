<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Administrador</title>
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
            <h1 class="page-title"><i class="bi bi-person-circle"></i> Mi Perfil</h1>
        </div>
        <div class="profile-card">
            <div class="profile-avatar"><i class="bi bi-person-fill"></i></div>
            <h1><?php echo htmlspecialchars($datos['filaAdmin']['nombre']); ?></h1>
            <p class="profile-email"><?php echo htmlspecialchars($datos['filaAdmin']['email']); ?></p>
            <?php if(isset($datos['mensaje']) && !empty($datos['mensaje'])): ?>
                <p class="form-alert"><?php echo $datos['mensaje']; ?></p>
            <?php endif; ?>
            <div class="profile-info">
                <label><i class="bi bi-person"></i> Nombre de usuario</label>
                <input readonly type="text" value="<?php echo htmlspecialchars($datos['filaAdmin']['nombre']); ?>">
                <label><i class="bi bi-envelope"></i> Correo electronico</label>
                <input readonly type="email" value="<?php echo htmlspecialchars($datos['filaAdmin']['email']); ?>">
            </div>
            <div class="profile-actions">
                <a href="?c=Admin&m=vistaEditarPerfil" class="btn btn-warning"><i class="bi bi-pencil"></i> Editar nombre</a>
                <a href="?c=Admin&m=vistaModificarcontrasena" class="btn btn-info"><i class="bi bi-key"></i> Cambiar contrasena</a>
                <a href="?c=Admin&m=vistaDashboardAdmin" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
            </div>
        </div>
            </div>
        </div>
    </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
