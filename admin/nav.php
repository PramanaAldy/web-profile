<?php
require_once __DIR__ . '/auth.php';
requireLogin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Panel' ?> — Web Profile</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Space Grotesk', system-ui, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Admin Header -->
    <header class="bg-[#0B132B] text-white border-b border-white/10 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-[#C9182B] text-white flex items-center justify-center font-mono font-bold text-xs">
                    AKP
                </span>
                <div>
                    <span class="font-bold text-sm tracking-tight text-white block">PANEL KONTROL WEB PROFILE</span>
                    <span class="text-[10px] text-white/50 font-mono">Logged in as: <?= htmlspecialchars($_SESSION['admin_user'] ?? 'aldy') ?></span>
                </div>
            </div>

            <div class="flex items-center gap-4 text-xs font-mono">
                <a href="/WebProfile/" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-white/10 hover:bg-white/20 text-white transition-colors">
                    <i class="fas fa-external-link-alt text-[10px]"></i>
                    <span>Lihat Web Utama</span>
                </a>
                <form method="POST" action="logout.php">
                    <?= csrfField() ?>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-red-600/80 hover:bg-red-600 text-white transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="max-w-7xl mx-auto px-6 flex items-center gap-1 border-t border-white/5 overflow-x-auto text-xs font-mono font-medium">
            <a href="dashboard.php" class="px-4 py-2.5 border-b-2 transition-colors <?= $currentPage === 'dashboard.php' ? 'border-[#C9182B] text-white' : 'border-transparent text-white/60 hover:text-white' ?>">
                <i class="fas fa-chart-line mr-1.5"></i> Ringkasan
            </a>
            <a href="profile.php" class="px-4 py-2.5 border-b-2 transition-colors <?= $currentPage === 'profile.php' ? 'border-[#C9182B] text-white' : 'border-transparent text-white/60 hover:text-white' ?>">
                <i class="fas fa-user-edit mr-1.5"></i> Edit Profil
            </a>
            <a href="services.php" class="px-4 py-2.5 border-b-2 transition-colors <?= $currentPage === 'services.php' ? 'border-[#C9182B] text-white' : 'border-transparent text-white/60 hover:text-white' ?>">
                <i class="fas fa-concierge-bell mr-1.5"></i> Layanan
            </a>
            <a href="projects.php" class="px-4 py-2.5 border-b-2 transition-colors <?= $currentPage === 'projects.php' ? 'border-[#C9182B] text-white' : 'border-transparent text-white/60 hover:text-white' ?>">
                <i class="fas fa-laptop-code mr-1.5"></i> Portofolio
            </a>
            <a href="experience.php" class="px-4 py-2.5 border-b-2 transition-colors <?= $currentPage === 'experience.php' ? 'border-[#C9182B] text-white' : 'border-transparent text-white/60 hover:text-white' ?>">
                <i class="fas fa-briefcase mr-1.5"></i> Pengalaman
            </a>
            <a href="skills.php" class="px-4 py-2.5 border-b-2 transition-colors <?= $currentPage === 'skills.php' ? 'border-[#C9182B] text-white' : 'border-transparent text-white/60 hover:text-white' ?>">
                <i class="fas fa-tools mr-1.5"></i> Keahlian
            </a>
            <a href="messages.php" class="px-4 py-2.5 border-b-2 transition-colors <?= $currentPage === 'messages.php' ? 'border-[#C9182B] text-white' : 'border-transparent text-white/60 hover:text-white' ?>">
                <i class="fas fa-envelope mr-1.5"></i> Pesan Masuk
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8">
