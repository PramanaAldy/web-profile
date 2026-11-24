# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

PHP 8.x + MySQL (via Laragon — sudah tersedia di environment)
- Frontend: HTML5, CSS3 vanilla + Alpine.js (interaktivitas ringan)
- Styling: Tailwind CSS (utility-first, tidak pasaran jika dikonfigurasi custom)
- PDF CV: Dompdf (generate PDF dari HTML/CSS — otomatis sinkron dengan data web)
- Admin: Vanilla PHP dengan session auth (sederhana, tidak over-engineered)
- Font: Instrument Serif (display) + Space Grotesk (body) + JetBrains Mono (kode)

## Users

**Pengunjung utama:** Recruiter, klien bisnis, sesama developer, dan hiring manager yang ingin menilai kemampuan dan track record seorang developer berbakat berpengalaman 2 tahun.

**Admin:** Pemilik website (senior developer itu sendiri) yang mengelola konten melalui panel admin tanpa menyentuh kode.

## Product Purpose

Web profile profesional berganda fungsi:
1. Menampilkan kemampuan, skill, pengalaman, dan karya seorang senior developer
2. Membangun personal brand yang kuat dan berkesan
3. Menyediakan CV yang selalu up-to-date dan bisa diunduh langsung sebagai PDF
4. Dikelola melalui admin panel tanpa perlu coding

Sukses = pengunjung memahami dalam 10 detik bahwa ini developer senior kelas atas, dan langsung tahu cara menghubunginya.

## Positioning

Bukan sekadar portfolio — ini adalah *professional presence* seorang engineer: terstruktur, berwibawa, dan setiap detail mencerminkan 2 tahun pengalaman nyata. CV yang diunduh identik dengan apa yang ditampilkan di web, bukan dokumen terpisah.

## Operating Context

- Dikunjungi dari desktop dan mobile
- Pengunjung menghabiskan waktu singkat — kesan pertama sangat menentukan
- Admin dikelola sendiri oleh developer — form-based, tidak perlu dokumentasi
- Hosting: Laragon local (development), bisa deploy ke VPS/shared hosting

## Capabilities and Constraints

**Fitur wajib:**
- [ ] Hero section: nama, jabatan, tagline, status ketersediaan
- [ ] About: bio singkat + foto profil
- [ ] Skills: grouped by category (Frontend, Backend, DevOps, dll)
- [ ] Experience: timeline pekerjaan dengan detail peran dan pencapaian
- [ ] Projects/Portfolio: kartu proyek dengan deskripsi, tech stack, link
- [ ] Contact: form + link sosial
- [ ] Download CV: PDF otomatis ter-generate dari data database (Dompdf)
- [ ] Admin Panel: CRUD untuk semua section di atas
- [ ] Admin Auth: login dengan session PHP

**Tech constraints:**
- PHP 8.x, MySQL, berjalan di Laragon
- Tanpa framework besar (no Laravel/CodeIgniter) — vanilla PHP + PDO
- Desain bukan template — dibuat custom

**Nama:** I Ketut Aldy Pramana Putra
**Jabatan:** IT Support & Full Stack Developer
**Lokasi:** Denpasar, Bali
**[CLOSED]** Foto profil — `assets/images/profile.jpg` (jas formal latar merah)

## Brand Commitments

**Palet warna (binding):**
- Navy: `#0B132B` (primary structure, text berat)
- Cream: `#FBF9F5` (background utama, hangat dan premium)
- Red: `#A8201A` (aksen, CTA, highlight interaktif)

**Karakter:** Editorial, berwibawa, presisi — bukan gimmick. Seperti arsitektur, bukan dekorasi.

**Tipografi:**
- Display: Instrument Serif (judul besar, editorial)
- Body: Space Grotesk (teks bersih, modern)
- Mono: JetBrains Mono (kode, label teknis)

## Evidence on Hand

- Nama: I Ketut Aldy Pramana Putra
- Jabatan: IT Support & Full Stack Developer
- Lokasi: Denpasar, Bali
- Skill utama: Vibe Coding, PHP, Full Stack Development, Web Maintenance, Project Joki Website
- Konten project & experience: diisi melalui admin panel
- Foto profil: disediakan oleh pemilik

## Product Principles

1. **Kesan pertama menentukan** — hero section menyampaikan senioritas dalam 5 detik
2. **Satu sumber kebenaran** — semua konten di web = apa yang ada di CV PDF
3. **Presisi di atas dekorasi** — setiap elemen punya tujuan, tidak ada hiasan kosong
4. **Mudah dikelola** — admin panel intuitif, tidak perlu dokumentasi
5. **Performa dan aksesibilitas** — cepat, responsif, dapat dibaca di semua device

## Accessibility & Inclusion

Kontras warna yang kuat (navy on cream), font yang terbaca, struktur semantik HTML yang benar.
