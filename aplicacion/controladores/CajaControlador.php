<?php
namespace App\Controladores;

use App\Nucleo\Controlador;
use App\Modelos\Caja;
use App\Modelos\MovimientoCaja;
use App\Nucleo\Seguridad;
use App\Nucleo\Validacion;

/**
 * Controlador de Caja
 * Apertura, cierre y movimientos
 */
class CajaControlador extends Controlador
{
    public function index()
    {
        $this->verificarEmpleado();

        $caja = new Caja();
        $idUsuario = $this->sesion->get('id_usuario');
        $idRol = $this->sesion->get('id_rol');

        if ($idRol == 1) {
            // Admin ve todas las cajas
            $cajaActual = $caja->obtenerCajaAbiertaAdmin();
            $movimientos = [];
        } else {
            // Empleado ve su caja
            $cajaActual = $caja->obtenerCajaAbierta($idUsuario);
            $movimientos = $cajaActual ? $caja->obtenerMovimientos($cajaActual['id_caja']) : [];
        }

        return $this->vista('caja/index', [
            'caja' => $cajaActual,
            'movimientos' => $movimientos,
            'csrf_token' => Seguridad::generarTokenCSRF()
        ]);
    }

    public function abrir()
    {
        $this->verificarEmpleado();

        if (!Seguridad::validarTokenCSRF($_POST['csrf_token'] ?? '')) {
            die('Token CSRF inválido');
        }

        $validacion = new Validacion();
        $validacion->validar($_POST, [
            'saldo_inicial' => 'required|numeric|min:0'
        ]);

        if ($validacion->falla()) {
            $this->redirigir('/caja?error=' . urlencode($validacion->primerError()));
        }

        $caja = new Caja();
        $idUsuario = $this->sesion->get('id_usuario');

        // Verificar que no tenga caja abierta
        $cajaAbierta = $caja->obtenerCajaAbierta($idUsuario);
        if ($cajaAbierta) {
            $this->redirigir('/caja?error=2');
        }

        $resultado = $caja->abrir($idUsuario, $_POST['saldo_inicial']);

        if ($resultado) {
            $this->redirigir('/caja?success=1');
        } else {
            $this->redirigir('/caja?error=3');
        }
    }

    public function cerrar()
    {
        $this->verificarEmpleado();

        if (!Seguridad::validarTokenCSRF($_POST['csrf_token'] ?? '')) {
            die('Token CSRF inválido');
        }

        $caja = new Caja();
        $idUsuario = $this->sesion->get('id_usuario');

        $cajaAbierta = $caja->obtenerCajaAbierta($idUsuario);
        if (!$cajaAbierta) {
            $this->redirigir('/caja?error=4');
        }

        $resultado = $caja->cerrar($cajaAbierta['id_caja']);

        if ($resultado) {
            $this->redirigir('/caja?success=2');
        } else {
            $this->redirigir('/caja?error=5');
        }
    }

    public function movimientos()
    {
        $this->verificarEmpleado();

        $caja = new Caja();
        $idUsuario = $this->sesion->get('id_usuario');

        $cajaAbierta = $caja->obtenerCajaAbierta($idUsuario);
        if (!$cajaAbierta) {
            $this->redirigir('/caja?error=4');
        }

        $movimientos = $caja->obtenerMovimientos($cajaAbierta['id_caja']);

        return $this->vista('caja/movimientos', [
            'movimientos' => $movimientos,
            'caja' => $cajaAbierta
        ]);
    }

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
}