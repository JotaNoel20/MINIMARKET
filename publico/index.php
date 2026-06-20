<?php
/**
 * Controlador Frontal - Punto de entrada de la aplicación
 */

// ============================================================
// 1. AUTOLOADER - Carga automática de clases
// ============================================================

spl_autoload_register(function($clase) {
    // Si la clase comienza con 'App\', la buscamos en aplicacion/
    if (strpos($clase, 'App\\') === 0) {
        // Quitar 'App\' del inicio
        $rutaRelativa = substr($clase, 4);
        // Reemplazar \ por /
        $rutaRelativa = str_replace('\\', '/', $rutaRelativa);
        // Construir ruta completa
        $archivo = __DIR__ . '/../aplicacion/' . $rutaRelativa . '.php';
        
        if (file_exists($archivo)) {
            require_once $archivo;
            return true;
        }
    }
    return false;
});

// ============================================================
// 2. CARGAR CONFIGURACIÓN
// ============================================================

require_once __DIR__ . '/../aplicacion/configuracion/config.php';
require_once __DIR__ . '/../aplicacion/configuracion/conexion.php';

// ============================================================
// 3. INICIAR SESIÓN
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
// 4. CARGAR AYUDANTES (opcional pero recomendado)
// ============================================================

if (file_exists(__DIR__ . '/../aplicacion/ayudantes/funciones.php')) {
    require_once __DIR__ . '/../aplicacion/ayudantes/funciones.php';
}
if (file_exists(__DIR__ . '/../aplicacion/ayudantes/formateadores.php')) {
    require_once __DIR__ . '/../aplicacion/ayudantes/formateadores.php';
}

// ============================================================
// 5. CARGAR RUTAS
// ============================================================

$enrutador = new App\Nucleo\Enrutador();
require_once __DIR__ . '/../aplicacion/configuracion/rutas.php';

// ============================================================
// 6. DESPACHAR RUTA
// ============================================================

$url = isset($_GET['url']) ? $_GET['url'] : '/';
$enrutador->despachar($url);