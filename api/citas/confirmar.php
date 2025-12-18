<?php
// api/citas/confirmar.php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../config/db_config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

try {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'ID inválido']);
        exit;
    }

    $db = getDB();

    $stmt = $db->prepare("UPDATE citas SET estado = 'confirmada' WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'No se encontró la cita o ya estaba igual']);
        exit;
    }

    echo json_encode(['ok' => true, 'data' => ['id' => $id, 'estado' => 'confirmada']]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error al confirmar cita']);
}
