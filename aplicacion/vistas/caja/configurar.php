<?php
$titulo = 'Configurar Caja';
$activo = 'configurar_caja';
ob_start();
?>

<div class="barra-superior">
    <h2>Configurar Caja del Día</h2>
    <a href="<?php echo URL_BASE; ?>/caja" class="boton">Volver</a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alerta alerta-exito">
        Monto general configurado con éxito
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        Error al configurar el monto general
    </div>
<?php endif; ?>

<div class="tarjeta" style="max-width:500px;margin:0 auto;">
    <h3>Monto General de Apertura</h3>
    <p style="color:#666;font-size:14px;margin-bottom:20px;">
        Este monto se asignará a <strong>TODOS los empleados</strong> al abrir su caja hoy.
    </p>

    <form action="<?php echo URL_BASE; ?>/caja/configurar/guardar" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="campo">
            <label for="monto">Monto General (Bs.)</label>
            <input type="number" id="monto" name="monto" class="campo" 
                   step="0.01" min="0" value="<?php echo number_format($monto_actual, 2); ?>" required>
            <small style="color:#999;">Ejemplo: 500 (monto con el que empiezan todos los empleados)</small>
        </div>

        <button type="submit" class="boton boton-exito" style="width:100%;padding:12px;">
            <i class="bi bi-check-circle"></i> Guardar Monto del Día
        </button>
    </form>

    <hr style="margin:25px 0;">

    <h4>📋 Historial de Configuraciones</h4>
    <table class="tabla" style="font-size:14px;">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Configurado por</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($historial)): ?>
                <tr>
                    <td colspan="3" style="text-align:center;color:#999;">No hay registros</td>
                </tr>
            <?php else: ?>
                <?php foreach ($historial as $h): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($h['fecha_actualizacion'])); ?></td>
                        <td>Bs. <?php echo number_format($h['valor'], 2); ?></td>
                        <td><?php echo htmlspecialchars($h['creado_por_nombre'] ?? 'Sistema'); ?></td>
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