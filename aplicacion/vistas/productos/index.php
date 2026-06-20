<?php
$titulo = 'Productos';
$activo = 'productos';
ob_start();
?>

<div class="barra-superior">
    <h2>Lista de Productos</h2>
    <a href="<?php echo URL_BASE; ?>/productos/crear" class="boton boton-exito">
        <i class="bi bi-plus-circle"></i> Nuevo Producto
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alerta alerta-exito">
        <?php
            $mensajes = [
                1 => 'Producto creado con éxito',
                2 => 'Producto actualizado con éxito',
                3 => 'Producto eliminado con éxito'
            ];
            echo $mensajes[$_GET['success']] ?? 'Operación exitosa';
        ?>
    </div>
<?php endif; ?>

<div class="tarjeta">
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio Venta</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($productos)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;color:#999;">No hay productos registrados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($productos as $prod): ?>
                    <tr>
                        <td><?php echo $prod['id_producto']; ?></td>
                        <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($prod['categoria_nombre']); ?></td>
                        <td>Bs. <?php echo number_format($prod['precio_venta'], 2); ?></td>
                        <td>
                            <span style="<?php echo $prod['stock_actual'] <= $prod['stock_minimo'] ? 'color:#d32f2f;font-weight:bold;' : ''; ?>">
                                <?php echo $prod['stock_actual']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo URL_BASE; ?>/productos/ver/<?php echo $prod['id_producto']; ?>" class="boton-accion" style="color:#0288d1;">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?php echo URL_BASE; ?>/productos/editar/<?php echo $prod['id_producto']; ?>" class="boton-accion" style="color:#f57c00;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?php echo URL_BASE; ?>/productos/eliminar/<?php echo $prod['id_producto']; ?>" class="boton-accion" style="color:#d32f2f;" onclick="return confirm('¿Eliminar este producto?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>