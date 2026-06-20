<?php
$titulo = 'Detalle de Venta #' . $venta['id_venta'];
$activo = 'historial';
ob_start();
?>

<div class="barra-superior">
    <h2>Detalle de Venta #<?php echo $venta['id_venta']; ?></h2>
    <a href="<?php echo URL_BASE; ?>/ventas/historial" class="boton">Volver</a>
</div>

<div class="tarjeta">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div>
            <h3>Información de la Venta</h3>
            <table style="width:100%;">
                <tr><td style="font-weight:bold;">Fecha:</td><td><?php echo date('d/m/Y H:i', strtotime($venta['fecha_venta'])); ?></td></tr>
                <tr><td style="font-weight:bold;">Vendedor:</td><td><?php echo htmlspecialchars($venta['usuario_nombre']); ?></td></tr>
                <tr><td style="font-weight:bold;">Subtotal:</td><td>Bs. <?php echo number_format($venta['subtotal'], 2); ?></td></tr>
                <tr><td style="font-weight:bold;">Descuento:</td><td>Bs. <?php echo number_format($venta['descuento'], 2); ?></td></tr>
                <tr><td style="font-weight:bold;font-size:18px;">Total:</td><td style="font-size:18px;font-weight:bold;">Bs. <?php echo number_format($venta['total'], 2); ?></td></tr>
            </table>
        </div>
        <div>
            <h3>Método de Pago</h3>
            <?php if ($pago): ?>
                <table style="width:100%;">
                    <tr><td style="font-weight:bold;">Método:</td><td><?php echo $pago['metodo_pago']; ?></td></tr>
                    <tr><td style="font-weight:bold;">Monto:</td><td>Bs. <?php echo number_format($pago['monto'], 2); ?></td></tr>
                    <tr><td style="font-weight:bold;">Referencia:</td><td><?php echo $pago['referencia'] ?? 'N/A'; ?></td></tr>
                    <tr><td style="font-weight:bold;">Fecha:</td><td><?php echo date('d/m/Y H:i', strtotime($pago['fecha_pago'])); ?></td></tr>
                </table>
            <?php else: ?>
                <p style="color:#999;">No se registró pago para esta venta.</p>
            <?php endif; ?>
        </div>
    </div>

    <hr style="margin:20px 0;">

    <h3>Productos Vendidos</h3>
    <table class="tabla">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Descuento</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $det): ?>
                <tr>
                    <td><?php echo htmlspecialchars($det['producto_nombre']); ?></td>
                    <td><?php echo $det['cantidad']; ?></td>
                    <td>Bs. <?php echo number_format($det['precio_unitario'], 2); ?></td>
                    <td>Bs. <?php echo number_format($det['descuento'], 2); ?></td>
                    <td>Bs. <?php echo number_format($det['subtotal'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>