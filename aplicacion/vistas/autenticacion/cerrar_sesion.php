<?php
// Esta vista usa la plantilla login.php (sin sidebar)
$contenido = '
<div style="text-align:center;padding:40px 20px;">
    <i class="bi bi-check-circle-fill" style="font-size:64px;color:#2e7d32;"></i>
    <h2 style="margin:20px 0 10px 0;">Sesión cerrada correctamente</h2>
    <p style="color:#666;margin-bottom:30px;">Has cerrado sesión exitosamente.</p>
    <a href="' . URL_BASE . '/login" class="boton boton-primario">
        <i class="bi bi-arrow-return-left"></i> Iniciar Sesión nuevamente
    </a>
</div>
';
include __DIR__ . '/../plantillas/login.php';
?>