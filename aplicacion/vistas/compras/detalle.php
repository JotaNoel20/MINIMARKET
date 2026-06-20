<?php
$titulo = 'Detalle de Compra #' . $compra['id_compra'];
$activo = 'compras';
ob_start();
?>

<div class="barra-superior">
    <h2>Detalle de Compra #<?php echo $compra['id_compra']; ?></h2>
    <div>
        <a href="<?php echo URL_BASE; ?>/compras" class="boton">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="tarjeta">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:25px;margin-bottom:25px;">
        <!-- Información de la compra -->
        <div>
            <h3 style="margin-top:0;">Información de la Compra</h3>
            <table style="width:100%;">
                <tr>
                    <td style="font-weight:bold;padding:6px 0;">Fecha:</td>
                    <td style="padding:6px 0;"><?php echo date('d/m/Y H:i', strtotime($compra['fecha_compra'])); ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;padding:6px 0;">Registrado por:</td>
                    <td style="padding:6px 0;"><?php echo htmlspecialchars($compra['usuario_nombre']); ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;padding:6px 0;">Proveedor:</td>
                    <td style="padding:6px 0;"><?php echo htmlspecialchars($proveedor['nombre']); ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;padding:6px 0;">NIT:</td>
                    <td style="padding:6px 0;"><?php echo htmlspecialchars($proveedor['nit'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;padding:6px 0;">Teléfono:</td>
                    <td style="padding:6px 0;"><?php echo htmlspecialchars($proveedor['telefono'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;padding:6px 0;font-size:18px;">Total:</td>
                    <td style="padding:6px 0;font-size:18px;font-weight:bold;color:#0d47a1;">
                        Bs. <?php echo number_format($compra['total'], 2); ?>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Información del proveedor -->
        <div>
            <h3 style="margin-top:0;">Datos del Proveedor</h3>
            <table style="width:100%;">
                <tr>
                    <td style="font-weight:bold;padding:6px 0;">Dirección:</td>
                    <td style="padding:6px 0;"><?php echo htmlspecialchars($proveedor['direccion'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;padding:6px 0;">Email:</td>
                    <td style="padding:6px 0;"><?php echo htmlspecialchars($proveedor['email'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;padding:6px 0;">Estado:</td>
                    <td style="padding:6px 0;">
                        <span style="<?php echo $proveedor['estado'] == 1 ? 'color:#2e7d32;' : 'color:#d32f2f;'; ?>">
                            <?php echo $proveedor['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <hr style="margin:20px 0;">

    <h3>Productos Comprados</h3>
    <table class="tabla">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Compra</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $det): ?>
                <tr>
                    <td><?php echo htmlspecialchars($det['producto_nombre']); ?></td>
                    <td><?php echo $det['cantidad']; ?></td>
                    <td>Bs. <?php echo number_format($det['precio_compra'], 2); ?></td>
                    <td>Bs. <?php echo number_format($det['subtotal'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot style="font-weight:bold;background:#f8f9fa;">
            <tr>
                <td colspan="3" style="text-align:right;padding:12px 15px;">TOTAL:</td>
                <td style="padding:12px 15px;color:#0d47a1;">
                    Bs. <?php echo number_format($compra['total'], 2); ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>