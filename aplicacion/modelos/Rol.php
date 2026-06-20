<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Rol
 * Gestiona la tabla 'rol'
 */
class Rol extends Modelo
{
    protected $tabla = 'rol';
    protected $llavePrimaria = 'id_rol';

    /**
     * Obtiene todos los roles activos
     */
    public function obtenerActivos()
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene un rol por su ID
     */
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE id_rol = :id";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id' => $id]);
        return $stmt->obtener();
    }

    /**
     * Obtiene todos los roles
     */
    public function obtenerTodos()
    {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY nombre ASC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }
}