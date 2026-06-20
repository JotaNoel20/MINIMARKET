<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Pago
 * Gestiona la tabla 'pago'
 */
class Pago extends Modelo
{
    protected $tabla = 'pago';
    protected $llavePrimaria = 'id_pago';

    /**
     * Obtiene el pago de una venta
     */
    public function obtenerPorVenta($idVenta)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE id_venta = :id_venta";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id_venta' => $idVenta]);
        return $stmt->obtener();
    }

    /**
     * Elimina el pago de una venta
     */
    public function eliminarPorVenta($idVenta)
    {
        $sql = "DELETE FROM {$this->tabla} WHERE id_venta = :id_venta";
        $stmt = $this->bd->preparar($sql);
        return $stmt->ejecutar(['id_venta' => $idVenta]);
    }
}