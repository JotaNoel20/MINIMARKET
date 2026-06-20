<?php
$titulo = 'Nueva Venta';
$activo = 'ventas';
ob_start();
?>

<div class="barra-superior">
    <h2>Punto de Venta</h2>
    <div>
        <span class="info-usuario">
            <i class="bi bi-person-circle"></i>
            <?php echo htmlspecialchars($_SESSION['nombre']); ?>
        </span>
    </div>
</div>

<!-- ============================================================ -->
<!-- ✅ MENSAJES DE ÉXITO Y ERROR (AGREGADO)                       -->
<!-- ============================================================ -->
<?php if (isset($_SESSION['mensaje_exito'])): ?>
    <div class="alerta alerta-exito">
        <i class="bi bi-check-circle-fill"></i> 
        <?php echo $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_error'])): ?>
    <div class="alerta alerta-error">
        <i class="bi bi-exclamation-triangle-fill"></i> 
        <?php echo $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?>
    </div>
<?php endif; ?>
<!-- ============================================================ -->

<?php if (!isset($caja_abierta) || !$caja_abierta): ?>
    <div class="alerta alerta-error">
        <i class="bi bi-exclamation-triangle"></i>
        No hay caja abierta. Abre la caja desde el módulo de Caja antes de comenzar a vender.
        <br><a href="<?php echo URL_BASE; ?>/caja" class="boton boton-primario" style="display:inline-block;margin-top:10px;">Ir a Caja</a>
    </div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 350px;gap:25px;">
    <!-- Lista de productos -->
    <div class="tarjeta">
        <h3><i class="bi bi-search"></i> Seleccionar Productos</h3>
        <table class="tabla">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="4" style="text-align:center;color:#999;">No hay productos disponibles</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productos as $prod): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                            <td>Bs. <?php echo number_format($prod['precio_venta'], 2); ?></td>
                            <td><?php echo $prod['stock_actual']; ?></td>
                            <td>
                                <button class="boton boton-exito" onclick="agregarAlCarrito(<?php echo $prod['id_producto']; ?>, '<?php echo addslashes($prod['nombre']); ?>', <?php echo $prod['precio_venta']; ?>, <?php echo $prod['stock_actual']; ?>)">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Carrito -->
    <div class="tarjeta" style="position:sticky;top:20px;">
        <h3><i class="bi bi-cart4"></i> Carrito</h3>
        <div id="contenedor-carrito" style="max-height:300px;overflow-y:auto;">
            <p style="text-align:center;color:#999;">El carrito está vacío</p>
        </div>
        <hr>
        <div style="text-align:center;font-size:24px;font-weight:bold;color:#0d47a1;">
            Total: Bs. <span id="monto-total">0.00</span>
        </div>
        <form action="<?php echo URL_BASE; ?>/ventas/procesar" method="POST" id="formulario-venta">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="json_detalles" id="json-detalles">
            <div style="margin-top:15px;">
                <label for="metodo_pago">Método de Pago</label>
                <select id="metodo_pago" name="metodo_pago" class="campo">
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="qr">QR</option>
                    <option value="transferencia">Transferencia</option>
                </select>
            </div>
            <button type="button" class="boton boton-exito" style="width:100%;margin-top:15px;" onclick="finalizarVenta()">
                <i class="bi bi-cash-coin"></i> Registrar Venta
            </button>
        </form>
    </div>
</div>

<script>
let carrito = [];

function agregarAlCarrito(id, nombre, precio, stockMax) {
    let item = carrito.find(p => p.id_producto === id);
    if (item) {
        if (item.cantidad < stockMax) {
            item.cantidad++;
        } else {
            alert('No hay suficiente stock.');
            return;
        }
    } else {
        carrito.push({ id_producto: id, nombre: nombre, precio: precio, cantidad: 1 });
    }
    renderizarCarrito();
}

function modificarCantidad(id, cambio) {
    let item = carrito.find(p => p.id_producto === id);
    if (item) {
        item.cantidad += cambio;
        if (item.cantidad <= 0) {
            carrito = carrito.filter(p => p.id_producto !== id);
        }
    }
    renderizarCarrito();
}

function renderizarCarrito() {
    const contenedor = document.getElementById('contenedor-carrito');
    const totalPago = document.getElementById('monto-total');
    
    if (carrito.length === 0) {
        contenedor.innerHTML = '<p style="text-align:center;color:#999;">El carrito está vacío</p>';
        totalPago.innerText = '0.00';
        return;
    }

    contenedor.innerHTML = '';
    let total = 0;
    carrito.forEach(p => {
        total += p.precio * p.cantidad;
        contenedor.innerHTML += `
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #eee;">
                <div>
                    <strong>${p.nombre}</strong>
                    <br><small>Bs. ${p.precio.toFixed(2)}</small>
                </div>
                <div style="display:flex;gap:8px;align-items:center;">
                    <button type="button" onclick="modificarCantidad(${p.id_producto}, -1)">-</button>
                    <span>${p.cantidad}</span>
                    <button type="button" onclick="modificarCantidad(${p.id_producto}, 1)">+</button>
                </div>
            </div>
        `;
    });
    totalPago.innerText = total.toFixed(2);
}

function finalizarVenta() {
    if (carrito.length === 0) {
        alert('Agregue productos al carrito.');
        return;
    }
    document.getElementById('json-detalles').value = JSON.stringify(carrito);
    document.getElementById('formulario-venta').submit();
}
</script>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>