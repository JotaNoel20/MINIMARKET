<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Producto
 * Gestiona la tabla 'producto'
 */
class Producto extends Modelo
{
    protected $tabla = 'producto';
    protected $llavePrimaria = 'id_producto';

    /**
     * Obtiene todos los productos con su categoría
     */
    public function obtenerTodosConCategoria()
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM {$this->tabla} p
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE p.estado = 1
                ORDER BY p.nombre ASC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene productos con stock disponible (para ventas)
     */
    public function obtenerConStock()
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM {$this->tabla} p
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE p.estado = 1 AND p.stock_actual > 0
                ORDER BY p.nombre ASC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Obtiene un producto por su ID con categoría
     */
    public function obtenerPorId($id)
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre, pro.nombre AS proveedor_nombre
                FROM {$this->tabla} p
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria
                LEFT JOIN proveedor pro ON p.id_proveedor = pro.id_proveedor
                WHERE p.id_producto = :id";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id' => $id]);
        return $stmt->obtener();
    }

    /**
     * Obtiene productos con stock bajo (menor o igual al mínimo)
     */
    public function obtenerProductosStockBajo()
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM {$this->tabla} p
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE p.estado = 1 AND p.stock_actual <= p.stock_minimo
                ORDER BY p.stock_actual ASC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Cuenta productos con stock bajo
     */
    public function contarStockBajo()
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM {$this->tabla} 
                WHERE estado = 1 AND stock_actual <= stock_minimo";
        $stmt = $this->bd->consultar($sql);
        $resultado = $stmt->obtener();
        return $resultado['total'] ?? 0;
    }

    /**
     * Cuenta productos activos
     */
    public function contarActivos()
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->tabla} WHERE estado = 1";
        $stmt = $this->bd->consultar($sql);
        $resultado = $stmt->obtener();
        return $resultado['total'] ?? 0;
    }

    /**
     * Obtiene el stock total de todos los productos
     */
    public function obtenerStockTotal()
    {
        $sql = "SELECT SUM(stock_actual) AS total FROM {$this->tabla} WHERE estado = 1";
        $stmt = $this->bd->consultar($sql);
        $resultado = $stmt->obtener();
        return $resultado['total'] ?? 0;
    }

    /**
     * Obtiene todos los productos (para compras)
     */
    public function obtenerTodos()
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM {$this->tabla} p
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE p.estado = 1
                ORDER BY p.nombre ASC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();
    }

    /**
     * Crea un nuevo producto
     */
    public function crear($datos)
    {
        return $this->insertar($datos);
    }

    /**
     * Actualiza un producto
     */
    public function actualizar($id, $datos)
    {
        return $this->actualizarPorId($id, $datos);
    }

    /**
     * Desactiva un producto (eliminación lógica)
     */
    public function desactivar($id)
    {
        return $this->actualizarPorId($id, ['estado' => 0]);
    }

    /**
     * Actualiza el stock de un producto
     */
    public function actualizarStock($id, $cantidad)
    {
        $sql = "UPDATE {$this->tabla} SET stock_actual = stock_actual + :cantidad 
                WHERE id_producto = :id";
        $stmt = $this->bd->preparar($sql);
        return $stmt->ejecutar(['cantidad' => $cantidad, 'id' => $id]);
    }

    /**
     * Verifica stock disponible para una cantidad
     */
    public function verificarStock($id, $cantidad)
    {
        $sql = "SELECT stock_actual FROM {$this->tabla} WHERE id_producto = :id AND estado = 1";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id' => $id]);
        $resultado = $stmt->obtener();
        return $resultado && $resultado['stock_actual'] >= $cantidad;
    }
}