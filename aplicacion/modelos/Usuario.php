<?php
namespace App\Modelos;

use App\Nucleo\Modelo;

/**
 * Modelo de Usuario
 * Gestiona la tabla 'usuario'
 */
class Usuario extends Modelo
{
    protected $tabla = 'usuario';
    protected $llavePrimaria = 'id_usuario';

    /**
     * Busca un usuario por su nombre de usuario
     */
    public function buscarPorUsuario($usuario)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE username = :usuario AND estado = 1 LIMIT 1";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['usuario' => $usuario]);  // ✅ ejecutar()
        return $stmt->obtener();  // ✅ obtener()
    }

    /**
     * Busca un usuario por su ID
     */
    public function obtenerPorId($id)
    {
        $sql = "SELECT u.*, r.nombre AS rol_nombre 
                FROM {$this->tabla} u
                INNER JOIN rol r ON u.id_rol = r.id_rol
                WHERE u.id_usuario = :id";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['id' => $id]);  // ✅ ejecutar()
        return $stmt->obtener();  // ✅ obtener()
    }

    /**
     * Obtiene todos los usuarios activos
     */
    public function obtenerTodos()
    {
        $sql = "SELECT u.*, r.nombre AS rol_nombre 
                FROM {$this->tabla} u
                INNER JOIN rol r ON u.id_rol = r.id_rol
                WHERE u.estado = 1
                ORDER BY u.nombre ASC";
        $stmt = $this->bd->consultar($sql);
        return $stmt->obtenerTodos();  // ✅ obtenerTodos()
    }

    /**
     * Cuenta usuarios activos
     */
    public function contarActivos()
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->tabla} WHERE estado = 1";
        $stmt = $this->bd->consultar($sql);
        $resultado = $stmt->obtener();  // ✅ obtener()
        return $resultado['total'] ?? 0;
    }

    /**
     * Verifica si un username ya existe
     */
    public function existeUsername($username)
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->tabla} WHERE username = :username";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['username' => $username]);  // ✅ ejecutar()
        $resultado = $stmt->obtener();  // ✅ obtener()
        return $resultado['total'] > 0;
    }

    /**
     * Verifica si un username ya existe (exceptuando un ID)
     */
    public function existeUsernameExcepto($username, $id)
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->tabla} 
                WHERE username = :username AND id_usuario != :id";
        $stmt = $this->bd->preparar($sql);
        $stmt->ejecutar(['username' => $username, 'id' => $id]);  // ✅ ejecutar()
        $resultado = $stmt->obtener();  // ✅ obtener()
        return $resultado['total'] > 0;
    }

    /**
     * Crea un nuevo usuario
     */
    public function crear($datos)
    {
        return $this->insertar($datos);
    }

    /**
     * Actualiza un usuario
     */
    public function actualizar($id, $datos)
    {
        return $this->actualizarPorId($id, $datos);
    }

    /**
     * Desactiva un usuario (eliminación lógica)
     */
    public function desactivar($id)
    {
        return $this->actualizarPorId($id, ['estado' => 0]);
    }
}