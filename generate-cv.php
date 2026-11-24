<?php
// ========================================================
// Dynamic CV Generator — I Ketut Aldy Pramana Putra
// Powered by Dompdf + MySQL Real-time Sync
// ========================================================

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/functions.php';

// Check if Dompdf exists
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die("Composer autoload belum terinstall. Jalankan 'composer install' terlebih dahulu.");
}
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$profile = getProfile();
$experiences = getExperiences();
$skillsGrouped = getSkillsGrouped();
$projects = getProjects(true); // featured projects
if (empty($projects)) {
    $projects = array_slice(getProjects(), 0, 3);
}

// Build HTML for CV
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($profile['full_name'] ?? 'I Ketut Aldy Pramana Putra') ?> — Curriculum Vitae</title>
    <style>
        @page {
            margin: 20mm 18mm 20mm 18mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0B132B;
            background: #FFFFFF;
            font-size: 10pt;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0B132B;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header-title {
            font-size: 20pt;
            font-weight: bold;
            color: #0B132B;
            letter-spacing: -0.5px;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }
        .header-subtitle {
            font-size: 11pt;
            color: #C9182B;
            font-weight: bold;
            margin-top: 4px;
        }
        .contact-info {
            text-align: right;
            font-size: 8.5pt;
            color: #4A5568;
            line-height: 1.5;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #0B132B;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 1px solid #CBD5E1;
            padding-bottom: 3px;
            margin-top: 14px;
            margin-bottom: 8px;
        }
        .section-title span {
            color: #C9182B;
            margin-right: 4px;
        }
        .summary-text {
            font-size: 9.5pt;
            color: #2D3748;
            text-align: justify;
            margin-bottom: 12px;
        }
        .exp-item {
            margin-bottom: 12px;
        }
        .exp-table {
            width: 100%;
            margin-bottom: 2px;
        }
        .exp-role {
            font-weight: bold;
            font-size: 10pt;
            color: #0B132B;
        }
        .exp-company {
            color: #4A5568;
            font-size: 9pt;
            font-style: italic;
        }
        .exp-date {
            text-align: right;
            font-size: 8.5pt;
            color: #718096;
            font-weight: bold;
        }
        .exp-desc {
            font-size: 9pt;
            color: #2D3748;
            margin-top: 3px;
            line-height: 1.4;
        }
        .skills-table {
            width: 100%;
            margin-top: 4px;
        }
        .skills-cell {
            vertical-align: top;
            width: 50%;
            padding-right: 12px;
        }
        .skill-cat {
            font-size: 9pt;
            font-weight: bold;
            color: #C9182B;
            margin-bottom: 2px;
        }
        .skill-list {
            font-size: 8.5pt;
            color: #2D3748;
            margin-bottom: 6px;
        }
        .proj-title {
            font-weight: bold;
            font-size: 9.5pt;
            color: #0B132B;
        }
        .proj-stack {
            font-size: 8pt;
            color: #C9182B;
            font-family: 'Courier New', Courier, monospace;
        }
        .proj-desc {
            font-size: 8.5pt;
            color: #4A5568;
            margin-bottom: 6px;
        }
        .footer-note {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px dashed #CBD5E1;
            font-size: 7.5pt;
            color: #A0AEC0;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 75px; vertical-align: top; padding-right: 14px;">
                <img src="<?= __DIR__ . '/assets/images/profile.jpg' ?>" style="width: 68px; height: 85px; border-radius: 4px; border: 1px solid #0B132B;">
            </td>
            <td style="vertical-align: top;">
                <div class="header-title"><?= htmlspecialchars($profile['full_name'] ?? 'I Ketut Aldy Pramana Putra') ?></div>
                <div class="header-subtitle"><?= htmlspecialchars($profile['title'] ?? 'IT Support & Full Stack Developer') ?></div>
            </td>
            <td class="contact-info" style="vertical-align: top;">
                <div>📍 <?= htmlspecialchars($profile['location'] ?? 'Denpasar, Bali, Indonesia') ?></div>
                <div>📱 <?= htmlspecialchars($profile['phone'] ?? '0896-5351-7850') ?></div>
                <div>✉️ <?= htmlspecialchars($profile['email'] ?? 'aldy@webprofile.dev') ?></div>
                <div>💼 <?= htmlspecialchars($profile['years_experience'] ?? '2') ?>+ Tahun Pengalaman</div>
            </td>
        </tr>
    </table>

    <!-- Ringkasan Profesional -->
    <div class="section-title"><span>■</span> Ringkasan Profesional</div>
    <div class="summary-text">
        <?= nl2br(htmlspecialchars($profile['bio'] ?? 'Praktisi IT dengan 2 tahun pengalaman intensif di bidang infrastruktur IT Support (hardware, jaringan, server) dan pengembangan aplikasi web Full Stack (PHP, MySQL, Tailwind CSS, REST API). Mampu memecahkan masalah komputasi, optimasi performa sistem, serta membangun solusi digital end-to-end dengan ketelitian tinggi.')) ?>
    </div>

    <!-- Riwayat Pengalaman Kerja -->
    <div class="section-title"><span>■</span> Pengalaman Kerja & Rekam Jejak</div>
    <?php foreach ($experiences as $exp): ?>
    <div class="exp-item">
        <table class="exp-table">
            <tr>
                <td class="exp-role"><?= htmlspecialchars($exp['role']) ?></td>
                <td class="exp-date">
                    <?= formatDate($exp['start_date']) ?> — <?= !empty($exp['is_current']) ? 'SEKARANG' : formatDate($exp['end_date']) ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="exp-company"><?= htmlspecialchars($exp['company']) ?><?= !empty($exp['location']) ? ' | ' . htmlspecialchars($exp['location']) : '' ?></td>
            </tr>
        </table>
        <div class="exp-desc"><?= nl2br(htmlspecialchars($exp['description'])) ?></div>
    </div>
    <?php endforeach; ?>

    <!-- Keahlian Teknis -->
    <div class="section-title"><span>■</span> Keahlian Teknis & Tools</div>
    <table class="skills-table">
        <tr>
            <?php 
            $i = 0;
            foreach ($skillsGrouped as $category => $items): 
                $names = array_column($items, 'name');
            ?>
            <td class="skills-cell">
                <div class="skill-cat"><?= htmlspecialchars($category) ?></div>
                <div class="skill-list"><?= htmlspecialchars(implode(', ', $names)) ?></div>
            </td>
            <?php 
                $i++;
                if ($i % 2 === 0) echo '</tr><tr>';
            endforeach; 
            ?>
        </tr>
    </table>

    <!-- Proyek Unggulan -->
    <div class="section-title"><span>■</span> Portofolio & Proyek Pilihan</div>
    <?php foreach ($projects as $proj): ?>
    <div style="margin-bottom: 8px;">
        <span class="proj-title"><?= htmlspecialchars($proj['title']) ?></span> 
        <?php if (!empty($proj['tech_stack'])): ?>
        <span class="proj-stack">[ <?= htmlspecialchars($proj['tech_stack']) ?> ]</span>
        <?php endif; ?>
        <div class="proj-desc"><?= htmlspecialchars($proj['description']) ?></div>
    </div>
    <?php endforeach; ?>

    <!-- Footer Otomatis -->
    <div class="footer-note">
        Dokumen CV ini digenerate secara otomatis & tersinkronisasi langsung dari Web Profile resmi pada <?= date('d F Y, H:i') ?> WITA.
    </div>

</body>
</html>
<?php
$html = ob_get_clean();

// Check if user requested HTML preview (?preview=1)
if (isset($_GET['preview'])) {
    echo $html;
    exit;
}

// Generate PDF using Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $profile['full_name'] ?? 'CV');
$filename = "CV_{$cleanName}.pdf";

$dompdf->stream($filename, ["Attachment" => true]);
