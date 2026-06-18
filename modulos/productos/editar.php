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
    $consulta_categorias = "SELECT id_categoria, nombre FROM categoria WHERE estado = 1";
    $sentencia_categorias = $pdo->prepare($consulta_categorias);
    $sentencia_categorias->execute();
    $lista_categorias = $sentencia_categorias->fetchAll();
} catch (\PDOException $e) {
    die("Error al cargar categorías: " . $e->getMessage());
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_producto_get = $_GET['id'];

    try {
        $consulta_producto = "SELECT * FROM producto WHERE id_producto = :id LIMIT 1";
        $sentencia_producto = $pdo->prepare($consulta_producto);
        $sentencia_producto->execute(['id' => $id_producto_get]);
        $producto = $sentencia_producto->fetch();

        if (!$producto) {
            header('Location: /minimarket/modulos/productos/listar.php');
            exit;
        }
    } catch (\PDOException $e) {
        die("Error al buscar el producto: " . $e->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /minimarket/modulos/productos/listar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_prod_update = $_POST['id_producto'];
    $id_categoria   = $_POST['id_categoria'];
    $nombre         = trim($_POST['nombre']);
    $descripcion    = trim($_POST['descripcion']);
    $precio_venta   = $_POST['precio_venta'];
    $precio_compra  = $_POST['precio_compra'];
    $stock_actual   = $_POST['stock_actual'];
    $stock_minimo   = $_POST['stock_minimo'];

    try {
        $sql_update = "UPDATE producto 
                       SET id_categoria = :id_categoria, 
                           nombre = :nombre, 
                           descripcion = :descripcion, 
                           precio_venta = :precio_venta, 
                           precio_compra = :precio_compra, 
                           stock_actual = :stock_actual, 
                           stock_minimo = :stock_minimo 
                       WHERE id_producto = :id_producto";
        
        $sentencia_update = $pdo->prepare($sql_update);
        $sentencia_update->execute([
            'id_categoria' => $id_categoria,
            'nombre'       => $nombre,
            'descripcion'  => $descripcion,
            'precio_venta' => $precio_venta,
            'precio_compra'=> $precio_compra,
            'stock_actual' => $stock_actual,
            'stock_minimo' => $stock_minimo,
            'id_producto'  => $id_prod_update
        ]);

        header('Location: /minimarket/modulos/productos/listar.php');
        exit;
    } catch (\PDOException $e) {
        die("Error al actualizar el producto: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
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
        }

        .contenedor-formulario {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            max-width: 700px;
            margin: 0 auto;
        }

        .grupo-formulario {
            margin-bottom: 20px;
        }

        .grupo-formulario label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .campo-entrada {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .fila-formulario {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .boton-guardar {
            background-color: #0288d1;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 15px;
        }

        .boton-cancelar {
            background-color: #757575;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 15px;
            display: inline-block;
            text-align: center;
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
        <div class="contenedor-formulario">
            <h2>Modificar Producto</h2>
            <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 25px;">

            <form action="editar.php?id=<?php echo $producto['id_producto']; ?>" method="POST">
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

                <div class="grupo-formulario">
                    <label for="nombre">Nombre del Producto</label>
                    <input type="text" id="nombre" name="nombre" class="campo-entrada" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                </div>

                <div class="grupo-formulario">
                    <label for="id_categoria">Categoría</label>
                    <select id="id_categoria" name="id_categoria" class="campo-entrada" required>
                        <?php foreach ($lista_categorias as $cat): ?>
                            <option value="<?php echo $cat['id_categoria']; ?>" <?php echo ($cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grupo-formulario">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="campo-entrada" rows="3"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                </div>

                <div class="fila-formulario">
                    <div class="grupo-formulario">
                        <label for="precio_compra">Precio de Compra (Bs.)</label>
                        <input type="number" id="precio_compra" name="precio_compra" class="campo-entrada" step="0.01" min="0" value="<?php echo $producto['precio_compra']; ?>" required>
                    </div>
                    <div class="grupo-formulario">
                        <label for="precio_venta">Precio de Venta (Bs.)</label>
                        <input type="number" id="precio_venta" name="precio_venta" class="campo-entrada" step="0.01" min="0" value="<?php echo $producto['precio_venta']; ?>" required>
                    </div>
                </div>

                <div class="fila-formulario">
                    <div class="grupo-formulario">
                        <label for="stock_actual">Stock Actual</label>
                        <input type="number" id="stock_actual" name="stock_actual" class="campo-entrada" min="0" value="<?php echo $producto['stock_actual']; ?>" required>
                    </div>
                    <div class="grupo-formulario">
                        <label for="stock_minimo">Stock Mínimo</label>
                        <input type="number" id="stock_minimo" name="stock_minimo" class="campo-entrada" min="0" value="<?php echo $producto['stock_minimo']; ?>" required>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; margin-top: 10px;">
                    <button type="submit" class="boton-guardar">Actualizar Cambios</button>
                    <a href="listar.php" class="boton-cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

</body>
</html>