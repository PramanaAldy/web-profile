<?php
require_once __DIR__ . '/auth.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    $username = is_string($_POST['username'] ?? null) ? trim($_POST['username']) : '';
    $password = $_POST['password'] ?? null;

    if ($username !== '' && is_string($password) && $password !== '') {
        $db = getDB();
        $stmt = $db->prepare('SELECT id, username, password FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Tolak hash bawaan dari versi database.sql sebelumnya.
        $legacyHash = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRpFa.Be';
        if ($user && $user['password'] !== $legacyHash && $password !== 'admin123'
            && password_verify($password, $user['password'])) {
            if (!session_regenerate_id(true)) {
                http_response_code(500);
                exit('Sesi admin gagal diperbarui.');
            }
            $_SESSION = [];
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_user'] = $user['username'];
            $_SESSION['admin_password_fingerprint'] = hash('sha256', $user['password']);
            header('Location: dashboard.php', true, 303);
            exit;
        } else {
            $error = 'Username atau password tidak sesuai.';
        }
    } else {
        $error = 'Harap isi semua kolom login.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Web Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Space Grotesk', system-ui, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-[#0B132B] text-slate-100 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full bg-[#1C2541] border border-white/10 rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-[#C9182B] text-white flex items-center justify-center font-mono font-bold text-xl mx-auto mb-4 shadow-lg">
                AKP
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Login Panel Admin</h1>
            <p class="text-xs text-slate-400 mt-1 font-mono">Kelola tampilan web profile & CV otomatis</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="mb-6 p-3 rounded-lg bg-red-950 border border-red-700 text-red-200 text-xs flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-red-400"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="space-y-4">
            <?= csrfField() ?>
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-300 mb-2 font-semibold">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i class="fas fa-user text-xs"></i></span>
                    <input type="text" name="username" required autocomplete="username" value="<?= htmlspecialchars($username, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#0B132B] border border-white/15 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#C9182B] transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-300 mb-2 font-semibold">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i class="fas fa-lock text-xs"></i></span>
                    <input type="password" name="password" required autocomplete="current-password"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#0B132B] border border-white/15 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#C9182B] transition-colors">
                </div>
            </div>

            <button type="submit" class="w-full mt-6 py-3 px-4 rounded-xl bg-[#C9182B] hover:bg-[#A51423] text-white text-sm font-semibold tracking-wide shadow-lg transition-colors flex items-center justify-center gap-2">
                <span>Masuk ke Dashboard</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-white/10 text-center">
            <a href="/WebProfile/" class="text-xs text-slate-400 hover:text-white font-mono transition-colors">
                &larr; Kembali ke Halaman Utama
            </a>
        </div>
    </div>

</body>
</html>
