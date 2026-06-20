<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo NOMBRE_APP; ?> - Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/publico/assets/css/estilo.css">
    <style>
        body {
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
        }
        .login-container h1 {
            text-align: center;
            color: #0d47a1;
            margin-bottom: 30px;
        }
        .login-container h1 i {
            margin-right: 10px;
        }
        .login-container .campo {
            margin-bottom: 15px;
        }
        .login-container .campo label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .login-container .campo input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            box-sizing: border-box;
        }
        .login-container .campo input:focus {
            border-color: #0d47a1;
            outline: none;
        }
        .login-container .boton {
            width: 100%;
            padding: 12px;
            background: #0d47a1;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        .login-container .boton:hover {
            background: #1565c0;
        }
        .login-container .error {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #ef9a9a;
        }
        .login-container .info {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #999;
        }
        .login-container .info strong {
            color: #0d47a1;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1><i class="bi bi-shop"></i><?php echo NOMBRE_APP; ?></h1>
        <h3 style="text-align:center;color:#666;">Iniciar Sesión</h3>

        <?php if (isset($_GET['error'])): ?>
            <div class="error">
                <?php
                    $mensajes = [
                        1 => 'Usuario o contraseña incorrectos',
                        2 => 'Usuario o contraseña incorrectos',
                        3 => 'Tu cuenta está desactivada'
                    ];
                    echo $mensajes[$_GET['error']] ?? 'Error al iniciar sesión';
                ?>
            </div>
        <?php endif; ?>

        <!-- ✅ CAMBIADO: action="login" (relativo) -->
        <form action="login" method="POST">
            <div class="campo">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario" required autofocus>
            </div>
            <div class="campo">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" placeholder="Ingresa tu contraseña" required>
            </div>
            <button type="submit" class="boton">Iniciar Sesión</button>
        </form>

        <div class="info">
            <strong>Admin:</strong> admin / 123456 &nbsp;|&nbsp; <strong>Empleado:</strong> ventas / 123456
        </div>
    </div>
</body>
</html>