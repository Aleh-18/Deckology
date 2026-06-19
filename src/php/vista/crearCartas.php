<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Carta</title>
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
            <h1 class="page-title"><i class="bi bi-plus-circle"></i> Crear Carta</h1>
        </div>
        <div class="form-card">
            <form action="index.php?c=Carta&m=crearCarta" method="POST">
                <div class="form-group">
                    <label><i class="bi bi-globe"></i> Zona</label>
                    <select name="zona" id="zonaSelect" required>
                        <option value="">Seleccionar zona</option>
                        <?php foreach ($datos["zonas"] as $dato): ?>
                            <option value="<?php echo $dato["id"]; ?>"><?php echo $dato["nombre"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" name="creandoEventoNuevo" id="creandoEventoNuevo" value="0" />
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
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombreEvento" id="nombreEvento">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" name="descripcionEvento" id="descripcionEvento">
                    </div>
                    <div class="form-group">
                        <label>Daño</label>
                        <input type="number" name="danoEvento" id="danoEvento">
                    </div>
                    <div class="form-group">
                        <label>Rondas</label>
                        <input type="number" name="rondasEvento" id="rondasEvento">
                    </div>
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
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-text-paragraph"></i> Descripción</label>
                    <input type="text" name="descripcion" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-heart-pulse"></i> Curación</label>
                    <input type="number" name="curacion" required>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-emoji-smile"></i> Icono</label>
                    <select name="emoticono">
                        <option value="">Seleccionar icono</option>
                        <?php foreach ($datos["iconos"] as $dato): ?>
                            <option value="<?php echo $dato["id"]; ?>"><?php echo $dato["codigo"] . ' ' . $dato["nombre"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-actions">
                    <a href="index.php?c=Carta&m=listarCartas" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Cancelar</a>
                    <button type="reset" class="btn btn-ghost"><i class="bi bi-arrow-counterclockwise"></i> Reiniciar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Crear</button>
                </div>
            </form>
        </div>
            </div>
        </div>
    </main>
</div>
<script src="../js/vista/crear_cartas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
