<?php
namespace App\Middleware;

/**
 * Middleware de Administrador
 * Verifica que el usuario sea administrador (rol = 1)
 */
class Administrador
{
    /**
     * Ejecuta el middleware
     * @return bool True si es administrador, False si no
     */
    public static function ejecutar()
    {
        // Primero verificar autenticación
        Autenticacion::ejecutar();

        // Verificar rol
        if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
            header('Location: ' . URL_BASE . '/panel');
            exit;
        }

        return true;
    }

    /**
     * Verifica si el usuario es administrador (sin redirigir)
     * @return bool
     */
    public static function verificar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1;
    }
}