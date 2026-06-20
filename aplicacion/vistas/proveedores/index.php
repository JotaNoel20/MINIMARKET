<?php
$titulo = 'Proveedores';
$activo = 'proveedores';
ob_start();
?>

<div class="barra-superior">
    <h2>Lista de Proveedores</h2>
    <a href="<?php echo URL_BASE; ?>/proveedores/crear" class="boton boton-exito">
        <i class="bi bi-plus-circle"></i> Nuevo Proveedor
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alerta alerta-exito">
        <?php
            $mensajes = [
                1 => 'Proveedor creado con éxito',
                2 => 'Proveedor actualizado con éxito',
                3 => 'Proveedor eliminado con éxito'
            ];
            echo $mensajes[$_GET['success']] ?? 'Operación exitosa';
        ?>
    </div>
<?php endif; ?>

<div class="tarjeta">
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>NIT</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($proveedores)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;color:#999;">No hay proveedores registrados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($proveedores as $prov): ?>
                    <tr>
                        <td><?php echo $prov['id_proveedor']; ?></td>
                        <td><?php echo htmlspecialchars($prov['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($prov['nit'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($prov['telefono'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($prov['email'] ?? 'N/A'); ?></td>
                        <td>
                            <span style="<?php echo $prov['estado'] == 1 ? 'color:#2e7d32;' : 'color:#d32f2f;'; ?>">
                                <?php echo $prov['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo URL_BASE; ?>/proveedores/editar/<?php echo $prov['id_proveedor']; ?>" class="boton-accion" style="color:#f57c00;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo URL_BASE; ?>/proveedores/eliminar/<?php echo $prov['id_proveedor']; ?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este proveedor?')">
                                <input type="hidden" name="csrf_token" value="<?php echo \App\Nucleo\Seguridad::generarTokenCSRF(); ?>">
                                <button type="submit" class="boton-accion" style="color:#d32f2f;border:none;background:none;cursor:pointer;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>