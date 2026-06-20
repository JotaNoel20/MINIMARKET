<?php
require_once __DIR__ . '/../aplicacion/configuracion/conexion.php';

$password = '123456';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Contraseña: $password<br>";
echo "Hash generado: $hash<br><br>";

// Actualizar admin
$sql = "UPDATE usuario SET password = :hash WHERE username = 'admin'";
$stmt = $pdo->prepare($sql);
$stmt->execute(['hash' => $hash]);

echo "✅ Contraseña de admin actualizada con el nuevo hash.<br>";
echo "Prueba ahora con: admin / 123456";