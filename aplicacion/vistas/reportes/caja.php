<?php
$titulo = 'Reporte de Caja';
$activo = 'reportes';
ob_start();
?>

<div class="barra-superior">
    <h2>Reporte de Caja</h2>
    <div>
        <form method="GET" style="display:flex;gap:10px;align-items:center;">
            <label>Fecha:</label>
            <input type="date" name="fecha" value="<?php echo $fecha; ?>" class="campo" style="width:auto;">
            <button type="submit" class="boton boton-primario">Filtrar</button>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:25px;margin-bottom:25px;">
    <div class="tarjeta" style="border-left:4px solid #2e7d32;">
        <h3 style="color:#2e7d32;">✅ Total Ingresos</h3>
        <p style="font-size:28px;font-weight:bold;color:#2e7d32;">Bs. <?php echo number_format($total_ingresos, 2); ?></p>
    </div>
    <div class="tarjeta" style="border-left:4px solid #d32f2f;">
        <h3 style="color:#d32f2f;">❌ Total Egresos</h3>
        <p style="font-size:28px;font-weight:bold;color:#d32f2f;">Bs. <?php echo number_format($total_egresos, 2); ?></p>
    </div>
</div>

<div class="tarjeta">
    <h3>Movimientos del día</h3>
    <table class="tabla">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Tipo</th>
                <th>Concepto</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($movimientos)): ?>
                <tr><td colspan="5" style="text-align:center;color:#999;">No hay movimientos</td></tr>
            <?php else: ?>
                <?php foreach ($movimientos as $m): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($m['fecha'])); ?></td>
                        <td><?php echo htmlspecialchars($m['usuario_nombre']); ?></td>
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