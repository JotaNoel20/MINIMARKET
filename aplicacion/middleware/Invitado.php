<?php
namespace App\Middleware;

/**
 * Middleware de Invitado
 * Verifica que el usuario NO esté logueado
 * (Para páginas como login, donde no debe entrar si ya está logueado)
 */
class Invitado
{
    /**
     * Ejecuta el middleware
     * @return bool True si es invitado, False si está logueado
     */
    public static function ejecutar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si ya está logueado, redirigir al panel
        if (isset($_SESSION['id_usuario'])) {
            header('Location: ' . URL_BASE . '/panel');
            exit;
        }

        return true;
    }

    /**
     * Verifica si el usuario es invitado (sin redirigir)
     * @return bool
     */
    public static function verificar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !isset($_SESSION['id_usuario']);
    }
}