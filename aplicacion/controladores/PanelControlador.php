<?php
namespace App\Controladores;

use App\Nucleo\Controlador;
use App\Modelos\Venta;
use App\Modelos\Producto;
use App\Modelos\Usuario;

/**
 * Controlador del Panel Principal (Dashboard)
 * Muestra resumen de KPIs y estadísticas
 */
class PanelControlador extends Controlador
{
    /**
     * Muestra el panel principal
     */
    public function index()
    {
        // Verificar autenticación
        if (!$this->sesion->existe('id_usuario')) {
            $this->redirigir('/login');
        }

        $idRol = $this->sesion->get('id_rol');
        $nombre = $this->sesion->get('nombre');

        // Obtener KPIs según el rol
        if ($idRol == 1) {
            $datos = $this->obtenerKPIsAdmin();
        } else {
            $datos = $this->obtenerKPIsEmpleado();
        }

        $datos['nombre'] = $nombre;
        $datos['id_rol'] = $idRol;

        return $this->vista('panel/index', $datos);
    }

    private function obtenerKPIsAdmin()
    {
        $venta = new Venta();
        $producto = new Producto();
        $usuario = new Usuario();

        $ventasHoy = $venta->obtenerVentasDelDia();
        $totalStock = $producto->obtenerStockTotal();
        $stockBajo = $producto->contarStockBajo();
        $totalUsuarios = $usuario->contarActivos();

        return [
            'ventas_hoy' => $ventasHoy,
            'total_stock' => $totalStock,
            'stock_bajo' => $stockBajo,
            'total_usuarios' => $totalUsuarios
        ];
    }

    private function obtenerKPIsEmpleado()
    {
        $venta = new Venta();
        $idUsuario = $this->sesion->get('id_usuario');

        $ventasHoy = $venta->obtenerVentasDeUsuarioHoy($idUsuario);

        return [
            'ventas_hoy' => $ventasHoy,
            'nombre' => $this->sesion->get('nombre')
        ];
    }
}