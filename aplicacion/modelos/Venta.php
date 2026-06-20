<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Venta
 * Gestiona la tabla 'venta'
 */
class Venta extends Modelo
{
    protected $tabla = 'venta';
    protected $llavePrimaria = 'id_venta';

    /**
     * Obtiene todas las ventas con usuario
     */
    public function obtenerTodas()
    {
        $sql = "SELECT v.*, u.nombre AS usuario_nombre 
                FROM {$this->tabla} v
                INNER JOIN usuario u ON v.id_usuario = u.id_usuario
                ORDER BY v.fecha_venta DESC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene ventas de un usuario específico
     */
    public function obtenerPorUsuario($idUsuario)
    {
        $sql = "SELECT v.*, u.nombre AS usuario_nombre 
                FROM {$this->tabla} v
                INNER JOIN usuario u ON v.id_usuario = u.id_usuario
                WHERE v.id_usuario = :id_usuario
                ORDER BY v.fecha_venta DESC";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id_usuario' => $idUsuario]);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene ventas por rango de fechas
     */
    public function obtenerPorRangoFechas($fechaInicio, $fechaFin)
    {
        $sql = "SELECT v.*, u.nombre AS usuario_nombre 
                FROM {$this->tabla} v
                INNER JOIN usuario u ON v.id_usuario = u.id_usuario
                WHERE DATE(v.fecha_venta) BETWEEN :inicio AND :fin
                ORDER BY v.fecha_venta DESC";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['inicio' => $fechaInicio, 'fin' => $fechaFin]);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene el detalle completo de una venta
     */
    public function obtenerDetalleCompleto($idVenta)
    {
        // Datos de la venta
        $sqlVenta = "SELECT v.*, u.nombre AS usuario_nombre 
                     FROM {$this->tabla} v
                     INNER JOIN usuario u ON v.id_usuario = u.id_usuario
                     WHERE v.id_venta = :id";
        $stmt = $this->bd->preparar($sqlVenta);
        $stmt->ejecutar(['id' => $idVenta]);
        $venta = $stmt->obtener();

        if (!$venta) {
            return null;
        }

        // Detalles de la venta
        $sqlDetalles = "SELECT dv.*, p.nombre AS producto_nombre 
                        FROM detalle_venta dv
                        INNER JOIN producto p ON dv.id_producto = p.id_producto
                        WHERE dv.id_venta = :id";
        $stmt = $this->bd->preparar($sqlDetalles);
        $stmt->ejecutar(['id' => $idVenta]);
        $detalles = $stmt->obtenerTodos();

        // Pago de la venta
        $sqlPago = "SELECT * FROM pago WHERE id_venta = :id";
        $stmt = $this->bd->preparar($sqlPago);
        $stmt->ejecutar(['id' => $idVenta]);
        $pago = $stmt->obtener();

        return [
            'venta' => $venta,
            'detalles' => $detalles,
            'pago' => $pago
        ];
    }

    /**
     * Obtiene el total de ventas
     */
    public function obtenerTotalVentas()
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->tabla}";
        $stmt = $this->bd->consultar($sql);
        $resultado = $stmt->obtener();
        return $resultado['total'] ?? 0;
    }

    /**
     * Obtiene el total recaudado
     */
    public function obtenerTotalRecaudado()
    {
        $sql = "SELECT SUM(total) AS total FROM {$this->tabla}";
        $stmt = $this->bd->consultar($sql);
        $resultado = $stmt->obtener();
        return $resultado['total'] ?? 0;
    }

    /**
     * Obtiene el total recaudado por rango de fechas
     */
    public function obtenerTotalPorRango($fechaInicio, $fechaFin)
    {
        $sql = "SELECT SUM(total) AS total FROM {$this->tabla} 
                WHERE DATE(fecha_venta) BETWEEN :inicio AND :fin";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['inicio' => $fechaInicio, 'fin' => $fechaFin]);
        $resultado = $stmt->obtener();
        return $resultado['total'] ?? 0;
    }

    /**
     * Obtiene ventas del día actual
     */
    public function obtenerVentasDelDia()
    {
        $sql = "SELECT COUNT(*) AS total, SUM(total) AS recaudado 
                FROM {$this->tabla} 
                WHERE DATE(fecha_venta) = CURDATE()";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtener();
    }

    /**
     * Obtiene ventas de la semana
     */
    public function obtenerVentasSemana()
    {
        $sql = "SELECT COUNT(*) AS total, SUM(total) AS recaudado 
                FROM {$this->tabla} 
                WHERE YEARWEEK(fecha_venta) = YEARWEEK(CURDATE())";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtener();
    }

    /**
     * Obtiene ventas del mes
     */
    public function obtenerVentasMes()
    {
        $sql = "SELECT COUNT(*) AS total, SUM(total) AS recaudado 
                FROM {$this->tabla} 
                WHERE MONTH(fecha_venta) = MONTH(CURDATE()) 
                AND YEAR(fecha_venta) = YEAR(CURDATE())";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtener();
    }

    /**
     * Obtiene productos más vendidos
     */
    public function obtenerProductosMasVendidos($limite = 5)
    {
        $sql = "SELECT p.id_producto, p.nombre, c.nombre AS categoria,
                       SUM(dv.cantidad) AS total_unidades,
                       SUM(dv.subtotal) AS total_recaudado
                FROM detalle_venta dv
                INNER JOIN producto p ON dv.id_producto = p.id_producto
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria
                GROUP BY dv.id_producto
                ORDER BY total_unidades DESC
                LIMIT " . (int)$limite;
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene ventas por vendedor
     */
    public function obtenerVentasPorVendedor()
    {
        $sql = "SELECT u.id_usuario, u.nombre AS vendedor,
                       COUNT(v.id_venta) AS total_ventas,
                       SUM(v.total) AS total_recaudado
                FROM {$this->tabla} v
                INNER JOIN usuario u ON v.id_usuario = u.id_usuario
                GROUP BY v.id_usuario
                ORDER BY total_recaudado DESC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene ventas de un usuario en el día actual
     */
    public function obtenerVentasDeUsuarioHoy($idUsuario)
    {
        $sql = "SELECT COUNT(*) AS total, SUM(total) AS recaudado 
                FROM {$this->tabla} 
                WHERE id_usuario = :id_usuario 
                AND DATE(fecha_venta) = CURDATE()";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id_usuario' => $idUsuario]);
        return $stmt->obtener();
    }

    /**
     * Crea una venta completa (con transacción y validación de stock)
     */
    public function crearVenta($idUsuario, $carrito, $metodoPago, $montoRecibido, $idCaja)
    {
        $this->bd->iniciarTransaccion();

        try {
            // ============================================================
            // 1. VALIDAR STOCK DISPONIBLE ANTES DE VENDER
            // ============================================================
            $producto = new Producto();
            foreach ($carrito as $item) {
                $stockDisponible = $producto->verificarStock($item['id_producto'], $item['cantidad']);
                if (!$stockDisponible) {
                    throw new \Exception("Stock insuficiente para el producto ID: " . $item['id_producto']);
                }
            }

            // ============================================================
            // 2. CALCULAR TOTAL
            // ============================================================
            $totalVenta = 0;
            foreach ($carrito as $item) {
                $totalVenta += $item['precio'] * $item['cantidad'];
            }

            // ============================================================
            // 3. INSERTAR VENTA
            // ============================================================
            $idVenta = $this->insertar([
                'id_usuario' => $idUsuario,
                'fecha_venta' => date('Y-m-d H:i:s'),
                'subtotal' => $totalVenta,
                'descuento' => 0,
                'total' => $totalVenta
            ]);

            if (!$idVenta) {
                throw new \Exception('Error al crear la venta');
            }

            // ============================================================
            // 4. INSERTAR DETALLES Y ACTUALIZAR STOCK
            // ============================================================
            $detalleVenta = new DetalleVenta();
            foreach ($carrito as $item) {
                $subtotal = $item['precio'] * $item['cantidad'];
                $detalleVenta->insertar([
                    'id_venta' => $idVenta,
                    'id_producto' => $item['id_producto'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'descuento' => 0,
                    'subtotal' => $subtotal
                ]);

                // Actualizar stock (restar)
                $producto->actualizarStock($item['id_producto'], -$item['cantidad']);
            }

            // ============================================================
            // 5. INSERTAR PAGO
            // ============================================================
            $pago = new Pago();
            $pago->insertar([
                'id_venta' => $idVenta,
                'metodo_pago' => $metodoPago,
                'monto' => $totalVenta,
                'referencia' => null,
                'fecha_pago' => date('Y-m-d H:i:s')
            ]);

            // ============================================================
            // 6. INSERTAR MOVIMIENTO DE CAJA
            // ============================================================
            $movimiento = new MovimientoCaja();
            $movimiento->insertar([
                'id_caja' => $idCaja,
                'tipo' => 'ingreso',
                'concepto' => 'Venta #' . $idVenta,
                'monto' => $totalVenta,
                'id_referencia' => $idVenta,
                'fecha' => date('Y-m-d H:i:s')
            ]);

            // ============================================================
            // 7. ACTUALIZAR CAJA
            // ============================================================
            $caja = new Caja();
            $caja->actualizarIngresos($idCaja, $totalVenta);

            // ============================================================
            // 8. CONFIRMAR TRANSACCIÓN
            // ============================================================
            $this->bd->confirmarTransaccion();

            return [
                'success' => true,
                'venta_id' => $idVenta,
                'total' => $totalVenta
            ];

        } catch (\Exception $e) {
            $this->bd->revertirTransaccion();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Anula una venta (reversión de stock)
     */
    public function anularVenta($idVenta)
    {
        $this->bd->iniciarTransaccion();

        try {
            // Obtener detalles de la venta
            $detalleVenta = new DetalleVenta();
            $detalles = $detalleVenta->obtenerPorVenta($idVenta);

            // Revertir stock
            $producto = new Producto();
            foreach ($detalles as $detalle) {
                $producto->actualizarStock($detalle['id_producto'], $detalle['cantidad']);
            }

            // Eliminar pago
            $pago = new Pago();
            $pago->eliminarPorVenta($idVenta);

            // Eliminar detalles
            $detalleVenta->eliminarPorVenta($idVenta);

            // Eliminar venta
            $this->eliminarPorId($idVenta);

            $this->bd->confirmarTransaccion();
            return true;

        } catch (\Exception $e) {
            $this->bd->revertirTransaccion();
            return false;
        }
    }
}