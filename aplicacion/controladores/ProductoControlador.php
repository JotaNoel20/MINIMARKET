<?php
namespace App\Controladores;

use App\Nucleo\Controlador;
use App\Modelos\Producto;
use App\Modelos\Categoria;
use App\Nucleo\Seguridad;
use App\Nucleo\Validacion;

/**
 * Controlador de Productos
 * CRUD completo de productos
 */
class ProductoControlador extends Controlador
{
    /**
     * Lista todos los productos activos
     */
    public function index()
    {
        $this->verificarAdmin();

        $producto = new Producto();
        $productos = $producto->obtenerTodosConCategoria();

        return $this->vista('productos/index', [
            'productos' => $productos
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo producto
     */
    public function crear()
    {
        $this->verificarAdmin();

        $categoria = new Categoria();
        $categorias = $categoria->obtenerActivas();

        return $this->vista('productos/crear', [
            'categorias' => $categorias,
            'csrf_token' => Seguridad::generarTokenCSRF()
        ]);
    }

    /**
     * Guarda un nuevo producto en la base de datos
     */
    public function guardar()
    {
        $this->verificarAdmin();

        // Validar token CSRF
        if (!Seguridad::validarTokenCSRF($_POST['csrf_token'] ?? '')) {
            die('Token CSRF inválido');
        }

        // Validar datos
        $validacion = new Validacion();
        $validacion->validar($_POST, [
            'nombre' => 'required|min:3|max:150',
            'id_categoria' => 'required|numeric|min:1',
            'precio_venta' => 'required|numeric|min:0.01',
            'precio_compra' => 'required|numeric|min:0.01',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0'
        ]);

        if ($validacion->falla()) {
            $this->redirigir('/productos/crear?error=' . urlencode($validacion->primerError()));
        }

        // Guardar producto
        $producto = new Producto();
        $id = $producto->crear([
            'id_categoria' => $_POST['id_categoria'],
            'id_proveedor' => $_POST['id_proveedor'] ?? 1,
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'precio_venta' => $_POST['precio_venta'],
            'precio_compra' => $_POST['precio_compra'],
            'stock_actual' => $_POST['stock_actual'],
            'stock_minimo' => $_POST['stock_minimo'],
            'estado' => 1
        ]);

        if ($id) {
            $this->redirigir('/productos?success=1');
        } else {
            $this->redirigir('/productos/crear?error=1');
        }
    }

    /**
     * Muestra el formulario para editar un producto
     */
    public function editar($id)
    {
        $this->verificarAdmin();

        $producto = new Producto();
        $datosProducto = $producto->obtenerPorId($id);

        if (!$datosProducto) {
            $this->redirigir('/productos?error=404');
        }

        $categoria = new Categoria();
        $categorias = $categoria->obtenerActivas();

        return $this->vista('productos/editar', [
            'producto' => $datosProducto,
            'categorias' => $categorias,
            'csrf_token' => Seguridad::generarTokenCSRF()
        ]);
    }

    /**
     * Actualiza un producto en la base de datos
     */
    public function actualizar()
    {
        $this->verificarAdmin();

        // Validar token CSRF
        if (!Seguridad::validarTokenCSRF($_POST['csrf_token'] ?? '')) {
            die('Token CSRF inválido');
        }

        // Validar datos
        $validacion = new Validacion();
        $validacion->validar($_POST, [
            'id_producto' => 'required|numeric|min:1',
            'nombre' => 'required|min:3|max:150',
            'id_categoria' => 'required|numeric|min:1',
            'precio_venta' => 'required|numeric|min:0.01',
            'precio_compra' => 'required|numeric|min:0.01',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0'
        ]);

        if ($validacion->falla()) {
            $this->redirigir('/productos/editar/' . $_POST['id_producto'] . '?error=' . urlencode($validacion->primerError()));
        }

        // Actualizar producto
        $producto = new Producto();
        $resultado = $producto->actualizar($_POST['id_producto'], [
            'id_categoria' => $_POST['id_categoria'],
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'precio_venta' => $_POST['precio_venta'],
            'precio_compra' => $_POST['precio_compra'],
            'stock_actual' => $_POST['stock_actual'],
            'stock_minimo' => $_POST['stock_minimo']
        ]);

        if ($resultado) {
            $this->redirigir('/productos?success=2');
        } else {
            $this->redirigir('/productos/editar/' . $_POST['id_producto'] . '?error=1');
        }
    }

    /**
     * Muestra el detalle de un producto
     */
    public function ver($id)
    {
        $this->verificarAdmin();

        $producto = new Producto();
        $datosProducto = $producto->obtenerPorId($id);

        if (!$datosProducto) {
            $this->redirigir('/productos?error=404');
        }

        return $this->vista('productos/ver', [
            'producto' => $datosProducto
        ]);
    }

    /**
     * Elimina (desactiva) un producto
     */
    public function eliminar($id)
    {
        $this->verificarAdmin();

        $producto = new Producto();
        $resultado = $producto->desactivar($id);

        $this->redirigir('/productos?success=3');
    }

    /**
     * Verifica que el usuario sea administrador
     */
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