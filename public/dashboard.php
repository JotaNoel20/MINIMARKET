<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$nombre_usuario = $_SESSION['nombre'];
$id_rol         = $_SESSION['id_rol']; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimarket El Buen Precio - Panel Principal</title>
    
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

        .contenedor-opciones {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .tarjeta-opcion {
            background-color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.05);
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: transform 0.2s, box-shadow 0.2s;
            border-top: 5px solid #2e7d32;
        }

        .tarjeta-opcion:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .tarjeta-opcion i {
            font-size: 40px;
            color: #2e7d32;
            margin-bottom: 15px;
            display: block;
        }

        .tarjeta-opcion h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #0d47a1;
        }

        .tarjeta-opcion p {
            margin: 0;
            font-size: 13px;
            color: #666;
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
                <li><a href="dashboard.php"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                <?php if ($id_rol == 1): ?>
                    <li><a href="../modulos/productos/listar.php"><i class="bi bi-box-seam-fill"></i> Inventario</a></li>
                <?php endif; ?>
                <?php if ($id_rol == 2): ?>
                    <li><a href="../modulos/ventas/nueva_venta.php"><i class="bi bi-cart-plus-fill"></i> Nueva Venta</a></li>
                <?php endif; ?>
                <?php if ($id_rol == 1): ?>
                    <li><a href="../modulos/reportes/index.php"><i class="bi bi-graph-up-arrow"></i> Reportes</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <a href="../modulos/usuarios/logout.php" class="boton-salir">
            <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
        </a>
    </aside>

    <main class="contenido-principal">
        
        <div class="barra-superior">
            <div class="titulo-seccion">
                <h2>Panel de Control</h2>
            </div>
            <div class="info-usuario">
                <i class="bi bi-person-circle" style="font-size: 22px;"></i>
                <span>¡Hola, <?php echo htmlspecialchars($nombre_usuario); ?>!</span>
            </div>
        </div>

        <div class="contenedor-opciones">
            
            <?php if ($id_rol == 2): ?>
                <a href="../modulos/ventas/nueva_venta.php" class="tarjeta-opcion" style="border-top-color: #2e7d32;">
                    <i class="bi bi-calculator-fill" style="color: #2e7d32;"></i>
                    <h3>Abrir Caja</h3>
                    <p>Registrar ventas y emitir comprobantes.</p>
                </a>
            <?php endif; ?>

            <?php if ($id_rol == 1): ?>
                <a href="../modulos/productos/listar.php" class="tarjeta-opcion" style="border-top-color: #0288d1;">
                    <i class="bi bi-boxes" style="color: #0288d1;"></i>
                    <h3>Productos</h3>
                    <p>Administrar stock, precios y categorías.</p>
                </a>
            <?php endif; ?>

            <a href="../modulos/ventas/listar_ventas.php" class="tarjeta-opcion" style="border-top-color: #f57c00;">
                <i class="bi bi-receipt" style="color: #f57c00;"></i>
                <h3>Historial</h3>
                <p>Ver registros de ventas pasadas.</p>
            </a>

            <?php if ($id_rol == 1): ?>
                <a href="../modulos/reportes/index.php" class="tarjeta-opcion" style="border-top-color: #e91e63;">
                    <i class="bi bi-graph-up-arrow" style="color: #e91e63;"></i>
                    <h3>Reportes</h3>
                    <p>Analizar estadísticas y rendimiento.</p>
                </a>
            <?php endif; ?>

        </div>

    </main>

</body>
</html>