<?php
$pageTitle = 'Kelola Layanan & Solusi';
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/../db.php';

$db = getDB();
$msg = '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int)$_POST['delete'];
    $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $msg = 'Layanan berhasil dihapus.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? 'fas fa-check');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $editId = (int)($_POST['edit_id'] ?? 0);

    if (!empty($title)) {
        if ($editId > 0) {
            $stmt = $db->prepare("UPDATE services SET title=?, description=?, icon=?, sort_order=? WHERE id=?");
            $stmt->execute([$title, $description, $icon, $sortOrder, $editId]);
            $msg = 'Layanan berhasil diperbarui!';
        } else {
            $stmt = $db->prepare("INSERT INTO services (title, description, icon, sort_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $icon, $sortOrder]);
            $msg = 'Layanan baru berhasil ditambahkan!';
        }
    }
}

// Check edit request
$editItem = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$editId]);
    $editItem = $stmt->fetch();
}

$services = $db->query("SELECT * FROM services ORDER BY sort_order, id")->fetchAll();
?>

<div class="space-y-8">

    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Layanan & Solusi</h1>
            <p class="text-xs text-slate-500 font-mono mt-1">Kartu layanan yang ditampilkan di bagian "Layanan & Solusi" beranda.</p>
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
            <span><?= $editItem ? 'Edit Layanan: ' . htmlspecialchars($editItem['title']) : 'Tambah Layanan Baru' ?></span>
        </h3>

        <form method="POST" class="space-y-4">
            <?= csrfField() ?>
            <input type="hidden" name="edit_id" value="<?= $editItem['id'] ?? 0 ?>">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Nama Layanan *</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($editItem['title'] ?? '') ?>" placeholder="Misal: IT Support & Network Maintenance" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">FontAwesome Icon</label>
                    <input type="text" name="icon" value="<?= htmlspecialchars($editItem['icon'] ?? 'fas fa-wrench') ?>" placeholder="fas fa-wrench / fas fa-layer-group" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Deskripsi Layanan *</label>
                <textarea name="description" rows="3" required placeholder="Jelaskan cakupan layanan dan solusi yang Anda tawarkan..." 
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]"><?= htmlspecialchars($editItem['description'] ?? '') ?></textarea>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <div class="flex items-center gap-2 text-xs font-mono">
                    <label class="text-slate-500">Urutan Tampil:</label>
                    <input type="number" name="sort_order" value="<?= htmlspecialchars($editItem['sort_order'] ?? 0) ?>" class="w-16 px-2 py-1 rounded border border-slate-300">
                </div>

                <div class="flex items-center gap-2">
                    <?php if ($editItem): ?>
                    <a href="services.php" class="px-4 py-2 rounded-xl border border-slate-300 text-xs text-slate-600 hover:bg-slate-50">Batal</a>
                    <?php endif; ?>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#C9182B] hover:bg-[#A51423] text-white text-xs font-semibold shadow-md transition-colors">
                        <?= $editItem ? 'Simpan Perubahan' : 'Tambah Layanan' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Layanan -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 font-bold text-sm text-slate-900 flex items-center justify-between">
            <span>Daftar Layanan Aktif (<?= count($services) ?>)</span>
            <span class="text-xs font-mono text-slate-500">Tampil di halaman depan</span>
        </div>

        <div class="divide-y divide-slate-100">
            <?php foreach ($services as $s): ?>
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-start gap-4">
                    <span class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0 text-sm">
                        <i class="<?= htmlspecialchars($s['icon']) ?>"></i>
                    </span>
                    <div class="space-y-1">
                        <div class="font-bold text-slate-900 text-base"><?= htmlspecialchars($s['title']) ?></div>
                        <p class="text-xs text-slate-600 max-w-2xl leading-relaxed"><?= htmlspecialchars($s['description']) ?></p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="services.php?edit=<?= $s['id'] ?>" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:border-slate-400 text-xs font-mono text-slate-700">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form method="POST" action="services.php" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus layanan ini?');">
                        <?= csrfField() ?>
                        <button type="submit" name="delete" value="<?= (int)$s['id'] ?>" class="px-3 py-1.5 rounded-lg border border-red-200 hover:bg-red-50 text-xs font-mono text-red-600"><i class="fas fa-trash mr-1"></i> Hapus</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

</body>
</html>
