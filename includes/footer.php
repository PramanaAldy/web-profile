<?php
$profile = getProfile();
?>
    <!-- Footer -->
    <footer class="bg-navy text-cream pt-16 pb-12 border-t border-white/10 blueprint-grid-dark">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 pb-16 border-b border-white/10">
                
                <!-- Brand & Bio -->
                <div class="md:col-span-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full overflow-hidden border-2 border-white/20 shadow-md shrink-0">
                            <img src="assets/images/profile.jpg" alt="Aldy Pramana" class="w-full h-full object-cover object-top">
                        </div>
                        <div>
                            <span class="font-bold text-lg text-cream block"><?= e($profile['full_name'] ?? 'I Ketut Aldy Pramana Putra') ?></span>
                            <span class="text-xs text-cream/60 font-mono"><?= e($profile['title'] ?? 'IT Support & Full Stack Developer') ?></span>
                        </div>
                    </div>
                    <p class="text-sm text-cream/70 max-w-sm leading-relaxed">
                        Membangun infrastruktur IT yang kokoh dan website berkinerja tinggi. Berpengalaman 2 tahun menyelesaikan berbagai tantangan teknologi dari Denpasar, Bali.
                    </p>
                    <div class="inline-flex items-center gap-2 text-xs font-mono text-cream/60">
                        <i class="fas fa-map-marker-alt text-crimson"></i>
                        <span><?= e($profile['location'] ?? 'Denpasar, Bali, Indonesia') ?></span>
                    </div>
                </div>

                <!-- Navigasi Cepat -->
                <div class="md:col-span-3 space-y-3">
                    <h4 class="font-mono text-xs uppercase tracking-widest text-crimson font-semibold">Navigasi</h4>
                    <ul class="space-y-2 text-sm text-cream/80 font-sans">
                        <li><a href="#tentang" class="hover:text-cream hover:underline transition-all">Tentang Saya</a></li>
                        <li><a href="#layanan" class="hover:text-cream hover:underline transition-all">Layanan & Solusi</a></li>
                        <li><a href="#portofolio" class="hover:text-cream hover:underline transition-all">Portofolio Proyek</a></li>
                        <li><a href="#pengalaman" class="hover:text-cream hover:underline transition-all">Riwayat Pengalaman</a></li>
                        <li><a href="#keahlian" class="hover:text-cream hover:underline transition-all">Keahlian Teknis</a></li>
                    </ul>
                </div>

                <!-- Quick Action & CV -->
                <div class="md:col-span-4 space-y-4">
                    <h4 class="font-mono text-xs uppercase tracking-widest text-crimson font-semibold">Dokumentasi Resmi</h4>
                    <p class="text-xs text-cream/70 leading-relaxed">
                        CV selalu disinkronkan secara otomatis sesuai data web profile ini. Klik untuk mengunduh versi PDF resmi.
                    </p>
                    <div class="pt-1">
                        <a href="generate-cv.php" target="_blank" class="btn-primary text-xs py-2.5 px-5 justify-center inline-flex">
                            <i class="fas fa-file-pdf"></i>
                            <span>Unduh CV (PDF)</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Credits -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-cream/50 font-mono gap-4">
                <div>
                    &copy; <?= date('Y') ?> <?= e($profile['full_name'] ?? 'I Ketut Aldy Pramana Putra') ?>. All rights reserved.
                </div>
                <div class="flex items-center gap-4">
                    <span>Crafted with Human Precision</span>
                    <span>•</span>
                    <span class="text-crimson">Vibe Coding & Antigravity</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
