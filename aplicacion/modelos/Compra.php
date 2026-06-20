<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Compra
 * Gestiona la tabla 'compra'
 */
class Compra extends Modelo
{
    protected $tabla = 'compra';
    protected $llavePrimaria = 'id_compra';

    /**
     * Obtiene todas las compras con proveedor y usuario
     */
    public function obtenerTodas()
    {
        $sql = "SELECT c.*, p.nombre AS proveedor_nombre, u.nombre AS usuario_nombre 
                FROM {$this->tabla} c
                INNER JOIN proveedor p ON c.id_proveedor = p.id_proveedor
                INNER JOIN usuario u ON c.id_usuario = u.id_usuario
                ORDER BY c.fecha_compra DESC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene el detalle completo de una compra
     */
    public function obtenerDetalleCompleto($idCompra)
    {
        // Datos de la compra
        $sqlCompra = "SELECT c.*, p.nombre AS proveedor_nombre, u.nombre AS usuario_nombre 
                      FROM {$this->tabla} c
                      INNER JOIN proveedor p ON c.id_proveedor = p.id_proveedor
                      INNER JOIN usuario u ON c.id_usuario = u.id_usuario
                      WHERE c.id_compra = :id";
        $stmt = $this->bd->preparar($sqlCompra);
        $stmt->ejecutar(['id' => $idCompra]);
        $compra = $stmt->obtener();

        if (!$compra) {
            return null;
        }

        // Detalles de la compra
        $sqlDetalles = "SELECT dc.*, p.nombre AS producto_nombre 
                        FROM detalle_compra dc
                        INNER JOIN producto p ON dc.id_producto = p.id_producto
                        WHERE dc.id_compra = :id";
        $stmt = $this->bd->preparar($sqlDetalles);
        $stmt->ejecutar(['id' => $idCompra]);
        $detalles = $stmt->obtenerTodos();

        // Proveedor
        $proveedor = new Proveedor();
        $datosProveedor = $proveedor->obtenerPorId($compra['id_proveedor']);

        return [
            'compra' => $compra,
            'detalles' => $detalles,
            'proveedor' => $datosProveedor
        ];
    }

    /**
     * Obtiene total de compras por rango de fechas
     */
    public function obtenerTotalPorRango($fechaInicio, $fechaFin)
    {
        $sql = "SELECT SUM(total) AS total FROM {$this->tabla} 
                WHERE DATE(fecha_compra) BETWEEN :inicio AND :fin";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['inicio' => $fechaInicio, 'fin' => $fechaFin]);
        $resultado = $stmt->obtener();
        return $resultado['total'] ?? 0;
    }

    /**
     * Crea una compra completa (con transacción)
     */
    public function crearCompra($idProveedor, $idUsuario, $detalles)
    {
        $this->bd->iniciarTransaccion();

        try {
            $totalCompra = 0;
            foreach ($detalles as $item) {
                $totalCompra += $item['precio_compra'] * $item['cantidad'];
            }

            // Insertar compra
            $idCompra = $this->insertar([
                'id_proveedor' => $idProveedor,
                'id_usuario' => $idUsuario,
                'fecha_compra' => date('Y-m-d H:i:s'),
                'total' => $totalCompra,
                'estado' => 1
            ]);

            if (!$idCompra) {
                throw new \Exception('Error al crear la compra');
            }

            // Insertar detalles
            $detalleCompra = new DetalleCompra();
            foreach ($detalles as $item) {
                $subtotal = $item['precio_compra'] * $item['cantidad'];
                $detalleCompra->insertar([
                    'id_compra' => $idCompra,
                    'id_producto' => $item['id_producto'],
                    'cantidad' => $item['cantidad'],
                    'precio_compra' => $item['precio_compra'],
                    'subtotal' => $subtotal
                ]);

                // Actualizar stock del producto
                $producto = new Producto();
                $producto->actualizarStock($item['id_producto'], $item['cantidad']);
            }

            $this->bd->confirmarTransaccion();

            return [
                'success' => true,
                'compra_id' => $idCompra,
                'total' => $totalCompra
            ];

        } catch (\Exception $e) {
            $this->bd->revertirTransaccion();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}