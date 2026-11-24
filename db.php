<?php
// ============================================
// Database Connection — PDO
// ============================================

define('DB_HOST', getenv('WEBPROFILE_DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('WEBPROFILE_DB_PORT') ?: '3306');
define('DB_NAME', getenv('WEBPROFILE_DB_NAME') ?: 'webprofile');
define('DB_USER', getenv('WEBPROFILE_DB_USER') ?: 'root');
define('DB_PASS', (string)getenv('WEBPROFILE_DB_PASSWORD'));

function connectDB(bool $selectDatabase = true): PDO {
    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8mb4';
    if ($selectDatabase) {
        $dsn .= ';dbname=' . DB_NAME;
    }

    return new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = connectDB();
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Database connection failed.']));
        }
    }
    return $pdo;
}
