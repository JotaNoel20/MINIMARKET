<?php
$titulo = 'Movimientos de Caja';
$activo = 'caja';
ob_start();
?>

<div class="barra-superior">
    <h2>Movimientos de Caja</h2>
    <a href="<?php echo URL_BASE; ?>/caja" class="boton">Volver</a>
</div>

<div class="tarjeta">
    <p><strong>Caja:</strong> #<?php echo $caja['id_caja']; ?> | 
       <strong>Estado:</strong> <?php echo $caja['estado']; ?> | 
       <strong>Saldo:</strong> Bs. <?php echo number_format($caja['saldo_final'], 2); ?></p>
    <hr style="margin:15px 0;">

    <table class="tabla">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Concepto</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($movimientos)): ?>
                <tr>
                    <td colspan="4" style="text-align:center;color:#999;">No hay movimientos registrados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($movimientos as $m): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($m['fecha'])); ?></td>
                        <td>
                            <span style="<?php echo $m['tipo'] == 'ingreso' ? 'color:#2e7d32;font-weight:bold;' : 'color:#d32f2f;font-weight:bold;'; ?>">
                                <?php echo ucfirst($m['tipo']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($m['concepto']); ?></td>
                        <td style="<?php echo $m['tipo'] == 'ingreso' ? 'color:#2e7d32;' : 'color:#d32f2f;'; ?>">
                            <?php echo $m['tipo'] == 'ingreso' ? '+' : '-'; ?>
                            Bs. <?php echo number_format($m['monto'], 2); ?>
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