<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: /minimarket/public/index.php');
    exit;
}

if ($_SESSION['id_rol'] != 2) {
    header('Location: /minimarket/public/dashboard.php');
    exit;
}

require_once '../../config/conexion.php';

try {
    $consulta_productos = "SELECT p.*, c.nombre AS categoria 
                           FROM producto p 
                           LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                           WHERE p.estado = 1 AND p.stock_actual > 0";
    
    $sentencia = $pdo->prepare($consulta_productos);
    $sentencia->execute();
    $lista_productos = $sentencia->fetchAll();
} catch (\PDOException $e) {
    die("Error al cargar productos para la venta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Venta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { 
            margin: 0; 
            font-family: Arial, sans-serif; 
            background-color: #f4f6f9; 
            display: flex; 
            height: 100vh; 
        }

        .barra-lateral { 
            width: 260px; 
            background-color: #0d47a1; 
            color: white; 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
        }

        .encabezado-lateral { 
            padding: 20px; 
            text-align: center; 
            background-color: #0a3982; 
            font-size: 20px; 
            font-weight: bold; 
        }

        .menu-navegacion { 
            list-style: none; 
            padding: 0; 
            margin: 0; 
            flex-grow: 1; 
        }

        .menu-navegacion li a { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            padding: 15px 20px; 
            color: #e3f2fd; 
            text-decoration: none; 
            font-size: 15px; 
            border-bottom: 1px solid #0a3982; 
            transition: 0.3s; 
        }

        .menu-navegacion li a:hover { 
            background-color: #1565c0; 
            color: white; 
        }

        .boton-salir {
            background-color: #d32f2f;
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: bold;
            transition: 0.3s;
        }

        .boton-salir:hover {
            background-color: #b71c1c;
        }

        .contenido-principal { 
            flex-grow: 1; 
            padding: 30px; 
            overflow-y: auto; 
            display: grid; 
            grid-template-columns: 1fr 400px; 
            gap: 25px; 
        }

        .seccion-productos, .seccion-carrito { 
            background-color: white; 
            padding: 20px; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
            height: fit-content; 
        }
        
        .titulo-modulo { 
            margin: 0 0 20px 0; 
            color: #333; 
            font-size: 20px; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            text-align: left; 
        }

        th, td { 
            padding: 10px 12px; 
            border-bottom: 1px solid #eee; 
            font-size: 14px; 
        }

        th { 
            background-color: #f8f9fa; 
            color: #333; 
        }

        .boton-agregar { 
            background-color: #2e7d32; 
            color: white; 
            border: none; 
            padding: 6px 10px; 
            border-radius: 4px; 
            cursor: pointer; 
        }

        .boton-agregar:hover { 
            background-color: #1b5e20; 
        }

        .lista-carrito { 
            margin-bottom: 20px; 
            max-height: 350px; 
            overflow-y: auto; 
        }

        .item-carrito { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 10px 0; 
            border-bottom: 1px solid #eee; 
            font-size: 14px; 
        }

        .contenedor-total { 
            background-color: #e3f2fd; 
            padding: 15px; 
            border-radius: 8px; 
            text-align: center; 
            margin-bottom: 20px; 
        }

        .texto-total { 
            font-size: 24px; 
            font-weight: bold; 
            color: #0d47a1; 
        }

        .boton-cobrar { 
            width: 100%; 
            background-color: #2e7d32; 
            color: white; 
            border: none; 
            padding: 14px; 
            border-radius: 8px; 
            font-size: 16px; 
            font-weight: bold; 
            cursor: pointer; 
            transition: 0.3s; 
        }

        .boton-cobrar:hover { 
            background-color: #1b5e20; 
        }

        .alerta-exito { 
            background-color: #e8f5e9; 
            color: #2e7d32; 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            grid-column: span 2; 
            font-weight: bold; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            border: 1px solid #a5d6a7; 
        }
    </style>
</head>
<body>

    <aside class="barra-lateral">
        <div>
            <div class="encabezado-lateral"><i class="bi bi-shop"></i> El Buen Precio</div>
            <ul class="menu-navegacion">
                <li><a href="/minimarket/public/dashboard.php"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                <?php if ($_SESSION['id_rol'] == 1): ?>
                    <li><a href="../productos/listar.php"><i class="bi bi-box-seam-fill"></i> Inventario</a></li>
                <?php endif; ?>
                <li><a href="nueva_venta.php"><i class="bi bi-cart-plus-fill"></i> Nueva Venta</a></li>
            </ul>
        </div>
        <a href="../usuarios/logout.php" class="boton-salir">
            <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
        </a>
    </aside>

    <main class="contenido-principal">
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="alerta-exito">
                <i class="bi bi-check-circle-fill"></i> ¡Venta registrada y stock actualizado con éxito!
            </div>
        <?php endif; ?>
        
        <section class="seccion-productos">
            <h2 class="titulo-modulo"><i class="bi bi-search" style="color: #0d47a1;"></i> Seleccionar Productos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_productos as $prod): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                            <td>Bs. <?php echo number_format($prod['precio_venta'], 2); ?></td>
                            <td><?php echo $prod['stock_actual']; ?> u.</td>
                            <td>
                                <button class="boton-agregar" onclick="agregarAlCarrito(<?php echo $prod['id_producto']; ?>, '<?php echo htmlspecialchars($prod['nombre']); ?>', <?php echo $prod['precio_venta']; ?>, <?php echo $prod['stock_actual']; ?>)">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="seccion-carrito">
            <h2 class="titulo-modulo"><i class="bi bi-cart4" style="color: #2e7d32;"></i> Detalle de Venta</h2>
            
            <div class="lista-carrito" id="contenedor-carrito">
                <p style="text-align: center; color: #999; margin-top: 20px;" id="texto-carrito-vacio">El carrito está vacío</p>
            </div>

            <div class="contenedor-total">
                <div style="font-size: 14px; color: #555;">Total a Pagar</div>
                <div class="texto-total">Bs. <span id="monto-total">0.00</span></div>
            </div>

            <form action="procesar_venta.php" method="POST" id="formulario-venta">
                <input type="hidden" name="json_detalles" id="json-detalles">
                <button type="button" class="boton-cobrar" onclick="finalizarVenta()">
                    <i class="bi bi-cash-coin"></i> Registrar Venta
                </button>
            </form>
        </section>
    </main>

    <script>
        let carrito = [];

        function agregarAlCarrito(id, nombre, precio, stockMax) {
            let item = carrito.find(p => p.id_producto === id);
            if (item) {
                if (item.cantidad < stockMax) { item.cantidad++; } 
                else { alert("No hay suficiente stock."); return; }
            } else {
                carrito.push({ id_producto: id, nombre: nombre, precio: precio, cantidad: 1 });
            }
            renderizarCarrito();
        }

        function modificarCantidad(id, cambio) {
            let item = carrito.find(p => p.id_producto === id);
            if (item) {
                item.cantidad += cambio;
                if (item.cantidad <= 0) { carrito = carrito.filter(p => p.id_producto !== id); }
            }
            renderizarCarrito();
        }

        function renderizarCarrito() {
            const contenedor = document.getElementById('contenedor-carrito');
            const totalPago = document.getElementById('monto-total');
            
            if (carrito.length === 0) {
                contenedor.innerHTML = '<p style="text-align: center; color: #999; margin-top: 20px;">El carrito está vacío</p>';
                totalPago.innerText = '0.00';
                return;
            }

            contenedor.innerHTML = '';
            let total = 0;
            carrito.forEach(p => {
                total += p.precio * p.cantidad;
                contenedor.innerHTML += `
                    <div class="item-carrito">
                        <div><strong>${p.nombre}</strong><br><small>Bs. ${p.precio.toFixed(2)}</small></div>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <button type="button" onclick="modificarCantidad(${p.id_producto}, -1)">-</button>
                            <span>${p.cantidad}</span>
                            <button type="button" onclick="modificarCantidad(${p.id_producto}, 1)">+</button>
                        </div>
                    </div>`;
            });
            totalPago.innerText = total.toFixed(2);
        }

        function finalizarVenta() {
            if (carrito.length === 0) { alert("Agregue productos."); return; }
            document.getElementById('json-detalles').value = JSON.stringify(carrito);
            document.getElementById('formulario-venta').submit();
        }
    </script>
</body>
</html>