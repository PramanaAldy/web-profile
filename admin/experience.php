<?php
$pageTitle = 'Kelola Riwayat Pengalaman';
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/../db.php';

$db = getDB();
$msg = '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int)$_POST['delete'];
    $stmt = $db->prepare("DELETE FROM experience WHERE id = ?");
    $stmt->execute([$id]);
    $msg = 'Pengalaman kerja berhasil dihapus.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company = trim($_POST['company'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $startDate = trim($_POST['start_date'] ?? '');
    $endDate = !empty($_POST['end_date']) ? trim($_POST['end_date']) : null;
    $isCurrent = isset($_POST['is_current']) ? 1 : 0;
    if ($isCurrent) $endDate = null;
    $description = trim($_POST['description'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $editId = (int)($_POST['edit_id'] ?? 0);

    if (!empty($company) && !empty($role) && !empty($startDate)) {
        if ($editId > 0) {
            $stmt = $db->prepare("UPDATE experience SET company=?, role=?, location=?, start_date=?, end_date=?, is_current=?, description=?, sort_order=? WHERE id=?");
            $stmt->execute([$company, $role, $location, $startDate, $endDate, $isCurrent, $description, $sortOrder, $editId]);
            $msg = 'Pengalaman berhasil diperbarui!';
        } else {
            $stmt = $db->prepare("INSERT INTO experience (company, role, location, start_date, end_date, is_current, description, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$company, $role, $location, $startDate, $endDate, $isCurrent, $description, $sortOrder]);
            $msg = 'Pengalaman baru berhasil ditambahkan!';
        }
    }
}

// Check edit request
$editItem = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM experience WHERE id = ?");
    $stmt->execute([$editId]);
    $editItem = $stmt->fetch();
}

$experiences = $db->query("SELECT * FROM experience ORDER BY is_current DESC, start_date DESC")->fetchAll();
?>

<div class="space-y-8">

    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Rekam Jejak & Pengalaman</h1>
            <p class="text-xs text-slate-500 font-mono mt-1">Data riwayat karir ini menjadi pondasi timeline web dan lembar CV resmi.</p>
        </div>
    </div>

    <?php if ($msg): ?>
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm flex items-center gap-2">
        <i class="fas fa-check-circle text-emerald-600"></i>
        <span><?= htmlspecialchars($msg) ?></span>
    </div>
    <?php endif; ?>

    <!-- Form Tambah / Edit -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i class="fas <?= $editItem ? 'fa-edit text-amber-500' : 'fa-plus text-[#C9182B]' ?>"></i>
            <span><?= $editItem ? 'Edit Pengalaman: ' . htmlspecialchars($editItem['role']) : 'Tambah Riwayat Pengalaman Baru' ?></span>
        </h3>

        <form method="POST" class="space-y-4">
            <?= csrfField() ?>
            <input type="hidden" name="edit_id" value="<?= $editItem['id'] ?? 0 ?>">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Posisi / Jabatan *</label>
                    <input type="text" name="role" required value="<?= htmlspecialchars($editItem['role'] ?? '') ?>" placeholder="Misal: Senior IT Support & Web Specialist" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Perusahaan / Organisasi *</label>
                    <input type="text" name="company" required value="<?= htmlspecialchars($editItem['company'] ?? '') ?>" placeholder="Nama instansi atau 'Freelance / Consultant'" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Lokasi Kerja</label>
                    <input type="text" name="location" value="<?= htmlspecialchars($editItem['location'] ?? 'Denpasar, Bali') ?>" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Tanggal Mulai *</label>
                    <input type="date" name="start_date" required value="<?= htmlspecialchars($editItem['start_date'] ?? '') ?>" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="<?= htmlspecialchars($editItem['end_date'] ?? '') ?>" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                    <div class="mt-1">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-slate-700">
                            <input type="checkbox" name="is_current" value="1" <?= !empty($editItem['is_current']) ? 'checked' : '' ?> class="rounded text-[#C9182B] focus:ring-0">
                            <span>Masih berlangsung (Saat ini)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Deskripsi Tanggung Jawab & Pencapaian</label>
                <textarea name="description" rows="4" required placeholder="Jelaskan kontribusi Anda, teknologi yang ditangani, dan dampak yang dihasilkan..." 
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]"><?= htmlspecialchars($editItem['description'] ?? '') ?></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <?php if ($editItem): ?>
                <a href="experience.php" class="px-4 py-2 rounded-xl border border-slate-300 text-xs text-slate-600 hover:bg-slate-50">Batal</a>
                <?php endif; ?>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#C9182B] hover:bg-[#A51423] text-white text-xs font-semibold shadow-md transition-colors">
                    <?= $editItem ? 'Perbarui Pengalaman' : 'Tambah Pengalaman' ?>
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Pengalaman -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 font-bold text-sm text-slate-900">
            Daftar Pengalaman Kerja (<?= count($experiences) ?>)
        </div>

        <div class="divide-y divide-slate-100">
            <?php foreach ($experiences as $e): ?>
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                <div class="space-y-1 max-w-2xl">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-900 text-base"><?= htmlspecialchars($e['role']) ?></span>
                        <span class="text-xs text-slate-500 font-mono">@ <?= htmlspecialchars($e['company']) ?></span>
                        <?php if ($e['is_current']): ?>
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">Saat Ini</span>
                        <?php endif; ?>
                    </div>
                    <div class="text-xs font-mono text-slate-400">
                        <?= date('M Y', strtotime($e['start_date'])) ?> — <?= $e['is_current'] ? 'Sekarang' : date('M Y', strtotime($e['end_date'])) ?>
                        <?= !empty($e['location']) ? ' | ' . htmlspecialchars($e['location']) : '' ?>
                    </div>
                    <p class="text-xs text-slate-600 line-clamp-2 pt-1"><?= htmlspecialchars($e['description']) ?></p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="experience.php?edit=<?= $e['id'] ?>" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:border-slate-400 text-xs font-mono text-slate-700">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form method="POST" action="experience.php" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus riwayat ini?');">
                        <?= csrfField() ?>
                        <button type="submit" name="delete" value="<?= (int)$e['id'] ?>" class="px-3 py-1.5 rounded-lg border border-red-200 hover:bg-red-50 text-xs font-mono text-red-600"><i class="fas fa-trash mr-1"></i> Hapus</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

</body>
</html>
