<?php
namespace App\Nucleo;

/**
 * Modelo Base
 * Todos los modelos heredan de esta clase
 */
class Modelo
{
    protected $tabla;
    protected $llavePrimaria = 'id';
    protected $bd;

    public function __construct()
    {
        $this->bd = BaseDatos::obtenerInstancia();
    }

    /**
     * Obtiene todos los registros
     */
    public function obtenerTodos()
    {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY {$this->llavePrimaria} DESC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene un registro por su ID
     */
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE {$this->llavePrimaria} = :id LIMIT 1";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id' => $id]);
        return $stmt->obtener();
    }

    /**
     * Inserta un nuevo registro
     */
    public function insertar($datos)
    {
        $columnas = implode(', ', array_keys($datos));
        $placeholders = ':' . implode(', :', array_keys($datos));
        
        $sql = "INSERT INTO {$this->tabla} ($columnas) VALUES ($placeholders)";
        $stmt = $this->bd->preparar($sql);
        
        if ($stmt->ejecutar($datos)) {
            return $this->bd->obtenerUltimoId();
        }
        return false;
    }

    /**
     * Actualiza un registro
     */
    public function actualizarPorId($id, $datos)
    {
        $set = [];
        foreach ($datos as $key => $value) {
            $set[] = "$key = :$key";
        }
        $set = implode(', ', $set);
        
        $sql = "UPDATE {$this->tabla} SET $set WHERE {$this->llavePrimaria} = :id";
        $datos['id'] = $id;
        $stmt = $this->bd->preparar($sql);
        return $stmt->ejecutar($datos);
    }

    /**
     * Elimina un registro
     */
    public function eliminarPorId($id)
    {
        $sql = "DELETE FROM {$this->tabla} WHERE {$this->llavePrimaria} = :id";
        $stmt = $this->bd->preparar($sql);
        return $stmt->ejecutar(['id' => $id]);
    }

    /**
     * Cuenta registros
     */
    public function contar($condicion = '1')
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE $condicion";
        $stmt = $this->bd->consultar($sql);
        $resultado = $stmt->obtener();
        return $resultado['total'] ?? 0;
    }
}