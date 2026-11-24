<?php
require_once __DIR__ . '/includes/header.php';

// Handle contact form submission
$contactFeedback = null;
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'contact') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? 'Pesan dari Web Profile');
    $message = trim($_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $saved = saveMessage([
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message
            ]);
            if ($saved) {
                $contactFeedback = ['type' => 'success', 'msg' => 'Terima kasih, pesan Anda berhasil dikirim! Saya akan segera merespons.'];
            } else {
                $contactFeedback = ['type' => 'error', 'msg' => 'Gagal menyimpan pesan. Silakan coba lagi atau hubungi via WhatsApp.'];
            }
        } else {
            $contactFeedback = ['type' => 'error', 'msg' => 'Format email tidak valid.'];
        }
    } else {
        $contactFeedback = ['type' => 'error', 'msg' => 'Semua kolom wajib diisi.'];
    }
}

$services = getServices();
$projects = getProjects();
$experiences = getExperiences();
$skillsGrouped = getSkillsGrouped();
?>

    <!-- Toast Notification for Contact Form -->
    <?php if ($contactFeedback): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" 
         class="fixed bottom-6 right-6 z-50 max-w-md p-4 rounded-xl shadow-2xl border transition-all duration-300 <?= $contactFeedback['type'] === 'success' ? 'bg-emerald-900 text-white border-emerald-700' : 'bg-red-900 text-white border-red-700' ?>">
        <div class="flex items-start gap-3">
            <i class="fas <?= $contactFeedback['type'] === 'success' ? 'fa-check-circle text-emerald-300' : 'fa-exclamation-triangle text-red-300' ?> text-lg mt-0.5"></i>
            <div class="flex-1 text-sm leading-relaxed"><?= e($contactFeedback['msg']) ?></div>
            <button @click="show = false" class="text-white/70 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
    </div>
    <?php endif; ?>

    <!-- HERO SECTION -->
    <section id="hero" class="relative pt-32 md:pt-44 pb-20 md:pb-32 blueprint-grid border-b border-navy/10 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left Column: Editorial & Value Proposition -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Availability Pill & Location -->
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-cream-subtle border border-navy/15 text-navy font-mono text-xs tracking-wider">
                            <span class="pulse-available"></span>
                            <span>2 TAHUN PENGALAMAN IT</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-mono text-navy/60">
                            <i class="fas fa-map-marker-alt text-crimson"></i>
                            <span>Denpasar, Bali</span>
                        </span>
                    </div>

                    <!-- Main Editorial Headline -->
                    <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl leading-[1.08] tracking-tight text-navy">
                        Infrastruktur IT yang Kokoh, <br>
                        <span class="italic font-normal text-crimson">Aplikasi Web</span> yang Bekerja Nyata.
                    </h1>

                    <!-- Positioning Subtext -->
                    <p class="font-sans text-base sm:text-lg text-navy/80 max-w-xl font-normal leading-relaxed">
                        Halo, saya <strong class="font-semibold text-navy"><?= e($profile['full_name'] ?? 'I Ketut Aldy Pramana Putra') ?></strong>. 
                        Spesialis IT-Support & Web Developer. Berpengalaman membangun aplikasi sistem web custom (Kasir POS, SPP, Posyandu Desa), website WordPress profesional (travel, booking, organisasi), hingga pengalaman interaktif Virtual Tour 360° Pura Goa Lawah.
                    </p>

                    <!-- CTA Action Buttons -->
                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        <a href="#portofolio" class="btn-primary">
                            <span>Lihat Karya & Proyek</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                        <a href="generate-cv.php" target="_blank" class="btn-secondary">
                            <i class="fas fa-file-pdf text-crimson"></i>
                            <span>Unduh CV Otomatis</span>
                        </a>
                        <a href="https://wa.me/6289653517850?text=Halo%20Aldy,%20saya%20tertarik%20dengan%20layanan%20web%20profile%20Anda." target="_blank" class="inline-flex items-center gap-2 text-sm font-medium text-navy/70 hover:text-crimson px-3 py-2 transition-colors">
                            <i class="fab fa-whatsapp text-emerald-600 text-lg"></i>
                            <span>Konsultasi WhatsApp</span>
                        </a>
                    </div>

                    <!-- Trust Stats Bar -->
                    <div class="grid grid-cols-3 gap-3 sm:gap-4 pt-8 border-t border-navy/10 max-w-lg">
                        <div>
                            <div class="font-serif text-2xl sm:text-3xl lg:text-4xl text-navy font-bold">2+</div>
                            <div class="font-mono text-[10px] sm:text-xs text-navy/60 uppercase tracking-wider">Tahun Pengalaman</div>
                        </div>
                        <div>
                            <div class="font-serif text-2xl sm:text-3xl lg:text-4xl text-crimson font-bold">10+</div>
                            <div class="font-mono text-[10px] sm:text-xs text-navy/60 uppercase tracking-wider">Proyek Pilihan</div>
                        </div>
                        <div>
                            <div class="font-serif text-2xl sm:text-3xl lg:text-4xl text-navy font-bold">99%</div>
                            <div class="font-mono text-[10px] sm:text-xs text-navy/60 uppercase tracking-wider">Troubleshooting SLA</div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Vibe Coding / Terminal Widget -->
                <div class="lg:col-span-5">
                    <div class="terminal-card">
                        <div class="terminal-header">
                            <div class="flex items-center gap-2">
                                <span class="terminal-dot bg-red-500"></span>
                                <span class="terminal-dot bg-yellow-500"></span>
                                <span class="terminal-dot bg-green-500"></span>
                                <span class="text-[11px] text-white/50 ml-2 font-mono">engineer_specs.json</span>
                            </div>
                            <span class="text-[10px] text-crimson uppercase tracking-wider font-semibold">● ACTIVE SESSION</span>
                        </div>
                        <div class="p-6 space-y-4 text-xs font-mono leading-relaxed overflow-x-auto">
                            <div>
                                <span class="text-crimson font-semibold">const</span> <span class="text-emerald-400">engineer</span> = {
                            </div>
                            <div class="pl-4 space-y-1">
                                <div><span class="text-white/60">name:</span> <span class="text-amber-300">"<?= e($profile['full_name'] ?? 'I Ketut Aldy Pramana Putra') ?>"</span>,</div>
                                <div><span class="text-white/60">role:</span> <span class="text-amber-300">"IT Support & Full Stack Developer"</span>,</div>
                                <div><span class="text-white/60">experience:</span> <span class="text-cyan-300">"2 Years Track Record"</span>,</div>
                                <div><span class="text-white/60">location:</span> <span class="text-amber-300">"Denpasar, Bali, ID"</span>,</div>
                                <div><span class="text-white/60">core_stack:</span> [
                                    <span class="text-amber-300">"PHP"</span>, 
                                    <span class="text-amber-300">"MySQL"</span>, 
                                    <span class="text-amber-300">"WordPress"</span>, 
                                    <span class="text-amber-300">"Tailwind"</span>, 
                                    <span class="text-amber-300">"360° WebGL"</span>
                                ],</div>
                                <div><span class="text-white/60">capabilities:</span> [
                                    <span class="text-emerald-400">"Virtual Tour 360°"</span>,
                                    <span class="text-emerald-400">"Custom Web (POS/SPP)"</span>,
                                    <span class="text-emerald-400">"WordPress Solutions"</span>,
                                    <span class="text-emerald-400">"IT Support & Hosting"</span>
                                ],</div>
                                <div><span class="text-white/60">status:</span> <span class="text-emerald-400">"READY_FOR_DEPLOYMENT"</span></div>
                            </div>
                            <div>};</div>
                            
                            <div class="pt-3 border-t border-white/10 flex items-center justify-between text-[11px] text-white/50">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-terminal text-crimson"></i>
                                    <span>Laragon / PHP 8 / Antigravity</span>
                                </span>
                                <span class="text-emerald-400">UPTIME: 100%</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION: HOSTING & INFRASTRUCTURE ECOSYSTEM (RUNNING MARQUEE) -->
    <section class="py-10 bg-cream-subtle border-b border-navy/10 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-12 mb-6 text-center">
            <div class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-widest text-crimson font-bold mb-2">
                <i class="fas fa-server text-[11px]"></i>
                <span>Infrastructure & Cloud Hosting Ecosystem</span>
            </div>
            <p class="text-xs sm:text-sm text-navy/75 max-w-2xl mx-auto font-sans leading-relaxed">
                Terbiasa menangani deployment website, manajemen DNS, konfigurasi cPanel, dan pemeliharaan server pada berbagai penyedia hosting terkemuka:
            </p>
        </div>

        <!-- Infinite Running Marquee Track -->
        <div class="marquee-wrapper">
            <!-- Track 1 -->
            <div class="marquee-track">
                <!-- Hostinger -->
                <div class="hosting-badge group">
                    <span class="w-8 h-8 rounded-full bg-[#673AB7]/10 flex items-center justify-center text-[#673AB7] shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M14.53 0l-5.6 4.88 5.6 4.87v4.88l-9.33-8.12V24l5.6-4.88-5.6-4.87v-4.88l9.33 8.12V0z"/></svg>
                    </span>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-sm text-navy font-sans tracking-tight">Hostinger</span>
                        <span class="text-[10px] text-navy/60 font-mono">Cloud & VPS Deployment</span>
                    </div>
                </div>

                <!-- Niagahoster -->
                <div class="hosting-badge group">
                    <span class="w-8 h-8 rounded-full bg-[#0020FC]/10 flex items-center justify-center text-[#0020FC] shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="6" fill="#0020FC"/><path d="M7 8l5 4.5L7 17h3.5l5-4.5-5-4.5H7z" fill="#FFFFFF"/></svg>
                    </span>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-sm text-navy font-sans tracking-tight">Niagahoster</span>
                        <span class="text-[10px] text-navy/60 font-mono">cPanel & Shared Hosting</span>
                    </div>
                </div>

                <!-- Rumahweb -->
                <div class="hosting-badge group">
                    <span class="w-8 h-8 rounded-full bg-[#1769FF]/10 flex items-center justify-center text-[#1769FF] shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="6" fill="#1769FF"/><path d="M12 5l7 6v8a1 1 0 01-1 1h-4v-5h-4v5H6a1 1 0 01-1-1v-8l7-6z" fill="#FFFFFF"/></svg>
                    </span>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-sm text-navy font-sans tracking-tight">Rumahweb</span>
                        <span class="text-[10px] text-navy/60 font-mono">Domain & Cloud Server</span>
                    </div>
                </div>

                <!-- cPanel / WHM -->
                <div class="hosting-badge group">
                    <span class="w-8 h-8 rounded-full bg-[#FF6C2C]/10 flex items-center justify-center text-[#FF6C2C] shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M10.74 3.72a3.86 3.86 0 0 0-3.86 3.86v8.84a3.86 3.86 0 0 0 3.86 3.86h3.69a1.05 1.05 0 0 0 1.05-1.05v-1.63a1.05 1.05 0 0 0-1.05-1.05h-3.69a1.27 1.27 0 0 1-1.27-1.27V8.65a1.27 1.27 0 0 1 1.27-1.27h3.69a1.05 1.05 0 0 0 1.05-1.05V4.7a1.05 1.05 0 0 0-1.05-1.05h-3.69z"/></svg>
                    </span>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-sm text-navy font-sans tracking-tight">cPanel & WHM</span>
                        <span class="text-[10px] text-navy/60 font-mono">Web Host Manager</span>
                    </div>
                </div>

                <!-- Cloudflare -->
                <div class="hosting-badge group">
                    <span class="w-8 h-8 rounded-full bg-[#F38020]/10 flex items-center justify-center text-[#F38020] shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.23 11.2a4.4 4.4 0 0 0-4.32-3.56 4.47 4.47 0 0 0-4.22 3.03A3.75 3.75 0 0 0 6 14.28c0 2.06 1.68 3.72 3.75 3.72h8.38A3.87 3.87 0 0 0 22 14.13a3.87 3.87 0 0 0-3.77-2.93z"/></svg>
                    </span>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-sm text-navy font-sans tracking-tight">Cloudflare</span>
                        <span class="text-[10px] text-navy/60 font-mono">DNS & SSL Security</span>
                    </div>
                </div>
            </div>

            <!-- Track 2 (Duplicate for Seamless Infinite Loop) -->
            <div class="marquee-track" aria-hidden="true">
                <!-- Hostinger -->
                <div class="hosting-badge group">
                    <span class="w-8 h-8 rounded-full bg-[#673AB7]/10 flex items-center justify-center text-[#673AB7] shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M14.53 0l-5.6 4.88 5.6 4.87v4.88l-9.33-8.12V24l5.6-4.88-5.6-4.87v-4.88l9.33 8.12V0z"/></svg>
                    </span>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-sm text-navy font-sans tracking-tight">Hostinger</span>
                        <span class="text-[10px] text-navy/60 font-mono">Cloud & VPS Deployment</span>
                    </div>
                </div>

                <!-- Niagahoster -->
                <div class="hosting-badge group">
                    <span class="w-8 h-8 rounded-full bg-[#0020FC]/10 flex items-center justify-center text-[#0020FC] shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="6" fill="#0020FC"/><path d="M7 8l5 4.5L7 17h3.5l5-4.5-5-4.5H7z" fill="#FFFFFF"/></svg>
                    </span>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-sm text-navy font-sans tracking-tight">Niagahoster</span>
                        <span class="text-[10px] text-navy/60 font-mono">cPanel & Shared Hosting</span>
                    </div>
                </div>

                <!-- Rumahweb -->
                <div class="hosting-badge group">
                    <span class="w-8 h-8 rounded-full bg-[#1769FF]/10 flex items-center justify-center text-[#1769FF] shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="6" fill="#1769FF"/><path d="M12 5l7 6v8a1 1 0 01-1 1h-4v-5h-4v5H6a1 1 0 01-1-1v-8l7-6z" fill="#FFFFFF"/></svg>
                    </span>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-sm text-navy font-sans tracking-tight">Rumahweb</span>
                        <span class="text-[10px] text-navy/60 font-mono">Domain & Cloud Server</span>
                    </div>
                </div>

                <!-- cPanel / WHM -->
                <div class="hosting-badge group">
                    <span class="w-8 h-8 rounded-full bg-[#FF6C2C]/10 flex items-center justify-center text-[#FF6C2C] shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M10.74 3.72a3.86 3.86 0 0 0-3.86 3.86v8.84a3.86 3.86 0 0 0 3.86 3.86h3.69a1.05 1.05 0 0 0 1.05-1.05v-1.63a1.05 1.05 0 0 0-1.05-1.05h-3.69a1.27 1.27 0 0 1-1.27-1.27V8.65a1.27 1.27 0 0 1 1.27-1.27h3.69a1.05 1.05 0 0 0 1.05-1.05V4.7a1.05 1.05 0 0 0-1.05-1.05h-3.69z"/></svg>
                    </span>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-sm text-navy font-sans tracking-tight">cPanel & WHM</span>
                        <span class="text-[10px] text-navy/60 font-mono">Web Host Manager</span>
                    </div>
                </div>

                <!-- Cloudflare -->
                <div class="hosting-badge group">
                    <span class="w-8 h-8 rounded-full bg-[#F38020]/10 flex items-center justify-center text-[#F38020] shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.23 11.2a4.4 4.4 0 0 0-4.32-3.56 4.47 4.47 0 0 0-4.22 3.03A3.75 3.75 0 0 0 6 14.28c0 2.06 1.68 3.72 3.75 3.72h8.38A3.87 3.87 0 0 0 22 14.13a3.87 3.87 0 0 0-3.77-2.93z"/></svg>
                    </span>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-sm text-navy font-sans tracking-tight">Cloudflare</span>
                        <span class="text-[10px] text-navy/60 font-mono">DNS & SSL Security</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: TENTANG SAYA -->
    <section id="tentang" class="py-20 md:py-28 bg-cream border-b border-navy/10">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left: Profile Identity Card -->
                <div class="lg:col-span-5">
                    <div class="card-craft p-8 relative overflow-hidden group">
                        <div class="w-32 h-40 sm:w-36 sm:h-44 rounded-2xl overflow-hidden mb-6 border-2 border-crimson/30 shadow-xl group-hover:border-crimson transition-all duration-300 bg-navy">
                            <img src="assets/images/profile.jpg" alt="<?= e($profile['full_name'] ?? 'I Ketut Aldy Pramana Putra') ?>" class="w-full h-full object-cover object-top">
                        </div>
                        <h3 class="font-serif text-3xl font-bold text-navy mb-1"><?= e($profile['full_name'] ?? 'I Ketut Aldy Pramana Putra') ?></h3>
                        <p class="font-mono text-xs text-crimson uppercase tracking-wider mb-4 font-semibold"><?= e($profile['title'] ?? 'IT Support & Full Stack Developer') ?></p>
                        
                        <div class="space-y-3 text-sm text-navy/80 border-t border-navy/10 pt-4">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs text-navy/60">Domisili:</span>
                                <span class="font-medium text-navy"><?= e($profile['location'] ?? 'Denpasar, Bali') ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs text-navy/60">Pengalaman:</span>
                                <span class="font-medium text-navy">2 Tahun</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs text-navy/60">Spesialisasi:</span>
                                <span class="font-medium text-navy">Hardware, Server, Web App</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs text-navy/60">Metode Kerja:</span>
                                <span class="font-medium text-crimson">Vibe Coding & Precision</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-navy/10 flex items-center gap-3">
                            <a href="generate-cv.php" target="_blank" class="w-full btn-cv justify-center text-center">
                                <i class="fas fa-file-pdf"></i>
                                <span>Unduh Lembar CV Lengkap</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right: Narasi Filosofi Kerja -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="section-kicker">Filosofi & Rekam Jejak</div>
                    <h2 class="font-serif text-4xl sm:text-5xl text-navy leading-tight">
                        Menggabungkan ketelitian hardware dengan arsitektur web modern.
                    </h2>
                    <p class="text-base text-navy/80 leading-relaxed">
                        <?= nl2br(e($profile['bio'] ?? '10 tahun berkecimpung di dunia IT, dari troubleshooting infrastruktur hingga membangun aplikasi web full stack. Saya percaya bahwa teknologi yang baik adalah teknologi yang benar-benar memecahkan masalah nyata.')) ?>
                    </p>
                    <p class="text-base text-navy/80 leading-relaxed">
                        Banyak developer hanya paham kode di layar, dan banyak teknisi jaringan hanya paham kabel di server rack. Saya menjembatani keduanya: memahami bagaimana data mengalir dari switch jaringan fisik hingga dirender secara presisi di browser pengguna.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                        <div class="p-4 rounded-xl bg-cream-subtle border border-navy/10">
                            <div class="font-mono text-xs text-crimson font-bold uppercase tracking-wider mb-1">01 / Ketahanan Sistem</div>
                            <p class="text-xs text-navy/70 leading-relaxed">Website dan server dirawat dengan standar keamanan tinggi dan pemantauan berkala agar zero downtime.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-cream-subtle border border-navy/10">
                            <div class="font-mono text-xs text-crimson font-bold uppercase tracking-wider mb-1">02 / Eksekusi Cepat</div>
                            <p class="text-xs text-navy/70 leading-relaxed">Penyelesaian project joki dan web klien dengan deadline ketat tanpa mengorbankan kualitas kode.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION: LAYANAN & SOLUSI -->
    <section id="layanan" class="py-20 md:py-28 bg-cream-subtle border-b border-navy/10">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            <div class="max-w-2xl mb-16">
                <div class="section-kicker">Layanan & Solusi</div>
                <h2 class="font-serif text-4xl sm:text-5xl text-navy leading-tight">
                    Solusi teknis yang siap membantu bisnis dan kebutuhan digital Anda.
                </h2>
                <p class="text-sm text-navy/70 mt-3">
                    Setiap layanan dikerjakan dengan standar profesional 2 tahun pengalaman intensif, baik untuk korporat, UMKM, maupun personal.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($services as $srv): ?>
                <div class="card-craft flex flex-col justify-between group hover:border-crimson/40">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-navy text-cream flex items-center justify-center text-lg mb-6 group-hover:bg-crimson group-hover:text-white transition-colors duration-300">
                            <i class="<?= e($srv['icon'] ?? 'fas fa-check') ?>"></i>
                        </div>
                        <h3 class="font-serif text-2xl font-bold text-navy mb-3 group-hover:text-crimson transition-colors">
                            <?= e($srv['title']) ?>
                        </h3>
                        <p class="text-sm text-navy/70 leading-relaxed mb-6">
                            <?= e($srv['description']) ?>
                        </p>
                    </div>
                    <div class="pt-4 border-t border-navy/10 flex items-center justify-between text-xs font-mono text-navy/60">
                        <span>STATUS: READY</span>
                        <a href="#kontak" class="text-crimson font-bold hover:underline flex items-center gap-1">
                            <span>Pesan Layanan</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- SECTION: PORTOFOLIO / PROYEK -->
    <section id="portofolio" class="py-20 md:py-28 bg-cream border-b border-navy/10">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div class="max-w-2xl">
                    <div class="section-kicker">Portofolio Pilihan</div>
                    <h2 class="font-serif text-4xl sm:text-5xl text-navy leading-tight">
                        Karya yang telah dibangun dan dirawat dengan presisi.
                    </h2>
                </div>
                <div class="font-mono text-xs text-navy/60">
                    <span>MENAMPILKAN <?= count($projects) ?> PROYEK PILIHAN</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach ($projects as $idx => $proj): ?>
                <div class="card-craft flex flex-col justify-between group hover:border-navy <?= $idx === 0 ? 'border-crimson/50 bg-gradient-to-b from-cream to-cream-subtle shadow-md md:col-span-2 lg:col-span-1' : '' ?>">
                    <div>
                        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <span class="inline-block px-3 py-1 rounded-full bg-cream border border-navy/10 text-xs font-mono text-navy/70">
                                    <?= e($proj['category'] ?? 'Web Application') ?>
                                </span>
                                <?php if ($idx === 0): ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-crimson text-white text-[10px] font-mono font-bold tracking-wider uppercase shadow-sm">
                                    <i class="fas fa-star text-[9px]"></i>
                                    <span>SOROTAN UTAMA</span>
                                </span>
                                <?php endif; ?>
                            </div>
                            <span class="font-mono text-xs text-crimson font-bold">#<?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?></span>
                        </div>

                        <h3 class="font-serif text-2xl sm:text-3xl font-bold text-navy mb-3 group-hover:text-crimson transition-colors">
                            <?= e($proj['title']) ?>
                        </h3>

                        <p class="text-sm text-navy/80 leading-relaxed mb-6">
                            <?= e($proj['description']) ?>
                        </p>
                    </div>

                    <div>
                        <div class="pt-4 border-t border-navy/10 flex flex-wrap gap-2 mb-4">
                            <?= techBadges($proj['tech_stack'] ?? '') ?>
                        </div>

                        <?php if (!empty($proj['project_url']) || !empty($proj['github_url'])): ?>
                        <div class="flex items-center gap-4 text-xs font-mono">
                            <?php if (!empty($proj['project_url'])): ?>
                            <a href="<?= e($proj['project_url']) ?>" target="_blank" class="text-crimson font-semibold hover:underline flex items-center gap-1.5">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Kunjungi Website</span>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($proj['github_url'])): ?>
                            <a href="<?= e($proj['github_url']) ?>" target="_blank" class="text-navy/70 hover:text-navy flex items-center gap-1.5">
                                <i class="fab fa-github"></i>
                                <span>Source Code</span>
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- SECTION: PENGALAMAN KERJA (2 TAHUN) -->
    <section id="pengalaman" class="py-20 md:py-28 bg-cream-subtle border-b border-navy/10">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            <div class="max-w-2xl mb-16">
                <div class="section-kicker">Rekam Jejak 2 Tahun</div>
                <h2 class="font-serif text-4xl sm:text-5xl text-navy leading-tight">
                    Pengalaman nyata yang membentuk keahlian dan cara pandang teknis.
                </h2>
                <p class="text-sm text-navy/70 mt-3">
                    Konsisten berkembang dari teknisi hardware, administrator server, hingga arsitek aplikasi web full-stack.
                </p>
            </div>

            <!-- Timeline -->
            <div class="relative border-l-2 border-navy/20 pl-6 md:pl-10 ml-2 md:ml-6 space-y-12">
                <?php foreach ($experiences as $exp): ?>
                <div class="relative group">
                    <!-- Timeline Dot -->
                    <div class="absolute -left-[33px] md:-left-[49px] top-1.5 w-4 h-4 rounded-full bg-cream border-4 <?= !empty($exp['is_current']) ? 'border-crimson' : 'border-navy' ?>"></div>

                    <div class="flex flex-wrap items-center gap-3 mb-2">
                        <span class="font-mono text-xs px-2.5 py-1 rounded bg-navy text-cream font-medium">
                            <?= formatDate($exp['start_date']) ?> — <?= !empty($exp['is_current']) ? 'Sekarang' : formatDate($exp['end_date']) ?>
                        </span>
                        <?php if (!empty($exp['is_current'])): ?>
                        <span class="font-mono text-xs text-crimson font-semibold uppercase tracking-wider">● PERAN AKTIF</span>
                        <?php endif; ?>
                    </div>

                    <h3 class="font-serif text-2xl font-bold text-navy">
                        <?= e($exp['role']) ?>
                    </h3>
                    <div class="text-sm font-medium text-navy/80 mb-3 flex items-center gap-2">
                        <span><?= e($exp['company']) ?></span>
                        <?php if (!empty($exp['location'])): ?>
                        <span>•</span>
                        <span class="text-navy/60 font-mono text-xs"><?= e($exp['location']) ?></span>
                        <?php endif; ?>
                    </div>

                    <p class="text-sm text-navy/75 leading-relaxed max-w-3xl">
                        <?= nl2br(e($exp['description'])) ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- SECTION: KEAHLIAN & TOOLKIT -->
    <section id="keahlian" class="py-20 md:py-28 bg-cream border-b border-navy/10">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            <div class="max-w-2xl mb-16">
                <div class="section-kicker">Keahlian & Toolkit</div>
                <h2 class="font-serif text-4xl sm:text-5xl text-navy leading-tight">
                    Teknologi yang dikuasai untuk menyelesaikan pekerjaan.
                </h2>
                <p class="text-sm text-navy/70 mt-3">
                    Bukan sekadar daftar nama, melainkan alat kerja yang dipakai setiap hari untuk membangun dan menjaga sistem.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($skillsGrouped as $category => $items): ?>
                <div class="p-6 rounded-2xl bg-cream-subtle border border-navy/10">
                    <h3 class="font-mono text-xs uppercase tracking-widest text-crimson font-bold pb-3 mb-4 border-b border-navy/10 flex items-center justify-between">
                        <span><?= e($category) ?></span>
                        <span class="text-[10px] text-navy/50 font-normal"><?= count($items) ?> item</span>
                    </h3>
                    <ul class="space-y-3">
                        <?php foreach ($items as $skill): ?>
                        <li class="flex items-center gap-3 text-sm text-navy font-medium group">
                            <span class="w-7 h-7 rounded-lg bg-cream border border-navy/10 flex items-center justify-center text-xs text-navy group-hover:text-crimson group-hover:border-crimson transition-colors">
                                <i class="<?= e($skill['icon'] ?? 'fas fa-check') ?>"></i>
                            </span>
                            <span><?= e($skill['name']) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- SECTION: KONTAK & KONSULTASI -->
    <section id="kontak" class="py-20 md:py-28 bg-cream-subtle blueprint-grid">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Left: Contact Info -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="section-kicker">Mulai Kolaborasi</div>
                    <h2 class="font-serif text-4xl sm:text-5xl text-navy leading-tight">
                        Ada kebutuhan project, maintenance, atau IT Support?
                    </h2>
                    <p class="text-base text-navy/80 leading-relaxed">
                        Saya selalu terbuka untuk mendiskusikan project website baru, perbaikan bug darurat, optimasi server, maupun konsultasi infrastruktur digital di Bali atau remote.
                    </p>

                    <div class="space-y-4 pt-4 border-t border-navy/10">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-navy text-cream flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-crimson"></i>
                            </div>
                            <div>
                                <span class="text-xs font-mono text-navy/60 block">LOKASI FISIK</span>
                                <span class="font-medium text-navy text-sm"><?= e($profile['location'] ?? 'Denpasar, Bali, Indonesia') ?></span>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-navy text-cream flex items-center justify-center">
                                <i class="fas fa-envelope text-crimson"></i>
                            </div>
                            <div>
                                <span class="text-xs font-mono text-navy/60 block">EMAIL LANGSUNG</span>
                                <a href="mailto:<?= e($profile['email'] ?? 'aldy@webprofile.dev') ?>" class="font-medium text-navy text-sm hover:text-crimson hover:underline">
                                    <?= e($profile['email'] ?? 'aldy@webprofile.dev') ?>
                                </a>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-navy text-cream flex items-center justify-center">
                                <i class="fab fa-whatsapp text-emerald-400"></i>
                            </div>
                            <div>
                                <span class="text-xs font-mono text-navy/60 block">WHATSAPP / TELEPON</span>
                                <a href="https://wa.me/6289653517850?text=Halo%20Aldy,%20saya%20tertarik%20dengan%20layanan%20web%20profile%20Anda." target="_blank" class="font-bold text-emerald-700 text-sm hover:underline block">
                                    0896-5351-7850
                                </a>
                                <span class="text-[11px] text-navy/60 font-mono">Klik untuk Chat Langsung &rarr;</span>
                            </div>
                        </div>
                    </div>

                    <!-- Direct CV Banner -->
                    <div class="p-6 rounded-2xl bg-navy text-cream space-y-3 mt-6">
                        <div class="flex items-center gap-2 text-xs font-mono text-crimson font-bold uppercase tracking-wider">
                            <i class="fas fa-file-pdf"></i>
                            <span>DOKUMEN RESMI</span>
                        </div>
                        <h4 class="font-serif text-xl font-bold">Perlu lembar Curriculum Vitae?</h4>
                        <p class="text-xs text-cream/70 leading-relaxed">
                            Unduh CV PDF otomatis yang langsung disinkronkan dari database web ini.
                        </p>
                        <a href="generate-cv.php" target="_blank" class="btn-primary w-full justify-center text-center text-xs">
                            <i class="fas fa-download"></i>
                            <span>Unduh CV PDF Sekarang</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Working Contact Form -->
                <div class="lg:col-span-7">
                    <div class="card-craft p-8 sm:p-10 bg-cream border border-navy/15 shadow-xl">
                        <h3 class="font-serif text-3xl font-bold text-navy mb-2">Kirim Pesan Langsung</h3>
                        <p class="text-sm text-navy/70 mb-6 font-sans">
                            Pesan Anda akan langsung masuk ke database dan notifikasi saya.
                        </p>

                        <form action="#kontak" method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="contact">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-mono uppercase tracking-wider text-navy/80 mb-2 font-semibold">Nama Lengkap *</label>
                                    <input type="text" name="name" required placeholder="Nama Anda / Perusahaan" 
                                           class="w-full px-4 py-3 rounded-xl bg-cream-subtle border border-navy/15 text-sm text-navy focus:outline-none focus:border-crimson transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-mono uppercase tracking-wider text-navy/80 mb-2 font-semibold">Alamat Email *</label>
                                    <input type="email" name="email" required placeholder="nama@email.com" 
                                           class="w-full px-4 py-3 rounded-xl bg-cream-subtle border border-navy/15 text-sm text-navy focus:outline-none focus:border-crimson transition-colors">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase tracking-wider text-navy/80 mb-2 font-semibold">Kategori Kebutuhan</label>
                                <input type="text" name="subject" placeholder="Misal: Pembuatan Web / Joki Project / Maintenance IT" 
                                       class="w-full px-4 py-3 rounded-xl bg-cream-subtle border border-navy/15 text-sm text-navy focus:outline-none focus:border-crimson transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase tracking-wider text-navy/80 mb-2 font-semibold">Deskripsi Pesan / Proyek *</label>
                                <textarea name="message" rows="5" required placeholder="Ceritakan kebutuhan, deadline, atau kendala teknis yang ingin diselesaikan..." 
                                          class="w-full px-4 py-3 rounded-xl bg-cream-subtle border border-navy/15 text-sm text-navy focus:outline-none focus:border-crimson transition-colors resize-none"></textarea>
                            </div>

                            <button type="submit" class="btn-primary w-full justify-center text-center py-3.5">
                                <i class="fas fa-paper-plane"></i>
                                <span>Kirim Pesan Sekarang</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
