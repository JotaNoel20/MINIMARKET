<?php
namespace App\Controladores;

use App\Nucleo\Controlador;
use App\Modelos\Venta;
use App\Modelos\Producto;
use App\Modelos\Pago;
use App\Modelos\Caja;
use App\Modelos\MovimientoCaja;
use App\Nucleo\Seguridad;
use App\Nucleo\Validacion;

/**
 * Controlador de Ventas
 * Punto de venta, historial, procesamiento
 */
class VentaControlador extends Controlador
{
    /**
     * Punto de venta (POS)
     */
    public function nueva()
    {
        $this->verificarEmpleado();

        $producto = new Producto();
        $productos = $producto->obtenerConStock();

        // Verificar si hay caja abierta
        $caja = new Caja();
        $cajaAbierta = $caja->obtenerCajaAbierta($this->sesion->get('id_usuario'));

        return $this->vista('ventas/nueva', [
            'productos' => $productos,
            'caja_abierta' => $cajaAbierta,
            'csrf_token' => Seguridad::generarTokenCSRF()
        ]);
    }

    /**
     * Procesa una venta
     */
    public function procesar()
    {
        $this->verificarEmpleado();

        // Validar token CSRF
        if (!Seguridad::validarTokenCSRF($_POST['csrf_token'] ?? '')) {
            $_SESSION['mensaje_error'] = 'Token CSRF inválido';
            $this->redirigir('/ventas/nueva');
        }

        // Validar que haya productos
        if (empty($_POST['json_detalles'])) {
            $_SESSION['mensaje_error'] = 'No hay productos en el carrito';
            $this->redirigir('/ventas/nueva');
        }

        $carrito = json_decode($_POST['json_detalles'], true);
        if (empty($carrito)) {
            $_SESSION['mensaje_error'] = 'Carrito vacío';
            $this->redirigir('/ventas/nueva');
        }

        $idUsuario = $this->sesion->get('id_usuario');
        $metodoPago = $_POST['metodo_pago'] ?? 'efectivo';
        $montoRecibido = $_POST['monto_recibido'] ?? 0;

        try {
            // Verificar caja abierta
            $caja = new Caja();
            $cajaAbierta = $caja->obtenerCajaAbierta($idUsuario);
            
            if (!$cajaAbierta) {
                $_SESSION['mensaje_error'] = 'No hay caja abierta. Abra la caja primero.';
                $this->redirigir('/ventas/nueva');
            }

            // Procesar venta
            $venta = new Venta();
            $resultado = $venta->crearVenta($idUsuario, $carrito, $metodoPago, $montoRecibido, $cajaAbierta['id_caja']);

            if ($resultado['success']) {
                // ✅ Redirigir con mensaje de éxito
                $_SESSION['mensaje_exito'] = '¡Venta #' . $resultado['venta_id'] . ' registrada con éxito! Total: Bs. ' . number_format($resultado['total'], 2);
                $this->redirigir('/ventas/nueva');
            } else {
                $_SESSION['mensaje_error'] = $resultado['error'];
                $this->redirigir('/ventas/nueva');
            }

        } catch (\Exception $e) {
            error_log('Error al procesar venta: ' . $e->getMessage());
            $_SESSION['mensaje_error'] = 'Error al procesar la venta';
            $this->redirigir('/ventas/nueva');
        }
    }

    /**
     * Historial de ventas
     */
    public function historial()
    {
        $this->verificarEmpleado();

        $venta = new Venta();
        $idUsuario = $this->sesion->get('id_usuario');
        $idRol = $this->sesion->get('id_rol');

        if ($idRol == 1) {
            // Admin ve todas las ventas
            $ventas = $venta->obtenerTodas();
        } else {
            // Empleado ve solo sus ventas
            $ventas = $venta->obtenerPorUsuario($idUsuario);
        }

        return $this->vista('ventas/historial', [
            'ventas' => $ventas
        ]);
    }

    /**
     * Detalle de una venta
     */
    public function detalle($id)
    {
        $this->verificarEmpleado();

        $venta = new Venta();
        $detalle = $venta->obtenerDetalleCompleto($id);

        if (!$detalle) {
            $this->redirigir('/ventas/historial?error=404');
        }

        // Verificar que el empleado vea solo sus ventas
        if ($this->sesion->get('id_rol') == 2) {
            if ($detalle['venta']['id_usuario'] != $this->sesion->get('id_usuario')) {
                $this->redirigir('/ventas/historial?error=403');
            }
        }

        return $this->vista('ventas/detalle', [
            'venta' => $detalle['venta'],
            'detalles' => $detalle['detalles'],
            'pago' => $detalle['pago']
        ]);
    }

    /**
     * Anula una venta
     */
    public function anular($id)
    {
        $this->verificarAdmin();

        $venta = new Venta();
        $resultado = $venta->anularVenta($id);

        $this->redirigir('/ventas/historial?success=4');
    }

    /**
     * Verifica que el usuario sea empleado o admin
     */
    private function verificarEmpleado()
    {
        if (!$this->sesion->existe('id_usuario')) {
            $this->redirigir('/login');
        }

        $rol = $this->sesion->get('id_rol');
        if ($rol != 1 && $rol != 2) {
            $this->redirigir('/panel');
        }
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