<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Movimiento de Caja
 * Gestiona la tabla 'movimientos_caja'
 */
class MovimientoCaja extends Modelo
{
    protected $tabla = 'movimientos_caja';
    protected $llavePrimaria = 'id_movimiento';

    /**
     * Obtiene movimientos por caja
     */
    public function obtenerPorCaja($idCaja)
    {
        $sql = "SELECT * FROM {$this->tabla} 
                WHERE id_caja = :id_caja 
                ORDER BY fecha DESC";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id_caja' => $idCaja]);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene movimientos por tipo
     */
    public function obtenerPorTipo($idCaja, $tipo)
    {
        $sql = "SELECT * FROM {$this->tabla} 
                WHERE id_caja = :id_caja AND tipo = :tipo 
                ORDER BY fecha DESC";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id_caja' => $idCaja, 'tipo' => $tipo]);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene total de movimientos por caja
     */
    public function obtenerTotalPorCaja($idCaja, $tipo = null)
    {
        $sql = "SELECT SUM(monto) AS total 
                FROM {$this->tabla} 
                WHERE id_caja = :id_caja";
        if ($tipo) {
            $sql .= " AND tipo = :tipo";
        }
        $stmt = $this->bd->preparar($sql);
        $params = ['id_caja' => $idCaja];
        if ($tipo) {
            $params['tipo'] = $tipo;
        }
        $stmt->ejecutar($params);
        $resultado = $stmt->obtener();
        return $resultado['total'] ?? 0;
    }
}