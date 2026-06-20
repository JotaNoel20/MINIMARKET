<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Detalle de Compra
 * Gestiona la tabla 'detalle_compra'
 */
class DetalleCompra extends Modelo
{
    protected $tabla = 'detalle_compra';
    protected $llavePrimaria = 'id_detalle_compra';

    /**
     * Obtiene detalles de una compra
     */
    public function obtenerPorCompra($idCompra)
    {
        $sql = "SELECT dc.*, p.nombre AS producto_nombre 
                FROM {$this->tabla} dc
                INNER JOIN producto p ON dc.id_producto = p.id_producto
                WHERE dc.id_compra = :id_compra";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id_compra' => $idCompra]);
        return $stmt->obtenerTodos();
    }

    /**
     * Elimina detalles de una compra
     */
    public function eliminarPorCompra($idCompra)
    {
        $sql = "DELETE FROM {$this->tabla} WHERE id_compra = :id_compra";
        $stmt = $this->bd->preparar($sql);
        return $stmt->ejecutar(['id_compra' => $idCompra]);
    }
}