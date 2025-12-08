const selectHora = document.getElementById('hora');
const inputFecha = document.getElementById('fecha');

// Lista de horarios disponibles que USTEDES manejan
// Solo edita este arreglo para cambiar horarios o agregar más
const HORARIOS_DISPONIBLES = [
    { label: "10:00 am", value: "10:00" },
    { label: "11:00 am", value: "11:00" },
    { label: "12:00 pm", value: "12:00" },
    { label: "1:00 pm",  value: "13:00" },
    { label: "3:00 pm",  value: "15:00" },
    { label: "5:00 pm",  value: "17:00" }
];

// Opcional: desde qué día se puede agendar (hoy en adelante)
function configurarMinFecha() {
    if (!inputFecha) return;
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, "0");
    const dd = String(hoy.getDate()).padStart(2, "0");
    inputFecha.min = `${yyyy}-${mm}-${dd}`;
}

// Llenar el select de horas con los horarios definidos
function poblarHoras() {
    if (!selectHora) return;

    // Mantener la primera opción
    selectHora.innerHTML = '<option value="">Selecciona una hora</option>';

    HORARIOS_DISPONIBLES.forEach(h => {
        const opt = document.createElement("option");
        opt.value = h.value;
        opt.textContent = h.label;
        selectHora.appendChild(opt);
    });
}

configurarMinFecha();
poblarHoras();




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

// ===== LÓGICA DE AGENDADO =====
const ENDPOINT_SERVICIOS = "../api/servicios/listar.php";
const ENDPOINT_CITAS = "../api/citas/crear.php"; // lo haremos después

const main = document.querySelector('main');
const servicioId = main?.getAttribute('data-servicio-id') || "";

const resumenBox = document.getElementById('resumen-servicio');
const nombreServEl = resumenBox.querySelector('.servicio-nombre');
const detalleServEl = resumenBox.querySelector('.servicio-detalle');
const precioServEl = resumenBox.querySelector('.servicio-precio');

const form = document.getElementById('form-cita');
const metodoRadios = document.querySelectorAll('input[name="metodo_pago"]');
const grupoComprobante = document.getElementById('grupo-comprobante');
const mensajeForm = document.getElementById('mensaje-form');

// Mostrar u ocultar campo de comprobante según método de pago
function actualizarComprobante() {
    const metodo = [...metodoRadios].find(r => r.checked)?.value;
    if (metodo === 'transferencia') {
        grupoComprobante.classList.remove('oculto');
    } else {
        grupoComprobante.classList.add('oculto');
    }
}

metodoRadios.forEach(radio => {
    radio.addEventListener('change', actualizarComprobante);
});

actualizarComprobante();

// Cargar datos del servicio si hay ID
async function cargarServicioSeleccionado() {
    if (!servicioId) return;

    try {
        const resp = await fetch(ENDPOINT_SERVICIOS);
        const json = await resp.json();

        if (!json.ok) return;

        const lista = json.data || [];
        const serv = lista.find(s => String(s.id) === String(servicioId));

        if (!serv) return;

        nombreServEl.textContent = serv.nombre;
        detalleServEl.textContent = `${serv.duracion_min} min · ${serv.descripcion ?? ""}`;
        precioServEl.textContent = `$${parseFloat(serv.precio).toFixed(2)} MXN`;

        resumenBox.classList.remove('oculto');
    } catch (error) {
        console.error("Error al cargar servicio:", error);
    }
}

cargarServicioSeleccionado();

// Manejo de envío del formulario
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    mensajeForm.textContent = "Enviando tu cita...";

    const formData = new FormData(form);

    try {
        const resp = await fetch(ENDPOINT_CITAS, {
            method: "POST",
            body: formData
        });

        const json = await resp.json();

        if (!json.ok) {
            mensajeForm.textContent = json.error || "No se pudo registrar la cita. Intenta más tarde.";
            mensajeForm.style.color = "#b70000";
            return;
        }

        mensajeForm.textContent = "Tu cita se registró correctamente. Te contactaremos para confirmarla.";
        mensajeForm.style.color = "#1b7a32";

        form.reset();
        actualizarComprobante();
    } catch (error) {
        console.error(error);
        mensajeForm.textContent = "Error de conexión. Intenta nuevamente.";
        mensajeForm.style.color = "#b70000";
    }
});
