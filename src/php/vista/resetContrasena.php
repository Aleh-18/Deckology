<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Deckology - Restablecer contraseña</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="contenedor-principal-login">
        <a id="volver-atras" href="index.php?c=Usuario&m=mostrarLogin">  Volver</a>

        <form id="formReset" class="formulario-base" action="index.php?c=Usuario&m=resetContrasena" method="post">
            <h2>Nueva contraseña</h2>
            <p>Introduce tu nueva contraseña.</p>

            <input type="hidden" name="token" value="<?php echo htmlspecialchars($datos['token'] ?? ''); ?>">

            <label for="password">Contraseña:</label>
            <div class="contenedor-password">
                <input type="password" name="password" id="password" required>
                <span id="iconoContrasena"></span>
            </div>

            <label for="password2">Repite la contraseña:</label>
            <input type="password" name="password2" id="password2" required>

            <?php if (!empty($datos['error'])): ?>
                <div class="errorFormulario"><?php echo $datos['error']; ?></div>
            <?php endif; ?>

            <?php if (!empty($datos['mensaje'])): ?>
                <div class="okFormulario"><?php echo $datos['mensaje']; ?></div>
            <?php endif; ?>

            <button type="submit" class="boton enviar">Guardar contraseña</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputPassword = document.getElementById('password');
            const botonVer = document.getElementById('iconoContrasena');
            if (!inputPassword || !botonVer) return;
            botonVer.addEventListener('click', () => {
                if (inputPassword.type === 'password') {
                    inputPassword.type = 'text';
                    botonVer.textContent = '';
                } else {
                    inputPassword.type = 'password';
                    botonVer.textContent = '';
                }
            });
        });
    </script>
</body>

</html>

