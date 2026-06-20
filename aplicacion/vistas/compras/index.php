<?php
$titulo = 'Compras';
$activo = 'compras';
ob_start();
?>

<div class="barra-superior">
    <h2>Lista de Compras</h2>
    <a href="<?php echo URL_BASE; ?>/compras/crear" class="boton boton-exito">
        <i class="bi bi-plus-circle"></i> Nueva Compra
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alerta alerta-exito">
        <?php
            $mensajes = [
                1 => 'Compra registrada con éxito',
                2 => 'Compra actualizada con éxito'
            ];
            echo $mensajes[$_GET['success']] ?? 'Operación exitosa';
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        <?php
            $mensajes = [
                404 => 'Compra no encontrada',
                403 => 'No tienes permiso para ver esta compra'
            ];
            echo $mensajes[$_GET['error']] ?? 'Error en la operación';
        ?>
    </div>
<?php endif; ?>

<div class="tarjeta">
    <table class="tabla">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Registrado por</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($compras)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;color:#999;padding:30px 0;">
                        <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:10px;"></i>
                        No hay compras registradas
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($compras as $c): ?>
                    <tr>
                        <td><?php echo $c['id_compra']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($c['fecha_compra'])); ?></td>
                        <td><?php echo htmlspecialchars($c['proveedor_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($c['usuario_nombre']); ?></td>
                        <td>Bs. <?php echo number_format($c['total'], 2); ?></td>
                        <td>
                            <a href="<?php echo URL_BASE; ?>/compras/detalle/<?php echo $c['id_compra']; ?>" class="boton-accion" style="color:#0288d1;" title="Ver detalle">
                                <i class="bi bi-eye"></i>
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