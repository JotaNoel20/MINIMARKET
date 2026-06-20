<?php
namespace App\Nucleo;

/**
 * Gestor de Base de Datos (Singleton)
 * Maneja la conexión PDO y operaciones
 */
class BaseDatos
{
    private static $instancia = null;
    private $pdo;
    private $transaccionActiva = false;

    private function __construct()
    {
        $this->conectar();
    }

    /**
     * Obtiene la instancia única (Singleton)
     */
    public static function obtenerInstancia()
    {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    /**
     * Establece la conexión a la base de datos
     */
    private function conectar()
    {
        global $dsn, $usuario, $contrasena, $opciones;
        $this->pdo = new \PDO($dsn, $usuario, $contrasena, $opciones);
    }

    /**
     * Obtiene la conexión PDO
     */
    public function getConexion()
    {
        return $this->pdo;
    }

    /**
     * Prepara una consulta SQL y devuelve MiPDOStatement
     */
    public function preparar($sql)
    {
        $stmt = $this->pdo->prepare($sql);
        return new MiPDOStatement($stmt);
    }

    /**
     * Ejecuta una consulta SQL directa y devuelve MiPDOStatement
     */
    public function consultar($sql)
    {
        $stmt = $this->pdo->query($sql);
        return new MiPDOStatement($stmt);
    }

    /**
     * Obtiene el último ID insertado
     */
    public function obtenerUltimoId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Inicia una transacción
     */
    public function iniciarTransaccion()
    {
        if (!$this->transaccionActiva) {
            $this->pdo->beginTransaction();
            $this->transaccionActiva = true;
        }
    }

    /**
     * Confirma una transacción
     */
    public function confirmarTransaccion()
    {
        if ($this->transaccionActiva) {
            $this->pdo->commit();
            $this->transaccionActiva = false;
        }
    }

    /**
     * Revierte una transacción
     */
    public function revertirTransaccion()
    {
        if ($this->transaccionActiva) {
            $this->pdo->rollBack();
            $this->transaccionActiva = false;
        }
    }

    /**
     * Escapa una cadena para evitar inyección SQL (seguridad extra)
     */
    public function escapar($valor)
    {
        return $this->pdo->quote($valor);
    }
}