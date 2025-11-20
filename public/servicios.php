<?php
// public/servicios.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios | Spa Mamá</title>

    <style>
        :root {
            --color-bg:        #FFE1AF;  /* crema suave */
            --color-card:      #E2B59A;  /* durazno café */
            --color-primary:   #B77466;  /* terracota */
            --color-accent:    #957C62;  /* café suave */
            --color-text:      #5A4634;  /* café oscuro */
            --color-header-bg: #FFE1AF;
            --color-header-border: rgba(149,124,98,0.3);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--color-bg);
            font-family: "Poppins", Arial, sans-serif;
            color: var(--color-text);
        }

        /* ===== HEADER CON LOGO + MENÚ ===== */

        header {
            position: sticky;
            top: 0;
            z-index: 20;
            background: var(--color-header-bg);
            border-bottom: 1px solid var(--color-header-border);
            padding: 10px 16px;
        }

        .header-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand img {
            height: 70px;
            width: 70px;
            object-fit: contain;
            border-radius: 50%;
        }

        .brand-text h1 {
            font-size: 1.1rem;
            color: var(--color-accent);
            font-weight: 700;
        }

        .brand-text p {
            font-size: 0.75rem;
            color: var(--color-text);
            opacity: 0.8;
        }

        /* NAV ESCRITORIO */

        .nav {
            display: flex;
            gap: 10px;
            font-size: 0.9rem;
        }

        .nav a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 999px;
            color: var(--color-text);
            transition: background 0.2s ease, color 0.2s ease;
        }

        .nav a:hover {
            background: var(--color-card);
            color: #fff;
        }

        .nav a.activo {
            background: var(--color-primary);
            color: #fff;
        }

        /* BOTÓN HAMBURGUESA */

        .nav-toggle {
            display: none; /* se muestra solo en móvil */
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
        }

        .nav-toggle:focus {
            outline: 2px solid var(--color-primary);
            border-radius: 6px;
        }

        .nav-toggle .bar {
            display: block;
            width: 22px;
            height: 2px;
            border-radius: 999px;
            background: var(--color-primary);
            margin: 4px 0;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        /* ANIMACIÓN DEL ICONO CUANDO ESTÁ ABIERTO */
        .nav-open .nav-toggle .bar:nth-child(1) {
            transform: translateY(6px) rotate(45deg);
        }
        .nav-open .nav-toggle .bar:nth-child(2) {
            opacity: 0;
        }
        .nav-open .nav-toggle .bar:nth-child(3) {
            transform: translateY(-6px) rotate(-45deg);
        }

        /* ===== CONTENIDO PRINCIPAL ===== */

        main {
            max-width: 1100px;
            margin: 30px auto 40px auto;
            padding: 0 16px;
        }

        .titulo-pagina {
            text-align: center;
            margin-bottom: 8px;
            color: var(--color-accent);
            font-weight: 700;
        }

        .subtitulo {
            text-align: center;
            font-size: 0.9rem;
            margin-bottom: 24px;
            opacity: 0.85;
        }

        .servicios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }

        .card-servicio {
            background: var(--color-card);
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 18px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-servicio:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        }

        .img-servicio {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 12px;
        }

        .card-servicio h3 {
            margin-bottom: 6px;
            color: var(--color-accent);
            font-weight: 600;
            font-size: 1.05rem;
        }

        .card-servicio p {
            margin: 4px 0;
            font-size: 0.9rem;
        }

        .duracion {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .precio {
            font-weight: 700;
            color: var(--color-primary);
            margin-top: 8px;
        }

        .btn-agendar {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 14px;
            background: var(--color-primary);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.2s ease, transform 0.1s ease;
        }

        .btn-agendar:hover {
            background: var(--color-accent);
            transform: translateY(-1px);
        }

        .mensaje-estado {
            text-align: center;
            margin-top: 20px;
            font-size: 0.95rem;
        }

        /* ===== RESPONSIVE ===== */

        @media (max-width: 768px) {
            .header-inner {
                justify-content: space-between;
            }

            /* Mostrar botón hamburguesa en móvil */
            .nav-toggle {
                display: block;
            }

            /* Menú oculto por defecto en móvil */
            .nav {
                display: none;
                position: absolute;
                top: 60px; /* debajo del header */
                right: 0;
                left: 0;
                background: var(--color-header-bg);
                border-bottom: 1px solid var(--color-header-border);
                padding: 10px 16px 12px;
                flex-direction: column;
                gap: 6px;
            }

            /* Menú visible cuando el header tiene clase nav-open */
            .nav-open .nav {
                display: flex;
            }

            .nav a {
                padding: 8px 10px;
            }
        }
    </style>
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
