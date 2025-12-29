<?php
// admin/login.php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: citas_hoy.php");
    exit;
}

$err = $_GET['err'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin | Iniciar sesión</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>

<main class="wrap">
  <section class="card">
    <div class="brand">
      <img src="../public/img/logo.jpg" alt="Logo">
      <div>
        <h1>Admin · Spa Mamá</h1>
        <p>Acceso para administración</p>
      </div>
    </div>

    <?php if ($err): ?>
      <p class="msg error">Usuario o contraseña incorrectos.</p>
    <?php endif; ?>

    <form method="POST" action="login_post.php" class="form">
      <div class="campo">
        <label for="usuario">Usuario</label>
        <input id="usuario" name="usuario" required autocomplete="username" />
      </div>

      <div class="campo">
        <label for="password">Contraseña</label>
        <input id="password" type="password" name="password" required autocomplete="current-password" />
      </div>

      <button class="btn" type="submit">Entrar</button>
    </form>

  </section>
</main>

</body>
</html>
