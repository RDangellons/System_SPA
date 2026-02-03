<?php
// public/index.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | Masajes</title>

    <!-- CSS de esta página -->
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<header id="main-header">
    <div class="header-inner">
        <div class="brand">
            <!-- Cambia la ruta si tu logo se llama distinto -->
            <img src="img/logo.jpg" alt="Logo Spa Mamá">
            <div class="brand-text">
                <h1>Masajes LIDOMI</h1>
            </div>
        </div>

        <!-- Botón hamburguesa (móvil) -->
        <button class="nav-toggle" aria-label="Abrir menú de navegación">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <!-- Menú -->
        <nav class="nav">
            <a href="index.php" class="activo">Inicio</a>
            <a href="servicios.php">Servicios</a>
            <a href="agendar.php">Agendar cita</a>
<!--<a href="mi-cuenta.php">Mi cuenta</a> -->
            <a href="contacto.php">Contacto</a>
            <a href="../admin/login.php">Administrador</a>
        </nav>
    </div>
</header>

<main>
    <!-- HERO PRINCIPAL -->
    <section class="hero">
        <div class="hero-text">
            <span class="hero-pill">Spa & Bienestar</span>
            <h2>Un masaje no es un lujo, Es un bienestar para tu salud.</h2>
            <!--  PROMOCION DEL MES -->
        <div class="hero-visual">
            <div class="hero-card">
                <span class="hero-tag">Promoción del mes</span>
                <h3>Masaje relajante + facial hidratante</h3>
                <p>Sesión completa para liberar tensión y devolverle luz a tu piel.</p>
                <p class="precio">$599 MXN</p>
                <p class="duracion">Duración aproximada: 90 min</p>
            </div>

            <div class="hero-bubbles">
                <div class="bubble big"></div>
                <div class="bubble medium"></div>
                <div class="bubble small"></div>
            </div>
        </div>
    </section>

            <div class="hero-badges">
                <span class="badge">Agenda en línea ahora!!</span>
                <span class="badge">Ambiente relajante</span>
                <span class="badge">Atención personalizada</span>
            </div>

            <!-- <div class="hero-actions">
                <a href="agendar.php" class="btn-primary">Agendar mi cita</a>
                <a href="servicios.php" class="btn-ghost">Ver servicios</a>
            </div>
-->

            <p class="hero-note">
                Antes de tu masaje, te pediremos un breve cuestionario de salud para cuidar de ti de forma segura 🧡
            </p>
        </div>

         <!-- SECCIÓN: CÓMO FUNCIONA -->
    <section class="seccion pasos">
        <h3 class="seccion-title">¿Cómo funciona?</h3>
        <p class="seccion-subtitle">
            Tu bienestar en tres pasos sencillos.
        </p>

        <div class="pasos-grid">
            <div class="paso">
                <span class="paso-num">1</span>
                <h4>Elige tu servicio</h4>
                <p>Explora el catálogo, conoce los tiempos y beneficios de cada tratamiento.</p>
            </div>
            <div class="paso">
                <span class="paso-num">2</span>
                <h4>Agenda tu cita</h4>
                <p>Selecciona el día y la hora, y llena tu cuestionario de salud.</p>
            </div>
            <div class="paso">
                <span class="paso-num">3</span>
                <h4>Disfruta tu momento</h4>
                <p>Llega al spa, respira profundo y permite que  te consintamos.</p>
            </div>
        </div>
    </section>

      <!-- SECCIÓN: SERVICIOS DESTACADOS (desde la BD) -->
    <section class="seccion destacados">
        <h3 class="seccion-title">Servicios destacados</h3>
        <p class="seccion-subtitle">
            Los favoritos de nuestras clientas.
        </p>

        <div id="servicios-destacados" class="servicios-grid"></div>
        <p id="servicios-msg" class="servicios-msg"></p>
    </section>

    <!-- SECCIÓN: BENEFICIOS -->
    <section class="seccion beneficios">
        <h3 class="seccion-title">Una experiencia pensada para ti</h3>
        <p class="seccion-subtitle">
            Cada detalle del spa está diseñado para que te sientas tranquila, cuidada y escuchada.
        </p>

        <div class="beneficios-grid">
            <article class="beneficio-card">
                <h4>Ambiente cálido y acogedor</h4>
                <p>
                    Aromas suaves, música relajante y una atmósfera íntima para desconectar del ritmo diario.
                </p>
            </article>

            <article class="beneficio-card">
                <h4>Atención profesional</h4>
                <p>
                    Tratamientos realizados con conocimientos, cuidado y sensibilidad, respetando tu salud y bienestar.
                </p>
            </article>

            <article class="beneficio-card">
                <h4>Citas fáciles de gestionar</h4>
                <p>
                    Agenda tu cita en línea, recibe confirmación y elige el método de pago que más te acomode.
                </p>
            </article>
        </div>
    </section>

   
  

    <!-- SECCIÓN: CTA FINAL -->
    <section class="seccion cta-final">
        <div class="cta-inner">
            <h3>Regálate el cuidado que mereces</h3>
            <p>
                Agenda tu cita en línea y vive una experiencia de spa cálida, cercana y profesional.
            </p>
            <a href="agendar.php" class="btn-primary">Quiero agendar ahora</a>
        </div>
    </section>
</main>

<footer>
    <p>© <?php echo date("Y"); ?> Spa LIADOMI · Todos los derechos reservados</p>
</footer>

<!-- JS de esta página -->
<script src="js/index.js"></script>
</body>
</html>
