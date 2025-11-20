<?php
// api/db.php

require_once __DIR__ . '/../config/db_config.php';

/**
 * Devuelve una instancia PDO conectada a la BD spa_mama.
 * Usa patrón singleton simple (si ya existe, reutiliza la misma).
 *
 * @return PDO
 */
function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errores como excepciones
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch asociativo por defecto
                PDO::ATTR_EMULATE_PREPARES   => false,                  // prepara sentencias reales
            ]);
        } catch (PDOException $e) {
            if (APP_DEBUG) {
                // En desarrollo: mostrar el error (para depurar)
                die('Error de conexión a la base de datos: ' . $e->getMessage());
            } else {
                // En producción: NO mostrar detalles técnicos
                error_log('DB Connection error: ' . $e->getMessage());
                die('Error de conexión a la base de datos. Intenta más tarde.');
            }
        }
    }

    return $pdo;
}
