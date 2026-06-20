<?php
$titulo = 'Nuevo Usuario';
$activo = 'usuarios';
ob_start();
?>

<div class="barra-superior">
    <h2>Nuevo Usuario</h2>
    <a href="<?php echo URL_BASE; ?>/usuarios" class="boton">Volver</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alerta alerta-error">
        <?php
            $mensajes = [
                2 => 'El nombre de usuario ya existe',
                3 => 'Error al crear el usuario'
            ];
            echo $mensajes[$_GET['error']] ?? htmlspecialchars($_GET['error']);
        ?>
    </div>
<?php endif; ?>

<div class="tarjeta" style="max-width:600px;margin:0 auto;">
    <form action="<?php echo URL_BASE; ?>/usuarios/guardar" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="campo">
            <label for="nombre">Nombre Completo *</label>
            <input type="text" id="nombre" name="nombre" class="campo" placeholder="Ej: Juan Pérez" required autofocus>
        </div>

        <div class="campo">
            <label for="username">Nombre de Usuario *</label>
            <input type="text" id="username" name="username" class="campo" placeholder="Ej: jperez" required>
            <small style="color:#999;">Mínimo 3 caracteres, sin espacios</small>
        </div>

        <div class="campo">
            <label for="password">Contraseña *</label>
            <input type="password" id="password" name="password" class="campo" placeholder="Mínimo 6 caracteres" required>
        </div>

        <div class="campo">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="campo" placeholder="Ej: juan@correo.com">
        </div>

        <div class="campo">
            <label for="id_rol">Rol *</label>
            <select id="id_rol" name="id_rol" class="campo" required>
                <option value="">Seleccionar rol</option>
                <?php foreach ($roles as $rol): ?>
                    <option value="<?php echo $rol['id_rol']; ?>">
                        <?php echo htmlspecialchars($rol['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display:flex;gap:15px;margin-top:20px;">
            <button type="submit" class="boton boton-exito" style="flex:1;">Guardar Usuario</button>
            <a href="<?php echo URL_BASE; ?>/usuarios" class="boton" style="flex:1;text-align:center;">Cancelar</a>
        </div>
    </form>
</div>

<?php
$contenido = ob_get_clean();
include __DIR__ . '/../plantillas/principal.php';
?>