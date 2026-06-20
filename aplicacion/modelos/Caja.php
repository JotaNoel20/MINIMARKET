<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Caja
 * Gestiona la tabla 'caja'
 */
class Caja extends Modelo
{
    protected $tabla = 'caja';
    protected $llavePrimaria = 'id_caja';

    /**
     * Obtiene la caja abierta de un usuario
     */
    public function obtenerCajaAbierta($idUsuario)
    {
        $sql = "SELECT * FROM {$this->tabla} 
                WHERE id_usuario = :id_usuario AND estado = 'ABIERTO'
                LIMIT 1";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id_usuario' => $idUsuario]);
        return $stmt->obtener();
    }

    /**
     * Obtiene la caja abierta (para admin)
     */
    public function obtenerCajaAbiertaAdmin()
    {
        $sql = "SELECT c.*, u.nombre AS usuario_nombre 
                FROM {$this->tabla} c
                INNER JOIN usuario u ON c.id_usuario = u.id_usuario
                WHERE c.estado = 'ABIERTO'
                LIMIT 1";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtener();
    }

    /**
     * Abre una nueva caja
     */
    public function abrir($idUsuario, $saldoInicial)
    {
        return $this->insertar([
            'id_usuario' => $idUsuario,
            'fecha_apertura' => date('Y-m-d H:i:s'),
            'fecha_cierre' => null,
            'saldo_inicial' => $saldoInicial,
            'total_ingresos' => 0,
            'total_egresos' => 0,
            'saldo_final' => $saldoInicial,
            'estado' => 'ABIERTO'
        ]);
    }

    /**
     * Cierra una caja
     */
    public function cerrar($idCaja)
    {
        return $this->actualizarPorId($idCaja, [
            'fecha_cierre' => date('Y-m-d H:i:s'),
            'estado' => 'CERRADO'
        ]);
    }

    /**
     * Actualiza los ingresos de la caja (CORREGIDO)
     */
    public function actualizarIngresos($idCaja, $monto)
    {
        // ✅ Usamos los valores directamente en la SQL para evitar problemas con placeholders duplicados
        $sql = "UPDATE {$this->tabla} 
                SET total_ingresos = total_ingresos + " . (float)$monto . ",
                    saldo_final = saldo_final + " . (float)$monto . "
                WHERE id_caja = " . (int)$idCaja;
        
        $stmt = $this->bd->consultar($sql);
        return true;
    }

    /**
     * Obtiene movimientos de una caja
     */
    public function obtenerMovimientos($idCaja)
    {
        $sql = "SELECT * FROM movimientos_caja 
                WHERE id_caja = :id_caja 
                ORDER BY fecha DESC";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id_caja' => $idCaja]);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene movimientos por fecha (para admin)
     */
    public function obtenerMovimientosPorFecha($fecha)
    {
        $sql = "SELECT mc.*, c.id_usuario, u.nombre AS usuario_nombre
                FROM movimientos_caja mc
                INNER JOIN caja c ON mc.id_caja = c.id_caja
                INNER JOIN usuario u ON c.id_usuario = u.id_usuario
                WHERE DATE(mc.fecha) = :fecha
                ORDER BY mc.fecha DESC";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['fecha' => $fecha]);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene total de ingresos de una fecha
     */
    public function obtenerTotalIngresosFecha($fecha)
    {
        $sql = "SELECT SUM(monto) AS total 
                FROM movimientos_caja mc
                INNER JOIN caja c ON mc.id_caja = c.id_caja
                WHERE DATE(mc.fecha) = :fecha AND mc.tipo = 'ingreso'";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['fecha' => $fecha]);
        $resultado = $stmt->obtener();
        return $resultado['total'] ?? 0;
    }

    /**
     * Obtiene total de egresos de una fecha
     */
    public function obtenerTotalEgresosFecha($fecha)
    {
        $sql = "SELECT SUM(monto) AS total 
                FROM movimientos_caja mc
                INNER JOIN caja c ON mc.id_caja = c.id_caja
                WHERE DATE(mc.fecha) = :fecha AND mc.tipo = 'egreso'";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['fecha' => $fecha]);
        $resultado = $stmt->obtener();
        return $resultado['total'] ?? 0;
    }
}