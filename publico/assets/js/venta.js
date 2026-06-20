/**
 * LÓGICA DE VENTAS (PUNTO DE VENTA)
 */

let carrito = [];

// Agregar producto al carrito
function agregarAlCarrito(id, nombre, precio, stockMax) {
    let item = carrito.find(p => p.id_producto === id);
    if (item) {
        if (item.cantidad < stockMax) {
            item.cantidad++;
        } else {
            alert('No hay suficiente stock disponible.');
            return;
        }
    } else {
        carrito.push({ id_producto: id, nombre: nombre, precio: precio, cantidad: 1 });
    }
    renderizarCarrito();
}

// Modificar cantidad
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

// Renderizar carrito
function renderizarCarrito() {
    const contenedor = document.getElementById('contenedor-carrito');
    const totalPago = document.getElementById('monto-total');
    
    if (carrito.length === 0) {
        contenedor.innerHTML = '<p style="text-align:center;color:#999;margin-top:20px;">El carrito está vacío</p>';
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

// Finalizar venta
function finalizarVenta() {
    if (carrito.length === 0) {
        alert('Agregue productos al carrito.');
        return;
    }
    document.getElementById('json-detalles').value = JSON.stringify(carrito);
    document.getElementById('formulario-venta').submit();
}