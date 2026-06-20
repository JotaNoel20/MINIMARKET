<?php http_response_code(404); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>404 - Página no encontrada</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { margin:0; font-family:Arial; background:#f4f6f9; display:flex; justify-content:center; align-items:center; height:100vh; }
        .error { text-align:center; background:white; padding:50px; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.05); }
        .error i { font-size:64px; color:#d32f2f; }
        .error h1 { font-size:72px; margin:10px 0; color:#333; }
        .error h2 { color:#666; }
        .error p { color:#999; }
        .boton { display:inline-block; margin-top:20px; padding:12px 25px; background:#0d47a1; color:white; text-decoration:none; border-radius:6px; }
        .boton:hover { background:#1565c0; }
    </style>
</head>
<body>
    <div class="error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <h1>404</h1>
        <h2>¡Página no encontrada!</h2>
        <p>La página que buscas no existe o ha sido movida.</p>
        <a href="/minimarket/publico/" class="boton"><i class="bi bi-house-door-fill"></i> Volver al inicio</a>
    </div>
</body>
</html>