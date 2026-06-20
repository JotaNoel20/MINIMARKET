<?php
/**
 * DEFINICIÓN DE RUTAS
 * Todas las rutas de la aplicación
 */

// ============================================================
// RUTAS DE AUTENTICACIÓN
// ============================================================

$enrutador->get('/', 'AutenticacionControlador@mostrarLogin');
$enrutador->get('/login', 'AutenticacionControlador@mostrarLogin');
$enrutador->post('/login', 'AutenticacionControlador@iniciarSesion');
$enrutador->get('/logout', 'AutenticacionControlador@cerrarSesion');

// ============================================================
// RUTAS DEL PANEL
// ============================================================

$enrutador->get('/panel', 'PanelControlador@index');

// ============================================================
// RUTAS DE PRODUCTOS (SOLO ADMIN)
// ============================================================

$enrutador->get('/productos', 'ProductoControlador@index', ['Administrador']);
$enrutador->get('/productos/crear', 'ProductoControlador@crear', ['Administrador']);
$enrutador->post('/productos/guardar', 'ProductoControlador@guardar', ['Administrador']);
$enrutador->get('/productos/editar/{id}', 'ProductoControlador@editar', ['Administrador']);
$enrutador->post('/productos/actualizar', 'ProductoControlador@actualizar', ['Administrador']);
$enrutador->get('/productos/ver/{id}', 'ProductoControlador@ver', ['Administrador']);
$enrutador->post('/productos/eliminar/{id}', 'ProductoControlador@eliminar', ['Administrador']);

// ============================================================
// RUTAS DE VENTAS
// ============================================================

$enrutador->get('/ventas/nueva', 'VentaControlador@nueva', ['Empleado']);
$enrutador->post('/ventas/procesar', 'VentaControlador@procesar', ['Empleado']);
$enrutador->get('/ventas/historial', 'VentaControlador@historial', ['Empleado']);
$enrutador->get('/ventas/detalle/{id}', 'VentaControlador@detalle', ['Empleado']);
$enrutador->get('/ventas/anular/{id}', 'VentaControlador@anular', ['Administrador']);

// ============================================================
// RUTAS DE CATEGORÍAS (SOLO ADMIN)
// ============================================================

$enrutador->get('/categorias', 'CategoriaControlador@index', ['Administrador']);
$enrutador->get('/categorias/crear', 'CategoriaControlador@crear', ['Administrador']);
$enrutador->post('/categorias/guardar', 'CategoriaControlador@guardar', ['Administrador']);
$enrutador->get('/categorias/editar/{id}', 'CategoriaControlador@editar', ['Administrador']);
$enrutador->post('/categorias/actualizar', 'CategoriaControlador@actualizar', ['Administrador']);
$enrutador->post('/categorias/eliminar/{id}', 'CategoriaControlador@eliminar', ['Administrador']);

// ============================================================
// RUTAS DE PROVEEDORES (SOLO ADMIN)
// ============================================================

$enrutador->get('/proveedores', 'ProveedorControlador@index', ['Administrador']);
$enrutador->get('/proveedores/crear', 'ProveedorControlador@crear', ['Administrador']);
$enrutador->post('/proveedores/guardar', 'ProveedorControlador@guardar', ['Administrador']);
$enrutador->get('/proveedores/editar/{id}', 'ProveedorControlador@editar', ['Administrador']);
$enrutador->post('/proveedores/actualizar', 'ProveedorControlador@actualizar', ['Administrador']);
$enrutador->post('/proveedores/eliminar/{id}', 'ProveedorControlador@eliminar', ['Administrador']);

// ============================================================
// RUTAS DE COMPRAS (SOLO ADMIN)
// ============================================================

$enrutador->get('/compras', 'CompraControlador@index', ['Administrador']);
$enrutador->get('/compras/crear', 'CompraControlador@crear', ['Administrador']);
$enrutador->post('/compras/guardar', 'CompraControlador@guardar', ['Administrador']);
$enrutador->get('/compras/detalle/{id}', 'CompraControlador@detalle', ['Administrador']);

// ============================================================
// RUTAS DE CAJA
// ============================================================

$enrutador->get('/caja', 'CajaControlador@index', ['Empleado']);
$enrutador->post('/caja/abrir', 'CajaControlador@abrir', ['Empleado']);
$enrutador->post('/caja/cerrar', 'CajaControlador@cerrar', ['Empleado']);
$enrutador->get('/caja/movimientos', 'CajaControlador@movimientos', ['Empleado']);

// ============================================================
// RUTAS DE USUARIOS (SOLO ADMIN)
// ============================================================

$enrutador->get('/usuarios', 'UsuarioControlador@index', ['Administrador']);
$enrutador->get('/usuarios/crear', 'UsuarioControlador@crear', ['Administrador']);
$enrutador->post('/usuarios/guardar', 'UsuarioControlador@guardar', ['Administrador']);
$enrutador->get('/usuarios/editar/{id}', 'UsuarioControlador@editar', ['Administrador']);
$enrutador->post('/usuarios/actualizar', 'UsuarioControlador@actualizar', ['Administrador']);
$enrutador->post('/usuarios/eliminar/{id}', 'UsuarioControlador@eliminar', ['Administrador']);

// ============================================================
// RUTAS DE REPORTES (SOLO ADMIN)
// ============================================================

$enrutador->get('/reportes', 'ReporteControlador@index', ['Administrador']);
$enrutador->get('/reportes/ventas', 'ReporteControlador@ventas', ['Administrador']);
$enrutador->get('/reportes/inventario', 'ReporteControlador@inventario', ['Administrador']);
$enrutador->get('/reportes/caja', 'ReporteControlador@caja', ['Administrador']);
$enrutador->get('/reportes/ganancias', 'ReporteControlador@ganancias', ['Administrador']);

// ============================================================
// RUTA POR DEFECTO (404)
// ============================================================

// Si no coincide ninguna ruta, el enrutador muestra 404 automáticamente