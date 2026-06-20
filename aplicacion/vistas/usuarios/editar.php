<?php
$titulo = 'Editar Usuario';
$activo = 'usuarios';
ob_start();
?>

<div class="barra-superior">
    <h2>Editar Usuario</h2>
    <a href="<?php echo URL_BASE; ?>/usuarios" class="boton">Volver</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        <?php
            $mensajes = [
                2 => 'El nombre de usuario ya existe',
                3 => 'Error al actualizar el usuario'
            ];
            echo $mensajes[$_GET['error']] ?? htmlspecialchars($_GET['error']);
        ?>
    </div>
<?php endif; ?>

<div class="tarjeta" style="max-width:600px;margin:0 auto;">
    <form action="<?php echo URL_BASE; ?>/usuarios/actualizar" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">

        <div class="campo">
            <label for="nombre">Nombre Completo *</label>
            <input type="text" id="nombre" name="nombre" class="campo" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required autofocus>
        </div>

        <div class="campo">
            <label for="username">Nombre de Usuario *</label>
            <input type="text" id="username" name="username" class="campo" value="<?php echo htmlspecialchars($usuario['username']); ?>" required>
            <small style="color:#999;">Mínimo 3 caracteres, sin espacios</small>
        </div>

        <div class="campo">
            <label for="password">Nueva Contraseña</label>
            <input type="password" id="password" name="password" class="campo" placeholder="Dejar en blanco para no cambiar">
            <small style="color:#999;">Mínimo 6 caracteres</small>
        </div>

        <div class="campo">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="campo" value="<?php echo htmlspecialchars($usuario['email']); ?>">
        </div>

        <div class="campo">
            <label for="id_rol">Rol *</label>
            <select id="id_rol" name="id_rol" class="campo" required>
                <option value="">Seleccionar rol</option>
                <?php foreach ($roles as $rol): ?>
                    <option value="<?php echo $rol['id_rol']; ?>" <?php echo $rol['id_rol'] == $usuario['id_rol'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($rol['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label for="estado">Estado</label>
            <select id="estado" name="estado" class="campo">
                <option value="1" <?php echo $usuario['estado'] == 1 ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?php echo $usuario['estado'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>

        <div style="display:flex;gap:15px;margin-top:20px;">
            <button type="submit" class="boton boton-exito" style="flex:1;">Actualizar Usuario</button>
            <a href="<?php echo URL_BASE; ?>/usuarios" class="boton" style="flex:1;text-align:center;">Cancelar</a>
        </div>
    </form>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>