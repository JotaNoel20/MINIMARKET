<?php
namespace App\Controladores;

use App\Nucleo\Controlador;
use App\Modelos\Usuario;
use App\Modelos\Rol;
use App\Nucleo\Seguridad;
use App\Nucleo\Validacion;

/**
 * Controlador de Usuarios
 * CRUD completo de usuarios del sistema
 */
class UsuarioControlador extends Controlador
{
    public function index()
    {
        $this->verificarAdmin();

        $usuario = new Usuario();
        $usuarios = $usuario->obtenerTodos();

        return $this->vista('usuarios/index', [
            'usuarios' => $usuarios
        ]);
    }

    public function crear()
    {
        $this->verificarAdmin();

        $rol = new Rol();
        $roles = $rol->obtenerActivos();

        return $this->vista('usuarios/crear', [
            'roles' => $roles,
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
            'nombre' => 'required|min:3|max:100',
            'username' => 'required|min:3|max:50',
            'password' => 'required|min:6',
            'id_rol' => 'required|numeric|min:1'
        ]);

        if ($validacion->falla()) {
            $this->redirigir('/usuarios/crear?error=' . urlencode($validacion->primerError()));
        }

        // Verificar que el username no exista
        $usuario = new Usuario();
        if ($usuario->existeUsername($_POST['username'])) {
            $this->redirigir('/usuarios/crear?error=2');
        }

        // Crear usuario con hash de contraseña
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $id = $usuario->crear([
            'id_rol' => $_POST['id_rol'],
            'nombre' => trim($_POST['nombre']),
            'username' => trim($_POST['username']),
            'password' => $passwordHash,
            'email' => trim($_POST['email'] ?? ''),
            'estado' => 1
        ]);

        if ($id) {
            $this->redirigir('/usuarios?success=1');
        } else {
            $this->redirigir('/usuarios/crear?error=3');
        }
    }

    public function editar($id)
    {
        $this->verificarAdmin();

        $usuario = new Usuario();
        $datos = $usuario->obtenerPorId($id);

        if (!$datos) {
            $this->redirigir('/usuarios?error=404');
        }

        $rol = new Rol();
        $roles = $rol->obtenerActivos();

        return $this->vista('usuarios/editar', [
            'usuario' => $datos,
            'roles' => $roles,
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
            'id_usuario' => 'required|numeric|min:1',
            'nombre' => 'required|min:3|max:100',
            'username' => 'required|min:3|max:50',
            'id_rol' => 'required|numeric|min:1'
        ]);

        if ($validacion->falla()) {
            $this->redirigir('/usuarios/editar/' . $_POST['id_usuario'] . '?error=' . urlencode($validacion->primerError()));
        }

        $usuario = new Usuario();

        // Verificar que el username no exista (excepto el mismo)
        if ($usuario->existeUsernameExcepto($_POST['username'], $_POST['id_usuario'])) {
            $this->redirigir('/usuarios/editar/' . $_POST['id_usuario'] . '?error=2');
        }

        $datos = [
            'id_rol' => $_POST['id_rol'],
            'nombre' => trim($_POST['nombre']),
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email'] ?? ''),
            'estado' => $_POST['estado'] ?? 1
        ];

        // Si se envió una nueva contraseña, actualizarla
        if (!empty($_POST['password'])) {
            $datos['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $resultado = $usuario->actualizar($_POST['id_usuario'], $datos);

        if ($resultado) {
            $this->redirigir('/usuarios?success=2');
        } else {
            $this->redirigir('/usuarios/editar/' . $_POST['id_usuario'] . '?error=3');
        }
    }

    public function eliminar($id)
    {
        $this->verificarAdmin();

        // No permitir eliminar el usuario admin principal
        if ($id == 1) {
            $this->redirigir('/usuarios?error=4');
        }

        $usuario = new Usuario();
        $usuario->desactivar($id);

        $this->redirigir('/usuarios?success=3');
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