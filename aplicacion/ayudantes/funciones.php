<?php
/**
 * FUNCIONES GLOBALES
 * Funciones auxiliares disponibles en toda la aplicación
 */

/**
 * Debug: Muestra una variable con formato
 */
function dd($variable)
{
    echo '<pre style="background:#f4f4f4;padding:15px;border-radius:5px;font-size:14px;line-height:1.5;max-height:500px;overflow:auto;">';
    var_dump($variable);
    echo '</pre>';
    die();
}

/**
 * Debug: Muestra una variable con formato (sin detener)
 */
function d($variable)
{
    echo '<pre style="background:#f4f4f4;padding:15px;border-radius:5px;font-size:14px;line-height:1.5;max-height:500px;overflow:auto;">';
    var_dump($variable);
    echo '</pre>';
}

/**
 * Redirige a una URL
 */
function redirigir($url)
{
    header('Location: ' . URL_BASE . $url);
    exit;
}

/**
 * Redirige con mensaje de éxito
 */
function redirigirConExito($url, $mensaje)
{
    $_SESSION['mensaje_exito'] = $mensaje;
    redirigir($url);
}

/**
 * Redirige con mensaje de error
 */
function redirigirConError($url, $mensaje)
{
    $_SESSION['mensaje_error'] = $mensaje;
    redirigir($url);
}

/**
 * Obtiene un mensaje de sesión y lo elimina
 */
function obtenerMensaje()
{
    $mensaje = '';
    if (isset($_SESSION['mensaje_exito'])) {
        $mensaje = ['tipo' => 'exito', 'texto' => $_SESSION['mensaje_exito']];
        unset($_SESSION['mensaje_exito']);
    } elseif (isset($_SESSION['mensaje_error'])) {
        $mensaje = ['tipo' => 'error', 'texto' => $_SESSION['mensaje_error']];
        unset($_SESSION['mensaje_error']);
    }
    return $mensaje;
}

/**
 * Verifica si el usuario es administrador
 */
function esAdministrador()
{
    return isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1;
}

/**
 * Verifica si el usuario es empleado
 */
function esEmpleado()
{
    return isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2;
}

/**
 * Verifica si el usuario está logueado
 */
function estaLogueado()
{
    return isset($_SESSION['id_usuario']);
}

/**
 * Obtiene el nombre del usuario actual
 */
function obtenerNombreUsuario()
{
    return $_SESSION['nombre'] ?? 'Invitado';
}

/**
 * Obtiene el ID del usuario actual
 */
function obtenerIdUsuario()
{
    return $_SESSION['id_usuario'] ?? null;
}

/**
 * Genera una URL amigable
 */
function urlAmigable($texto)
{
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9]/', '-', $texto);
    $texto = preg_replace('/-+/', '-', $texto);
    return trim($texto, '-');
}

/**
 * Limita un texto a cierta cantidad de caracteres
 */
function limitarTexto($texto, $limite = 100, $sufijo = '...')
{
    if (strlen($texto) <= $limite) {
        return $texto;
    }
    return substr($texto, 0, $limite) . $sufijo;
}

/**
 * Obtiene la IP del usuario
 */
function obtenerIP()
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    
    return $ip;
}

/**
 * Obtiene el User Agent del usuario
 */
function obtenerUserAgent()
{
    return $_SERVER['HTTP_USER_AGENT'] ?? '';
}

/**
 * Verifica si la solicitud es AJAX
 */
function esAjax()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * Verifica si la solicitud es POST
 */
function esPost()
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Verifica si la solicitud es GET
 */
function esGet()
{
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Sanitiza un array de entrada (POST/GET)
 */
function sanitizarInput($datos)
{
    $limpio = [];
    foreach ($datos as $key => $value) {
        if (is_array($value)) {
            $limpio[$key] = sanitizarInput($value);
        } else {
            $limpio[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
        }
    }
    return $limpio;
}

/**
 * Genera un slug para URLs
 */
function generarSlug($texto)
{
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9-]/', '-', $texto);
    $texto = preg_replace('/-+/', '-', $texto);
    return trim($texto, '-');
}

/**
 * Obtiene la URL actual
 */
function urlActual()
{
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $protocolo . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Verifica si una cadena comienza con otra
 */
function empiezaCon($cadena, $inicio)
{
    return strpos($cadena, $inicio) === 0;
}

/**
 * Verifica si una cadena termina con otra
 */
function terminaCon($cadena, $final)
{
    return substr($cadena, -strlen($final)) === $final;
}