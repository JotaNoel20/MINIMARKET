<?php
namespace App\Nucleo;

/**
 * Controlador Base
 * Todos los controladores heredan de esta clase
 */
class Controlador
{
    protected $bd;
    protected $sesion;
    protected $vista;

    public function __construct()
    {
        $this->bd = BaseDatos::obtenerInstancia();
        $this->sesion = Sesion::obtenerInstancia();
        $this->vista = new Vista();
    }

    /**
     * Renderiza una vista
     */
    protected function vista($ruta, $datos = [])
    {
        return $this->vista->renderizar($ruta, $datos);
    }

    /**
     * Redirige a una URL
     */
    protected function redirigir($url)
    {
        header('Location: ' . URL_BASE . $url);
        exit;
    }

    /**
     * Retorna una respuesta JSON
     */
    protected function json($datos)
    {
        header('Content-Type: application/json');
        echo json_encode($datos);
        exit;
    }
}