<?php
$titulo = 'Abrir Caja';
$activo = 'caja';
ob_start();
?>

<div class="barra-superior">
    <h2>Abrir Caja</h2>
    <a href="<?php echo URL_BASE; ?>/caja" class="boton">Volver</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        <?php
            $mensajes = [
                1 => 'El saldo inicial debe ser mayor o igual a 0',
                2 => 'Ya tienes una caja abierta',
                3 => 'Error al abrir la caja'
            ];
            echo $mensajes[$_GET['error']] ?? 'Error en la operación';
        ?>
    </div>
<?php endif; ?>

<div class="tarjeta" style="max-width:500px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:30px;">
        <i class="bi bi-cash-stack" style="font-size:48px;color:#0d47a1;"></i>
        <h3 style="margin:10px 0 5px 0;">Apertura de Caja</h3>
        <p style="color:#666;font-size:14px;">Ingresa el saldo inicial para abrir la caja</p>
    </div>

    <form action="<?php echo URL_BASE; ?>/caja/abrir" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="campo">
            <label for="saldo_inicial">Saldo Inicial (Bs.) *</label>
            <input type="number" id="saldo_inicial" name="saldo_inicial" class="campo" 
                   step="0.01" min="0" value="0.00" required autofocus>
            <small style="color:#999;">Monto en efectivo con el que se inicia la caja</small>
        </div>

        <div style="display:flex;gap:15px;margin-top:20px;">
            <button type="submit" class="boton boton-exito" style="flex:1;">
                <i class="bi bi-check-circle"></i> Abrir Caja
            </button>
            <a href="<?php echo URL_BASE; ?>/caja" class="boton" style="flex:1;text-align:center;">Cancelar</a>
        </div>
    </form>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>