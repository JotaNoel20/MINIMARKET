<?php
namespace App\Middleware;

/**
 * Middleware de Empleado
 * Verifica que el usuario sea empleado o administrador (rol = 1 o 2)
 */
class Empleado
{
    /**
     * Ejecuta el middleware
     * @return bool True si es empleado/admin, False si no
     */
    public static function ejecutar()
    {
        // Primero verificar autenticación
        Autenticacion::ejecutar();

        // Verificar rol (1=Admin, 2=Empleado)
        if (!isset($_SESSION['id_rol']) || ($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 2)) {
            header('Location: ' . URL_BASE . '/panel');
            exit;
        }

        return true;
    }

    /**
     * Verifica si el usuario es empleado o admin (sin redirigir)
     * @return bool
     */
    public static function verificar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['id_rol']) && ($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 2);
    }

    /**
     * Verifica si es específicamente empleado (rol = 2)
     * @return bool
     */
    public static function esEmpleado()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2;
    }
}