// ===== MENÚ HAMBURGUESA =====
const header = document.getElementById('main-header');
const toggleBtn = document.querySelector('.nav-toggle');

if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        header.classList.toggle('nav-open');
    });
}

const navLinks = document.querySelectorAll('.nav a');
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        header.classList.remove('nav-open');
    });
});

// ===== SERVICIOS DESTACADOS DESDE LA API =====
const ENDPOINT_SERVICIOS = "../api/servicios/listar.php";
const contenedorDestacados = document.getElementById("servicios-destacados");
const serviciosMsg = document.getElementById("servicios-msg");

async function cargarServiciosDestacados() {
    if (!contenedorDestacados) return;

    try {
        const resp = await fetch(ENDPOINT_SERVICIOS);
        const json = await resp.json();

        if (!json.ok) {
            serviciosMsg.textContent = "No fue posible cargar los servicios destacados.";
            return;
        }

        const servicios = json.data || [];

        if (servicios.length === 0) {
            serviciosMsg.textContent = "Pronto estaremos agregando nuevos servicios.";
            return;
        }

        serviciosMsg.textContent = "";

        // Mostrar máximo 3
        const destacados = servicios.slice(0, 3);

        destacados.forEach(serv => {
            const card = document.createElement("div");
            card.classList.add("card-servicio");

            card.innerHTML = `
                <h3>${serv.nombre}</h3>
                <p>${serv.descripcion ?? "Servicio de spa."}</p>
                <p class="duracion">Duración: ${serv.duracion_min} min</p>
                <p class="precio">$${parseFloat(serv.precio).toFixed(2)} MXN</p>
                <a href="agendar.php?servicio=${serv.id}">Agendar</a>
            `;

            contenedorDestacados.appendChild(card);
        });

    } catch (error) {
        console.error(error);
        serviciosMsg.textContent = "No se pudo conectar con el servidor.";
    }
}

cargarServiciosDestacados();
