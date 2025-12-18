<?php
// admin/citas_hoy.php

require_once __DIR__ . '/../api/db.php';
require_once __DIR__ . '/../config/db_config.php';

function e($str)
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

try {
    $db = getDB();

    // Citas de HOY (con info de cliente, servicio y pago si existe)
    date_default_timezone_set('America/Mexico_City');
    $hoy = date('Y-m-d');
    $sql = "
        SELECT
            c.id,
            c.fecha,
            c.hora,
            c.estado,
            c.origen,
            c.total_cita,
            c.saldo_pendiente,
            c.notas_internas,
            c.creado_en,

            cl.nombre AS cliente_nombre,
            cl.telefono AS cliente_telefono,
            cl.email AS cliente_email,

            s.nombre AS servicio_nombre,

            p.metodo AS pago_metodo,
            p.estado AS pago_estado,
            p.comprobante_url AS comprobante_url,
            p.monto AS pago_monto
        FROM citas c
        INNER JOIN clientes cl ON cl.id = c.cliente_id
        INNER JOIN servicios s ON s.id = c.servicio_id
        LEFT JOIN pagos p ON p.cita_id = c.id
        WHERE c.fecha = :hoy
        ORDER BY c.hora ASC
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([':hoy' => $hoy]);
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $ex) {
    $citas = [];
    $error = "No se pudo cargar la agenda de hoy.";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin | Citas de hoy</title>

    <link rel="stylesheet" href="css/citas_hoy.css">
</head>

<body>

    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <img src="./img/logo.jpg" alt="Logo">
                <div>
                    <h1>Admin · Spa Mamá</h1>
                    <p>Agenda de hoy: <strong><?php echo date('Y-m-d'); ?></strong></p>
                </div>
            </div>

            <nav class="top-actions">
                <a class="btn" href="../public/index.php">Ver sitio</a>
                <!-- más adelante ponemos login + cerrar sesión -->
            </nav>
        </div>
    </header>

    <main class="container">

        <section class="panel">
            <div class="panel-head">
                <h2>Citas de hoy</h2>

                <div class="tools">
                    <input id="buscador" type="text" placeholder="Buscar por nombre, teléfono o servicio...">
                    <select id="filtroEstado">
                        <option value="todos">Todas</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="cancelada">Cancelada</option>
                        <option value="completada">Completada</option>
                    </select>
                </div>
            </div>

            <?php if (!empty($error)): ?>
                <p class="msg error"><?php echo e($error); ?></p>
            <?php endif; ?>

            <?php if (count($citas) === 0): ?>
                <p class="msg">No hay citas registradas para hoy.</p>
            <?php else: ?>
                <div class="tabla-wrap">
                    <table class="tabla" id="tablaCitas">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Pago</th>
                                <th>Estado cita</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($citas as $c):
                                $estado = strtolower((string)$c['estado']);
                                $pagoMetodo = $c['pago_metodo'] ?? '';
                                $pagoEstado = $c['pago_estado'] ?? '';
                                $comprobante = $c['comprobante_url'] ?? '';

                                $searchBlob = strtolower(
                                    ($c['cliente_nombre'] ?? '') . ' ' .
                                        ($c['cliente_telefono'] ?? '') . ' ' .
                                        ($c['cliente_email'] ?? '') . ' ' .
                                        ($c['servicio_nombre'] ?? '') . ' ' .
                                        ($estado ?? '')
                                );
                            ?>
                                <tr
                                    data-estado="<?php echo e($estado); ?>"
                                    data-search="<?php echo e($searchBlob); ?>">
                                    <td class="hora"><?php echo e(substr((string)$c['hora'], 0, 5)); ?></td>

                                    <td class="cliente">
                                        <div class="cliente-nombre"><?php echo e($c['cliente_nombre']); ?></div>
                                        <div class="cliente-meta">
                                            <span><?php echo e($c['cliente_telefono']); ?></span>
                                            <?php if (!empty($c['cliente_email'])): ?>
                                                <span>· <?php echo e($c['cliente_email']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td class="servicio">
                                        <div class="servicio-nombre"><?php echo e($c['servicio_nombre']); ?></div>
                                        <div class="servicio-meta">
                                            Total: <strong>$<?php echo e(number_format((float)$c['total_cita'], 2)); ?></strong>
                                            · Saldo: <strong>$<?php echo e(number_format((float)$c['saldo_pendiente'], 2)); ?></strong>
                                        </div>
                                    </td>

                                    <td class="pago">
                                        <div class="badges">
                                            <span class="badge badge-metodo"><?php echo e($pagoMetodo ?: '—'); ?></span>
                                            <span class="badge badge-pago"><?php echo e($pagoEstado ?: '—'); ?></span>
                                        </div>

                                        <?php if (!empty($comprobante)): ?>
                                            <a class="link" href="../<?php echo e($comprobante); ?>" target="_blank" rel="noopener">
                                                Ver comprobante
                                            </a>
                                        <?php endif; ?>
                                    </td>

                                    <td class="estado">
                                        <span class="badge badge-estado" id="estado-<?php echo e($c['id']); ?>">
                                            <?php echo e($estado); ?>
                                        </span>
                                    </td>


                                    <td class="acciones">
                                        <button
                                            class="btn-outline btn-ver"
                                            data-id="<?php echo e($c['id']); ?>"
                                            data-notas="<?php echo e($c['notas_internas'] ?? ''); ?>"
                                            data-creado="<?php echo e($c['creado_en'] ?? ''); ?>">
                                            Ver
                                        </button>

                                        <button
                                            class="btn-mini btn-ok"
                                            data-id="<?php echo e($c['id']); ?>">
                                            Confirmar
                                        </button>

                                        <button
                                            class="btn-mini btn-bad"
                                            data-id="<?php echo e($c['id']); ?>">
                                            Cancelar
                                        </button>
                                    </td>

                                <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            <?php endif; ?>
        </section>

        <!-- Modal simple para detalles -->
        <div class="modal" id="modal">
            <div class="modal-card">
                <div class="modal-head">
                    <h3>Detalle de cita</h3>
                    <button class="modal-close" id="modalClose">✕</button>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> <span id="mId">—</span></p>
                    <p><strong>Creado en:</strong> <span id="mCreado">—</span></p>
                    <p><strong>Notas internas:</strong></p>
                    <div class="nota" id="mNotas">—</div>
                </div>
            </div>
        </div>

    </main>

    <script src="js/citas_hoy.js"></script>
</body>

</html>