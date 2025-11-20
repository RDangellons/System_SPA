<?php
// api/servicios/listar.php

require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = getDB();

    // Obtenemos los servicios activos ordenados
    $sql = "SELECT id, nombre, descripcion, duracion_min, precio, imagen, es_destacado
            FROM servicios
            WHERE activo = 1
            ORDER BY es_destacado DESC, nombre ASC";

    $stmt = $db->query($sql);
    $servicios = $stmt->fetchAll();

    echo json_encode([
        'ok'   => true,
        'data' => $servicios
    ]);
} catch (Exception $e) {
    if (APP_DEBUG) {
        error_log('Error en listar servicios: ' . $e->getMessage());
    }

    http_response_code(500);
    echo json_encode([
        'ok'    => false,
        'error' => 'Error al obtener la lista de servicios.'
    ]);
}
