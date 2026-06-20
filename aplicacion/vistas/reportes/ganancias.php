<?php
$titulo = 'Reporte de Ganancias';
$activo = 'reportes';
ob_start();
?>

<div class="barra-superior">
    <h2>Reporte de Ganancias</h2>
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

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:25px;">
    <div class="tarjeta" style="border-left:4px solid #0d47a1;">
        <h3>💰 Ventas</h3>
        <p style="font-size:28px;font-weight:bold;color:#0d47a1;">Bs. <?php echo number_format($ventas, 2); ?></p>
    </div>
    <div class="tarjeta" style="border-left:4px solid #f57c00;">
        <h3>📦 Compras</h3>
        <p style="font-size:28px;font-weight:bold;color:#f57c00;">Bs. <?php echo number_format($compras, 2); ?></p>
    </div>
    <div class="tarjeta" style="border-left:4px solid #2e7d32;">
        <h3>📈 Ganancia Bruta</h3>
        <p style="font-size:28px;font-weight:bold;color:#2e7d32;">Bs. <?php echo number_format($ganancia, 2); ?></p>
    </div>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>