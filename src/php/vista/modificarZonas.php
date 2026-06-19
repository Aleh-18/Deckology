<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Zona</title>
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
            <h1 class="page-title"><i class="bi bi-pencil-square"></i> Modificar Zona</h1>
        </div>
        <div class="form-card">
            <form action="index.php?c=Zona&m=editar&id_zona=<?php echo $datos['id_zona']; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="bi bi-card-text"></i> Nombre</label>
                    <input id="nombre" type="text" name="nombre" value="<?php echo htmlspecialchars($datos['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-image"></i> Imagen Zona</label>
                    <?php if (!empty($datos['imagenZona'])): ?>
                        <div style="text-align:center;margin-bottom:8px;"><img src="<?php echo $datos['imagenZona']; ?>" width="120" style="border-radius:10px;object-fit:contain;max-height:120px;"></div>
                    <?php else: ?>
                        <p style="color:var(--admin-text-muted);text-align:center;">Sin imagen</p>
                    <?php endif; ?>
                    <input type="file" name="imagenZona" accept="image/*">
                </div>
                <div class="form-group">
                    <label><i class="bi bi-card-image"></i> Fondo Zona</label>
                    <?php if (!empty($datos['fondoZona'])): ?>
                        <div style="text-align:center;margin-bottom:8px;"><img src="<?php echo $datos['fondoZona']; ?>" width="120" style="border-radius:10px;object-fit:contain;max-height:120px;"></div>
                        <label style="font-weight:400;font-size:0.85rem;"><input type="checkbox" name="borrarFondo" value="1" style="width:auto;display:inline;"> Eliminar fondo</label>
                    <?php else: ?>
                        <p style="color:var(--admin-text-muted);text-align:center;">Sin fondo</p>
                    <?php endif; ?>
                    <input type="file" name="fondoZona" accept="image/*">
                </div>
                <div class="form-group">
                    <label><i class="bi bi-credit-card"></i> Imagen Cartas</label>
                    <?php if (!empty($datos['imagenCartas'])): ?>
                        <div style="text-align:center;margin-bottom:8px;"><img src="<?php echo $datos['imagenCartas']; ?>" width="120" style="border-radius:10px;object-fit:contain;max-height:120px;"></div>
                        <label style="font-weight:400;font-size:0.85rem;"><input type="checkbox" name="borrarCarta" value="1" style="width:auto;display:inline;"> Eliminar imagen de carta</label>
                    <?php else: ?>
                        <p style="color:var(--admin-text-muted);text-align:center;">Sin imagen</p>
                    <?php endif; ?>
                    <input type="file" name="imagenCartas" accept="image/*">
                </div>
                <div class="form-group">
                    <label><i class="bi bi-exclamation-triangle"></i> Imagen Eventos</label>
                    <?php if (!empty($datos['imagenEventos'])): ?>
                        <div style="text-align:center;margin-bottom:8px;"><img src="<?php echo $datos['imagenEventos']; ?>" width="120" style="border-radius:10px;object-fit:contain;max-height:120px;"></div>
                        <label style="font-weight:400;font-size:0.85rem;"><input type="checkbox" name="borrarEvento" value="1" style="width:auto;display:inline;"> Eliminar imagen de evento</label>
                    <?php else: ?>
                        <p style="color:var(--admin-text-muted);text-align:center;">Sin imagen</p>
                    <?php endif; ?>
                    <input type="file" name="imagenEventos" accept="image/*">
                </div>
                <div class="form-actions">
                    <a href="index.php?c=Zona&m=listar" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button>
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
