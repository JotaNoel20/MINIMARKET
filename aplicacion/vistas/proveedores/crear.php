<?php
$titulo = 'Nuevo Proveedor';
$activo = 'proveedores';
ob_start();
?>

<div class="barra-superior">
    <h2>Nuevo Proveedor</h2>
    <a href="<?php echo URL_BASE; ?>/proveedores" class="boton">Volver</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<div class="tarjeta" style="max-width:600px;margin:0 auto;">
    <form action="<?php echo URL_BASE; ?>/proveedores/guardar" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="campo">
            <label for="nombre">Nombre del Proveedor *</label>
            <input type="text" id="nombre" name="nombre" class="campo" placeholder="Ej: Distribuidora XYZ" required autofocus>
        </div>

        <div class="campo">
            <label for="nit">NIT</label>
            <input type="text" id="nit" name="nit" class="campo" placeholder="Ej: 123456789">
        </div>

        <div class="campo">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" class="campo" placeholder="Ej: 76543210">
        </div>

        <div class="campo">
            <label for="direccion">Dirección</label>
            <input type="text" id="direccion" name="direccion" class="campo" placeholder="Ej: Av. Buenos Aires #123">
        </div>

        <div class="campo">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="campo" placeholder="Ej: contacto@proveedor.com">
        </div>

        <div style="display:flex;gap:15px;margin-top:20px;">
            <button type="submit" class="boton boton-exito" style="flex:1;">Guardar Proveedor</button>
            <a href="<?php echo URL_BASE; ?>/proveedores" class="boton" style="flex:1;text-align:center;">Cancelar</a>
        </div>
    </form>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>