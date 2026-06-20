<?php
namespace App\Middleware;

/**
 * Middleware de Autenticación
 * Verifica que el usuario haya iniciado sesión
 */
class Autenticacion
{
    /**
     * Ejecuta el middleware
     * @return bool True si está autenticado, False si no
     */
    public static function ejecutar()
    {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si el usuario está logueado
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . URL_BASE . '/login');
            exit;
        }

        return true;
    }

    /**
     * Verifica si el usuario está autenticado (sin redirigir)
     * @return bool
     */
    public static function verificar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['id_usuario']);
    }

    /**
     * Obtiene el ID del usuario actual
     * @return int|null
     */
    public static function obtenerUsuarioId()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['id_usuario'] ?? null;
    }

    /**
     * Obtiene el rol del usuario actual
     * @return int|null
     */
    public static function obtenerRol()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['id_rol'] ?? null;
    }
}