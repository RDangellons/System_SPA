const buscador = document.getElementById('buscador');
const filtroEstado = document.getElementById('filtroEstado');
const tabla = document.getElementById('tablaCitas');

function aplicarFiltros() {
  if (!tabla) return;

  const q = (buscador?.value || '').toLowerCase().trim();
  const estado = (filtroEstado?.value || 'todos').toLowerCase();

  const rows = tabla.querySelectorAll('tbody tr');

  rows.forEach(tr => {
    const blob = (tr.getAttribute('data-search') || '').toLowerCase();
    const est = (tr.getAttribute('data-estado') || '').toLowerCase();

    const okSearch = q === '' || blob.includes(q);
    const okEstado = estado === 'todos' || est === estado;

    tr.style.display = (okSearch && okEstado) ? '' : 'none';
  });
}

buscador?.addEventListener('input', aplicarFiltros);
filtroEstado?.addEventListener('change', aplicarFiltros);



// Modal detalle
const modal = document.getElementById('modal');
const modalClose = document.getElementById('modalClose');
const mId = document.getElementById('mId');
const mCreado = document.getElementById('mCreado');
const mNotas = document.getElementById('mNotas');



const ENDPOINT_CONFIRMAR = "../api/citas/confirmar.php";
const ENDPOINT_CANCELAR  = "../api/citas/cancelar.php";

async function actualizarEstadoCita(id, nuevoEstado) {
  const badge = document.getElementById(`estado-${id}`);
  if (badge) badge.textContent = nuevoEstado;

  // actualizar dataset del <tr> para que el filtro por estado siga funcionando
  const tr = badge?.closest('tr');
  if (tr) tr.setAttribute('data-estado', nuevoEstado);

  // re-aplicar filtros (por si estaba filtrado por estado)
  aplicarFiltros();
}

async function postEstado(endpoint, id) {
  const fd = new FormData();
  fd.append("id", id);

  const resp = await fetch(endpoint, { method: "POST", body: fd });
  return await resp.json();
}


document.addEventListener('click', (e) => {
  const btn = e.target.closest('.btn-ver');
  if (!btn) return;

  const id = btn.getAttribute('data-id') || '—';
  const creado = btn.getAttribute('data-creado') || '—';
  const notas = btn.getAttribute('data-notas') || '—';

  mId.textContent = id;
  mCreado.textContent = creado;
  mNotas.textContent = notas && notas.trim() ? notas : '—';

  modal.classList.add('open');
});

modalClose?.addEventListener('click', () => modal.classList.remove('open'));
modal?.addEventListener('click', (e) => {
  if (e.target === modal) modal.classList.remove('open');
});


document.addEventListener('click', async (e) => {
  const btnOk = e.target.closest('.btn-ok');
  const btnBad = e.target.closest('.btn-bad');

  if (!btnOk && !btnBad) return;

  const id = (btnOk || btnBad).getAttribute('data-id');

  // Confirmación rápida para cancelar
  if (btnBad) {
    const seguro = confirm("¿Seguro que quieres CANCELAR esta cita?");
    if (!seguro) return;
  }

  try {
    (btnOk || btnBad).disabled = true;
    (btnOk || btnBad).textContent = "Procesando...";

    const endpoint = btnOk ? ENDPOINT_CONFIRMAR : ENDPOINT_CANCELAR;
    const json = await postEstado(endpoint, id);

    if (!json.ok) {
      alert(json.error || "No se pudo actualizar la cita.");
      return;
    }

    await actualizarEstadoCita(id, json.data.estado);

  } catch (err) {
    console.error(err);
    alert("Error de conexión.");
  } finally {
    // Restaurar texto
    if (btnOk) {
      btnOk.disabled = false;
      btnOk.textContent = "Confirmar";
    }
    if (btnBad) {
      btnBad.disabled = false;
      btnBad.textContent = "Cancelar";
    }
  }
});

// Inicial
aplicarFiltros();
