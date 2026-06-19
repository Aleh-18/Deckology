<?php
/**
 * sidebar_admin.php - Sidebar compartido para todas las vistas del admin
 * Incluye navegacion con icons, perfil del admin, y logo.
 * 
 * Variables esperadas:
 *   $currentSection - string con la seccion activa (ej: 'dashboard', 'usuarios', 'cartas', etc.)
 *   $_SESSION['nombreAdmin'] - nombre del admin logueado
 */
$currentSection = $currentSection ?? 'dashboard';
$nombreAdmin = $_SESSION['nombreAdmin'] ?? 'Admin';
$initial = strtoupper(substr($nombreAdmin, 0, 1));
?>
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="?c=Admin&m=vistaDashboardAdmin" class="sidebar-logo">Deckology</a>
        <div class="sidebar-logo-sub">Panel Admin</div>
    </div>
    <nav class="sidebar-nav">
        <a href="?c=Admin&m=vistaDashboardAdmin" class="sidebar-nav-item <?php echo $currentSection === 'dashboard' ? 'active' : ''; ?>">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>
        <div class="sidebar-divider"></div>
        <a href="?c=Usuario&m=listarUsuarios" class="sidebar-nav-item <?php echo $currentSection === 'usuarios' ? 'active' : ''; ?>">
            <i class="bi bi-people-fill"></i>
            <span>Usuarios</span>
        </a>
        <a href="?c=Zona&m=listar" class="sidebar-nav-item <?php echo $currentSection === 'zonas' ? 'active' : ''; ?>">
            <i class="bi bi-globe-americas"></i>
            <span>Zonas</span>
        </a>
        <a href="?c=Carta&m=listarCartas" class="sidebar-nav-item <?php echo $currentSection === 'cartas' ? 'active' : ''; ?>">
            <i class="bi bi-credit-card-2-front-fill"></i>
            <span>Cartas</span>
        </a>
        <a href="?c=Evento&m=vistaListarEventos" class="sidebar-nav-item <?php echo $currentSection === 'eventos' ? 'active' : ''; ?>">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>Eventos</span>
        </a>
        <a href="?c=Icono&m=listarIconos" class="sidebar-nav-item <?php echo $currentSection === 'iconos' ? 'active' : ''; ?>">
            <i class="bi bi-emoji-smile-fill"></i>
            <span>Iconos</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="?c=Admin&m=perfilAdministrador" class="sidebar-user">
            <div class="sidebar-user-icon"><?php echo $initial; ?></div>
            <div>
                <div class="sidebar-user-name"><?php echo htmlspecialchars($nombreAdmin); ?></div>
                <div class="sidebar-user-role">Administrador</div>
            </div>
        </a>
        <a href="?c=Usuario&m=cerrarSesion" class="sidebar-nav-item" style="margin-top:8px; padding:8px 0;">
            <i class="bi bi-box-arrow-left"></i>
            <span>Cerrar sesión</span>
        </a>
    </div>
</aside>
