<?php
namespace App\Nucleo;

/**
 * Clase de Seguridad
 * Maneja CSRF, hash, sanitización y otras funciones de seguridad
 */
class Seguridad
{
    /**
     * Genera un token CSRF
     */
    public static function generarTokenCSRF()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Valida un token CSRF
     */
    public static function validarTokenCSRF($token)
    {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Regenera el token CSRF
     */
    public static function regenerarTokenCSRF()
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }

    /**
     * Sanitiza una cadena para prevenir XSS
     */
    public static function sanitizar($valor)
    {
        return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitiza un array completo
     */
    public static function sanitizarArray($array)
    {
        if (is_array($array)) {
            return array_map([self::class, 'sanitizar'], $array);
        }
        return self::sanitizar($array);
    }

    /**
     * Hashea una contraseña
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verifica una contraseña contra su hash
     */
    public static function verificarPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Genera una cadena aleatoria
     */
    public static function generarCadenaAleatoria($longitud = 32)
    {
        return bin2hex(random_bytes($longitud / 2));
    }

    /**
     * Escapa datos para prevenir inyección SQL (extra)
     */
    public static function escaparSQL($valor)
    {
        global $pdo;
        return $pdo->quote($valor);
    }

    /**
     * Valida que un dato sea un ID entero positivo
     */
    public static function validarId($id)
    {
        return filter_var($id, FILTER_VALIDATE_INT) && $id > 0;
    }

    /**
     * Sanitiza un email
     */
    public static function sanitizarEmail($email)
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Valida un email
     */
    public static function validarEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Sanitiza una URL
     */
    public static function sanitizarUrl($url)
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * Previene ataques de fuerza bruta (ejemplo: limitar intentos)
     */
    public static function limitarIntentos($clave, $limite = 5, $tiempo = 300)
    {
        if (!isset($_SESSION['intentos_' . $clave])) {
            $_SESSION['intentos_' . $clave] = 0;
            $_SESSION['intentos_tiempo_' . $clave] = time();
        }

        // Revisar si expiró el tiempo
        if (time() - $_SESSION['intentos_tiempo_' . $clave] > $tiempo) {
            $_SESSION['intentos_' . $clave] = 0;
            $_SESSION['intentos_tiempo_' . $clave] = time();
        }

        if ($_SESSION['intentos_' . $clave] >= $limite) {
            return false;
        }

        $_SESSION['intentos_' . $clave]++;
        return true;
    }

    /**
     * Reinicia los intentos fallidos
     */
    public static function reiniciarIntentos($clave)
    {
        unset($_SESSION['intentos_' . $clave]);
        unset($_SESSION['intentos_tiempo_' . $clave]);
    }
}