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

if (isset($_GET['id']) && !empty($_GET['id'])) {
    require_once '../../config/conexion.php';
    
    $id_producto = $_GET['id'];

    try {
        $consulta_desactivar = "UPDATE producto SET estado = 0 WHERE id_producto = :id";
        $sentencia = $pdo->prepare($consulta_desactivar);
        $sentencia->execute(['id' => $id_producto]);
        
        header('Location: /minimarket/modulos/productos/listar.php');
        exit;
    } catch (\PDOException $e) {
        die("Error al desactivar el producto: " . $e->getMessage());
    }
} else {
    header('Location: /minimarket/modulos/productos/listar.php');
    exit;
}
?>