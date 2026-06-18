<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimarket El Buen Precio - Login</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #0288d1, #26a69a, #00e676);
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .cuadro-login {
            background-color: white;
            width: 100%;
            max-width: 360px;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            text-align: center;
        }

        .titulo {
            color: #0d47a1;
            font-size: 30px;
            font-weight: bold;
            margin: 0;
        }

        .linea {
            width: 75px;
            height: 4px;
            background-color: #2e7d32;
            margin: 10px auto 20px auto;
        }

        .icono-cart {
            color: #2e7d32;
            font-size: 45px;
            margin-bottom: 20px;
        }

        .grupo {
            margin-bottom: 20px;
            text-align: left;
        }

        .label-form {
            display: block;
            color: #0d47a1;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .div-input {
            position: relative;
        }

        .div-input i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #2e7d32;
            font-size: 18px;
        }

        .input-txt {
            width: 100%;
            height: 45px;
            padding-left: 45px;
            border: 2px solid #2e7d32;
            border-radius: 12px;
            font-size: 14px;
            box-sizing: border-box;
            outline: none;
        }

        .input-txt:focus {
            border-color: #0d47a1;
        }

        .btn-entrar {
            width: 100%;
            height: 45px;
            background-color: #1b5e20;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-entrar:hover {
            background-color: #123e17;
        }
    </style>
</head>
<body>

    <div class="cuadro-login">
        <h1 class="titulo">El Buen Precio</h1>
        <div class="linea"></div>
        <div class="icono-cart">
            <i class="bi bi-cart-check-fill"></i>
        </div>
        
        <form action="../modulos/usuarios/login.php" method="POST">
            
            <div class="grupo">
                <label class="label-form">Usuario o Correo</label>
                <div class="div-input">
                    <i class="bi bi-person-fill"></i>
                    <input type="text" name="usuario" class="input-txt" placeholder="Ej. administrador" required>
                </div>
            </div>
            
            <div class="grupo">
                <label class="label-form">Contraseña</label>
                <div class="div-input">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password" class="input-txt" placeholder="••••••••" required>
                </div>
            </div>
            
            <button type="submit" class="btn-entrar">
                <i class="bi bi-lock-fill"></i> Iniciar Sesión
            </button>
        </form>
    </div>

</body>
</html>