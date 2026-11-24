<?php
$pageTitle = 'Kelola Keahlian & Toolkit';
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/../db.php';

$db = getDB();
$msg = '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int)$_POST['delete'];
    $stmt = $db->prepare("DELETE FROM skills WHERE id = ?");
    $stmt->execute([$id]);
    $msg = 'Keahlian berhasil dihapus.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category'] ?? 'Frontend');
    $name = trim($_POST['name'] ?? '');
    $icon = trim($_POST['icon'] ?? 'fas fa-check');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $editId = (int)($_POST['edit_id'] ?? 0);

    if (!empty($name)) {
        if ($editId > 0) {
            $stmt = $db->prepare("UPDATE skills SET category=?, name=?, icon=?, sort_order=? WHERE id=?");
            $stmt->execute([$category, $name, $icon, $sortOrder, $editId]);
            $msg = 'Keahlian berhasil diperbarui!';
        } else {
            $stmt = $db->prepare("INSERT INTO skills (category, name, icon, sort_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$category, $name, $icon, $sortOrder]);
            $msg = 'Keahlian baru berhasil ditambahkan!';
        }
    }
}

// Check edit request
$editItem = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM skills WHERE id = ?");
    $stmt->execute([$editId]);
    $editItem = $stmt->fetch();
}

$skills = $db->query("SELECT * FROM skills ORDER BY category, sort_order, name")->fetchAll();
?>

<div class="space-y-8">

    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Keahlian & Teknologi</h1>
            <p class="text-xs text-slate-500 font-mono mt-1">Dikelompokkan rapi per kategori agar CV dan web terlihat terstruktur.</p>
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
            <span><?= $editItem ? 'Edit Keahlian: ' . htmlspecialchars($editItem['name']) : 'Tambah Keahlian Baru' ?></span>
        </h3>

        <form method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <?= csrfField() ?>
            <input type="hidden" name="edit_id" value="<?= $editItem['id'] ?? 0 ?>">

            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Kategori</label>
                <select name="category" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                    <option value="Frontend" <?= ($editItem['category'] ?? '') === 'Frontend' ? 'selected' : '' ?>>Frontend</option>
                    <option value="Backend" <?= ($editItem['category'] ?? '') === 'Backend' ? 'selected' : '' ?>>Backend</option>
                    <option value="IT & DevOps" <?= ($editItem['category'] ?? '') === 'IT & DevOps' ? 'selected' : '' ?>>IT & DevOps</option>
                    <option value="Tools" <?= ($editItem['category'] ?? '') === 'Tools' ? 'selected' : '' ?>>Tools & Workflow</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Nama Skill *</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($editItem['name'] ?? '') ?>" placeholder="Misal: PHP 8.x / Docker" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
            </div>

            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">FontAwesome Icon</label>
                <input type="text" name="icon" value="<?= htmlspecialchars($editItem['icon'] ?? 'fas fa-check') ?>" placeholder="fab fa-php / fas fa-server" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
            </div>

            <div class="flex items-center gap-2">
                <?php if ($editItem): ?>
                <a href="skills.php" class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-600 hover:bg-slate-50">Batal</a>
                <?php endif; ?>
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#C9182B] hover:bg-[#A51423] text-white text-xs font-semibold shadow-md transition-colors">
                    <?= $editItem ? 'Simpan' : 'Tambah' ?>
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Skills -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 font-bold text-sm text-slate-900">
            Daftar Keahlian Aktif (<?= count($skills) ?>)
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 font-mono text-slate-500 uppercase">
                    <tr>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Nama Skill</th>
                        <th class="px-6 py-3">Icon Preview</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($skills as $s): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-3 font-mono text-slate-500"><?= htmlspecialchars($s['category']) ?></td>
                        <td class="px-6 py-3 font-bold text-slate-900 text-sm"><?= htmlspecialchars($s['name']) ?></td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center gap-2 text-slate-700">
                                <i class="<?= htmlspecialchars($s['icon']) ?>"></i>
                                <code class="text-[10px] text-slate-400"><?= htmlspecialchars($s['icon']) ?></code>
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right space-x-2">
                            <a href="skills.php?edit=<?= $s['id'] ?>" class="text-blue-600 hover:underline font-mono">Edit</a>
                            <form method="POST" action="skills.php" class="inline-block" onsubmit="return confirm('Hapus skill ini?');">
                                <?= csrfField() ?>
                                <button type="submit" name="delete" value="<?= (int)$s['id'] ?>" class="text-red-600 hover:underline font-mono">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
