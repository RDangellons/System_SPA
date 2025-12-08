<?php
// public/servicios.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/servicio.css">
    <title>Servicios | Spa Mamá</title>

  
</head>
<body>

<header id="main-header">
    <div class="header-inner">
        <div class="brand">
            <!-- 🔁 Cambia la ruta/archivo del logo si se llama distinto -->
            <img src="img/logo.jpg" alt="Logo Spa Mamá">
            <div class="brand-text">
                <h1>LIDOMI</h1>
                <p>Bienestar, relajación y belleza</p>
            </div>
        </div>

        <!-- Botón hamburguesa (solo móvil) -->
        <button class="nav-toggle" aria-label="Abrir menú de navegación">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <!-- Menú -->
        <nav class="nav">
            <a href="index.php">Inicio</a>
            <a href="servicios.php" class="activo">Servicios</a>
            <a href="agendar.php">Agendar cita</a>
            <a href="mi-cuenta.php">Mi cuenta</a>
            <a href="contacto.php">Contacto</a>
        </nav>
    </div>
</header>

<main>
    <h2 class="titulo-pagina">Nuestros servicios</h2>
    <p class="subtitulo">
        Tratamientos diseñados para que te relajes, te cuides y te sientas increíble.
    </p>

    <div id="lista-servicios" class="servicios-grid">
        <!-- Aquí se cargarán las cards de servicios con JS -->
    </div>

    <p id="msg-estado" class="mensaje-estado"></p>
</main>

<script src="js/servicios.js"></script>
    


</body>
</html>
