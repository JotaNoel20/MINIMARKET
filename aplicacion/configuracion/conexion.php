<?php
/**
 * CONEXIÓN A LA BASE DE DATOS
 * Configuración y conexión PDO
 */

// ============================================================
// DATOS DE CONEXIÓN
// ============================================================

$host     = 'localhost';      // Servidor
$puerto   = '3307';           // Puerto (3306 por defecto)
$base_datos = 'minimarket';   // Nombre de la base de datos
$usuario  = 'root';           // Usuario de la BD
$contrasena = 'admin';        // Contraseña de la BD
$charset  = 'utf8mb4';        // Juego de caracteres

// ============================================================
// CADENA DE CONEXIÓN (DSN)
// ============================================================

$dsn = "mysql:host=$host;port=$puerto;dbname=$base_datos;charset=$charset";

// ============================================================
// OPCIONES DE PDO
// ============================================================

$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch por defecto: arreglo asociativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usar preparaciones nativas
    PDO::ATTR_PERSISTENT         => false,                  // No usar conexiones persistentes
    //PDO::ATTR_STATEMENT_CLASS    => ['App\Nucleo\MiPDOStatement', []]
];

// ============================================================
// CREAR CONEXIÓN
// ============================================================

try {
    require_once __DIR__ . '/../nucleo/MiPDOStatement.php';
    $pdo = new PDO($dsn, $usuario, $contrasena, $opciones);
} catch (PDOException $e) {
    // En desarrollo mostrar error, en producción loguear
    if (ENTORNO === 'desarrollo') {
        die('Error de conexión a la base de datos: ' . $e->getMessage());
    } else {
        // Registrar error en log
        error_log('Error de conexión a la BD: ' . $e->getMessage());
        die('Error de conexión a la base de datos. Por favor, intenta más tarde.');
    }
}

// ============================================================
// FUNCIÓN PARA OBTENER LA CONEXIÓN (SINGLETON)
// ============================================================

/**
 * Obtiene la instancia de la conexión PDO
 * @return PDO
 */
function obtenerConexion() {
    global $pdo;
    return $pdo;
}