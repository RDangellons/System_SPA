<?php
// api/citas/crear.php

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../config/db_config.php';

header('Content-Type: application/json; charset=utf-8');

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'error' => 'Método no permitido'
    ]);
    exit;
}

try {
    $db = getDB();

    // 1️⃣ Datos del formulario
    $servicio_id = isset($_POST['servicio_id']) ? (int) $_POST['servicio_id'] : null;
    $nombre      = trim($_POST['nombre'] ?? '');
    $telefono    = trim($_POST['telefono'] ?? '');
    $correo      = trim($_POST['correo'] ?? '');
    $fecha       = trim($_POST['fecha'] ?? '');
    $hora        = trim($_POST['hora'] ?? '');
    $metodo_pago = trim($_POST['metodo_pago'] ?? 'efectivo'); // efectivo | transferencia
    $notas       = trim($_POST['notas'] ?? '');

    // 2️⃣ Validaciones básicas
    if (!$servicio_id || $nombre === '' || $telefono === '' || $fecha === '' || $hora === '') {
        http_response_code(400);
        echo json_encode([
            'ok' => false,
            'error' => 'Faltan datos obligatorios.'
        ]);
        exit;
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        http_response_code(400);
        echo json_encode([
            'ok' => false,
            'error' => 'Fecha inválida.'
        ]);
        exit;
    }

    if (!preg_match('/^\d{2}:\d{2}$/', $hora)) {
        http_response_code(400);
        echo json_encode([
            'ok' => false,
            'error' => 'Hora inválida.'
        ]);
        exit;
    }

    // 3️⃣ Obtener precio del servicio para total_cita / saldo_pendiente
    $stmtServ = $db->prepare("SELECT precio FROM servicios WHERE id = :id AND activo = 1");
    $stmtServ->execute([':id' => $servicio_id]);
    $servData = $stmtServ->fetch(PDO::FETCH_ASSOC);

    if (!$servData) {
        http_response_code(400);
        echo json_encode([
            'ok' => false,
            'error' => 'El servicio seleccionado no existe o está inactivo.'
        ]);
        exit;
    }

    $precioServicio = (float) $servData['precio'];

    // 4️⃣ Empezamos transacción
    $db->beginTransaction();

    // 4.1 Buscar o crear cliente por teléfono
    $stmtCli = $db->prepare("SELECT id FROM clientes WHERE telefono = :tel LIMIT 1");
    $stmtCli->execute([':tel' => $telefono]);
    $cliente = $stmtCli->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        $cliente_id = (int) $cliente['id'];
    } else {
        $sqlInsCli = "INSERT INTO clientes (nombre, telefono, email, primera_visita, activo)
                      VALUES (:nombre, :tel, :email, CURDATE(), 1)";
        $stmtInsCli = $db->prepare($sqlInsCli);
        $stmtInsCli->execute([
            ':nombre' => $nombre,
            ':tel'    => $telefono,
            ':email'  => $correo !== '' ? $correo : null
        ]);
        $cliente_id = (int) $db->lastInsertId();
    }

    // 4.2 Insertar cita en tabla `citas`
    // Usamos columnas: fecha, hora, estado, origen, total_cita, saldo_pendiente, notas_internas
    $saldoInicial = $precioServicio; // por defecto, todo pendiente

    $sqlCita = "INSERT INTO citas
        (cliente_id, servicio_id, fecha, hora, estado, origen, total_cita, saldo_pendiente, notas_internas)
        VALUES
        (:cliente_id, :servicio_id, :fecha, :hora, :estado, :origen, :total_cita, :saldo_pendiente, :notas)";

    $stmtCita = $db->prepare($sqlCita);
    $stmtCita->execute([
        ':cliente_id'     => $cliente_id,
        ':servicio_id'    => $servicio_id,
        ':fecha'          => $fecha,
        ':hora'           => $hora,
        ':estado'         => 'pendiente',      // se puede cambiar a confirmada desde admin
        ':origen'         => 'invitado',       // viene sin login
        ':total_cita'     => $precioServicio,
        ':saldo_pendiente'=> $saldoInicial,
        ':notas'          => $notas !== '' ? $notas : null
    ]);

    $cita_id = (int) $db->lastInsertId();

    // 4.3 Si el método es transferencia, registrar pago (pendiente de validación)
    $pago_id = null;
    $rutaComprobante = null;

    if ($metodo_pago === 'transferencia') {
        // Subir archivo de comprobante (si viene)
        if (
            isset($_FILES['comprobante']) &&
            is_uploaded_file($_FILES['comprobante']['tmp_name']) &&
            $_FILES['comprobante']['error'] === UPLOAD_ERR_OK
        ) {
            $dirBase = __DIR__ . '/../../uploads/comprobantes/';
            if (!is_dir($dirBase)) {
                mkdir($dirBase, 0775, true);
            }

            $ext = pathinfo($_FILES['comprobante']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = 'comp_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            $rutaFisica = $dirBase . $nombreArchivo;

            if (move_uploaded_file($_FILES['comprobante']['tmp_name'], $rutaFisica)) {
                // ruta relativa para guardar en BD
                $rutaComprobante = 'uploads/comprobantes/' . $nombreArchivo;
            }
        }

        // Insertar pago ligado a esta cita
        $sqlPago = "INSERT INTO pagos
            (cita_id, monto, metodo, estado, comprobante_url, referencia_cliente, notas)
            VALUES
            (:cita_id, :monto, :metodo, :estado, :comprobante_url, :referencia_cliente, :notas)";

        $stmtPago = $db->prepare($sqlPago);
        $stmtPago->execute([
            ':cita_id'          => $cita_id,
            ':monto'            => $precioServicio,
            ':metodo'           => 'transferencia',
            ':estado'           => $rutaComprobante ? 'pendiente_validacion' : 'pendiente',
            ':comprobante_url'  => $rutaComprobante,
            ':referencia_cliente' => $telefono, // puedes cambiar por algo más
            ':notas'            => $notas !== '' ? $notas : null
        ]);

        $pago_id = (int) $db->lastInsertId();
    }

    $db->commit();

    echo json_encode([
        'ok'   => true,
        'msg'  => 'Cita registrada correctamente.',
        'data' => [
            'cita_id'  => $cita_id,
            'cliente_id' => $cliente_id,
            'pago_id'  => $pago_id
        ]
    ]);

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }

    if (defined('APP_DEBUG') && APP_DEBUG) {
        error_log('Error al crear cita: ' . $e->getMessage());
    }

    http_response_code(500);
    echo json_encode([
        'ok'    => false,
        'error' => 'Ocurrió un error al registrar la cita.'
    ]);
}
