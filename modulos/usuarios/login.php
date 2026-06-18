<?php
session_start();

require_once '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usuario_recibido  = trim($_POST['usuario']);
    $password_recibida = trim($_POST['password']);

    try {
        $sql = "SELECT * FROM usuario WHERE username = :usuario LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['usuario' => $usuario_recibido]);
        $user = $stmt->fetch();

        if ($user) {
            // DIAGNÓSTICO: Si el usuario existe, comparamos manualmente en pantalla
            if ($password_recibida == $user['password']) {
                
                $_SESSION['id_usuario'] = $user['id_usuario']; 
                $_SESSION['nombre']     = $user['nombre'];
                $_SESSION['id_rol']     = $user['id_rol']; 

                header('Location: /minimarket/public/dashboard.php');
                exit;

            } else {
                echo "<h1>Error de contraseña</h1>";
                echo "Escribiste en el login: [" . htmlspecialchars($password_recibida) . "]<br>";
                echo "En la Base de Datos hay: [" . htmlspecialchars($user['password']) . "]<br>";
                echo "<br><a href='/minimarket/public/index.php'>Volver a intentar</a>";
            }
        } else {
            echo "<h1>Error de Usuario</h1>";
            echo "El username [" . htmlspecialchars($usuario_recibido) . "] no se encontró en la base de datos.<br>";
            echo "<br><a href='/minimarket/public/index.php'>Volver a intentar</a>";
        }

    } catch (\PDOException $e) {
        die("Error en la consulta de login: " . $e->getMessage());
    }

} else {
    header('Location: /minimarket/public/index.php');
    exit;
}
?>