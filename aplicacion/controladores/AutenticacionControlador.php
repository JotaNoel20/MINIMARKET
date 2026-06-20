<?php
namespace App\Controladores;

use App\Nucleo\Controlador;
use App\Modelos\Usuario;
use App\Nucleo\Seguridad;

/**
 * Controlador de Autenticación
 * Maneja login, logout y verificación de sesión
 */
class AutenticacionControlador extends Controlador
{
    /**
     * Muestra el formulario de login
     */
    public function mostrarLogin()
    {
        // Si ya está logueado, redirigir al panel
        if ($this->sesion->existe('id_usuario')) {
            $this->redirigir('/panel');
        }
        
        return $this->vista('autenticacion/login');
    }

    /**
     * Procesa el inicio de sesión
     */
    public function iniciarSesion()
    {
        // Validar que se hayan enviado los datos
        if (empty($_POST['usuario']) || empty($_POST['contrasena'])) {
            $this->redirigir('/login?error=1');
        }

        $usuario = trim($_POST['usuario']);
        $contrasena = trim($_POST['contrasena']);

        // Buscar usuario en la base de datos
        $modeloUsuario = new Usuario();
        $usuarioEncontrado = $modeloUsuario->buscarPorUsuario($usuario);

        // Verificar credenciales
        if ($usuarioEncontrado && password_verify($contrasena, $usuarioEncontrado['password'])) {
            // Verificar que el usuario esté activo
            if ($usuarioEncontrado['estado'] != 1) {
                $this->redirigir('/login?error=3');
            }

            // Guardar datos en sesión
            $this->sesion->set('id_usuario', $usuarioEncontrado['id_usuario']);
            $this->sesion->set('nombre', $usuarioEncontrado['nombre']);
            $this->sesion->set('id_rol', $usuarioEncontrado['id_rol']);
            $this->sesion->set('username', $usuarioEncontrado['username']);

            // Redirigir al panel
            $this->redirigir('/panel');
        } else {
            // Error de credenciales
            $this->redirigir('/login?error=2');
        }
    }

    /**
     * Cierra la sesión del usuario
     */
    public function cerrarSesion()
    {
        // Destruir sesión
        $this->sesion->destruir();
        
        // Redirigir al login
        $this->redirigir('/login');
    }
}