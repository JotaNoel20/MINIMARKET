<?php
/**
 * FORMATEADORES
 * Funciones para formatear datos (moneda, fechas, números)
 */

/**
 * Formatea un número como moneda (Bolivianos)
 */
function formatearMoneda($monto, $mostrarSimbolo = true)
{
    $formateado = number_format($monto, 2, ',', '.');
    return $mostrarSimbolo ? 'Bs. ' . $formateado : $formateado;
}

/**
 * Formatea un número con separadores de miles
 */
function formatearNumero($numero, $decimales = 0)
{
    return number_format($numero, $decimales, ',', '.');
}

/**
 * Formatea una fecha a formato español
 */
function formatearFecha($fecha, $formato = 'd/m/Y')
{
    if (empty($fecha) || $fecha === '0000-00-00') {
        return 'N/A';
    }
    
    $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
    return date($formato, $timestamp);
}

/**
 * Formatea una fecha y hora a formato español
 */
function formatearFechaHora($fecha)
{
    return formatearFecha($fecha, 'd/m/Y H:i');
}

/**
 * Formatea una fecha a formato legible (ej: "Hoy, 15 de mayo")
 */
function formatearFechaLegible($fecha)
{
    $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
    $dias = [
        0 => 'Domingo', 1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
        4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado'
    ];
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    
    $diaSemana = $dias[date('w', $timestamp)];
    $dia = date('d', $timestamp);
    $mes = $meses[(int)date('m', $timestamp)];
    $anio = date('Y', $timestamp);
    
    return "$diaSemana, $dia de $mes de $anio";
}

/**
 * Obtiene la diferencia de tiempo en formato legible
 */
function tiempoRelativo($fecha)
{
    $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
    $diferencia = time() - $timestamp;
    
    if ($diferencia < 60) {
        return 'Hace ' . $diferencia . ' segundos';
    }
    
    $minutos = floor($diferencia / 60);
    if ($minutos < 60) {
        return 'Hace ' . $minutos . ' ' . ($minutos == 1 ? 'minuto' : 'minutos');
    }
    
    $horas = floor($minutos / 60);
    if ($horas < 24) {
        return 'Hace ' . $horas . ' ' . ($horas == 1 ? 'hora' : 'horas');
    }
    
    $dias = floor($horas / 24);
    if ($dias < 7) {
        return 'Hace ' . $dias . ' ' . ($dias == 1 ? 'día' : 'días');
    }
    
    $semanas = floor($dias / 7);
    if ($semanas < 4) {
        return 'Hace ' . $semanas . ' ' . ($semanas == 1 ? 'semana' : 'semanas');
    }
    
    return formatearFecha($fecha);
}

/**
 * Formatea un número de teléfono
 */
function formatearTelefono($telefono)
{
    if (empty($telefono)) {
        return '';
    }
    
    // Limpiar caracteres no numéricos
    $telefono = preg_replace('/[^0-9]/', '', $telefono);
    
    // Si tiene 8 dígitos: 76543210 → 7654-3210
    if (strlen($telefono) === 8) {
        return substr($telefono, 0, 4) . '-' . substr($telefono, 4);
    }
    
    return $telefono;
}

/**
 * Formatea un número de NIT
 */
function formatearNit($nit)
{
    if (empty($nit)) {
        return '';
    }
    
    $nit = preg_replace('/[^0-9]/', '', $nit);
    
    // Si tiene entre 7 y 8 dígitos: 12345678 → 1234567-8
    if (strlen($nit) >= 7 && strlen($nit) <= 8) {
        return substr($nit, 0, -1) . '-' . substr($nit, -1);
    }
    
    return $nit;
}

/**
 * Convierte un número a letras (Bolivianos)
 */
function numeroALetras($numero)
{
    $unidades = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
    $decenas = ['', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
    $centenas = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];
    
    // Función recursiva para convertir números pequeños
    $convertirTres = function($num) use ($unidades, $decenas, $centenas) {
        $num = (int)$num;
        if ($num == 0) return '';
        if ($num == 100) return 'cien';
        
        $centena = floor($num / 100);
        $resto = $num % 100;
        $decena = floor($resto / 10);
        $unidad = $resto % 10;
        
        $resultado = '';
        if ($centena > 0) {
            $resultado .= $centenas[$centena] . ' ';
        }
        if ($decena > 0) {
            if ($decena == 1 && $unidad > 0) {
                $especiales = ['', 'once', 'doce', 'trece', 'catorce', 'quince'];
                $resultado .= $especiales[$unidad] . ' ';
            } else {
                $resultado .= $decenas[$decena] . ' ';
                if ($unidad > 0) {
                    $resultado .= ($decena > 2 ? 'y ' : '') . $unidades[$unidad] . ' ';
                }
            }
        } elseif ($unidad > 0) {
            $resultado .= $unidades[$unidad] . ' ';
        }
        return trim($resultado);
    };
    
    $partes = explode('.', number_format($numero, 2, '.', ''));
    $entero = (int)$partes[0];
    $decimal = isset($partes[1]) ? (int)$partes[1] : 0;
    
    if ($entero == 0) {
        $resultado = 'cero';
    } else {
        $millones = floor($entero / 1000000);
        $miles = floor(($entero % 1000000) / 1000);
        $resto = $entero % 1000;
        
        $resultado = '';
        if ($millones > 0) {
            $resultado .= ($millones == 1 ? 'un millón' : $convertirTres($millones) . ' millones') . ' ';
        }
        if ($miles > 0) {
            $resultado .= ($miles == 1 ? 'mil' : $convertirTres($miles) . ' mil') . ' ';
        }
        if ($resto > 0) {
            $resultado .= $convertirTres($resto);
        }
    }
    
    $resultado = trim($resultado) . ' ' . $decimal . '/100 bolivianos';
    return ucfirst($resultado);
}

/**
 * Formatea un porcentaje
 */
function formatearPorcentaje($valor, $decimales = 0)
{
    return number_format($valor, $decimales, ',', '.') . '%';
}

/**
 * Formatea bytes a formato legible
 */
function formatearBytes($bytes, $decimales = 2)
{
    $unidades = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($unidades) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return number_format($bytes, $decimales, ',', '.') . ' ' . $unidades[$i];
}

/**
 * Obtiene el nombre del mes en español
 */
function nombreMes($numero)
{
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    return $meses[$numero] ?? '';
}

/**
 * Obtiene el nombre del día en español
 */
function nombreDia($numero)
{
    $dias = [
        0 => 'Domingo', 1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
        4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado'
    ];
    return $dias[$numero] ?? '';
}