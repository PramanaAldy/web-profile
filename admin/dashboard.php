<?php
$pageTitle = 'Dashboard Overview';
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/../db.php';

$db = getDB();

$projectCount = (int)$db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$skillCount = (int)$db->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$expCount = (int)$db->query("SELECT COUNT(*) FROM experience")->fetchColumn();
$msgCount = (int)$db->query("SELECT COUNT(*) FROM messages")->fetchColumn();
$unreadMsgCount = (int)$db->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();

$profile = $db->query("SELECT * FROM profile LIMIT 1")->fetch();
$recentMessages = $db->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<div class="space-y-8">

    <!-- Welcome Card -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-mono font-medium mb-3">
                <span class="w-2 h-2 rounded-full bg-[#C9182B]"></span>
                <span>PANEL ADMINISTRATOR AKTIF</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Selamat Datang, <?= htmlspecialchars($profile['full_name'] ?? 'Aldy') ?></h1>
            <p class="text-sm text-slate-500 mt-1 max-w-xl">
                Semua perubahan yang Anda simpan di sini akan langsung tampil di halaman depan website dan otomatis memperbarui dokumen CV PDF saat diunduh.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="/WebProfile/" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 text-xs font-mono font-semibold transition-colors flex items-center gap-2">
                <i class="fas fa-eye"></i>
                <span>Preview Web</span>
            </a>
            <a href="/WebProfile/generate-cv.php" target="_blank" class="px-4 py-2.5 rounded-xl bg-[#C9182B] text-white hover:bg-[#A51423] text-xs font-mono font-semibold transition-colors flex items-center gap-2">
                <i class="fas fa-file-pdf"></i>
                <span>Cek CV PDF</span>
            </a>
        </div>
    </div>

    <!-- Metric Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono text-slate-500 uppercase tracking-wider font-semibold">Portofolio</span>
                <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm"><i class="fas fa-laptop-code"></i></span>
            </div>
            <div class="text-3xl font-bold text-slate-900 mt-3"><?= $projectCount ?></div>
            <div class="mt-2 text-xs text-slate-500 flex items-center justify-between">
                <span>Proyek Terdaftar</span>
                <a href="projects.php" class="text-blue-600 hover:underline font-mono">Kelola &rarr;</a>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono text-slate-500 uppercase tracking-wider font-semibold">Pengalaman</span>
                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm"><i class="fas fa-briefcase"></i></span>
            </div>
            <div class="text-3xl font-bold text-slate-900 mt-3"><?= $expCount ?></div>
            <div class="mt-2 text-xs text-slate-500 flex items-center justify-between">
                <span>Riwayat Kerja</span>
                <a href="experience.php" class="text-emerald-600 hover:underline font-mono">Kelola &rarr;</a>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono text-slate-500 uppercase tracking-wider font-semibold">Keahlian</span>
                <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm"><i class="fas fa-tools"></i></span>
            </div>
            <div class="text-3xl font-bold text-slate-900 mt-3"><?= $skillCount ?></div>
            <div class="mt-2 text-xs text-slate-500 flex items-center justify-between">
                <span>Skills Aktif</span>
                <a href="skills.php" class="text-purple-600 hover:underline font-mono">Kelola &rarr;</a>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono text-slate-500 uppercase tracking-wider font-semibold">Pesan Masuk</span>
                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm"><i class="fas fa-envelope"></i></span>
            </div>
            <div class="text-3xl font-bold text-slate-900 mt-3"><?= $msgCount ?></div>
            <div class="mt-2 text-xs text-slate-500 flex items-center justify-between">
                <span class="text-amber-700 font-medium"><?= $unreadMsgCount ?> belum dibaca</span>
                <a href="messages.php" class="text-amber-600 hover:underline font-mono">Lihat &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Quick Navigation / Shortcuts -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Quick Links -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-[#C9182B]"></i>
                    <span>Tindakan Cepat</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="profile.php" class="p-4 rounded-xl border border-slate-200 hover:border-[#C9182B] hover:bg-slate-50 transition-all flex items-start gap-3">
                        <span class="w-8 h-8 rounded-lg bg-red-100 text-[#C9182B] flex items-center justify-center shrink-0"><i class="fas fa-user-edit text-xs"></i></span>
                        <div>
                            <div class="font-bold text-sm text-slate-800">Edit Profil & Bio</div>
                            <div class="text-xs text-slate-500 mt-0.5">Ubah nama, title, kontak, & tagline hero</div>
                        </div>
                    </a>
                    <a href="projects.php" class="p-4 rounded-xl border border-slate-200 hover:border-[#C9182B] hover:bg-slate-50 transition-all flex items-start gap-3">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0"><i class="fas fa-plus text-xs"></i></span>
                        <div>
                            <div class="font-bold text-sm text-slate-800">Tambah Portofolio</div>
                            <div class="text-xs text-slate-500 mt-0.5">Upload proyek web dan deskripsi baru</div>
                        </div>
                    </a>
                    <a href="experience.php" class="p-4 rounded-xl border border-slate-200 hover:border-[#C9182B] hover:bg-slate-50 transition-all flex items-start gap-3">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0"><i class="fas fa-briefcase text-xs"></i></span>
                        <div>
                            <div class="font-bold text-sm text-slate-800">Riwayat Pengalaman</div>
                            <div class="text-xs text-slate-500 mt-0.5">Perbarui karir kerja untuk CV & web</div>
                        </div>
                    </a>
                    <a href="skills.php" class="p-4 rounded-xl border border-slate-200 hover:border-[#C9182B] hover:bg-slate-50 transition-all flex items-start gap-3">
                        <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center shrink-0"><i class="fas fa-tools text-xs"></i></span>
                        <div>
                            <div class="font-bold text-sm text-slate-800">Kelola Skills</div>
                            <div class="text-xs text-slate-500 mt-0.5">Tambah teknologi atau bahasa baru</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: Recent Inquiries / Messages -->
        <div class="lg:col-span-5">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-inbox text-amber-500"></i>
                        <span>Pesan Terbaru</span>
                    </h3>
                    <a href="messages.php" class="text-xs font-mono text-slate-500 hover:text-slate-800">Semua &rarr;</a>
                </div>

                <?php if (empty($recentMessages)): ?>
                <div class="text-center py-8 text-slate-400 text-xs font-mono">
                    <i class="fas fa-comment-slash text-2xl mb-2 block text-slate-300"></i>
                    Belum ada pesan masuk dari pengunjung web.
                </div>
                <?php else: ?>
                <div class="divide-y divide-slate-100 text-xs">
                    <?php foreach ($recentMessages as $msg): ?>
                    <div class="py-3">
                        <div class="flex items-center justify-between font-semibold text-slate-800">
                            <span><?= htmlspecialchars($msg['name']) ?></span>
                            <span class="text-[10px] text-slate-400 font-mono"><?= date('d/m/Y', strtotime($msg['created_at'])) ?></span>
                        </div>
                        <div class="text-slate-500 truncate mt-0.5"><?= htmlspecialchars($msg['subject'] ?: $msg['message']) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

</body>
</html>
