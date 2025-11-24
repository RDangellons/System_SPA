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

<script>
    // ===== MENÚ HAMBURGUESA =====
    const header = document.getElementById('main-header');
    const btnToggle = document.querySelector('.nav-toggle');

    btnToggle.addEventListener('click', () => {
        header.classList.toggle('nav-open');
    });

    // Si quieres cerrar el menú al hacer click en un link:
    const navLinks = document.querySelectorAll('.nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            header.classList.remove('nav-open');
        });
    });

    // ===== CONSUMIR ENDPOINT DE SERVICIOS =====
    const ENDPOINT = "../api/servicios/listar.php";
    const contenedor = document.getElementById("lista-servicios");
    const msgEstado = document.getElementById("msg-estado");

    async function cargarServicios() {
        try {
            const resp = await fetch(ENDPOINT);
            const json = await resp.json();

            if (!json.ok) {
                msgEstado.textContent = "Hubo un error al cargar los servicios. Intenta más tarde.";
                return;
            }

            const servicios = json.data;

            if (!servicios || servicios.length === 0) {
                msgEstado.textContent = "Por el momento no hay servicios disponibles.";
                return;
            }

            msgEstado.textContent = "";

            servicios.forEach(serv => {
                let imgRuta = (serv.imagen && serv.imagen !== "")
                    ? serv.imagen
                    : "img/servicio-default.jpg"; // crea esta imagen en /public/img

                const card = document.createElement("div");
                card.classList.add("card-servicio");

                card.innerHTML = `
                    <img src="${imgRuta}" alt="Imagen de ${serv.nombre}" class="img-servicio">
                    <h3>${serv.nombre}</h3>
                    <p>${serv.descripcion ?? "Sin descripción disponible."}</p>
                    <p class="duracion">Duración: ${serv.duracion_min} min</p>
                    <p class="precio">$${parseFloat(serv.precio).toFixed(2)} MXN</p>
                    <a href="agendar.php?servicio=${serv.id}" class="btn-agendar">Agendar</a>
                `;

                contenedor.appendChild(card);
            });

        } catch (error) {
            console.error(error);
            msgEstado.textContent = "No se pudo conectar con el servidor.";
        }
    }

    cargarServicios();
</script>

</body>
</html>
