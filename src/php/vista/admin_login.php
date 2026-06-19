<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Deckology - Acceso Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../imagenes/Logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<div class="login-layout">
    <div class="login-card">
        <div class="login-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <h1>Panel de Administración</h1>
        <p class="login-subtitle">Acceso restringido - Solo personal autorizado</p>
        <?php if(isset($datos['mensaje']) && !empty($datos['mensaje'])): ?>
            <p class="form-alert"><?php echo $datos['mensaje']; ?></p>
        <?php endif; ?>
        <form action="?c=Admin&m=procesarLoginAdmin" method="POST">
            <div class="form-group">
                <label><i class="bi bi-envelope"></i> Email</label>
                <input type="email" name="email" placeholder="admin@deckology.local" required>
            </div>
            <div class="form-group">
                <label><i class="bi bi-lock"></i> Contrasena</label>
                <input type="password" name="password" placeholder="Tu contrasena" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Acceder al panel</button>
        </form>
        <a href="?c=Usuario&m=mostrarAcceso" class="login-back"><i class="bi bi-arrow-left"></i> Volver al inicio</a>
    </div>
</div>
</body>
</html>
