<?php
$titulo = 'Nueva Categoría';
$activo = 'categorias';
ob_start();
?>

<div class="barra-superior">
    <h2>Nueva Categoría</h2>
    <a href="<?php echo URL_BASE; ?>/categorias" class="boton">Volver</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<div class="tarjeta" style="max-width:600px;margin:0 auto;">
    <form action="<?php echo URL_BASE; ?>/categorias/guardar" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="campo">
            <label for="nombre">Nombre de la Categoría *</label>
            <input type="text" id="nombre" name="nombre" class="campo" placeholder="Ej: Bebidas" required autofocus>
        </div>

        <div class="campo">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="campo" rows="3" placeholder="Descripción opcional..."></textarea>
        </div>

        <div style="display:flex;gap:15px;margin-top:20px;">
            <button type="submit" class="boton boton-exito" style="flex:1;">Guardar Categoría</button>
            <a href="<?php echo URL_BASE; ?>/categorias" class="boton" style="flex:1;text-align:center;">Cancelar</a>
        </div>
    </form>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>