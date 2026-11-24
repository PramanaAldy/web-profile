<?php
$pageTitle = 'Pesan Masuk';
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/../db.php';

$db = getDB();
$msgNotice = '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int)$_POST['delete'];
    $stmt = $db->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$id]);
    $msgNotice = 'Pesan berhasil dihapus.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_read'])) {
    $id = (int)$_POST['mark_read'];
    $stmt = $db->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
}

$messages = $db->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();
?>

<div class="space-y-8">

    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pesan & Kontak Masuk</h1>
            <p class="text-xs text-slate-500 font-mono mt-1">Daftar pesan yang dikirimkan oleh pengunjung melalui formulir kontak web.</p>
        </div>
    </div>

    <?php if ($msgNotice): ?>
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm flex items-center gap-2">
        <i class="fas fa-check-circle text-emerald-600"></i>
        <span><?= htmlspecialchars($msgNotice) ?></span>
    </div>
    <?php endif; ?>

    <?php if (empty($messages)): ?>
    <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center text-slate-400">
        <i class="fas fa-envelope-open text-4xl mb-3 text-slate-300 block"></i>
        <div class="text-base font-bold text-slate-700">Kotak Masuk Masih Bersih</div>
        <div class="text-xs text-slate-500 mt-1">Belum ada formulir kontak yang dikirim oleh pengunjung web.</div>
    </div>
    <?php else: ?>
    <div class="space-y-4">
        <?php foreach ($messages as $m): ?>
        <div class="bg-white border <?= $m['is_read'] ? 'border-slate-200' : 'border-[#C9182B]/40 bg-red-50/10' ?> rounded-2xl p-6 shadow-sm space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                        <?= htmlspecialchars(mb_strtoupper(mb_substr($m['name'], 0, 1)), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                    </span>
                    <div>
                        <span class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($m['name']) ?></span>
                        <a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="text-xs text-[#C9182B] hover:underline ml-2 font-mono">
                            <?= htmlspecialchars($m['email']) ?>
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-xs font-mono text-slate-400">
                    <span><?= date('d M Y, H:i', strtotime($m['created_at'])) ?> WITA</span>
                    <?php if (!$m['is_read']): ?>
                    <form method="POST" action="messages.php">
                        <?= csrfField() ?>
                        <button type="submit" name="mark_read" value="<?= (int)$m['id'] ?>" class="text-blue-600 hover:underline">Tandai Dibaca</button>
                    </form>
                    <?php endif; ?>
                    <form method="POST" action="messages.php" onsubmit="return confirm('Hapus pesan ini?');">
                        <?= csrfField() ?>
                        <button type="submit" name="delete" value="<?= (int)$m['id'] ?>" class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>

            <?php if (!empty($m['subject'])): ?>
            <div class="text-xs font-mono text-slate-500 font-semibold">
                Perihal: <span class="text-slate-800"><?= htmlspecialchars($m['subject']) ?></span>
            </div>
            <?php endif; ?>

            <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-xl border border-slate-100">
                <?= nl2br(htmlspecialchars($m['message'])) ?>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="mailto:<?= htmlspecialchars($m['email']) ?>?subject=Re: <?= urlencode($m['subject'] ?: 'Web Profile Inquiry') ?>" class="text-xs font-mono font-semibold text-[#C9182B] hover:underline flex items-center gap-1.5">
                    <i class="fas fa-reply"></i>
                    <span>Balas lewat Email</span>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

</body>
</html>
