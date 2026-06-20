<?php
namespace App\Controladores;

use App\Nucleo\Controlador;
use App\Modelos\Compra;
use App\Modelos\Producto;
use App\Modelos\Proveedor;
use App\Modelos\DetalleCompra;
use App\Nucleo\Seguridad;
use App\Nucleo\Validacion;

/**
 * Controlador de Compras
 * Gestión de compras a proveedores
 */
class CompraControlador extends Controlador
{
    public function index()
    {
        $this->verificarAdmin();

        $compra = new Compra();
        $compras = $compra->obtenerTodas();

        return $this->vista('compras/index', [
            'compras' => $compras
        ]);
    }

    public function crear()
    {
        $this->verificarAdmin();

        $producto = new Producto();
        $productos = $producto->obtenerTodos();

        $proveedor = new Proveedor();
        $proveedores = $proveedor->obtenerActivos();

        return $this->vista('compras/crear', [
            'productos' => $productos,
            'proveedores' => $proveedores,
            'csrf_token' => Seguridad::generarTokenCSRF()
        ]);
    }

    public function guardar()
    {
        $this->verificarAdmin();

        if (!Seguridad::validarTokenCSRF($_POST['csrf_token'] ?? '')) {
            die('Token CSRF inválido');
        }

        // Validar datos básicos
        $validacion = new Validacion();
        $validacion->validar($_POST, [
            'id_proveedor' => 'required|numeric|min:1',
            'json_detalles' => 'required'
        ]);

        if ($validacion->falla()) {
            $this->redirigir('/compras/crear?error=' . urlencode($validacion->primerError()));
        }

        $detalles = json_decode($_POST['json_detalles'], true);
        if (empty($detalles)) {
            $this->redirigir('/compras/crear?error=2');
        }

        $idUsuario = $this->sesion->get('id_usuario');
        $idProveedor = $_POST['id_proveedor'];

        try {
            $compra = new Compra();
            $resultado = $compra->crearCompra($idProveedor, $idUsuario, $detalles);

            if ($resultado['success']) {
                $this->redirigir('/compras?success=1');
            } else {
                $this->redirigir('/compras/crear?error=' . urlencode($resultado['error']));
            }
        } catch (\Exception $e) {
            error_log('Error al crear compra: ' . $e->getMessage());
            $this->redirigir('/compras/crear?error=3');
        }
    }

    public function detalle($id)
    {
        $this->verificarAdmin();

        $compra = new Compra();
        $detalle = $compra->obtenerDetalleCompleto($id);

        if (!$detalle) {
            $this->redirigir('/compras?error=404');
        }

        return $this->vista('compras/detalle', [
            'compra' => $detalle['compra'],
            'detalles' => $detalle['detalles'],
            'proveedor' => $detalle['proveedor']
        ]);
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