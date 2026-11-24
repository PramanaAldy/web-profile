<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/db.php';

function initializeDatabase(): void {
    if (!preg_match('/\A[a-zA-Z0-9_]+\z/', DB_NAME)) {
        throw new RuntimeException('Nama database tidak valid.');
    }

    $db = connectDB(false);
    $db->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $db->exec('USE `' . DB_NAME . '`');
    $lock = $db->prepare('SELECT GET_LOCK(?, 10)');
    $lock->execute([hash('sha256', 'webprofile-setup:' . DB_NAME)]);
    if ((int)$lock->fetchColumn() !== 1) {
        throw new RuntimeException('Setup database sedang berjalan. Coba lagi setelah selesai.');
    }
    // MySQL releases this lock when the setup connection closes.

    $schema = file_get_contents(__DIR__ . '/database.sql');
    if ($schema === false) {
        throw new RuntimeException('database.sql tidak dapat dibaca.');
    }

    $schema = preg_replace('/^--[^\r\n]*/m', '', $schema);
    foreach (array_filter(array_map('trim', explode(';', $schema))) as $statement) {
        if (preg_match('/\AINSERT INTO (profile|skills|services)\b/i', $statement, $match)
            && $db->query('SELECT 1 FROM `' . $match[1] . '` LIMIT 1')->fetchColumn()) {
            continue;
        }
        $db->exec($statement);
    }

    echo "Database siap. Jalankan reset-admin-password.ps1 untuk mengatur password admin.\n";
}

function setAdminPassword(string $username, ?string $email): void {
    if (!preg_match('/\A[a-zA-Z0-9_.-]{1,50}\z/', $username)) {
        throw new RuntimeException('Username tidak valid.');
    }
    if ($email !== null && (strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL))) {
        throw new RuntimeException('Email admin tidak valid.');
    }
    if (stream_isatty(STDIN)) {
        throw new RuntimeException('Gunakan reset-admin-password.ps1 atau kirim password lewat STDIN.');
    }

    $line = fgets(STDIN, 75);
    $password = $line === false ? '' : rtrim($line, "\r\n");
    if (strlen($password) > 72 || strpos($password, "\0") !== false
        || !mb_check_encoding($password, 'UTF-8') || mb_strlen($password, 'UTF-8') < 15
        || trim($password) === '') {
        throw new RuntimeException('Password harus minimal 15 karakter dan maksimal 72 byte UTF-8.');
    }

    $db = connectDB();
    $stmt = $db->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $id = $stmt->fetchColumn();
    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    if ($hash === false) {
        throw new RuntimeException('Hash password gagal dibuat.');
    }

    if ($id !== false) {
        $stmt = $db->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$hash, $id]);
        if ($stmt->rowCount() !== 1) {
            throw new RuntimeException('Password admin gagal diperbarui.');
        }
    } else {
        if ($email === null) {
            throw new RuntimeException('Username tidak ditemukan. Sertakan email untuk membuat admin baru.');
        }
        $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$username, $email, $hash]);
    }

    echo "Password admin {$username} berhasil diatur.\n";
}

try {
    if ($argc === 1 || ($argc === 2 && $argv[1] === 'init')) {
        initializeDatabase();
    } elseif (($argc === 3 || $argc === 4) && $argv[1] === 'password') {
        setAdminPassword($argv[2], $argv[3] ?? null);
    } else {
        throw new RuntimeException('Pemakaian: php setup.php init atau php setup.php password <username> [email]');
    }
} catch (PDOException $e) {
    fwrite(STDERR, "Operasi database gagal. Periksa layanan MySQL, izin akun database, dan email yang sudah terdaftar.\n");
    exit(1);
} catch (RuntimeException $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}
