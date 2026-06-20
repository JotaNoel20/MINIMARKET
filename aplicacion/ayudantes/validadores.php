<?php
/**
 * VALIDADORES PERSONALIZADOS
 * Validadores específicos para el sistema
 */

/**
 * Valida que un campo sea un número entero positivo
 */
function validarId($valor)
{
    return filter_var($valor, FILTER_VALIDATE_INT) && $valor > 0;
}

/**
 * Valida que un campo sea un número decimal positivo
 */
function validarPrecio($valor)
{
    return filter_var($valor, FILTER_VALIDATE_FLOAT) && $valor >= 0;
}

/**
 * Valida que un campo sea un NIT válido
 */
function validarNit($nit)
{
    // Limpiar caracteres no numéricos
    $nit = preg_replace('/[^0-9]/', '', $nit);
    
    // Verificar longitud (7-8 dígitos para Bolivia)
    return strlen($nit) >= 7 && strlen($nit) <= 8;
}

/**
 * Valida que un campo sea un teléfono válido (Bolivia)
 */
function validarTelefono($telefono)
{
    $telefono = preg_replace('/[^0-9]/', '', $telefono);
    return strlen($telefono) === 7 || strlen($telefono) === 8;
}

/**
 * Valida que un campo tenga solo letras y espacios
 */
function validarSoloLetras($texto)
{
    return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $texto);
}

/**
 * Valida que un campo sea una fecha en formato YYYY-MM-DD
 */
function validarFecha($fecha)
{
    $partes = explode('-', $fecha);
    if (count($partes) !== 3) {
        return false;
    }
    return checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0]);
}

/**
 * Valida que un campo sea una fecha en formato DD/MM/YYYY
 */
function validarFechaDMY($fecha)
{
    $partes = explode('/', $fecha);
    if (count($partes) !== 3) {
        return false;
    }
    return checkdate((int)$partes[1], (int)$partes[0], (int)$partes[2]);
}

/**
 * Valida que un campo sea una hora en formato HH:MM
 */
function validarHora($hora)
{
    return preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $hora);
}

/**
 * Valida que un campo no tenga caracteres especiales peligrosos
 */
function validarTexto($texto)
{
    return !preg_match('/[<>"\']/', $texto);
}

/**
 * Valida que un campo sea un username válido
 */
function validarUsername($username)
{
    return preg_match('/^[a-zA-Z0-9_-]{3,50}$/', $username);
}

/**
 * Valida que un campo tenga una contraseña fuerte
 */
function validarPassword($password)
{
    // Al menos 6 caracteres, una mayúscula y un número
    return strlen($password) >= 6 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[0-9]/', $password);
}

/**
 * Valida que un campo sea un código de barras válido
 */
function validarCodigoBarras($codigo)
{
    return preg_match('/^[0-9]{8,13}$/', $codigo);
}

/**
 * Valida que un campo sea una URL válida
 */
function validarUrl($url)
{
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Valida que un campo sea un nombre de archivo válido
 */
function validarNombreArchivo($nombre)
{
    return preg_match('/^[a-zA-Z0-9_\-\.]+$/', $nombre);
}

/**
 * Valida que un campo esté dentro de un rango de números
 */
function validarRango($valor, $min, $max)
{
    return is_numeric($valor) && $valor >= $min && $valor <= $max;
}

/**
 * Valida que un campo sea una opción válida de un select
 */
function validarOpcion($valor, $opcionesPermitidas)
{
    return in_array($valor, $opcionesPermitidas);
}

/**
 * Valida que un campo contenga solo dígitos
 */
function validarSoloNumeros($valor)
{
    return preg_match('/^[0-9]+$/', $valor);
}

/**
 * Valida que un campo contenga solo letras y números
 */
function validarAlfanumerico($valor)
{
    return preg_match('/^[a-zA-Z0-9]+$/', $valor);
}

/**
 * Valida que un campo sea un estado válido (activo/inactivo)
 */
function validarEstado($estado)
{
    return in_array($estado, [0, 1]);
}

/**
 * Valida que un campo sea un rol válido
 */
function validarRol($rol)
{
    return in_array($rol, [1, 2]);
}