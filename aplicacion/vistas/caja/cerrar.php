<?php
$titulo = 'Cerrar Caja';
$activo = 'caja';
ob_start();
?>

<div class="barra-superior">
    <h2>Cerrar Caja</h2>
    <a href="<?php echo URL_BASE; ?>/caja" class="boton">Volver</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        <?php
            $mensajes = [
                1 => 'No hay caja abierta para cerrar',
                2 => 'Error al cerrar la caja'
            ];
            echo $mensajes[$_GET['error']] ?? 'Error en la operación';
        ?>
    </div>
<?php endif; ?>

<?php if ($caja): ?>
    <div class="tarjeta" style="max-width:600px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:25px;">
            <i class="bi bi-exclamation-triangle" style="font-size:48px;color:#f57c00;"></i>
            <h3 style="margin:10px 0 5px 0;">¿Cerrar Caja?</h3>
            <p style="color:#666;font-size:14px;">Revisa los datos antes de cerrar la caja</p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;background:#f8f9fa;padding:20px;border-radius:8px;margin-bottom:25px;">
            <div>
                <div style="color:#999;font-size:13px;">Saldo Inicial</div>
                <div style="font-size:18px;font-weight:bold;">Bs. <?php echo number_format($caja['saldo_inicial'], 2); ?></div>
            </div>
            <div>
                <div style="color:#999;font-size:13px;">Total Ingresos</div>
                <div style="font-size:18px;font-weight:bold;color:#2e7d32;">Bs. <?php echo number_format($caja['total_ingresos'], 2); ?></div>
            </div>
            <div>
                <div style="color:#999;font-size:13px;">Total Egresos</div>
                <div style="font-size:18px;font-weight:bold;color:#d32f2f;">Bs. <?php echo number_format($caja['total_egresos'], 2); ?></div>
            </div>
            <div>
                <div style="color:#999;font-size:13px;">Saldo Final</div>
                <div style="font-size:22px;font-weight:bold;color:#0d47a1;">Bs. <?php echo number_format($caja['saldo_final'], 2); ?></div>
            </div>
        </div>

        <div style="background:#fff3e0;padding:15px;border-radius:6px;margin-bottom:20px;border-left:4px solid #f57c00;">
            <p style="margin:0;color:#e65100;font-size:14px;">
                <i class="bi bi-info-circle"></i> 
                Al cerrar la caja, no podrás registrar más movimientos en este turno.
            </p>
        </div>

        <form action="<?php echo URL_BASE; ?>/caja/cerrar" method="POST" onsubmit="return confirm('¿Estás seguro de cerrar la caja? Esta acción no se puede deshacer.')">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div style="display:flex;gap:15px;">
                <button type="submit" class="boton boton-peligro" style="flex:1;">
                    <i class="bi bi-x-circle"></i> Cerrar Caja
                </button>
                <a href="<?php echo URL_BASE; ?>/caja" class="boton" style="flex:1;text-align:center;">Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="alerta alerta-error">
        <i class="bi bi-exclamation-triangle"></i> 
        No hay una caja abierta para cerrar.
        <br><a href="<?php echo URL_BASE; ?>/caja" class="boton boton-primario" style="display:inline-block;margin-top:10px;">Ir a Caja</a>
    </div>
<?php endif; ?>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>