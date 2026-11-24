<?php
$pageTitle = 'Kelola Portofolio';
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/../db.php';

$db = getDB();
$msg = '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int)$_POST['delete'];
    $stmt = $db->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $msg = 'Proyek berhasil dihapus.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $techStack = trim($_POST['tech_stack'] ?? '');
    $category = trim($_POST['category'] ?? 'Web Application');
    $projectUrl = trim($_POST['project_url'] ?? '');
    $githubUrl = trim($_POST['github_url'] ?? '');
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $editId = (int)($_POST['edit_id'] ?? 0);

    if (!empty($title)) {
        if ($editId > 0) {
            $stmt = $db->prepare("UPDATE projects SET title=?, description=?, tech_stack=?, category=?, project_url=?, github_url=?, is_featured=?, sort_order=? WHERE id=?");
            $stmt->execute([$title, $description, $techStack, $category, $projectUrl, $githubUrl, $isFeatured, $sortOrder, $editId]);
            $msg = 'Proyek berhasil diperbarui!';
        } else {
            $stmt = $db->prepare("INSERT INTO projects (title, description, tech_stack, category, project_url, github_url, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $techStack, $category, $projectUrl, $githubUrl, $isFeatured, $sortOrder]);
            $msg = 'Proyek baru berhasil ditambahkan!';
        }
    }
}

// Check edit request
$editItem = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$editId]);
    $editItem = $stmt->fetch();
}

$projects = $db->query("SELECT * FROM projects ORDER BY sort_order, created_at DESC")->fetchAll();
?>

<div class="space-y-8">

    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Portofolio & Proyek</h1>
            <p class="text-xs text-slate-500 font-mono mt-1">Tambah, edit, dan atur urutan karya yang ditampilkan.</p>
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
            <span><?= $editItem ? 'Edit Proyek: ' . htmlspecialchars($editItem['title']) : 'Tambah Proyek Baru' ?></span>
        </h3>

        <form method="POST" class="space-y-4">
            <?= csrfField() ?>
            <input type="hidden" name="edit_id" value="<?= $editItem['id'] ?? 0 ?>">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Judul Proyek *</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($editItem['title'] ?? '') ?>" placeholder="Misal: Sistem Manajemen Inventori & POS" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Kategori</label>
                    <input type="text" name="category" value="<?= htmlspecialchars($editItem['category'] ?? 'Web Application') ?>" placeholder="Web / IT / Joki" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Deskripsi Singkat (Permasalahan & Solusi)</label>
                <textarea name="description" rows="3" required placeholder="Jelaskan peran Anda, tantangan yang diselesaikan, dan hasil proyek..." 
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]"><?= htmlspecialchars($editItem['description'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Tech Stack (Pisahkan dengan koma)</label>
                <input type="text" name="tech_stack" value="<?= htmlspecialchars($editItem['tech_stack'] ?? '') ?>" placeholder="PHP 8, MySQL, Tailwind CSS, Alpine.js" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Link Demo / Website Live</label>
                    <input type="text" name="project_url" value="<?= htmlspecialchars($editItem['project_url'] ?? '') ?>" placeholder="https://..." 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-1 font-semibold">Link GitHub Repository</label>
                    <input type="text" name="github_url" value="<?= htmlspecialchars($editItem['github_url'] ?? '') ?>" placeholder="https://github.com/..." 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between pt-4 border-t border-slate-100 gap-4">
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                        <input type="checkbox" name="is_featured" value="1" <?= !empty($editItem['is_featured']) ? 'checked' : '' ?> class="rounded text-[#C9182B] focus:ring-0">
                        <span>Tampilkan di CV & Unggulan</span>
                    </label>
                    <div class="flex items-center gap-2 text-xs font-mono">
                        <label class="text-slate-500">Urutan:</label>
                        <input type="number" name="sort_order" value="<?= htmlspecialchars($editItem['sort_order'] ?? 0) ?>" class="w-16 px-2 py-1 rounded border border-slate-300">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <?php if ($editItem): ?>
                    <a href="projects.php" class="px-4 py-2 rounded-xl border border-slate-300 text-xs text-slate-600 hover:bg-slate-50">Batal</a>
                    <?php endif; ?>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#C9182B] hover:bg-[#A51423] text-white text-xs font-semibold shadow-md transition-colors">
                        <?= $editItem ? 'Perbarui Proyek' : 'Tambah Proyek' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Proyek -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 font-bold text-sm text-slate-900 flex items-center justify-between">
            <span>Daftar Proyek Aktif (<?= count($projects) ?>)</span>
            <span class="text-xs font-mono text-slate-500">Otomatis sinkron ke web & CV</span>
        </div>

        <div class="divide-y divide-slate-100">
            <?php foreach ($projects as $p): ?>
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                <div class="space-y-1 max-w-2xl">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-900 text-base"><?= htmlspecialchars($p['title']) ?></span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-slate-100 text-slate-600 border border-slate-200"><?= htmlspecialchars($p['category']) ?></span>
                        <?php if ($p['is_featured']): ?>
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-red-50 text-[#C9182B] font-semibold border border-red-200">Unggulan</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-slate-600 line-clamp-2"><?= htmlspecialchars($p['description']) ?></p>
                    <div class="text-[11px] font-mono text-slate-500 pt-1">
                        <span class="text-[#C9182B] font-semibold">Stack:</span> <?= htmlspecialchars($p['tech_stack']) ?>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="projects.php?edit=<?= $p['id'] ?>" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:border-slate-400 text-xs font-mono text-slate-700">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form method="POST" action="projects.php" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus proyek ini?');">
                        <?= csrfField() ?>
                        <button type="submit" name="delete" value="<?= (int)$p['id'] ?>" class="px-3 py-1.5 rounded-lg border border-red-200 hover:bg-red-50 text-xs font-mono text-red-600"><i class="fas fa-trash mr-1"></i> Hapus</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

</body>
</html>
