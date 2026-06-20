<?php
$titulo = 'Detalle del Producto';
$activo = 'productos';
ob_start();
?>

<div class="barra-superior">
    <h2>Detalle del Producto</h2>
    <div>
        <a href="<?php echo URL_BASE; ?>/productos/editar/<?php echo $producto['id_producto']; ?>" class="boton boton-primario">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="<?php echo URL_BASE; ?>/productos" class="boton">Volver</a>
    </div>
</div>

<div class="tarjeta">
    <table style="width:100%;">
        <tr>
            <td style="font-weight:bold;width:150px;">ID</td>
            <td><?php echo $producto['id_producto']; ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Nombre</td>
            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Categoría</td>
            <td><?php echo htmlspecialchars($producto['categoria_nombre']); ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Descripción</td>
            <td><?php echo htmlspecialchars($producto['descripcion'] ?? 'Sin descripción'); ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Proveedor</td>
            <td><?php echo htmlspecialchars($producto['proveedor_nombre'] ?? 'Sin proveedor'); ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Precio de Compra</td>
            <td>Bs. <?php echo number_format($producto['precio_compra'], 2); ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Precio de Venta</td>
            <td>Bs. <?php echo number_format($producto['precio_venta'], 2); ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Stock Actual</td>
            <td>
                <span style="<?php echo $producto['stock_actual'] <= $producto['stock_minimo'] ? 'color:#d32f2f;font-weight:bold;' : ''; ?>">
                    <?php echo $producto['stock_actual']; ?>
                </span>
            </td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Stock Mínimo</td>
            <td><?php echo $producto['stock_minimo']; ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Estado</td>
            <td><?php echo $producto['estado'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Fecha Registro</td>
            <td><?php echo date('d/m/Y H:i', strtotime($producto['fecha_registro'])); ?></td>
        </tr>
    </table>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>