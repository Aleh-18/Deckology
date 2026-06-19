<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar Eventos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509,05587">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-layout">
    <?php $currentSection = 'eventos'; include 'sidebar_admin.php'; ?>
    <main class="admin-content">
        <div class="admin-wrap">
            <div class="page-header">
                <h1 class="page-title"><i class="bi bi-exclamation-triangle-fill"></i> Eventos</h1>
                <a href="?c=Evento&m=vistaCrearEvento" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Crear Evento</a>
            </div>
            <div class="search-bar">
                <div class="search-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" id="busqueda" class="search-input" placeholder="Buscar evento...">
                </div>
            </div>
            <div class="table-card">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th><i class="bi bi-exclamation-circle"></i> Nombre</th>
                            <th><i class="bi bi-heart-break"></i> Daño</th>
                            <th><i class="bi bi-clock"></i> Duración</th>
                            <th><i class="bi bi-trash3"></i> Eliminar</th>
                            <th><i class="bi bi-pencil"></i> Modificar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $evento): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($evento['nombre']); ?></strong>
                                <br><small><?php echo htmlspecialchars($evento['codigo_icono']); ?> &middot; <?php echo htmlspecialchars($evento['nombre_zona']); ?></small>
                            </td>
                            <td>
                                <span style="color:var(--admin-red);font-weight:800;">
                                    <i class="bi bi-heart-fill" style="font-size:0.7rem;"></i> <?php echo htmlspecialchars($evento['dano']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($evento['turnos_duracion']); ?> turnos</td>
                            <td>
                                <a href="?c=Evento&m=procesarEliminarEvento&idEvento=<?php echo $evento['id_evento']; ?>" onclick="return confirm('¿Eliminar este evento?')">
                                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></button>
                                </a>
                            </td>
                            <td>
                                <a href="?c=Evento&m=vistaModificarEvento&idEvento=<?php echo $evento['id_evento']; ?>">
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
