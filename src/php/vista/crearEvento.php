<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Evento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-layout">
    <?php $currentSection = 'eventos'; include 'sidebar_admin.php'; ?>
    <main class="admin-content">
        <div class="admin-wrap">
        <div class="page-header">
            <h1 class="page-title"><i class="bi bi-plus-circle"></i> Crear Evento</h1>
        </div>
        <div class="form-card">
            <form action="?c=Evento&m=procesarCrearEvento" method="POST">
                <?php if(isset($datos['mensaje']) && !empty($datos['mensaje'])): ?>
                    <p class="form-alert"><?php echo $datos['mensaje']; ?></p>
                <?php endif; ?>
                <div class="form-group">
                    <label><i class="bi bi-card-text"></i> Nombre</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-text-paragraph"></i> Descripción</label>
                    <input type="text" name="descripcion">
                </div>
                <div class="form-group">
                    <label><i class="bi bi-heart-break"></i> Daño</label>
                    <input type="number" name="dano" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-clock"></i> Rondas</label>
                    <input type="number" name="turnos_duracion" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-globe"></i> Zona</label>
                    <select name="id_zona">
                        <?php foreach($datos['zonas'] as $zona): ?>
                            <option value="<?php echo $zona['id_Zona']; ?>"><?php echo htmlspecialchars($zona['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-emoji-smile"></i> Icono</label>
                    <select name="id_icono">
                        <?php foreach($datos['iconos'] as $icono): ?>
                            <option value="<?php echo $icono['id_icono']; ?>"><?php echo htmlspecialchars($icono['codigo']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-actions">
                    <a href="?c=Evento&m=vistaListarEventos" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Cancelar</a>
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
