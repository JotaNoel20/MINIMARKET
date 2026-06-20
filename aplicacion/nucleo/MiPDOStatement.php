<?php
namespace App\Nucleo;

/**
 * Clase de Sentencia PDO Personalizada
 * Envuelve un PDOStatement nativo y agrega métodos en español
 */
class MiPDOStatement
{
    private $stmt;

    /**
     * Constructor - recibe un PDOStatement nativo
     */
    public function __construct(\PDOStatement $stmt)
    {
        $this->stmt = $stmt;
    }

    /**
     * Alias de execute() en español
     */
    public function ejecutar($parametros = null)
    {
        return $this->stmt->execute($parametros);
    }

    /**
     * Alias de fetchAll() en español
     */
    public function obtenerTodos()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Alias de fetch() en español
     */
    public function obtener()
    {
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Alias de fetchColumn() en español
     */
    public function obtenerColumna($columna = 0)
    {
        return $this->stmt->fetchColumn($columna);
    }

    /**
     * Alias de rowCount() en español
     */
    public function contarFilas()
    {
        return $this->stmt->rowCount();
    }
}