<?php
namespace App\Controladores;

use App\Nucleo\Controlador;
use App\Modelos\Categoria;
use App\Nucleo\Seguridad;
use App\Nucleo\Validacion;

/**
 * Controlador de Categorías
 * CRUD completo de categorías
 */
class CategoriaControlador extends Controlador
{
    public function index()
    {
        $this->verificarAdmin();

        $categoria = new Categoria();
        $categorias = $categoria->obtenerTodas();

        return $this->vista('categorias/index', [
            'categorias' => $categorias
        ]);
    }

    public function crear()
    {
        $this->verificarAdmin();
        return $this->vista('categorias/crear', [
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
            'nombre' => 'required|min:3|max:100'
        ]);

        if ($validacion->falla()) {
            $this->redirigir('/categorias/crear?error=' . urlencode($validacion->primerError()));
        }

        $categoria = new Categoria();
        $id = $categoria->crear([
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'estado' => 1
        ]);

        $this->redirigir('/categorias?success=1');
    }

    public function editar($id)
    {
        $this->verificarAdmin();

        $categoria = new Categoria();
        $datos = $categoria->obtenerPorId($id);

        if (!$datos) {
            $this->redirigir('/categorias?error=404');
        }

        return $this->vista('categorias/editar', [
            'categoria' => $datos,
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
            'id_categoria' => 'required|numeric|min:1',
            'nombre' => 'required|min:3|max:100'
        ]);

        if ($validacion->falla()) {
            $this->redirigir('/categorias/editar/' . $_POST['id_categoria'] . '?error=' . urlencode($validacion->primerError()));
        }

        $categoria = new Categoria();
        $categoria->actualizar($_POST['id_categoria'], [
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? '')
        ]);

        $this->redirigir('/categorias?success=2');
    }

    public function eliminar($id)
    {
        $this->verificarAdmin();

        $categoria = new Categoria();
        $categoria->desactivar($id);

        $this->redirigir('/categorias?success=3');
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