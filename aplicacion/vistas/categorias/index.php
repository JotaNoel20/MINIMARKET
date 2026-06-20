<?php
$titulo = 'Categorías';
$activo = 'categorias';
ob_start();
?>

<div class="barra-superior">
    <h2>Lista de Categorías</h2>
    <a href="<?php echo URL_BASE; ?>/categorias/crear" class="boton boton-exito">
        <i class="bi bi-plus-circle"></i> Nueva Categoría
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alerta alerta-exito">
        <?php
            $mensajes = [
                1 => 'Categoría creada con éxito',
                2 => 'Categoría actualizada con éxito',
                3 => 'Categoría eliminada con éxito'
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
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categorias)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;color:#999;">No hay categorías registradas</td>
                </tr>
            <?php else: ?>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><?php echo $cat['id_categoria']; ?></td>
                        <td><?php echo htmlspecialchars($cat['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($cat['descripcion'] ?? 'Sin descripción'); ?></td>
                        <td>
                            <span style="<?php echo $cat['estado'] == 1 ? 'color:#2e7d32;' : 'color:#d32f2f;'; ?>">
                                <?php echo $cat['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo URL_BASE; ?>/categorias/editar/<?php echo $cat['id_categoria']; ?>" class="boton-accion" style="color:#f57c00;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo URL_BASE; ?>/categorias/eliminar/<?php echo $cat['id_categoria']; ?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta categoría?')">
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