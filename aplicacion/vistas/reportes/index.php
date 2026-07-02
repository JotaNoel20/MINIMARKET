<?php
$titulo = 'Panel de Reportes';
$activo = 'reportes';
ob_start();
?>

<div class="barra-superior">
    <h2>Panel de Reportes y Estadísticas</h2>
</div>

<div class="tarjeta-grid">
    <div class="tarjeta tarjeta-kpi">
        <div class="numero"><?php echo number_format($total_recaudado ?? 0, 2); ?></div>
        <div>Total Recaudado</div>
    </div>
    <div class="tarjeta tarjeta-kpi">
        <div class="numero"><?php echo $total_ventas ?? 0; ?></div>
        <div>Ventas Totales</div>
    </div>
    <div class="tarjeta tarjeta-kpi">
        <div class="numero"><?php echo $total_productos ?? 0; ?></div>
        <div>Productos Activos</div>
    </div>
    <div class="tarjeta tarjeta-kpi" style="border-left-color:#d32f2f;">
        <div class="numero" style="color:#d32f2f;"><?php echo $stock_bajo ?? 0; ?></div>
        <div>Stock Bajo</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:25px;margin-top:25px;">
    <div class="tarjeta">
        <h3>📈 Ventas del día</h3>
        <p><strong>Ventas:</strong> <?php echo $ventas_hoy['total'] ?? 0; ?></p>
        <p><strong>Recaudado:</strong> Bs. <?php echo number_format($ventas_hoy['recaudado'] ?? 0, 2); ?></p>
    </div>
    <div class="tarjeta">
        <h3>📊 Ventas de la semana</h3>
        <p><strong>Ventas:</strong> <?php echo $ventas_semana['total'] ?? 0; ?></p>
        <p><strong>Recaudado:</strong> Bs. <?php echo number_format($ventas_semana['recaudado'] ?? 0, 2); ?></p>
    </div>
</div>

<div class="tarjeta" style="margin-top:25px;">
    <h3>🏆 Productos más vendidos</h3>
    <table class="tabla">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Unidades</th>
                <th>Recaudado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($productos_top)): ?>
                <tr><td colspan="4" style="text-align:center;color:#999;">Sin datos</td></tr>
            <?php else: ?>
                <?php foreach ($productos_top as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($p['categoria']); ?></td>
                        <td><?php echo $p['total_unidades']; ?></td>
                        <td>Bs. <?php echo number_format($p['total_recaudado'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="display:flex;gap:15px;flex-wrap:wrap;margin-top:25px;">
    <a href="<?php echo URL_BASE; ?>/reportes/ventas" class="boton boton-primario">📄 Reporte de Ventas</a>
    <a href="<?php echo URL_BASE; ?>/reportes/inventario" class="boton boton-primario">📦 Reporte de Inventario</a>
    <a href="<?php echo URL_BASE; ?>/reportes/caja" class="boton boton-primario">💰 Reporte de Caja</a>
    <a href="<?php echo URL_BASE; ?>/reportes/ganancias" class="boton boton-primario">📈 Reporte de Ganancias</a>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>