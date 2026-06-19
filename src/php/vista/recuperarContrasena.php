<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Deckology - Recuperar contraseña</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="contenedor-principal-login">
        <a id="volver-atras" href="index.php?c=Usuario&m=mostrarLogin">  Volver</a>
        <form class="formulario-base" action="index.php?c=Usuario&m=enviarRecuperacion" method="post">
            <h2>Recuperar contraseña</h2>
            <p>Te generamos un enlace para crear una nueva contraseña.</p>

            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" id="email" required>

            <?php if (!empty($datos['error'])): ?>
                <div class="errorFormulario"><?php echo $datos['error']; ?></div>
            <?php endif; ?>

            <?php if (!empty($datos['mensaje'])): ?>
                <div class="okFormulario"><?php echo $datos['mensaje']; ?></div>
            <?php endif; ?>

            <?php if (!empty($datos['reset_link'])): ?>
                <p class="texto-ayuda" style="margin-top: 12px;">
                    En local, usa este enlace:
                    <a href="<?php echo htmlspecialchars($datos['reset_link']); ?>">Restablecer contraseña</a>
                </p>
            <?php endif; ?>

            <button type="submit" class="boton enviar">Generar enlace</button>
        </form>
    </div>
</body>

</html>

