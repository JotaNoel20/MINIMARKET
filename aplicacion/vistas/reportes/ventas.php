<?php
$titulo = 'Reporte de Ventas';
$activo = 'reportes';
ob_start();
?>

<div class="barra-superior">
    <h2>Reporte de Ventas</h2>
    <div>
        <form method="GET" style="display:flex;gap:10px;align-items:center;">
            <label>Desde:</label>
            <input type="date" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>" class="campo" style="width:auto;">
            <label>Hasta:</label>
            <input type="date" name="fecha_fin" value="<?php echo $fecha_fin; ?>" class="campo" style="width:auto;">
            <button type="submit" class="boton boton-primario">Filtrar</button>
        </form>
    </div>
</div>

<div class="tarjeta">
    <p><strong>Total recaudado:</strong> Bs. <?php echo number_format($total, 2); ?></p>
    <hr style="margin:15px 0;">
    <table class="tabla">
        <thead>
            <tr>
                <th># Venta</th>
                <th>Fecha</th>
                <th>Vendedor</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ventas)): ?>
                <tr><td colspan="4" style="text-align:center;color:#999;">No hay ventas en este rango</td></tr>
            <?php else: ?>
                <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td><?php echo $v['id_venta']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($v['fecha_venta'])); ?></td>
                        <td><?php echo htmlspecialchars($v['usuario_nombre']); ?></td>
                        <td>Bs. <?php echo number_format($v['total'], 2); ?></td>
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