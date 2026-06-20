<?php
namespace App\Nucleo;

/**
 * Gestor de Sesiones (Singleton)
 * Maneja todas las operaciones de sesión
 */
class Sesion
{
    private static $instancia = null;
    private $iniciada = false;

    private function __construct()
    {
        $this->iniciar();
    }

    /**
     * Obtiene la instancia única (Singleton)
     */
    public static function obtenerInstancia()
    {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    /**
     * Inicia la sesión
     */
    private function iniciar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            $this->iniciada = true;
        } elseif (session_status() === PHP_SESSION_ACTIVE) {
            $this->iniciada = true;
        }
    }

    /**
     * Verifica si la sesión está iniciada
     */
    public function estaIniciada()
    {
        return $this->iniciada;
    }

    /**
     * Establece un valor en la sesión
     */
    public function set($clave, $valor)
    {
        $_SESSION[$clave] = $valor;
    }

    /**
     * Obtiene un valor de la sesión
     */
    public function get($clave, $default = null)
    {
        return $_SESSION[$clave] ?? $default;
    }

    /**
     * Verifica si existe una clave en la sesión
     */
    public function existe($clave)
    {
        return isset($_SESSION[$clave]);
    }

    /**
     * Elimina una clave de la sesión
     */
    public function eliminar($clave)
    {
        if (isset($_SESSION[$clave])) {
            unset($_SESSION[$clave]);
        }
    }

    /**
     * Destruye toda la sesión
     */
    public function destruir()
    {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        $this->iniciada = false;
    }

    /**
     * Regenera el ID de sesión (seguridad)
     */
    public function regenerarId()
    {
        if ($this->iniciada) {
            session_regenerate_id(true);
        }
    }
}