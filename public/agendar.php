<?php
// public/agendar.php
$servicioId = isset($_GET['servicio']) ? (int) $_GET['servicio'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar cita | Spa Mamá</title>

    <link rel="stylesheet" href="css/agendar.css">
</head>
<body>

<header id="main-header">
    <div class="header-inner">
        <div class="brand">
            <img src="img/logo.jpg" alt="Logo Spa Mamá">
            <div class="brand-text">
                <h1>Spa Mamá</h1>
                <p>Bienestar, relajación y belleza</p>
            </div>
        </div>

        <button class="nav-toggle" aria-label="Abrir menú de navegación">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <nav class="nav">
            <a href="index.php">Inicio</a>
            <a href="servicios.php">Servicios</a>
            <a href="agendar.php" class="activo">Agendar cita</a>
            <a href="mi-cuenta.php">Mi cuenta</a>
            <a href="contacto.php">Contacto</a>
        </nav>
    </div>
</header>

<main data-servicio-id="<?php echo htmlspecialchars($servicioId); ?>">
    <section class="encabezado-agendar">
        <h2>Agendar cita</h2>
        <p>Completa tus datos y elige el horario que mejor te acomode.</p>
    </section>

    <!-- Resumen del servicio seleccionado -->
    <section id="resumen-servicio" class="resumen-servicio oculto">
        <h3>Servicio seleccionado</h3>
        <p class="servicio-nombre"></p>
        <p class="servicio-detalle"></p>
        <p class="servicio-precio"></p>
    </section>

    <!-- Formulario de cita -->
    <section class="formulario-cita">
        <form id="form-cita" enctype="multipart/form-data">
            <input type="hidden" name="servicio_id" id="servicio_id" value="<?php echo htmlspecialchars($servicioId); ?>">

            <div class="campo">
                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Ej. Ana López">
            </div>

            <div class="campo">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" required placeholder="Ej. 55 1234 5678">
            </div>

            <div class="campo">
                <label for="correo">Correo electrónico (opcional)</label>
                <input type="email" id="correo" name="correo" placeholder="Ej. ana@mail.com">
            </div>

                <div class="grupo-fecha-hora">
    <div class="campo">
        <label for="fecha">Fecha</label>
        <input type="date" id="fecha" name="fecha" required>
    </div>

    <div class="campo">
        <label for="hora">Hora disponible</label>
        <select id="hora" name="hora" required>
            <option value="">Selecciona una hora</option>
            <!-- Las opciones se llenan desde JS -->
        </select>
    </div>
</div>

            </div>

            <div class="campo">
                <label>Método de pago</label>
                <div class="opciones-pago">
                    <label>
                        <input type="radio" name="metodo_pago" value="efectivo" checked>
                        Efectivo en el spa
                    </label>
                    <label>
                        <input type="radio" name="metodo_pago" value="transferencia">
                        Transferencia bancaria
                    </label>
                </div>
            </div>

            <div class="campo oculto" id="grupo-comprobante">
                <label for="comprobante">Comprobante de pago (foto o captura)</label>
                <input type="file" id="comprobante" name="comprobante" accept="image/*,application/pdf">
                <p class="ayuda-campo">
                    Puedes enviar la foto del comprobante si realizas la transferencia antes de tu cita.
                </p>
            </div>

            <div class="campo">
                <label for="notas">Comentarios (opcional)</label>
                <textarea id="notas" name="notas" rows="3" placeholder="¿Algo que debamos considerar? (ej. dolor en espalda, embarazo, etc.)"></textarea>
            </div>

            <div class="campo aviso-salud">
                <p>
                    Antes de tu cita, te haremos algunas preguntas de salud para asegurarnos de que el servicio
                    sea seguro para ti 🧡
                </p>
            </div>

            <div class="acciones-form">
                <button type="submit" class="btn-primary">Confirmar cita</button>
                <a href="servicios.php" class="btn-ghost">Volver a servicios</a>
            </div>

            <p id="mensaje-form" class="mensaje-form"></p>
        </form>
    </section>
</main>

<footer>
    <p>© <?php echo date("Y"); ?> Spa Mamá · Agenda de citas</p>
</footer>

<script src="js/agendar.js"></script>
</body>
</html>
