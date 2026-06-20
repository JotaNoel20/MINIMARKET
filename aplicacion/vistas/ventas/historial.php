<?php
$titulo = 'Historial de Ventas';
$activo = 'historial';
ob_start();
?>

<div class="barra-superior">
    <h2>Historial de Ventas</h2>
    <?php if ($_SESSION['id_rol'] == 2): ?>
        <a href="<?php echo URL_BASE; ?>/ventas/nueva" class="boton boton-exito">
            <i class="bi bi-plus-circle"></i> Nueva Venta
        </a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] == 4): ?>
    <div class="alerta alerta-exito">Venta anulada con éxito</div>
<?php endif; ?>

<div class="tarjeta">
    <table class="tabla">
        <thead>
            <tr>
                <th>N° Venta</th>
                <th>Fecha</th>
                <th>Vendedor</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ventas)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;color:#999;">No hay ventas registradas</td>
                </tr>
            <?php else: ?>
                <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td>#<?php echo $v['id_venta']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($v['fecha_venta'])); ?></td>
                        <td><?php echo htmlspecialchars($v['usuario_nombre']); ?></td>
                        <td>Bs. <?php echo number_format($v['total'], 2); ?></td>
                        <td>
                            <a href="<?php echo URL_BASE; ?>/ventas/detalle/<?php echo $v['id_venta']; ?>" class="boton-accion" style="color:#0288d1;">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if ($_SESSION['id_rol'] == 1): ?>
                                <a href="<?php echo URL_BASE; ?>/ventas/anular/<?php echo $v['id_venta']; ?>" class="boton-accion" style="color:#d32f2f;" onclick="return confirm('¿Anular esta venta? Se revertirá el stock.')">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            <?php endif; ?>
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