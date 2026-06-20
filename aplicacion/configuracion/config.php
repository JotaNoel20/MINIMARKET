<?php
/**
 * CONFIGURACIÓN GENERAL DE LA APLICACIÓN
 * Todas las constantes y configuraciones del sistema
 */

// ============================================================
// CONFIGURACIÓN DE LA APLICACIÓN
// ============================================================

// Nombre de la aplicación
define('NOMBRE_APP', 'El Buen Precio');

// Versión
define('VERSION_APP', '2.0.0');

// Entorno (desarrollo, pruebas, produccion)
define('ENTORNO', 'desarrollo');

// URL base del sistema
define('URL_BASE', '/minimarket');

// URL pública
define('URL_PUBLICA', 'http://localhost/minimarket');

// ============================================================
// ZONA HORARIA
// ============================================================

date_default_timezone_set('America/La_Paz');

// ============================================================
// RUTAS DE CARPETAS
// ============================================================

define('RUTA_RAIZ', dirname(__DIR__, 2));
define('RUTA_APP', RUTA_RAIZ . '/aplicacion');
define('RUTA_PUBLICO', RUTA_RAIZ . '/publico');
define('RUTA_VIEWS', RUTA_APP . '/vistas');
define('RUTA_LOGS', RUTA_RAIZ . '/registros');

// ============================================================
// CONFIGURACIÓN DE SESIÓN
// ============================================================

// Nombre de la sesión
ini_set('session.name', 'MINIMARKET_SESSION');

// Duración de la sesión (30 minutos)
ini_set('session.gc_maxlifetime', 1800);

// Configuración de cookies de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en HTTPS
ini_set('session.cookie_samesite', 'Lax');

// ============================================================
// CONFIGURACIÓN DE ERRORES
// ============================================================

if (ENTORNO === 'desarrollo') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', RUTA_LOGS . '/error.log');
}

// ============================================================
// CONFIGURACIÓN DE SEGURIDAD
// ============================================================

// Clave secreta para CSRF y tokens
define('CLAVE_SECRETA', 'tu_clave_secreta_aqui_cambiala_por_una_segura');

// Tiempo de expiración del token CSRF (30 minutos)
define('CSRF_TIEMPO_EXPIRACION', 1800);