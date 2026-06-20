<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Categoría
 * Gestiona la tabla 'categoria'
 */
class Categoria extends Modelo
{
    protected $tabla = 'categoria';
    protected $llavePrimaria = 'id_categoria';

    /**
     * Obtiene todas las categorías activas
     */
    public function obtenerActivas()
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene todas las categorías
     */
    public function obtenerTodas()
    {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY nombre ASC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene una categoría por su ID
     */
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE id_categoria = :id";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id' => $id]);
        return $stmt->obtener();
    }

    /**
     * Crea una nueva categoría
     */
    public function crear($datos)
    {
        return $this->insertar($datos);
    }

    /**
     * Actualiza una categoría
     */
    public function actualizar($id, $datos)
    {
        return $this->actualizarPorId($id, $datos);
    }

    /**
     * Desactiva una categoría
     */
    public function desactivar($id)
    {
        return $this->actualizarPorId($id, ['estado' => 0]);
    }
}