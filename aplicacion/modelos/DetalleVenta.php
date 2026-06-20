<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Detalle de Venta
 * Gestiona la tabla 'detalle_venta'
 */
class DetalleVenta extends Modelo
{
    protected $tabla = 'detalle_venta';
    protected $llavePrimaria = 'id_detalle_venta';

    /**
     * Obtiene detalles de una venta
     */
    public function obtenerPorVenta($idVenta)
    {
        $sql = "SELECT dv.*, p.nombre AS producto_nombre 
                FROM {$this->tabla} dv
                INNER JOIN producto p ON dv.id_producto = p.id_producto
                WHERE dv.id_venta = :id_venta";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id_venta' => $idVenta]);
        return $stmt->obtenerTodos();
    }

    /**
     * Elimina detalles de una venta
     */
    public function eliminarPorVenta($idVenta)
    {
        $sql = "DELETE FROM {$this->tabla} WHERE id_venta = :id_venta";
        $stmt = $this->bd->preparar($sql);
        return $stmt->ejecutar(['id_venta' => $idVenta]);
    }
}