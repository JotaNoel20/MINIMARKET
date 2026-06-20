<?php http_response_code(403); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>403 - Acceso denegado</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { margin:0; font-family:Arial; background:#f4f6f9; display:flex; justify-content:center; align-items:center; height:100vh; }
        .error { text-align:center; background:white; padding:50px; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.05); }
        .error i { font-size:64px; color:#f57c00; }
        .error h1 { font-size:72px; margin:10px 0; color:#333; }
        .error h2 { color:#666; }
        .error p { color:#999; }
        .boton { display:inline-block; margin-top:20px; padding:12px 25px; background:#0d47a1; color:white; text-decoration:none; border-radius:6px; }
        .boton:hover { background:#1565c0; }
    </style>
</head>
<body>
    <div class="error">
        <i class="bi bi-shield-exclamation"></i>
        <h1>403</h1>
        <h2>¡Acceso denegado!</h2>
        <p>No tienes permiso para acceder a esta página.</p>
        <a href="/minimarket/publico/" class="boton"><i class="bi bi-house-door-fill"></i> Volver al inicio</a>
    </div>
</body>
</html>