<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../../public/index.php');
    exit;
}

require_once '../../config/conexion.php';

try {
    $consulta_ventas = "SELECT v.id_venta, v.fecha_venta, v.subtotal, v.descuento, v.total, u.nombre AS nombre_usuario
                        FROM venta v
                        INNER JOIN usuario u ON v.id_usuario = u.id_usuario
                        ORDER BY v.fecha_venta DESC";
    
    $sentencia = $pdo->prepare($consulta_ventas);
    $sentencia->execute();
    $lista_ventas = $sentencia->fetchAll();
} catch (\PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Ventas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; height: 100vh; }
        
        .barra-lateral { width: 260px; background-color: #0d47a1; color: white; display: flex; flex-direction: column; }
        .encabezado-lateral { padding: 20px; background-color: #0a3982; font-weight: bold; text-align: center; }
        .menu-navegacion { list-style: none; padding: 0; }
        .menu-navegacion li a { display: flex; align-items: center; gap: 10px; padding: 15px 20px; color: white; text-decoration: none; border-bottom: 1px solid #0a3982; }
        
        .contenido-principal { flex-grow: 1; padding: 30px; overflow-y: auto; }
        .contenedor-tabla { background-color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        th { background-color: #f8f9fa; }
        
        .boton-detalle { background-color: #0d47a1; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>
    <aside class="barra-lateral">
        <div class="encabezado-lateral"><i class="bi bi-shop"></i> El Buen Precio</div>
        <ul class="menu-navegacion">
            <li><a href="../../public/dashboard.php"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
            <li><a href="listar_ventas.php"><i class="bi bi-receipt"></i> Historial Ventas</a></li>
        </ul>
    </aside>

    <main class="contenido-principal">
        <div class="contenedor-tabla">
            <h2>Historial de Ventas</h2>
            <table>
                <thead>
                    <tr>
                        <th>N° Venta</th>
                        <th>Fecha</th>
                        <th>Vendedor</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_ventas as $venta): ?>
                        <tr>
                            <td>#<?php echo $venta['id_venta']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($venta['fecha_venta'])); ?></td>
                            <td><?php echo htmlspecialchars($venta['nombre_usuario']); ?></td>
                            <td>Bs. <?php echo number_format($venta['total'], 2); ?></td>
                            <td><a href="ver_detalle.php?id=<?php echo $venta['id_venta']; ?>" class="boton-detalle">Ver Ítems</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>