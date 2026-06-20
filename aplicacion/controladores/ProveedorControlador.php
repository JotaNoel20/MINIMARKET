<?php
namespace App\Controladores;

use App\Nucleo\Controlador;
use App\Modelos\Proveedor;
use App\Nucleo\Seguridad;
use App\Nucleo\Validacion;

/**
 * Controlador de Proveedores
 * CRUD completo de proveedores
 */
class ProveedorControlador extends Controlador
{
    public function index()
    {
        $this->verificarAdmin();

        $proveedor = new Proveedor();
        $proveedores = $proveedor->obtenerTodas();

        return $this->vista('proveedores/index', [
            'proveedores' => $proveedores
        ]);
    }

    public function crear()
    {
        $this->verificarAdmin();
        return $this->vista('proveedores/crear', [
            'csrf_token' => Seguridad::generarTokenCSRF()
        ]);
    }

    public function guardar()
    {
        $this->verificarAdmin();

        if (!Seguridad::validarTokenCSRF($_POST['csrf_token'] ?? '')) {
            die('Token CSRF inválido');
        }

        $validacion = new Validacion();
        $validacion->validar($_POST, [
            'nombre' => 'required|min:3|max:150'
        ]);

        if ($validacion->falla()) {
            $this->redirigir('/proveedores/crear?error=' . urlencode($validacion->primerError()));
        }

        $proveedor = new Proveedor();
        $id = $proveedor->crear([
            'nombre' => trim($_POST['nombre']),
            'nit' => trim($_POST['nit'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'estado' => 1
        ]);

        $this->redirigir('/proveedores?success=1');
    }

    public function editar($id)
    {
        $this->verificarAdmin();

        $proveedor = new Proveedor();
        $datos = $proveedor->obtenerPorId($id);

        if (!$datos) {
            $this->redirigir('/proveedores?error=404');
        }

        return $this->vista('proveedores/editar', [
            'proveedor' => $datos,
            'csrf_token' => Seguridad::generarTokenCSRF()
        ]);
    }

    public function actualizar()
    {
        $this->verificarAdmin();

        if (!Seguridad::validarTokenCSRF($_POST['csrf_token'] ?? '')) {
            die('Token CSRF inválido');
        }

        $validacion = new Validacion();
        $validacion->validar($_POST, [
            'id_proveedor' => 'required|numeric|min:1',
            'nombre' => 'required|min:3|max:150'
        ]);

        if ($validacion->falla()) {
            $this->redirigir('/proveedores/editar/' . $_POST['id_proveedor'] . '?error=' . urlencode($validacion->primerError()));
        }

        $proveedor = new Proveedor();
        $proveedor->actualizar($_POST['id_proveedor'], [
            'nombre' => trim($_POST['nombre']),
            'nit' => trim($_POST['nit'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'email' => trim($_POST['email'] ?? '')
        ]);

        $this->redirigir('/proveedores?success=2');
    }

    public function eliminar($id)
    {
        $this->verificarAdmin();

        $proveedor = new Proveedor();
        $proveedor->desactivar($id);

        $this->redirigir('/proveedores?success=3');
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