<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: /minimarket/public/index.php');
    exit;
}

if ($_SESSION['id_rol'] != 1) {
    header('Location: /minimarket/public/dashboard.php');
    exit;
}

require_once '../../config/conexion.php';

try {
    $consulta_sql = "SELECT p.*, c.nombre AS categoria 
                     FROM producto p 
                     LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                     WHERE p.estado = 1";
    $sentencia = $pdo->prepare($consulta_sql);
    $sentencia->execute();
    $lista_productos = $sentencia->fetchAll();
} catch (\PDOException $e) {
    die("Error al consultar productos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Productos</title>
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

        .boton-crear {
            background-color: #2e7d32;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contenedor-tabla {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            color: #333;
        }

        .etiqueta-stock-ok {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: bold;
        }

        .etiqueta-stock-bajo {
            background-color: #ffebee;
            color: #c62828;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: bold;
        }

        .boton-accion {
            text-decoration: none;
            color: #555;
            margin-right: 10px;
            font-size: 18px;
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
                <li><a href="listar.php"><i class="bi bi-box-seam-fill"></i> Inventario</a></li>
                <li><a href="../reportes/index.php"><i class="bi bi-graph-up-arrow"></i> Reportes</a></li>
            </ul>
        </div>
        <a href="../usuarios/logout.php" class="boton-salir">
            <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
        </a>
    </aside>

    <main class="contenido-principal">
        <div class="barra-superior">
            <h2>Inventario de Productos</h2>
            <?php if ($_SESSION['id_rol'] == 1): ?>
                <a href="crear.php" class="boton-crear"><i class="bi bi-plus-circle"></i> Nuevo Producto</a>
            <?php endif; ?>
        </div>

        <div class="contenedor-tabla">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio Venta</th>
                        <th>Stock Actual</th>
                        <?php if ($_SESSION['id_rol'] == 1): ?>
                            <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lista_productos)): ?>
                        <tr>
                            <td colspan="<?php echo ($_SESSION['id_rol'] == 1) ? 6 : 5; ?>" style="text-align: center; color: #999;">No hay productos registrados aún.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($lista_productos as $prod): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($prod['id_producto']); ?></td>
                                <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($prod['categoria'] ?? 'Sin categoría'); ?></td>
                                <td>Bs. <?php echo number_format($prod['precio_venta'], 2); ?></td>
                                <td>
                                    <span class="<?php echo ($prod['stock_actual'] > $prod['stock_minimo']) ? 'etiqueta-stock-ok' : 'etiqueta-stock-bajo'; ?>">
                                        <?php echo htmlspecialchars($prod['stock_actual']); ?>
                                    </span>
                                </td>
                                <?php if ($_SESSION['id_rol'] == 1): ?>
                                    <td>
                                        <a href="editar.php?id=<?php echo $prod['id_producto']; ?>" class="boton-accion" style="color: #0288d1;"><i class="bi bi-pencil-square"></i></a>
                                        <a href="/minimarket/modulos/productos/eliminar.php?id=<?php echo $prod['id_producto']; ?>" class="boton-accion" style="color: #d32f2f;" onclick="return confirm('¿Seguro que deseas eliminar este producto?')"><i class="bi bi-trash-fill"></i></a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>