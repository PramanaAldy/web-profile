<?php
require_once __DIR__ . '/../db.php';

// Sesi lama dibuat saat login masih menerima password bawaan.
session_name('WebProfileAdmin');
if (!session_start([
    'use_strict_mode' => 1,
    'use_only_cookies' => 1,
    'cookie_path' => rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/',
    'cookie_httponly' => true,
    'cookie_secure' => !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off',
    'cookie_samesite' => 'Lax',
])) {
    http_response_code(500);
    exit('Sesi admin gagal dibuat.');
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function isLoggedIn(): bool {
    if (!isset($_SESSION['admin_id'], $_SESSION['admin_password_fingerprint'])) {
        return false;
    }

    $stmt = getDB()->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $passwordHash = $stmt->fetchColumn();

    // Rotasi password langsung membatalkan sesi yang memakai hash sebelumnya.
    if ($passwordHash && hash_equals($_SESSION['admin_password_fingerprint'], hash('sha256', $passwordHash))) {
        return true;
    }

    $_SESSION = [];
    return false;
}

function csrfField(): string {
    $_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

function verifyCsrfToken(): void {
    $token = $_POST['csrf_token'] ?? null;
    if (!is_string($token) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        exit('Formulir kedaluwarsa. Muat ulang halaman dan coba lagi.');
    }
}

function logout(): void {
    $_SESSION = [];
    $cookie = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => $cookie['path'],
        'domain' => $cookie['domain'],
        'secure' => $cookie['secure'],
        'httponly' => $cookie['httponly'],
        'samesite' => $cookie['samesite'],
    ]);
    session_destroy();
    header('Location: login.php', true, 303);
    exit;
}
