<?php
// admin/login_post.php
session_start();

require_once __DIR__ . '/../api/db.php';
require_once __DIR__ . '/../config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$usuario = trim($_POST['usuario'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($usuario === '' || $password === '') {
    header("Location: login.php?err=1");
    exit;
}

try {
    $db = getDB();

    // Ajusta nombres si tu tabla admins usa otros campos (ej. username/pass_hash)
   $stmt = $db->prepare("SELECT id, usuario, password_hash, activo FROM admins WHERE usuario = :u LIMIT 1");
$stmt->execute([':u' => $usuario]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin || (int)$admin['activo'] !== 1) {
    header("Location: login.php?err=1");
    exit;
}

$hash = (string)$admin['password_hash'];

if (!password_verify($password, $hash)) {
    header("Location: login.php?err=1");
    exit;
}

                                                                                                                                  
    // Login OK
    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['admin_usuario'] = (string)$admin['usuario'];

    header("Location: citas_hoy.php");
    exit;

} catch (Exception $e) {
    header("Location: login.php?err=1");
    exit;
}
