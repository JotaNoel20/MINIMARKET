<?php
$titulo = 'Nuevo Producto';
$activo = 'productos';
ob_start();
?>

<div class="barra-superior">
    <h2>Nuevo Producto</h2>
    <a href="<?php echo URL_BASE; ?>/productos" class="boton">Volver</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<div class="tarjeta">
    <form action="<?php echo URL_BASE; ?>/productos/guardar" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="campo">
            <label for="nombre">Nombre del Producto *</label>
            <input type="text" id="nombre" name="nombre" class="campo" required>
        </div>

        <div class="campo">
            <label for="id_categoria">Categoría *</label>
            <select id="id_categoria" name="id_categoria" class="campo" required>
                <option value="">Seleccionar</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo $cat['id_categoria']; ?>">
                        <?php echo htmlspecialchars($cat['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="campo" rows="3"></textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
            <div class="campo">
                <label for="precio_compra">Precio de Compra (Bs.) *</label>
                <input type="number" id="precio_compra" name="precio_compra" class="campo" step="0.01" min="0" required>
            </div>
            <div class="campo">
                <label for="precio_venta">Precio de Venta (Bs.) *</label>
                <input type="number" id="precio_venta" name="precio_venta" class="campo" step="0.01" min="0" required>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
            <div class="campo">
                <label for="stock_actual">Stock Inicial *</label>
                <input type="number" id="stock_actual" name="stock_actual" class="campo" min="0" required>
            </div>
            <div class="campo">
                <label for="stock_minimo">Stock Mínimo *</label>
                <input type="number" id="stock_minimo" name="stock_minimo" class="campo" min="0" required>
            </div>
        </div>

        <div style="display:flex;gap:15px;margin-top:20px;">
            <button type="submit" class="boton boton-exito">Guardar Producto</button>
            <a href="<?php echo URL_BASE; ?>/productos" class="boton">Cancelar</a>
        </div>
    </form>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>