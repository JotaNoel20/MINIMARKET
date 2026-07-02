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
     * Actualiza los ingresos de la caja
     */
    public function actualizarIngresos($idCaja, $monto)
    {
        $sql = "UPDATE {$this->tabla} 
                SET total_ingresos = total_ingresos + " . (float)$monto . ",
                    saldo_final = saldo_final + " . (float)$monto . "
                WHERE id_caja = " . (int)$idCaja;
        
        $stmt = $this->bd->consultar($sql);
        return true;
    }

    /**
     * Obtiene el monto general configurado para hoy
     */
    public function obtenerMontoGeneral()
    {
        $sql = "SELECT valor FROM configuracion 
                WHERE clave = 'monto_general_caja' 
                ORDER BY fecha_actualizacion DESC 
                LIMIT 1";
        $stmt = $this->bd->consultar($sql);
        $resultado = $stmt->obtener();
        return $resultado ? (float)$resultado['valor'] : null;
    }

    /**
     * Guarda el monto general del día
     */
    public function guardarMontoGeneral($monto, $idUsuario)
    {
        // Primero eliminar la configuración anterior
        $sqlDelete = "DELETE FROM configuracion WHERE clave = 'monto_general_caja'";
        $this->bd->consultar($sqlDelete);

        // Insertar nueva configuración
        $sqlInsert = "INSERT INTO configuracion (clave, valor, creado_por) 
                      VALUES ('monto_general_caja', :valor, :creado_por)";
        $stmt = $this->bd->preparar($sqlInsert);
        return $stmt->ejecutar([
            'valor' => $monto,
            'creado_por' => $idUsuario
        ]);
    }

    /**
     * Obtiene historial de montos configurados
     */
    public function obtenerHistorialMontos()
    {
        $sql = "SELECT c.*, u.nombre AS creado_por_nombre 
                FROM configuracion c
                LEFT JOIN usuario u ON c.creado_por = u.id_usuario
                WHERE c.clave = 'monto_general_caja'
                ORDER BY c.fecha_actualizacion DESC
                LIMIT 10";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
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