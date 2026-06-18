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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['json_detalles'])) {
    require_once '../../config/conexion.php';

    $id_usuario = $_SESSION['id_usuario'];
    $carrito = json_decode($_POST['json_detalles'], true);

    if (empty($carrito)) {
        header('Location: nueva_venta.php');
        exit;
    }

    $total_venta = 0;
    foreach ($carrito as $item) {
        $total_venta += $item['precio'] * $item['cantidad'];
    }

    $subtotal_general = $total_venta;
    $descuento_general = 0.00;

    try {
        $pdo->beginTransaction();

        $consulta_venta = "INSERT INTO venta (id_usuario, fecha_venta, subtotal, descuento, total) 
                           VALUES (?, NOW(), ?, ?, ?)";
        $preparar_venta = $pdo->prepare($consulta_venta);
        $preparar_venta->execute([
            (int)$id_usuario,
            (float)$subtotal_general,
            (float)$descuento_general,
            (float)$total_venta
        ]);

        $id_venta_generado = $pdo->lastInsertId();

        $consulta_detalle = "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, descuento, subtotal) 
                             VALUES (?, ?, ?, ?, ?, ?)";
        $preparar_detalle = $pdo->prepare($consulta_detalle);

        $consulta_stock = "UPDATE producto 
                           SET stock_actual = stock_actual - ? 
                           WHERE id_producto = ?";
        $preparar_stock = $pdo->prepare($consulta_stock);

        foreach ($carrito as $item) {
            $descuento_item = 0.00;
            $subtotal_item = ($item['cantidad'] * $item['precio']) - $descuento_item;

            $preparar_detalle->execute([
                $id_venta_generado,
                $item['id_producto'],
                $item['cantidad'],
                $item['precio'],
                $descuento_item,
                $subtotal_item
            ]);

            $preparar_stock->execute([
                $item['cantidad'],
                $item['id_producto']
            ]);
        }

        $pdo->commit();

        header('Location: nueva_venta.php?status=success');
        exit;

    } catch (\PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Error al procesar la venta: " . $e->getMessage());
    }
} else {
    header('Location: nueva_venta.php');
    exit;
}
?>