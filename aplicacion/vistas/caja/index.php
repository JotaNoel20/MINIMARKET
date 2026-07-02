<?php
$titulo = 'Caja';
$activo = 'caja';
ob_start();
?>

<div class="barra-superior">
    <h2>Control de Caja</h2>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alerta alerta-exito">
        <?php
            $mensajes = [
                1 => 'Caja abierta con éxito',
                2 => 'Caja cerrada con éxito'
            ];
            echo $mensajes[$_GET['success']] ?? 'Operación exitosa';
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        <?php
            $mensajes = [
                1 => 'El saldo inicial debe ser mayor o igual a 0',
                2 => 'Ya tienes una caja abierta',
                3 => 'Error al abrir la caja',
                4 => 'No hay caja abierta para cerrar',
                5 => 'Error al cerrar la caja',
                6 => 'No hay monto general configurado. Contacte al administrador.'
            ];
            echo $mensajes[$_GET['error']] ?? 'Error en la operación';
        ?>
    </div>
<?php endif; ?>

<?php if ($caja): ?>
    <!-- Caja abierta -->
    <div class="tarjeta-grid">
        <div class="tarjeta tarjeta-kpi">
            <div class="numero"><?php echo number_format($caja['saldo_inicial'], 2); ?></div>
            <div>Saldo Inicial</div>
        </div>
        <div class="tarjeta tarjeta-kpi" style="border-left-color:#2e7d32;">
            <div class="numero" style="color:#2e7d32;"><?php echo number_format($caja['total_ingresos'], 2); ?></div>
            <div>Total Ingresos</div>
        </div>
        <div class="tarjeta tarjeta-kpi" style="border-left-color:#d32f2f;">
            <div class="numero" style="color:#d32f2f;"><?php echo number_format($caja['total_egresos'], 2); ?></div>
            <div>Total Egresos</div>
        </div>
        <div class="tarjeta tarjeta-kpi" style="border-left-color:#0d47a1;">
            <div class="numero" style="color:#0d47a1;"><?php echo number_format($caja['saldo_final'], 2); ?></div>
            <div>Saldo Final</div>
        </div>
    </div>

    <div style="display:flex;gap:15px;margin-bottom:20px;">
        <form action="<?php echo URL_BASE; ?>/caja/cerrar" method="POST" onsubmit="return confirm('¿Estás seguro de cerrar la caja?')">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <button type="submit" class="boton boton-peligro">Cerrar Caja</button>
        </form>
        <a href="<?php echo URL_BASE; ?>/caja/movimientos" class="boton boton-primario">Ver Movimientos</a>
    </div>

    <div class="tarjeta">
        <h3>Últimos movimientos</h3>
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
                    <tr><td colspan="4" style="text-align:center;color:#999;">No hay movimientos</td></tr>
                <?php else: ?>
                    <?php foreach (array_slice($movimientos, 0, 10) as $m): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($m['fecha'])); ?></td>
                            <td>
                                <span style="<?php echo $m['tipo'] == 'ingreso' ? 'color:#2e7d32;' : 'color:#d32f2f;'; ?>">
                                    <?php echo ucfirst($m['tipo']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($m['concepto']); ?></td>
                            <td>Bs. <?php echo number_format($m['monto'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <!-- Sin caja abierta -->
    <div class="tarjeta" style="text-align:center;padding:40px;">
        <i class="bi bi-cash-stack" style="font-size:64px;color:#999;"></i>
        <h3 style="margin:15px 0;color:#666;">No hay caja abierta</h3>
        <p style="color:#999;">Para abrir la caja, el administrador debe configurar el monto general del día.</p>
        <form action="<?php echo URL_BASE; ?>/caja/abrir" method="POST" style="display:inline-block;margin-top:15px;">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <button type="submit" class="boton boton-exito">Abrir Caja</button>
        </form>
    </div>
<?php endif; ?>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>