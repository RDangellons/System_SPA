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
                    : "img/default.jpg"; // crea esta imagen en /public/img

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