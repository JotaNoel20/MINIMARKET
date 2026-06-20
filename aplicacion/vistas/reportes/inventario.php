<?php
$titulo = 'Reporte de Inventario';
$activo = 'reportes';
ob_start();
?>

<div class="barra-superior">
    <h2>Reporte de Inventario</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:25px;margin-bottom:25px;">
    <div class="tarjeta">
        <h3>📦 Stock Total</h3>
        <p style="font-size:32px;font-weight:bold;color:#0d47a1;"><?php echo $total_stock; ?></p>
        <p>unidades en inventario</p>
    </div>
    <div class="tarjeta" style="border-left:4px solid #d32f2f;">
        <h3 style="color:#d32f2f;">⚠️ Productos con Stock Bajo</h3>
        <p style="font-size:32px;font-weight:bold;color:#d32f2f;"><?php echo count($stock_bajo); ?></p>
        <p>productos necesitan reposición</p>
    </div>
</div>

<div class="tarjeta">
    <h3>Lista completa de productos</h3>
    <table class="tabla">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Mínimo</th>
                <th>Precio Venta</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($p['categoria_nombre']); ?></td>
                    <td style="<?php echo $p['stock_actual'] <= $p['stock_minimo'] ? 'color:#d32f2f;font-weight:bold;' : ''; ?>">
                        <?php echo $p['stock_actual']; ?>
                    </td>
                    <td><?php echo $p['stock_minimo']; ?></td>
                    <td>Bs. <?php echo number_format($p['precio_venta'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>