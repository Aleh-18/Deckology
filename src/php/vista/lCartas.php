<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar Cartas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509,05587">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-layout">
    <?php $currentSection = 'cartas'; include 'sidebar_admin.php'; ?>
    <main class="admin-content">
        <div class="admin-wrap">
            <div class="page-header">
                <h1 class="page-title"><i class="bi bi-credit-card-2-front-fill"></i> Cartas</h1>
                <a href="index.php?c=Carta&m=mostrarCrearCarta" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Crear Carta</a>
            </div>
            <div class="search-bar">
                <div class="search-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" id="filtroNombre" class="search-input" placeholder="Buscar carta...">
                </div>
            </div>
            <div class="table-card">
                <table class="tabla" id="tablaCartas">
                    <thead>
                        <tr>
                            <th><i class="bi bi-credit-card-2-front"></i> Nombre</th>
                            <th><i class="bi bi-globe"></i> Zona</th>
                            <th><i class="bi bi-heart-pulse"></i> Curación</th>
                            <th><i class="bi bi-stars"></i> Efecto</th>
                            <th><i class="bi bi-trash3"></i> Eliminar</th>
                            <th><i class="bi bi-pencil"></i> Modificar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos as $dato): ?>
                        <tr>
                            <td><strong><?php echo $dato["icono"] . ' ' . $dato['nombre']; ?></strong></td>
                            <td><?php echo $dato['zona']; ?></td>
                            <td><span style="color:var(--admin-accent);font-weight:800;"><?php echo $dato['curacion']; ?></span></td>
                            <td><?php echo $dato['evento']; ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="confirmarEliminar('<?php echo htmlspecialchars($dato['nombre'], ENT_QUOTES); ?>', '<?php echo $dato['id_carta']; ?>')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                            <td>
                                <a href="index.php?c=Carta&m=mostrarModificarCarta&id=<?php echo $dato['id_carta']; ?>">
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

<div id="modalConfirmacion" class="modal-overlay">
    <div class="modal-contenido">
        <h2><i class="bi bi-exclamation-triangle" style="color:var(--admin-red);"></i> Confirmar eliminación</h2>
        <p>¿Quieres borrar la carta <strong id="nombreCartaBorrar"></strong>?</p>
        <div class="modal-botones">
            <button class="btn btn-secondary" onclick="cerrarModal()"><i class="bi bi-x-lg"></i> Cancelar</button>
            <button class="btn btn-danger" id="btnConfirmarBorrado"><i class="bi bi-trash3"></i> Borrar</button>
        </div>
    </div>
</div>

<script src="../js/vista/lCartas.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
