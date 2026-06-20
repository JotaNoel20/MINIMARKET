<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo NOMBRE_APP; ?> - <?php echo $titulo ?? 'Panel'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/publico/assets/css/estilo.css">
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/publico/assets/css/admin.css">
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-shop"></i>
            <span><?php echo NOMBRE_APP; ?></span>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="<?php echo URL_BASE; ?>/panel" class="<?php echo ($activo ?? '') == 'panel' ? 'activo' : ''; ?>">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Inicio</span>
                </a>
            </li>

            <?php 
            // Verificar si existe sesión y rol
            $idRol = $_SESSION['id_rol'] ?? null;
            ?>

            <?php if ($idRol == 1): ?>
                <li>
                    <a href="<?php echo URL_BASE; ?>/productos" class="<?php echo ($activo ?? '') == 'productos' ? 'activo' : ''; ?>">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Productos</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_BASE; ?>/categorias" class="<?php echo ($activo ?? '') == 'categorias' ? 'activo' : ''; ?>">
                        <i class="bi bi-tags-fill"></i>
                        <span>Categorías</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_BASE; ?>/proveedores" class="<?php echo ($activo ?? '') == 'proveedores' ? 'activo' : ''; ?>">
                        <i class="bi bi-building-fill"></i>
                        <span>Proveedores</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_BASE; ?>/compras" class="<?php echo ($activo ?? '') == 'compras' ? 'activo' : ''; ?>">
                        <i class="bi bi-cart-plus-fill"></i>
                        <span>Compras</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_BASE; ?>/usuarios" class="<?php echo ($activo ?? '') == 'usuarios' ? 'activo' : ''; ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_BASE; ?>/reportes" class="<?php echo ($activo ?? '') == 'reportes' ? 'activo' : ''; ?>">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Reportes</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($idRol == 2): ?>
                <li>
                    <a href="<?php echo URL_BASE; ?>/ventas/nueva" class="<?php echo ($activo ?? '') == 'ventas' ? 'activo' : ''; ?>">
                        <i class="bi bi-cart-plus-fill"></i>
                        <span>Nueva Venta</span>
                    </a>
                </li>
            <?php endif; ?>

            <li>
                <a href="<?php echo URL_BASE; ?>/ventas/historial" class="<?php echo ($activo ?? '') == 'historial' ? 'activo' : ''; ?>">
                    <i class="bi bi-receipt"></i>
                    <span>Historial</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URL_BASE; ?>/caja" class="<?php echo ($activo ?? '') == 'caja' ? 'activo' : ''; ?>">
                    <i class="bi bi-cash-stack"></i>
                    <span>Caja</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <a href="<?php echo URL_BASE; ?>/logout">
                <i class="bi bi-box-arrow-left"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenido">
        <?php echo $contenido ?? ''; ?>
    </main>

    <script src="<?php echo URL_BASE; ?>/publico/assets/js/ap.js"></script>
    <?php if (isset($script_extra)): ?>
        <script src="<?php echo URL_BASE; ?>/publico/assets/js/<?php echo $script_extra; ?>.js"></script>
    <?php endif; ?>
</body>
</html>