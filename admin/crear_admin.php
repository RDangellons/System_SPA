<?php
require_once __DIR__ . '/../api/db.php';

$usuario = 'Admin';          // cámbialo si quieres
$pass    = 'Lilia2423';          // cámbialo por una contraseña real
$nombre  = 'Lilia Dominguez';

$hash = password_hash($pass, PASSWORD_DEFAULT);

$db = getDB();
$stmt = $db->prepare("INSERT INTO admins (usuario, password_hash, nombre) VALUES (:u, :p, :n)");
$stmt->execute([
  ':u' => $usuario,
  ':p' => $hash,
  ':n' => $nombre
]);

echo "Admin creado ✅ Usuario: $usuario | Password: $pass";
