<?php
$pageTitle = 'Edit Profil & Tampilan Beranda';
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/../db.php';

$db = getDB();
$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $tagline = trim($_POST['tagline'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $githubUrl = trim($_POST['github_url'] ?? '');
    $linkedinUrl = trim($_POST['linkedin_url'] ?? '');
    $yearsExp = (int)($_POST['years_experience'] ?? 2);
    $isAvailable = isset($_POST['is_available']) ? 1 : 0;
    $availText = trim($_POST['availability_text'] ?? 'Available for Projects');

    $stmt = $db->prepare("UPDATE profile SET full_name=?, title=?, tagline=?, bio=?, location=?, email=?, phone=?, github_url=?, linkedin_url=?, years_experience=?, is_available=?, availability_text=? WHERE id=1");
    $stmt->execute([$fullName, $title, $tagline, $bio, $location, $email, $phone, $githubUrl, $linkedinUrl, $yearsExp, $isAvailable, $availText]);

    $feedback = 'Profil berhasil diperbarui! Tampilan web dan CV PDF langsung terupdate.';
}

$profile = $db->query("SELECT * FROM profile LIMIT 1")->fetch();
?>

<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Profil & Hero Beranda</h1>
            <p class="text-xs text-slate-500 font-mono mt-1">Data ini langsung disinkronkan ke halaman utama dan CV PDF.</p>
        </div>
        <a href="/WebProfile/" target="_blank" class="text-xs font-mono text-[#C9182B] hover:underline flex items-center gap-1">
            <span>Lihat Hasil di Web</span>
            <i class="fas fa-external-link-alt text-[10px]"></i>
        </a>
    </div>

    <?php if ($feedback): ?>
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm flex items-center gap-3">
        <i class="fas fa-check-circle text-emerald-600"></i>
        <span><?= htmlspecialchars($feedback) ?></span>
    </div>
    <?php endif; ?>

    <form method="POST" class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        <?= csrfField() ?>
        
        <!-- Status Ketersediaan -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="font-bold text-sm text-slate-900 block">Status Ketersediaan (Availability Badge)</span>
                <span class="text-xs text-slate-500">Tampilkan sinyal status siap menerima project di navbar & hero web profile.</span>
            </div>
            <div class="flex items-center gap-4">
                <input type="text" name="availability_text" value="<?= htmlspecialchars($profile['availability_text'] ?? 'Available for Projects') ?>" 
                       class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-mono text-slate-800 focus:outline-none focus:border-[#C9182B]">
                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                    <input type="checkbox" name="is_available" value="1" <?= !empty($profile['is_available']) ? 'checked' : '' ?> class="rounded text-[#C9182B] focus:ring-0">
                    <span>Aktif</span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-2 font-semibold">Nama Lengkap *</label>
                <input type="text" name="full_name" required value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
            </div>

            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-2 font-semibold">Gelar / Jabatan Utama *</label>
                <input type="text" name="title" required value="<?= htmlspecialchars($profile['title'] ?? '') ?>" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-2 font-semibold">Tahun Pengalaman</label>
                <input type="number" name="years_experience" value="<?= htmlspecialchars($profile['years_experience'] ?? 2) ?>" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
            </div>
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-2 font-semibold">Domisili / Lokasi</label>
                <input type="text" name="location" value="<?= htmlspecialchars($profile['location'] ?? 'Denpasar, Bali') ?>" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
            </div>
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-2 font-semibold">Email Kontak</label>
                <input type="email" name="email" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
            </div>
        </div>

        <div>
            <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-2 font-semibold">Tagline Hero (Muncul di bawah headline)</label>
            <input type="text" name="tagline" value="<?= htmlspecialchars($profile['tagline'] ?? '') ?>" 
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
        </div>

        <div>
            <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-2 font-semibold">Biografi / Ringkasan Eksekutif (Masuk ke CV & Web)</label>
            <textarea name="bio" rows="5" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B] leading-relaxed"><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-2 font-semibold">GitHub URL</label>
                <input type="text" name="github_url" placeholder="https://github.com/username" value="<?= htmlspecialchars($profile['github_url'] ?? '') ?>" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
            </div>
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-700 mb-2 font-semibold">LinkedIn URL</label>
                <input type="text" name="linkedin_url" placeholder="https://linkedin.com/in/username" value="<?= htmlspecialchars($profile['linkedin_url'] ?? '') ?>" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-[#C9182B]">
            </div>
        </div>

        <div class="pt-6 border-t border-slate-200 flex items-center justify-end">
            <button type="submit" class="px-6 py-3 rounded-xl bg-[#C9182B] hover:bg-[#A51423] text-white text-sm font-semibold shadow-md transition-colors flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span>Simpan Perubahan Profil</span>
            </button>
        </div>

    </form>

</div>

</body>
</html>
