<?php
$titulo = 'Usuarios';
$activo = 'usuarios';
ob_start();
?>

<div class="barra-superior">
    <h2>Lista de Usuarios</h2>
    <a href="<?php echo URL_BASE; ?>/usuarios/crear" class="boton boton-exito">
        <i class="bi bi-plus-circle"></i> Nuevo Usuario
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alerta alerta-exito">
        <?php
            $mensajes = [
                1 => 'Usuario creado con éxito',
                2 => 'Usuario actualizado con éxito',
                3 => 'Usuario eliminado con éxito'
            ];
            echo $mensajes[$_GET['success']] ?? 'Operación exitosa';
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        <?php
            $mensajes = [
                2 => 'El nombre de usuario ya existe',
                3 => 'Error al crear el usuario',
                4 => 'No puedes eliminar al administrador principal'
            ];
            echo $mensajes[$_GET['error']] ?? 'Error en la operación';
        ?>
    </div>
<?php endif; ?>

<div class="tarjeta">
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;color:#999;">No hay usuarios registrados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $usr): ?>
                    <tr>
                        <td><?php echo $usr['id_usuario']; ?></td>
                        <td><?php echo htmlspecialchars($usr['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usr['username']); ?></td>
                        <td><?php echo htmlspecialchars($usr['rol_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usr['email'] ?? 'N/A'); ?></td>
                        <td>
                            <span style="<?php echo $usr['estado'] == 1 ? 'color:#2e7d32;' : 'color:#d32f2f;'; ?>">
                                <?php echo $usr['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo URL_BASE; ?>/usuarios/editar/<?php echo $usr['id_usuario']; ?>" class="boton-accion" style="color:#f57c00;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if ($usr['id_usuario'] != 1): ?>
                                <form action="<?php echo URL_BASE; ?>/usuarios/eliminar/<?php echo $usr['id_usuario']; ?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este usuario?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo \App\Nucleo\Seguridad::generarTokenCSRF(); ?>">
                                    <button type="submit" class="boton-accion" style="color:#d32f2f;border:none;background:none;cursor:pointer;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
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