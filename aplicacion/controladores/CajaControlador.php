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

        $caja = new Caja();
        $idUsuario = $this->sesion->get('id_usuario');

        // Verificar que no tenga caja abierta
        $cajaAbierta = $caja->obtenerCajaAbierta($idUsuario);
        if ($cajaAbierta) {
            $this->redirigir('/caja?error=2');
        }

        // ✅ Obtener el monto general configurado para hoy
        $montoGeneral = $caja->obtenerMontoGeneral();
        
        // Si no hay monto general configurado, usar 0
        if ($montoGeneral === null) {
            $this->redirigir('/caja?error=6'); // Error: no hay configuración
        }

        $resultado = $caja->abrir($idUsuario, $montoGeneral);

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

    /**
     * Configurar el monto general del día (Solo Admin)
     */
    public function configurar()
    {
        $this->verificarAdmin();

        $caja = new Caja();
        $montoActual = $caja->obtenerMontoGeneral();
        $historial = $caja->obtenerHistorialMontos();

        return $this->vista('caja/configurar', [
            'monto_actual' => $montoActual ?? 0,
            'historial' => $historial,
            'csrf_token' => Seguridad::generarTokenCSRF()
        ]);
    }

    /**
     * Guardar la configuración del monto general (Solo Admin)
     */
    public function guardarConfiguracion()
    {
        $this->verificarAdmin();

        if (!Seguridad::validarTokenCSRF($_POST['csrf_token'] ?? '')) {
            die('Token CSRF inválido');
        }

        $validacion = new Validacion();
        $validacion->validar($_POST, [
            'monto' => 'required|numeric|min:0'
        ]);

        if ($validacion->falla()) {
            $this->redirigir('/caja/configurar?error=' . urlencode($validacion->primerError()));
        }

        $caja = new Caja();
        $resultado = $caja->guardarMontoGeneral($_POST['monto'], $this->sesion->get('id_usuario'));

        if ($resultado) {
            $this->redirigir('/caja/configurar?success=1');
        } else {
            $this->redirigir('/caja/configurar?error=1');
        }
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