<?php
$titulo = 'Nueva Compra';
$activo = 'compras';
ob_start();
?>

<div class="barra-superior">
    <h2>Registrar Nueva Compra</h2>
    <a href="<?php echo URL_BASE; ?>/compras" class="boton">Volver</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        <?php
            $mensajes = [
                1 => 'Error al registrar la compra',
                2 => 'Debe agregar al menos un producto',
                3 => 'Error en la transacción'
            ];
            echo $mensajes[$_GET['error']] ?? htmlspecialchars($_GET['error']);
        ?>
    </div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:25px;">
    <!-- Panel izquierdo: Formulario -->
    <div class="tarjeta">
        <h3>Datos de la Compra</h3>
        <form action="<?php echo URL_BASE; ?>/compras/guardar" method="POST" id="formulario-compra">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="json_detalles" id="json-detalles">

            <div class="campo">
                <label for="id_proveedor">Proveedor *</label>
                <select id="id_proveedor" name="id_proveedor" class="campo" required>
                    <option value="">Seleccionar proveedor</option>
                    <?php foreach ($proveedores as $prov): ?>
                        <option value="<?php echo $prov['id_proveedor']; ?>">
                            <?php echo htmlspecialchars($prov['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-top:20px;">
                <h4>Agregar Productos</h4>
                <div style="max-height:300px;overflow-y:auto;border:1px solid #ddd;border-radius:6px;padding:10px;">
                    <?php foreach ($productos as $prod): ?>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f0f0;">
                            <span style="font-size:14px;"><?php echo htmlspecialchars($prod['nombre']); ?></span>
                            <div style="display:flex;gap:5px;align-items:center;flex-wrap:wrap;">
                                <input type="number" id="cantidad_<?php echo $prod['id_producto']; ?>" 
                                       placeholder="Cant." style="width:65px;padding:5px;border:1px solid #ddd;border-radius:4px;font-size:13px;" min="1">
                                <input type="number" id="precio_<?php echo $prod['id_producto']; ?>" 
                                       placeholder="Precio" style="width:85px;padding:5px;border:1px solid #ddd;border-radius:4px;font-size:13px;" step="0.01" min="0">
                                <button type="button" class="boton boton-exito" style="padding:4px 8px;font-size:12px;" 
                                        onclick="agregarProducto(<?php echo $prod['id_producto']; ?>, '<?php echo addslashes($prod['nombre']); ?>')">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="button" class="boton boton-exito" style="width:100%;margin-top:20px;padding:14px;" onclick="finalizarCompra()">
                <i class="bi bi-cart-check-fill"></i> Registrar Compra
            </button>
        </form>
    </div>

    <!-- Panel derecho: Carrito -->
    <div class="tarjeta" style="position:sticky;top:20px;height:fit-content;">
        <h3><i class="bi bi-cart4"></i> Productos a Comprar</h3>
        <div id="contenedor-carrito" style="max-height:400px;overflow-y:auto;min-height:100px;">
            <p style="text-align:center;color:#999;padding:20px 0;">No hay productos agregados</p>
        </div>
        <hr style="margin:15px 0;">
        <div style="text-align:center;font-size:24px;font-weight:bold;color:#0d47a1;">
            Total: Bs. <span id="monto-total">0.00</span>
        </div>
    </div>
</div>

<script>
let carrito = [];

function agregarProducto(id, nombre) {
    const cantidad = parseInt(document.getElementById('cantidad_' + id).value);
    const precio = parseFloat(document.getElementById('precio_' + id).value);

    if (!cantidad || cantidad <= 0) {
        alert('Ingrese una cantidad válida');
        return;
    }
    if (!precio || precio <= 0) {
        alert('Ingrese un precio válido');
        return;
    }

    let item = carrito.find(p => p.id_producto === id);
    if (item) {
        item.cantidad += cantidad;
        item.precio_compra = precio;
    } else {
        carrito.push({ id_producto: id, nombre: nombre, cantidad: cantidad, precio_compra: precio });
    }

    document.getElementById('cantidad_' + id).value = '';
    document.getElementById('precio_' + id).value = '';

    renderizarCarrito();
}

function eliminarProducto(id) {
    carrito = carrito.filter(p => p.id_producto !== id);
    renderizarCarrito();
}

function renderizarCarrito() {
    const contenedor = document.getElementById('contenedor-carrito');
    const totalPago = document.getElementById('monto-total');
    
    if (carrito.length === 0) {
        contenedor.innerHTML = '<p style="text-align:center;color:#999;padding:20px 0;">No hay productos agregados</p>';
        totalPago.innerText = '0.00';
        return;
    }

    contenedor.innerHTML = '';
    let total = 0;
    carrito.forEach(p => {
        const subtotal = p.precio_compra * p.cantidad;
        total += subtotal;
        contenedor.innerHTML += `
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #eee;">
                <div>
                    <strong style="font-size:14px;">${p.nombre}</strong>
                    <br><small style="color:#666;">${p.cantidad} x Bs. ${p.precio_compra.toFixed(2)}</small>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-weight:bold;font-size:14px;">Bs. ${subtotal.toFixed(2)}</span>
                    <button type="button" style="color:#d32f2f;border:none;background:none;cursor:pointer;font-size:18px;" onclick="eliminarProducto(${p.id_producto})">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            </div>
        `;
    });
    totalPago.innerText = total.toFixed(2);
}

function finalizarCompra() {
    if (carrito.length === 0) {
        alert('Agregue productos al carrito.');
        return;
    }
    if (!document.getElementById('id_proveedor').value) {
        alert('Seleccione un proveedor.');
        return;
    }
    document.getElementById('json-detalles').value = JSON.stringify(carrito);
    document.getElementById('formulario-compra').submit();
}
</script>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>