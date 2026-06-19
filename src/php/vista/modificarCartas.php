<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Carta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloAdmin.css?v=1781790509">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-layout">
    <?php $currentSection = 'cartas'; include 'sidebar_admin.php'; ?>
    <main class="admin-content">
        <div class="admin-wrap">
        <div class="page-header">
            <h1 class="page-title"><i class="bi bi-pencil-square"></i> Modificar Carta</h1>
        </div>
        <div class="form-card">
            <form action="index.php?c=Carta&m=modificarCarta&id=<?= htmlspecialchars($_GET['id'] ?? 0) ?>" method="POST">
                <div class="form-group">
                    <label><i class="bi bi-globe"></i> Zona</label>
                    <select name="zona" id="zonaSelect" required>
                        <?php
                        $id_zona_carta = $datos["carta"]["id_zona"];
                        foreach ($datos["zonas"] as $dato) {
                            $seleccionado = ($dato["id"] == $id_zona_carta) ? 'selected' : '';
                            echo '<option value="' . $dato["id"] . '"' . $seleccionado . '>' . $dato["nombre"] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="creandoEventoNuevo" id="creandoEventoNuevo" value="0" />
                <input type="hidden" name="idEventoAntiguo" id="idEventoAntiguo" value="<?= $datos["carta"]["elimina_id_evento"] ?>" />
                <div class="form-group">
                    <label><i class="bi bi-exclamation-triangle"></i> Evento</label>
                    <div class="evento-selector">
                        <select name="evento" id="eventoSelect">
                            <option value="">Seleccionar evento</option>
                        </select>
                        <button type="button" class="btn-crear-evento" id="btnCrearEvento"><i class="bi bi-plus-lg"></i> Crear</button>
                    </div>
                </div>
                <div id="formularioEventoNuevo">
                    <h2><i class="bi bi-plus-circle"></i> Crear Evento</h2>
                    <div class="form-group"><label>Nombre</label><input type="text" name="nombreEvento" id="nombreEvento"></div>
                    <div class="form-group"><label>Descripción</label><input type="text" name="descripcionEvento" id="descripcionEvento"></div>
                    <div class="form-group"><label>Daño</label><input type="number" name="danoEvento" id="danoEvento"></div>
                    <div class="form-group"><label>Rondas</label><input type="number" name="rondasEvento" id="rondasEvento"></div>
                    <div class="form-group">
                        <label>Icono</label>
                        <select name="emoticonoEvento" id="emoticonoEvento">
                            <option value="">Seleccionar icono</option>
                            <?php foreach ($datos["iconos"] as $dato): ?>
                                <option value="<?php echo $dato["id"]; ?>"><?php echo $dato["codigo"] . ' ' . $dato["nombre"]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-card-text"></i> Nombre</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($datos['carta']['nombre']) ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-text-paragraph"></i> Descripción</label>
                    <input type="text" name="descripcion" value="<?= htmlspecialchars($datos['carta']['descripcion']) ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-heart-pulse"></i> Curación</label>
                    <input type="number" name="curacion" value="<?= htmlspecialchars($datos['carta']['curacion']) ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-emoji-smile"></i> Icono</label>
                    <select name="emoticono">
                        <?php
                        $id_icono_carta = $datos["carta"]["id_icono"];
                        foreach ($datos["iconos"] as $dato) {
                            $seleccionado = ($dato["id"] == $id_icono_carta) ? 'selected' : '';
                            echo '<option value="' . $dato["id"] . '"' . $seleccionado . '>' . $dato["codigo"] . ' ' . $dato["nombre"] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" id="btnEliminarCarta" class="btn btn-danger"><i class="bi bi-trash3"></i> Eliminar</button>
                    <a href="index.php?c=Carta&m=listarCartas" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Modificar</button>
                </div>
            </form>
        </div>
            </div>
        </div>
    </main>
</div>

<div id="modalConfirmacion" class="modal-overlay">
    <div class="modal-contenido">
        <h2><i class="bi bi-exclamation-triangle" style="color:var(--admin-red);"></i> Eliminar carta</h2>
        <p>Vas a eliminar <strong><?= htmlspecialchars($datos["carta"]["nombre"]) ?></strong> permanentemente.</p>
        <div class="modal-botones">
            <button class="btn btn-secondary" id="btnCancelarModal"><i class="bi bi-x-lg"></i> Cancelar</button>
            <button class="btn btn-danger" id="btnConfirmarBorrado"><i class="bi bi-trash3"></i> Borrar</button>
        </div>
    </div>
</div>
<script src="../js/vista/modificar_cartas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
