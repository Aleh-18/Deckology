<?php
require_once __DIR__ . '/../modelo/mUsuario.php';

class CUsuario
{
    public $nombreVista;
    private $modelo;

    public function __construct()
    {
        $this->modelo = new Usuario();
    }

    private function urlActual($pathAndQuery)
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host . $pathAndQuery;
    }

    private function redirectUriGoogle()
    {
        $script = $_SERVER['SCRIPT_NAME'] ?? '/Deckology/src/php/index.php';
        return $this->urlActual($script . '?c=Usuario&m=googleCallback');
    }

    public function mostrarAcceso()
    {
        $this->nombreVista = "acceso";
    }

    /* ======== REGISTRO ======== */

    public function mostrarRegistro()
    {
        $this->nombreVista = "registro";
    }

    public function registrar()
    {
        if (!isset($_POST['usuario']) || !isset($_POST['email']) || !isset($_POST['password'])) {
            $this->nombreVista = "registro";
            return ["error" => "Faltan datos"];
        }

        $nombre = $_POST['usuario'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $pass2 = $_POST['password2'];

        if (empty($nombre) || empty($email) || ($pass != $pass2)) {
            $this->nombreVista = "registro";
            return ["error" => "Los datos no son válidos"];
        }

        // Comprobamos si el email ya existe antes de insertar
        $resultado = $this->modelo->login($email);

        if ($resultado) {
            $this->nombreVista = "registro";
            return ["error" => "El email ya está registrado."];
        }

        $passCifrada = password_hash($pass, PASSWORD_DEFAULT);
        $this->modelo->insertar($nombre, $email, $passCifrada);

        $this->nombreVista = "login";
        return ["mensaje" => "Usuario registrado correctamente"];
    }

    /* ======== LOGIN ======== */

    public function mostrarLogin()
    {
        $this->nombreVista = "login";
    }

    public function mostrarRecuperarContrasena()
    {
        $this->nombreVista = "recuperarContrasena";
        return [];
    }

    public function enviarRecuperacion()
    {
        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->nombreVista = "recuperarContrasena";
            return ['error' => 'Introduce un email válido.'];
        }

        $usuario = $this->modelo->login($email);

        $this->nombreVista = "recuperarContrasena";
        $respuesta = ['mensaje' => 'Si el email existe, te hemos generado un enlace para restablecer la contraseña.'];

        if (empty($usuario)) {
            return $respuesta;
        }

        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', time() + 60 * 60);

        $ok = $this->modelo->guardarTokenRecuperacion($email, $tokenHash, $expiresAt);
        if (!$ok) {
            $respuesta['error'] = 'No se pudo generar el enlace (revisa la base de datos).';
            return $respuesta;
        }

        $script = $_SERVER['SCRIPT_NAME'] ?? '/Deckology/src/php/index.php';
        $respuesta['reset_link'] = $this->urlActual($script . '?c=Usuario&m=mostrarResetContrasena&token=' . urlencode($token));
        return $respuesta;
    }

    public function mostrarResetContrasena()
    {
        $token = trim($_GET['token'] ?? '');
        $this->nombreVista = "resetContrasena";

        if (empty($token)) {
            return ['error' => 'Token inválido.', 'token' => ''];
        }

        return ['token' => $token];
    }

    public function resetContrasena()
    {
        $token = trim($_POST['token'] ?? '');
        $pass = $_POST['password'] ?? '';
        $pass2 = $_POST['password2'] ?? '';

        if (empty($token)) {
            $this->nombreVista = "resetContrasena";
            return ['error' => 'Token inválido.', 'token' => ''];
        }

        if (empty($pass) || ($pass !== $pass2)) {
            $this->nombreVista = "resetContrasena";
            return ['error' => 'Las contraseñas no coinciden.', 'token' => $token];
        }

        $tokenHash = hash('sha256', $token);
        $fila = $this->modelo->buscarPorTokenRecuperacion($tokenHash);

        if (empty($fila)) {
            $this->nombreVista = "resetContrasena";
            return ['error' => 'El enlace no es válido o ya fue usado.', 'token' => $token];
        }

        $expires = $fila['reset_expires'] ?? null;
        if (empty($expires) || strtotime($expires) < time()) {
            $this->nombreVista = "resetContrasena";
            return ['error' => 'El enlace ha caducado.', 'token' => $token];
        }

        $passHash = password_hash($pass, PASSWORD_DEFAULT);
        $ok = $this->modelo->actualizarContrasenaPorId($fila['id_usuario'], $passHash);

        if (!$ok) {
            $this->nombreVista = "resetContrasena";
            return ['error' => 'No se pudo guardar la contraseña.', 'token' => $token];
        }

        header("Location: index.php?c=Usuario&m=mostrarLogin");
        exit();
    }

    public function login()
    {
        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            $this->nombreVista = "login";
            return ["error" => "Datos incompletos"];
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $usuario = $this->modelo->login($email);

        if (empty($usuario)) {
            $this->nombreVista = "login";
            return ["error" => "Usuario no registrado"];
        }

        if (!password_verify($password, $usuario['contrasena'])) {
            $this->nombreVista = "login";
            return ["error" => "Contraseña incorrecta"];
        }

        $_SESSION['nombreUsuario'] = $usuario['nombre'];
        $_SESSION['idUsuario'] = $usuario['id_usuario'];

        header("Location: index.php?c=Usuario&m=mostrarZonas");
        exit();
    }

    /* ======== GOOGLE LOGIN ======== */

    public function iniciarSesionGoogle()
    {
        require_once __DIR__ . '/../config/google_oauth.php';

        if (empty(GOOGLE_CLIENT_ID) || empty(GOOGLE_CLIENT_SECRET)) {
            $_SESSION['oauth_error'] = 'Faltan credenciales de Google OAuth (CLIENT_ID / CLIENT_SECRET).';
            header("Location: index.php?c=Usuario&m=mostrarLogin");
            exit();
        }

        $state = bin2hex(random_bytes(16));
        $_SESSION['google_oauth_state'] = $state;

        $redirect_uri = GOOGLE_REDIRECT_URI ?: $this->redirectUriGoogle();

        $params = [
            'response_type' => 'code',
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => $redirect_uri,
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
            'include_granted_scopes' => 'true'
        ];

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        header("Location: $url");
        exit();
    }

    public function googleCallback()
    {
        require_once __DIR__ . '/../config/google_oauth.php';

        if (!empty($_GET['error'])) {
            $_SESSION['oauth_error'] = 'Google OAuth cancelado: ' . $_GET['error'];
            header("Location: index.php?c=Usuario&m=mostrarLogin");
            exit();
        }

        $code = $_GET['code'] ?? null;
        $state = $_GET['state'] ?? null;

        if (empty($code) || empty($state) || empty($_SESSION['google_oauth_state']) || !hash_equals($_SESSION['google_oauth_state'], $state)) {
            $_SESSION['oauth_error'] = 'Error de seguridad en Google OAuth (state inválido).';
            header("Location: index.php?c=Usuario&m=mostrarLogin");
            exit();
        }

        unset($_SESSION['google_oauth_state']);

        $redirect_uri = GOOGLE_REDIRECT_URI ?: $this->redirectUriGoogle();
        $postData = http_build_query([
            'code' => $code,
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => $redirect_uri,
            'grant_type' => 'authorization_code'
        ]);

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);

        $raw = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $token = json_decode($raw, true);
        if ($httpCode !== 200 || empty($token['access_token'])) {
            $_SESSION['oauth_error'] = 'No se pudo obtener el token de Google.';
            header("Location: index.php?c=Usuario&m=mostrarLogin");
            exit();
        }

        $accessToken = $token['access_token'];

        $ch = curl_init('https://openidconnect.googleapis.com/v1/userinfo');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken]
        ]);

        $rawUser = curl_exec($ch);
        $httpUser = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $googleUser = json_decode($rawUser, true);
        $email = $googleUser['email'] ?? null;
        $nombre = $googleUser['name'] ?? null;

        if ($httpUser !== 200 || empty($email)) {
            $_SESSION['oauth_error'] = 'No se pudo leer el perfil de Google.';
            header("Location: index.php?c=Usuario&m=mostrarLogin");
            exit();
        }

        $usuario = $this->modelo->login($email);

        if (empty($usuario)) {
            $nombreBase = $nombre ?: explode('@', $email)[0];
            $nombreFinal = $nombreBase;

            $intentos = 0;
            while ($intentos < 5) {
                $passCifrada = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
                $ok = $this->modelo->insertar($nombreFinal, $email, $passCifrada);
                if ($ok) {
                    break;
                }

                $intentos++;
                $nombreFinal = $nombreBase . '_' . random_int(1000, 9999);
            }

            $usuario = $this->modelo->login($email);
        }

        if (empty($usuario)) {
            $_SESSION['oauth_error'] = 'No se pudo iniciar sesión con Google.';
            header("Location: index.php?c=Usuario&m=mostrarLogin");
            exit();
        }

        $_SESSION['nombreUsuario'] = $usuario['nombre'];
        $_SESSION['idUsuario'] = $usuario['id_usuario'];

        header("Location: index.php?c=Usuario&m=mostrarZonas");
        exit();
    }

    /* ======== JUEGO ======== */

    public function mostrarZonas()
    {
        header("Location: index.php?c=Zona&m=listarFront");
        exit();
    }

    public function cerrarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        session_destroy();
        header("Location: index.php");
        exit();
    }

    public function obtenerPuntuaciones()
    {
        $this->nombreVista = "puntuaciones";
        return $resultado = $this->modelo->obtenerPuntuacion();
    }

    public function guardarPuntuacion()
    {
        if (!isset($_SESSION['idUsuario'])) {
            error_log("GUARDAR PUNTUACION: No autorizado. " . print_r($_SESSION, true));
            echo json_encode(["error" => "No autorizado"]);
            exit;
        }

        $puntos = $_POST['puntos'] ?? 0;
        $id = $_SESSION['idUsuario'];

        $resultado = $this->modelo->actualizarPuntuacion($id, $puntos);

        echo json_encode(["status" => "ok", "actualizado" => $resultado]);
        exit;
    }

    /* ======== PERFIL USUARIO ======== */

    public function perfilUsuario()
    {
        $idUsuario = $_SESSION['idUsuario'];
        $this->nombreVista = "perfilUsuario";
        $usuario = $this->modelo->mostrarUsuarioPerfil($idUsuario);
        $stats = $this->modelo->obtenerEstadisticas($idUsuario);
        return [
            'filaUsuario' => $usuario,
            'stats' => $stats,
            'fecha_registro' => $usuario['fecha_registro'] ?? null,
        ];
    }

    public function vistaModificarcontrasena()
    {
        $idUsuario = $_SESSION['idUsuario'];
        $this->nombreVista = "modificarContrasenaUsuario";
        $usuario = $this->modelo->mostrarUsuarioPerfil($idUsuario);
        $stats = $this->modelo->obtenerEstadisticas($idUsuario);
        return ['filaUsuario' => $usuario, 'stats' => $stats, 'fecha_registro' => $usuario['fecha_registro'] ?? null];
    }

    public function procesarCambioContrasena()
    {
        $idUsuario = $_SESSION['idUsuario'];
        $contrasena_actual = $_POST['contrasena_actual'];
        $nueva_contrasena = $_POST['nueva_contrasena'];
        $confirmar_contrasena = $_POST['confirmar_contrasena'];
        $mensaje = "";

        if (empty($contrasena_actual) || empty($nueva_contrasena) || empty($confirmar_contrasena)) {
            $this->nombreVista = "modificarContrasenaUsuario";
            return ['mensaje' => 'Error: Todos los campos son obligatorios', 'filaUsuario' => $this->modelo->mostrarUsuarioPerfil($idUsuario)];
        }

        if ($nueva_contrasena !== $confirmar_contrasena) {
            $this->nombreVista = "modificarContrasenaUsuario";
            return ['mensaje' => 'Error: Las contraseñas nuevas no coinciden', 'filaUsuario' => $this->modelo->mostrarUsuarioPerfil($idUsuario)];
        }

        $resultado = $this->modelo->modificarContrasenaUsuario($idUsuario, $contrasena_actual, $nueva_contrasena, $mensaje);

        if ($resultado) {
            $this->nombreVista = "perfilUsuario";
            return ['mensaje' => 'Contraseña cambiada correctamente', 'filaUsuario' => $this->modelo->mostrarUsuarioPerfil($idUsuario)];
        } else {
            $this->nombreVista = "modificarContrasenaUsuario";
            return ['mensaje' => $mensaje, 'filaUsuario' => $this->modelo->mostrarUsuarioPerfil($idUsuario)];
        }
    }

    public function vistaEditarPerfil()
    {
        $idUsuario = $_SESSION['idUsuario'];
        $this->nombreVista = "editarPerfilUsuario";
        $usuario = $this->modelo->mostrarUsuarioPerfil($idUsuario);
        $stats = $this->modelo->obtenerEstadisticas($idUsuario);
        return ['filaUsuario' => $usuario, 'stats' => $stats, 'fecha_registro' => $usuario['fecha_registro'] ?? null];
    }

    public function procesarEditarPerfil()
    {
        $idUsuario = $_SESSION['idUsuario'];
        $nombre = $_POST['nombre'];
        $mensaje = "";

        if (empty($nombre)) {
            $this->nombreVista = "editarPerfilUsuario";
            return ['mensaje' => 'Error: El nombre es obligatorio', 'filaUsuario' => $this->modelo->mostrarUsuarioPerfil($idUsuario)];
        }

        $resultado = $this->modelo->modificarPerfilUsuario($idUsuario, $nombre, '', '', '', $mensaje);

        if ($resultado) {
            $this->nombreVista = "perfilUsuario";
            return ['mensaje' => 'Perfil actualizado correctamente', 'filaUsuario' => $this->modelo->mostrarUsuarioPerfil($idUsuario)];
        } else {
            $this->nombreVista = "editarPerfilUsuario";
            return ['mensaje' => $mensaje, 'filaUsuario' => $this->modelo->mostrarUsuarioPerfil($idUsuario)];
        }
    }

    /* ======== ADMIN - GESTIÓN USUARIOS ======== */

    public function listarUsuarios()
    {
        $this->nombreVista = "lUsuarios";
        return $this->modelo->listarUsuarios();
    }

    public function eliminarUsuario()
    {
        $this->verificarAdmin();
        $idTarget = $_GET['idTarget'] ?? $_POST['idTarget'] ?? null;
        if ($idTarget) {
            $this->modelo->eliminarUsuario($idTarget);
        }
        $this->nombreVista = "lUsuarios";
        return $this->modelo->listarUsuarios();
    }

    /* ======== PANEL CONTROL (DEV TOOL) ======== */

    public function panelControl()
    {
        $mensaje = '';
        $tipo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $pass = $_POST['contrasena'] ?? '';
            $rol = $_POST['rol'] ?? 'admin';

            if (empty($nombre) || empty($email) || empty($pass)) {
                $mensaje = 'Todos los campos son obligatorios.';
                $tipo = 'error';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mensaje = 'El email no es válido.';
                $tipo = 'error';
            } elseif (strlen($pass) < 4) {
                $mensaje = 'La contraseña debe tener al menos 4 caracteres.';
                $tipo = 'error';
            } else {
                $existe = $this->modelo->login($email);
                if ($existe) {
                    $mensaje = 'Ya existe un usuario con ese email.';
                    $tipo = 'error';
                } else {
                    $this->modelo->insertar($nombre, $email, $pass);
                    $sql = "UPDATE usuarios SET rol = :rol WHERE email = :email";
                    require_once __DIR__ . '/../modelo/conexion.php';
                    $c = new Conexion();
                    $stmt = $c->conexion->prepare("UPDATE usuarios SET rol = :rol WHERE email = :email");
                    $stmt->execute([':rol' => $rol, ':email' => $email]);
                    $mensaje = "Usuario '$nombre' creado con rol '$rol'.";
                    $tipo = 'ok';
                }
            }
        }

        $sql = "SELECT id_usuario, nombre, email, rol, puntuacion, fecha_registro FROM usuarios ORDER BY rol, nombre";
        require_once __DIR__ . '/../modelo/conexion.php';
        $c = new Conexion();
        $usuarios = $c->conexion->query($sql)->fetchAll();

        $this->nombreVista = "panelControl";
        return ['usuarios' => $usuarios, 'mensaje' => $mensaje, 'tipo' => $tipo];
    }
}

?>
