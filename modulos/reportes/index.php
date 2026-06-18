<?php
session_start();

// Control de acceso: Solo administradores (Rol 1)
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header('Location: /minimarket/public/dashboard.php');
    exit;
}

require_once '../../config/conexion.php';

$nombre_usuario = $_SESSION['nombre'];

try {
    // 1. KPIs Generales
    // total recaudado y transacciones
    $kpi_ventas_stmt = $pdo->prepare("SELECT SUM(total) AS total_recaudado, COUNT(*) AS total_ventas FROM venta");
    $kpi_ventas_stmt->execute();
    $kpi_ventas = $kpi_ventas_stmt->fetch();

    // total stock
    $kpi_stock_stmt = $pdo->prepare("SELECT SUM(stock_actual) AS total_stock FROM producto WHERE estado = 1");
    $kpi_stock_stmt->execute();
    $kpi_stock = $kpi_stock_stmt->fetch();

    // alertas de stock bajo
    $kpi_alertas_stmt = $pdo->prepare("SELECT COUNT(*) AS total_alertas FROM producto WHERE stock_actual <= stock_minimo AND estado = 1");
    $kpi_alertas_stmt->execute();
    $kpi_alertas = $kpi_alertas_stmt->fetch();

    // 2. Ventas por Vendedor
    $ventas_vendedor_stmt = $pdo->prepare("
        SELECT u.nombre AS vendedor, SUM(v.total) AS total_vendido, COUNT(v.id_venta) AS ventas_realizadas
        FROM venta v
        INNER JOIN usuario u ON v.id_usuario = u.id_usuario
        GROUP BY v.id_usuario
        ORDER BY total_vendido DESC
    ");
    $ventas_vendedor_stmt->execute();
    $ventas_vendedor = $ventas_vendedor_stmt->fetchAll();

    // 3. Productos Más Vendidos
    $productos_mas_vendidos_stmt = $pdo->prepare("
        SELECT p.nombre AS producto, c.nombre AS categoria, SUM(dv.cantidad) AS total_unidades, SUM(dv.subtotal) AS total_recaudado
        FROM detalle_venta dv
        INNER JOIN producto p ON dv.id_producto = p.id_producto
        INNER JOIN categoria c ON p.id_categoria = c.id_categoria
        GROUP BY dv.id_producto
        ORDER BY total_unidades DESC
        LIMIT 5
    ");
    $productos_mas_vendidos_stmt->execute();
    $productos_mas_vendidos = $productos_mas_vendidos_stmt->fetchAll();

    // 4. Productos con Stock Crítico
    $stock_critico_stmt = $pdo->prepare("
        SELECT p.nombre, p.stock_actual, p.stock_minimo, c.nombre AS categoria
        FROM producto p
        INNER JOIN categoria c ON p.id_categoria = c.id_categoria
        WHERE p.stock_actual <= p.stock_minimo AND p.estado = 1
        ORDER BY p.stock_actual ASC
        LIMIT 5
    ");
    $stock_critico_stmt->execute();
    $stock_critico = $stock_critico_stmt->fetchAll();

    // 5. Ventas Diarias (Últimos 7 días con ventas)
    $ventas_diarias_stmt = $pdo->prepare("
        SELECT DATE(fecha_venta) AS fecha, SUM(total) AS total_dia, COUNT(*) AS transacciones_dia
        FROM venta
        GROUP BY DATE(fecha_venta)
        ORDER BY fecha DESC
        LIMIT 7
    ");
    $ventas_diarias_stmt->execute();
    $ventas_diarias = $ventas_diarias_stmt->fetchAll();

} catch (\PDOException $e) {
    die("Error al generar reportes: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Reportes y Estadísticas</title>
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

        .menu-navegacion li a:hover, .menu-navegacion li a.activo {
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
        }

        .barra-superior {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 15px 25px;
            margin-bottom: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .titulo-seccion h2 {
            margin: 0;
            color: #333;
        }

        .info-usuario {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #0d47a1;
            font-weight: bold;
        }

        /* Dashboard KPI Grid */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .kpi-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 20px;
            border-left: 6px solid #ccc;
        }

        .kpi-card.ventas { border-left-color: #2e7d32; }
        .kpi-card.transacciones { border-left-color: #f57c00; }
        .kpi-card.stock { border-left-color: #0288d1; }
        .kpi-card.alertas { border-left-color: #c62828; }

        .kpi-icon {
            font-size: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50px;
        }

        .kpi-card.ventas .kpi-icon { background-color: #e8f5e9; color: #2e7d32; }
        .kpi-card.transacciones .kpi-icon { background-color: #fff3e0; color: #f57c00; }
        .kpi-card.stock .kpi-icon { background-color: #e1f5fe; color: #0288d1; }
        .kpi-card.alertas .kpi-icon { background-color: #ffebee; color: #c62828; }

        .kpi-info h3 {
            margin: 0;
            font-size: 13px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-info p {
            margin: 5px 0 0 0;
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        /* Layout de reportes */
        .reporte-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        @media (max-width: 900px) {
            .reporte-layout {
                grid-template-columns: 1fr;
            }
        }

        .reporte-seccion {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .reporte-seccion h3 {
            margin: 0 0 20px 0;
            font-size: 18px;
            color: #0d47a1;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid #f4f6f9;
            padding-bottom: 10px;
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
            font-weight: bold;
        }

        .alerta-stock {
            color: #c62828;
            background-color: #ffebee;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
        }

        .total-destacado {
            font-weight: bold;
            color: #2e7d32;
        }
    </style>
</head>
<body>

    <aside class="barra-lateral">
        <div>
            <div class="encabezado-lateral">
                <i class="bi bi-shop"></i> El Buen Precio
            </div>
            <ul class="menu-navegacion">
                <li><a href="/minimarket/public/dashboard.php"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                <li><a href="../productos/listar.php"><i class="bi bi-box-seam-fill"></i> Inventario</a></li>
                <li><a href="index.php" class="activo"><i class="bi bi-graph-up-arrow"></i> Reportes</a></li>
            </ul>
        </div>
        <a href="../usuarios/logout.php" class="boton-salir">
            <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
        </a>
    </aside>

    <main class="contenido-principal">
        
        <div class="barra-superior">
            <div class="titulo-seccion">
                <h2>Panel de Reportes y Estadísticas</h2>
            </div>
            <div class="info-usuario">
                <i class="bi bi-person-circle" style="font-size: 22px;"></i>
                <span>¡Hola, <?php echo htmlspecialchars($nombre_usuario); ?>!</span>
            </div>
        </div>

        <!-- KPIs Generales -->
        <div class="kpi-grid">
            <div class="kpi-card ventas">
                <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
                <div class="kpi-info">
                    <h3>Total Recaudado</h3>
                    <p>Bs. <?php echo number_format($kpi_ventas['total_recaudado'] ?? 0, 2); ?></p>
                </div>
            </div>

            <div class="kpi-card transacciones">
                <div class="kpi-icon"><i class="bi bi-receipt"></i></div>
                <div class="kpi-info">
                    <h3>Ventas Totales</h3>
                    <p><?php echo $kpi_ventas['total_ventas'] ?? 0; ?> trans.</p>
                </div>
            </div>

            <div class="kpi-card stock">
                <div class="kpi-icon"><i class="bi bi-box-seam"></i></div>
                <div class="kpi-info">
                    <h3>Artículos en Stock</h3>
                    <p><?php echo $kpi_stock['total_stock'] ?? 0; ?> u.</p>
                </div>
            </div>

            <div class="kpi-card alertas">
                <div class="kpi-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="kpi-info">
                    <h3>Alertas de Stock</h3>
                    <p><?php echo $kpi_alertas['total_alertas'] ?? 0; ?> prod.</p>
                </div>
            </div>
        </div>

        <!-- Layout de reportes detallados -->
        <div class="reporte-layout">
            
            <!-- Ventas por Vendedor -->
            <div class="reporte-seccion">
                <h3><i class="bi bi-people-fill" style="color: #0d47a1;"></i> Rendimiento por Vendedor</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre Vendedor</th>
                            <th>Transacciones</th>
                            <th>Total Vendido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventas_vendedor)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #999;">No hay registros de ventas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ventas_vendedor as $vv): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($vv['vendedor']); ?></strong></td>
                                    <td><?php echo $vv['ventas_realizadas']; ?> ventas</td>
                                    <td class="total-destacado">Bs. <?php echo number_format($vv['total_sold'] ?? $vv['total_vendido'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Productos Más Vendidos -->
            <div class="reporte-seccion">
                <h3><i class="bi bi-star-fill" style="color: #f57c00;"></i> Productos Más Vendidos (Top 5)</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Cant. Vendida</th>
                            <th>Recaudado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos_mas_vendidos)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #999;">No hay datos de ventas de productos.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos_mas_vendidos as $pmv): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($pmv['producto']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($pmv['categoria']); ?></td>
                                    <td><?php echo $pmv['total_unidades']; ?> u.</td>
                                    <td class="total-destacado">Bs. <?php echo number_format($pmv['total_recaudado'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Productos con Stock Crítico -->
            <div class="reporte-seccion">
                <h3><i class="bi bi-shield-exclamation" style="color: #c62828;"></i> Alertas de Stock Crítico</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Stock Min.</th>
                            <th>Stock Act.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stock_critico)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #2e7d32; font-weight: bold;">
                                    <i class="bi bi-check-circle-fill"></i> ¡Todo el inventario está con stock óptimo!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($stock_critico as $sc): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($sc['nombre']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($sc['categoria']); ?></td>
                                    <td><?php echo $sc['stock_minimo']; ?> u.</td>
                                    <td>
                                        <span class="alerta-stock"><?php echo $sc['stock_actual']; ?> u.</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Ventas Diarias -->
            <div class="reporte-seccion">
                <h3><i class="bi bi-calendar-check-fill" style="color: #0288d1;"></i> Ingresos Diarios Recientes</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Transacciones</th>
                            <th>Total Recaudado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventas_diarias)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #999;">No hay registros diarios de ingresos.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ventas_diarias as $vd): ?>
                                <tr>
                                    <td><strong><?php echo date('d/m/Y', strtotime($vd['fecha'])); ?></strong></td>
                                    <td><?php echo $vd['transacciones_dia']; ?> trans.</td>
                                    <td class="total-destacado">Bs. <?php echo number_format($vd['total_dia'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </main>
</body>
</html>
