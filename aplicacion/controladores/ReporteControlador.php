<?php
namespace App\Controladores;

use App\Nucleo\Controlador;
use App\Modelos\Venta;
use App\Modelos\Producto;
use App\Modelos\Compra;
use App\Modelos\Caja;

/**
 * Controlador de Reportes
 * Generación de reportes y estadísticas
 */
class ReporteControlador extends Controlador
{
    public function index()
    {
        $this->verificarAdmin();

        $venta = new Venta();
        $producto = new Producto();

        $datos = [
            'total_ventas' => $venta->obtenerTotalVentas(),
            'total_recaudado' => $venta->obtenerTotalRecaudado(),
            'total_productos' => $producto->contarActivos(),
            'stock_bajo' => $producto->contarStockBajo(),
            'ventas_hoy' => $venta->obtenerVentasDelDia(),
            'ventas_semana' => $venta->obtenerVentasSemana(),
            'ventas_mes' => $venta->obtenerVentasMes(),
            'productos_top' => $venta->obtenerProductosMasVendidos(10),
            'ventas_por_vendedor' => $venta->obtenerVentasPorVendedor()
        ];

        return $this->vista('reportes/index', $datos);
    }

    public function ventas()
    {
        $this->verificarAdmin();

        $venta = new Venta();
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

        $datos = [
            'ventas' => $venta->obtenerPorRangoFechas($fechaInicio, $fechaFin),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'total' => $venta->obtenerTotalPorRango($fechaInicio, $fechaFin)
        ];

        return $this->vista('reportes/ventas', $datos);
    }

    public function inventario()
    {
        $this->verificarAdmin();

        $producto = new Producto();
        $datos = [
            'productos' => $producto->obtenerTodosConCategoria(),
            'stock_bajo' => $producto->obtenerProductosStockBajo(),
            'total_stock' => $producto->obtenerStockTotal()
        ];

        return $this->vista('reportes/inventario', $datos);
    }

    public function caja()
    {
        $this->verificarAdmin();

        $caja = new Caja();
        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        $datos = [
            'movimientos' => $caja->obtenerMovimientosPorFecha($fecha),
            'fecha' => $fecha,
            'total_ingresos' => $caja->obtenerTotalIngresosFecha($fecha),
            'total_egresos' => $caja->obtenerTotalEgresosFecha($fecha)
        ];

        return $this->vista('reportes/caja', $datos);
    }

    public function ganancias()
    {
        $this->verificarAdmin();

        $venta = new Venta();
        $compra = new Compra();

        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

        $datos = [
            'ventas' => $venta->obtenerTotalPorRango($fechaInicio, $fechaFin),
            'compras' => $compra->obtenerTotalPorRango($fechaInicio, $fechaFin),
            'ganancia' => 0, // Se calcula en la vista
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        $datos['ganancia'] = $datos['ventas'] - $datos['compras'];

        return $this->vista('reportes/ganancias', $datos);
    }

    private function verificarAdmin()
    {
        if (!$this->sesion->existe('id_usuario')) {
            $this->redirigir('/login');
        }

        if ($this->sesion->get('id_rol') != 1) {
            $this->redirigir('/panel');
        }
    }
}