<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Proveedor
 * Gestiona la tabla 'proveedor'
 */
class Proveedor extends Modelo
{
    protected $tabla = 'proveedor';
    protected $llavePrimaria = 'id_proveedor';

    /**
     * Obtiene todos los proveedores activos
     */
    public function obtenerActivos()
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene todos los proveedores
     */
    public function obtenerTodas()
    {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY nombre ASC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene un proveedor por su ID
     */
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE id_proveedor = :id";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id' => $id]);
        return $stmt->obtener();
    }

    /**
     * Crea un nuevo proveedor
     */
    public function crear($datos)
    {
        return $this->insertar($datos);
    }

    /**
     * Actualiza un proveedor
     */
    public function actualizar($id, $datos)
    {
        return $this->actualizarPorId($id, $datos);
    }

    /**
     * Desactiva un proveedor
     */
    public function desactivar($id)
    {
        return $this->actualizarPorId($id, ['estado' => 0]);
    }
}