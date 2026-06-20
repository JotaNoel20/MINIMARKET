<?php
$titulo = 'Panel Principal';
$activo = 'panel';
ob_start();
?>

<div class="barra-superior">
    <h2>Panel de Control</h2>
    <div class="info-usuario">
        <i class="bi bi-person-circle"></i>
        <span>¡Hola, <?php echo htmlspecialchars($nombre); ?>!</span>
    </div>
</div>

<?php if ($id_rol == 1): ?>
    <!-- KPIs para Administrador -->
    <div class="tarjeta-grid">
        <div class="tarjeta tarjeta-kpi">
            <div class="numero"><?php echo $ventas_hoy['total'] ?? 0; ?></div>
            <div>Ventas de hoy</div>
            <small>Bs. <?php echo number_format($ventas_hoy['recaudado'] ?? 0, 2); ?></small>
        </div>
        <div class="tarjeta tarjeta-kpi">
            <div class="numero"><?php echo $total_stock; ?></div>
            <div>Productos en stock</div>
        </div>
        <div class="tarjeta tarjeta-kpi" style="border-left-color: #d32f2f;">
            <div class="numero" style="color: #d32f2f;"><?php echo $stock_bajo; ?></div>
            <div>Alertas de stock bajo</div>
        </div>
        <div class="tarjeta tarjeta-kpi">
            <div class="numero"><?php echo $total_usuarios; ?></div>
            <div>Usuarios activos</div>
        </div>
    </div>
<?php else: ?>
    <!-- KPIs para Empleado -->
    <div class="tarjeta-grid">
        <div class="tarjeta tarjeta-kpi">
            <div class="numero"><?php echo $ventas_hoy['total'] ?? 0; ?></div>
            <div>Ventas realizadas hoy</div>
            <small>Bs. <?php echo number_format($ventas_hoy['recaudado'] ?? 0, 2); ?></small>
        </div>
    </div>
<?php endif; ?>

<div style="margin-top:30px;">
    <div class="tarjeta">
        <h3>Accesos rápidos</h3>
        <div style="display:flex;gap:15px;flex-wrap:wrap;margin-top:15px;">
            <?php if ($id_rol == 1): ?>
                <a href="<?php echo URL_BASE; ?>/productos" class="boton boton-primario">Gestionar Productos</a>
                <a href="<?php echo URL_BASE; ?>/reportes" class="boton boton-primario">Ver Reportes</a>
            <?php else: ?>
                <a href="<?php echo URL_BASE; ?>/ventas/nueva" class="boton boton-exito">Nueva Venta</a>
            <?php endif; ?>
            <a href="<?php echo URL_BASE; ?>/ventas/historial" class="boton boton-primario">Historial de Ventas</a>
            <a href="<?php echo URL_BASE; ?>/caja" class="boton boton-advertencia">Caja</a>
        </div>
    </div>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>