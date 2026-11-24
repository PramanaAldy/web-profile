<?php
require_once __DIR__ . '/functions.php';
$profile = getProfile();
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($profile['full_name'] ?? 'I Ketut Aldy Pramana Putra') ?> — <?= e($profile['title'] ?? 'IT Support & Full Stack Developer') ?></title>
    <meta name="description" content="<?= e($profile['tagline'] ?? 'Senior Web Developer & IT Specialist berbasis di Bali dengan 10 tahun pengalaman.') ?>">

    <!-- Google Fonts: Instrument Serif, Space Grotesk, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=JetBrains+Mono:wght@400;500;600&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS (CDN Play) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            DEFAULT: '#0B132B',
                            light: '#1C2541',
                            dark: '#050914',
                        },
                        cream: {
                            DEFAULT: '#FBF9F5',
                            subtle: '#F3EFEA',
                            dark: '#E8E2D8',
                        },
                        crimson: {
                            DEFAULT: '#C9182B',
                            hover: '#A51423',
                            subtle: 'rgba(201, 24, 43, 0.08)',
                        }
                    },
                    fontFamily: {
                        serif: ['"Instrument Serif"', 'Georgia', 'serif'],
                        sans: ['"Space Grotesk"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
</head>
<body class="bg-cream text-navy font-sans antialiased selection:bg-crimson selection:text-white">

    <!-- Navigation Header -->
    <header x-data="{ mobileOpen: false, scrolled: false }" 
            @scroll.window="scrolled = (window.pageYOffset > 30)"
            :class="scrolled ? 'bg-cream/90 backdrop-blur-md shadow-sm border-b border-navy/10 py-3' : 'bg-transparent py-6'"
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 md:px-12 flex items-center justify-between">
            
            <!-- Logo / Monogram -->
            <a href="#hero" class="flex items-center gap-3 group text-decoration-none">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-navy/20 group-hover:border-crimson transition-all duration-300 shadow-sm shrink-0">
                    <img src="assets/images/profile.jpg" alt="Aldy Pramana" class="w-full h-full object-cover object-top">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm tracking-tight text-navy">ALDY PRAMANA</span>
                    <span class="font-mono text-[10px] text-navy/60 uppercase tracking-widest">Bali, Indonesia</span>
                </div>
            </a>

            <!-- Desktop Nav Menu -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-navy/80">
                <a href="#tentang" class="hover:text-crimson transition-colors">Tentang</a>
                <a href="#layanan" class="hover:text-crimson transition-colors">Layanan</a>
                <a href="#portofolio" class="hover:text-crimson transition-colors">Portofolio</a>
                <a href="#pengalaman" class="hover:text-crimson transition-colors">Pengalaman</a>
                <a href="#keahlian" class="hover:text-crimson transition-colors">Keahlian</a>
                <a href="#kontak" class="hover:text-crimson transition-colors">Kontak</a>
            </nav>

            <!-- Actions / Status & CV Download -->
            <div class="hidden md:flex items-center gap-4">
                <?php if (!empty($profile['is_available'])): ?>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-mono">
                    <span class="pulse-available"></span>
                    <span><?= e($profile['availability_text'] ?? 'Available') ?></span>
                </div>
                <?php endif; ?>

                <a href="generate-cv.php" target="_blank" class="btn-cv" title="Download CV PDF yang tergenerate otomatis dari data profil">
                    <i class="fas fa-file-pdf text-crimson"></i>
                    <span>Unduh CV</span>
                </a>
            </div>

            <!-- Mobile Hamburger -->
            <button @click="mobileOpen = !mobileOpen" class="md:hidden text-navy p-2 focus:outline-none" aria-label="Toggle Menu">
                <i :class="mobileOpen ? 'fas fa-times text-xl' : 'fas fa-bars text-xl'"></i>
            </button>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-cream-subtle border-b border-navy/10 px-6 py-6 shadow-xl"
             style="display: none;">
            <div class="flex flex-col gap-4 text-base font-medium">
                <a @click="mobileOpen = false" href="#tentang" class="py-2 border-b border-navy/5 text-navy hover:text-crimson">Tentang</a>
                <a @click="mobileOpen = false" href="#layanan" class="py-2 border-b border-navy/5 text-navy hover:text-crimson">Layanan</a>
                <a @click="mobileOpen = false" href="#portofolio" class="py-2 border-b border-navy/5 text-navy hover:text-crimson">Portofolio</a>
                <a @click="mobileOpen = false" href="#pengalaman" class="py-2 border-b border-navy/5 text-navy hover:text-crimson">Pengalaman</a>
                <a @click="mobileOpen = false" href="#keahlian" class="py-2 border-b border-navy/5 text-navy hover:text-crimson">Keahlian</a>
                <a @click="mobileOpen = false" href="#kontak" class="py-2 border-b border-navy/5 text-navy hover:text-crimson">Kontak</a>
                
                <div class="pt-4 flex flex-col gap-3">
                    <a href="generate-cv.php" target="_blank" class="btn-primary justify-center text-center">
                        <i class="fas fa-file-pdf"></i>
                        <span>Unduh CV (PDF Otomatis)</span>
                    </a>
                </div>
            </div>
        </div>
    </header>
