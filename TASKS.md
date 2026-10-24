# TASKS.md — Rencana Kerja Frontend-First: Fitur Publik SIAKAD SMP Muhammadiyah 7 Medan

**Versi Dokumen:** v1.0  
**Tanggal:** 24 Oktober 2026  
**Peran:** Senior Frontend Architect, UI Implementation Planner, & AI Coding Agent  
**Acuan Utama:** `PRD.md` (v0.2), `DESIGN.md` (v1.0), `ARCHITECTURE.md` (v0.1)  
**Status Eksekusi:** Siap Dikerjakan (Ready for Execution)

---

## 1. Project Overview

Website Publik SIAKAD SMP Muhammadiyah 7 Medan merupakan portal informasi satu pintu sekolah berbasis **Laravel 12**, **Blade templating**, **Tailwind CSS v4**, dan **Vite** yang dapat diakses oleh masyarakat (orang tua, calon siswa, alumni, umum) tanpa login.

Website ini bertindak sebagai pintu gerbang resmi sekolah dengan fitur utama:
1. **Beranda:** Hero identitas sekolah, 3 kartu pintasan portal (CBT, Rapor, PPDB), sambutan kepala sekolah, berita terbaru, pengumuman terbaru, dan statistik dummy.
2. **Profil Sekolah:** Sub-halaman Tentang Sekolah (visi, misi, kontak, alamat), Kepala Sekolah (sambutan lengkap), Struktur Organisasi, dan Sarana Prasarana (fasilitas).
3. **Direktori Guru & Siswa:** Pencarian dan filter direktori yang bersumber langsung dari database RKM (CodeIgniter 3) yang dibaca secara *read-only* dengan aturan privasi ketat (tanpa NIP/NIS/NIK/foto/kontak pribadi).
4. **Kesiswaan:** Organisasi Siswa (IPM dsb), Ekstrakurikuler, dan Prestasi Siswa.
5. **Galeri:** Dokumentasi foto kegiatan sekolah yang teroptimasi dengan *lazy loading* dan format thumbnail.
6. **Berita & Pengumuman:** Halaman daftar (katalog) dan detail artikel dengan format slug.
7. **Pintasan Akses Eksternal/Internal:** Tautan keluar tab yang sama ke CBT, Rapor, PPDB (placeholder), dan tombol Login SIAKAD.

---

## 2. Frontend-First Strategy

Pendekatan **Frontend-First** menjamin antarmuka, tata letak visual, pengalaman pengguna (*user flow*), komponen desain (*design tokens & reusable UI components*), dan interaktivitas sisi klien dibangun, diuji, dan dievaluasi terlebih dahulu sebelum terikat penuh dengan logika database dan backend.

### Prinsip Utama Penerapan:
1. **Fondasi Visual & Desain Terlebih Dahulu:** Konfigurasi design tokens (Forest Green, Sage, Mint, Dark Green, Soft Background) pada Tailwind CSS v4 diatur sebelum menyusun komponen Blade apa pun.
2. **Komponen Bersama (Reusable Blade Components):** Seluruh elemen berulang (tombol, kartu berita, kartu direktori, pagination, breadcrumb, filter, modal/drawer mobile) dibuat mandiri sesuai spesifikasi `DESIGN.md` Bab 15.
3. **Mock Data / Fixtures yang Terstruktur:** Konten statis/dummy untuk profil, berita, pengumuman, fasilitas, organisasi, ekstrakurikuler, prestasi, galeri, dan direktori ditempatkan pada layer data terisolasi (`config/sekolah.php` dan `database/dummy/*.php` atau `app/Repositories/Dummy/`) dengan kontrak data yang identik dengan model database masa depan.
4. **Isolasi Tampilan dari Sumber Data:** Blade View hanya menerima variabel dari Controller / Repository; Blade tidak boleh melakukan *query* database langsung atau membedah file JSON/konfigurasi secara manual.
5. **Mobile-First & Aksesibel:** Setiap komponen dan layout dirancang adaptif untuk layar seluler (< 640px) terlebih dahulu, memastikan *touch target* minimal 44x44 px, kontras WCAG AA, navigasi keyboard, dan atribut semantik HTML.
6. **Kesiapan Integrasi RKM yang Terkontrol:** Antarmuka direktori guru dan siswa disiapkan dengan *mock fixtures* realistis (termasuk *empty states*, *long names*, dan *pagination*), kemudian dihubungkan ke database RKM melalui Model Eloquent *read-only* / Query Builder setelah struktur tabel diverifikasi.

---

## 3. Implementation Phases

Proses pengerjaan dibagi menjadi 7 fase logis:

- **Phase 0: Frontend Foundation & Build Setup**  
  Pengecekan *environment*, penyelarasan dependensi npm/composer, konfigurasi Tailwind CSS v4 token pada `resources/css/app.css`, struktur folder arsitektur Blade & data dummy.
- **Phase 1: Design System & Reusable Components**  
  Implementasi design tokens, tipografi, warna, dan seluruh komponen Blade generik & spesifik (Button, Cards, Badges, Search, Filter, Pagination, Empty State, Skeletons).
- **Phase 2: Global Layout & Navigation Shell**  
  Penyusunan Master Layout publik, Global Responsive Navbar dengan dropdown desktop dan mobile drawer/hamburger, Footer 4-kolom, dan Breadcrumb.
- **Phase 3: Page Structure & Visual Implementation (MVP Pages)**  
  Implementasi visual ke-16 rute halaman MVP menggunakan Blade templates dan dummy fixtures.
- **Phase 4: Frontend Interactions, Client State & Mock Data Fixtures**  
  Pengembangan interaktivitas frontend (menu mobile toggle, filter & pencarian berbasis query parameter / UI state, fixture pagination, image lazy loading, penandaan data dummy).
- **Phase 5: Frontend Quality Assurance & Accessibility**  
  Audit responsive breakpoints, navigasi keyboard, WCAG AA contrast check, validasi privasi direktori, audit performa Lighthouse, dan optimasi aset WebP/SVG.
- **Phase 6: Backend Integration & Data Layer Binding**  
  Penyambungan Controller tipis dengan Repository Pattern, migrasi binding direktori guru/siswa ke database RKM *read-only*, verifikasi penanganan error/koneksi database, dan *end-to-end smoke test*.

---

## 4. Milestone Summary

| Milestone | Fase | Sasaran Utama | Target Deliverables | Dependensi |
|---|---|---|---|---|
| **M0: Foundation Ready** | Phase 0 | Fondasi frontend, build Vite, tokens Tailwind v4 | `app.css` tersinkronisasi, folder resources & dummy terstruktur, Vite build lolos | Tidak ada |
| **M1: Design System Library** | Phase 1 | Komponen Blade reusable siap pakai | 14+ reusable Blade UI components terdokumentasi dan teruji | M0 |
| **M2: Global Shell Active** | Phase 2 | Master layout, navbar responsif, dan footer | `layouts/public.blade.php`, `navbar.blade.php`, `footer.blade.php` berfungsi responsif | M1 |
| **M3: Public Pages Visual MVP** | Phase 3 | Seluruh halaman MVP tampil utuh dengan mock data | Beranda, Profil (4), Direktori (2), Kesiswaan (3), Galeri (1), Berita (2), Pengumuman (2), Login redirect | M2 |
| **M4: Interactive Experience** | Phase 4 | Interaksi navigasi, filter mockup, search UI, pagination | Hamburger menu mulus, query string filter direktori bekerja, fixture dummy terisolasi | M3 |
| **M5: Quality & Accessibility Pass** | Phase 5 | Standar performa ringan, responsive lolos, WCAG AA | Laporan audit responsif (<640px, tablet, desktop), validasi privasi DIR-8, Lighthouse score tinggi | M4 |
| **M6: Production & RKM Integration** | Phase 6 | Repository terhubung ke RKM DB read-only, cache file | Direktori membaca tabel RKM, error handling database aktif, aset build siap rilis | M5, BE-RKM-001 |

---

## 5. Detailed Task Checklist

### Phase 0: Frontend Foundation & Build Setup

#### [FE-SETUP-001] Validasi Lingkungan Proyek dan Konfigurasi Build Tools
- **Phase:** Phase 0
- **Priority:** P0
- **Status:** Not Started
- **Category:** Setup
- **Description:** Memeriksa package Vite, Tailwind CSS v4, konfigurasi scripts, dan memastikan `npm run build` serta `npm run dev` berjalan tanpa kendala pada lingkungan lokal.
- **Source Requirements:** `ARCHITECTURE.md` Bagian 4 & 9; `DESIGN.md` Bab 18.
- **Dependencies:** None.
- **Implementation Notes:**
  - Proyek sudah memiliki `@tailwindcss/vite` v4.0.0 dan `tailwindcss` v4.0.0 di `package.json`.
  - Pastikan `vite.config.js` mengimpor `laravel-vite-plugin` dan `@tailwindcss/vite` dengan benar.
- **Acceptance Criteria:**
  - [ ] Perintah `npm run build` menghasilkan aset di `public/build` tanpa galat.
  - [ ] Hot module replacement / dev mode `npm run dev` dapat berjalan normal.
- **Verification:** Jalankan `npm run build` melalui terminal dan periksa direktori `public/build/manifest.json`.

---

#### [FE-SETUP-002] Definisi Design Tokens dan Tema Tailwind CSS v4
- **Phase:** Phase 0
- **Priority:** P0
- **Status:** Not Started
- **Category:** Design System
- **Description:** Mendaftarkan seluruh token warna, tipografi sans-serif, border-radius, dan container utility di `resources/css/app.css` menggunakan sintaks `@theme` bawaan Tailwind CSS v4.
- **Source Requirements:** `DESIGN.md` Bab 2 (Color Palette), Bab 3 (Tipografi), Bab 4 (Layout), Bab 18 (Aturan Tailwind).
- **Dependencies:** `FE-SETUP-001`
- **Implementation Notes:**
  - Daftarkan warna: Forest Green (`#344B39`), Sage Green (`#819A73`), Mint Green (`#B8CCC3`), Teal Green (`#3F5F58`), Dark Green (`#0F1D0F`), Olive Green (`#697B65`), Soft Background (`#F7F9F6`), Light Border (`#E5EAE3`), Muted Text (`#647067`).
  - Atur font sans-serif default menggunakan system font stack ringan (`system-ui, -apple-system, sans-serif`).
  - Tentukan border radius standar: button (10-12px / `rounded-xl`), card (16-20px / `rounded-2xl`).
- **Acceptance Criteria:**
  - [ ] `@theme` di `resources/css/app.css` memuat utilitas kelas warna kustom (`bg-forest`, `text-darkgreen`, `bg-soft`, `border-border-soft`, `text-sage`, dsb.).
  - [ ] Kontras warna teks memenuhi standar WCAG AA.
- **Verification:** Uji utility kelas di view contoh dan periksa hasil kompilasi CSS dengan `npm run build`.

---

#### [FE-SETUP-003] Strukturisasi Folder Frontend Blade dan Storage Aset
- **Phase:** Phase 0
- **Priority:** P1
- **Status:** Not Started
- **Category:** Setup
- **Description:** Menyiapkan struktur subdirektori modular di dalam `resources/views/` dan `public/images/` sesuai arsitektur yang disepakati.
- **Source Requirements:** `ARCHITECTURE.md` Bagian 7; `DESIGN.md` Bab 17 & 19.
- **Dependencies:** `FE-SETUP-001`
- **Implementation Notes:**
  - Buat subfolder di `resources/views/`: `layouts/`, `components/`, `pages/home/`, `pages/profile/`, `pages/directory/`, `pages/student-life/`, `pages/gallery/`, `pages/news/`, `pages/announcements/`.
  - Buat subfolder di `public/images/`: `school/`, `principal/`, `facilities/`, `student-life/`, `gallery/`, `news/`, `placeholders/`.
- **Acceptance Criteria:**
  - [ ] Seluruh direktori Blade dan aset gambar publik tersedia sesuai konvensi.
  - [ ] Disediakan file SVG atau WebP placeholder netral di `public/images/placeholders/` untuk gambar yang belum ada.
- **Verification:** Periksa susunan folder dengan perintah CLI file explorer.

---

### Phase 1: Design System and Shared Components

#### [FE-DS-001] Reusable Base UI Components (Button, Badge, Icon Wrapper)
- **Phase:** Phase 1
- **Priority:** P1
- **Status:** Not Started
- **Category:** Design System
- **Description:** Membuat komponen dasar Blade untuk tombol aksi, pill badge/tag kategori, dan wadah ikon SVG sebaris (inline SVG).
- **Source Requirements:** `PRD.md` NAV-7; `DESIGN.md` Bab 4.3, 15.2, 18.3.
- **Dependencies:** `FE-SETUP-002`, `FE-SETUP-003`
- **Implementation Notes:**
  - `resources/views/components/button.blade.php`: Mendukung varian primary (Forest Green, hover Teal), secondary (border lembut, text Forest), dan ukuran responsif (min touch target 44px).
  - `resources/views/components/badge.blade.php`: Bentuk pill, latar Mint Green / Soft Green, teks Dark Green / Olive.
  - `resources/views/components/icon.blade.php`: Render inline SVG bersih tanpa font icon eksternal.
- **Acceptance Criteria:**
  - [ ] Komponen tombol mendukung tag `<a>` maupun `<button>` via atribut Blade `$attributes`.
  - [ ] Tombol memiliki status hover, focus-visible dengan outline offset 2px, dan active state yang jelas.
- **Verification:** Render komponen di view pengetesan dan verifikasi tampilan via browser.

---

#### [FE-DS-002] Section Heading & Breadcrumb Components
- **Phase:** Phase 1
- **Priority:** P1
- **Status:** Not Started
- **Category:** Design System
- **Description:** Membuat komponen judul seksi (`section-heading.blade.php`) dan remah roti navigasi (`breadcrumb.blade.php`) untuk seluruh halaman sub-level.
- **Source Requirements:** `DESIGN.md` Bab 3 (Hierarki Tipografi), 7.1, 11.2, 12.2, 15.1.
- **Dependencies:** `FE-DS-001`
- **Implementation Notes:**
  - `section-heading.blade.php`: Menerima tag pill/kicker atas (Sage Green), judul besar (Dark Green), dan deskripsi pendukung opsional (Muted Text).
  - `breadcrumb.blade.php`: Menampilkan hierarki halaman (contoh: Beranda / Profil / Fasilitas) dengan semantic `<nav aria-label="Breadcrumb">`.
- **Acceptance Criteria:**
  - [ ] Hierarki heading menggunakan tag semantik `<h1>` atau `<h2>` yang proporsional di desktop dan mobile.
  - [ ] Remah roti memiliki aria markup yang valid untuk pembaca layar.
- **Verification:** Periksa semantik markup HTML melalui DevTools Elements inspector.

---

#### [FE-DS-003] Content Cards (News, Announcement, & Portal Cards)
- **Phase:** Phase 1
- **Priority:** P1
- **Status:** Not Started
- **Category:** Design System
- **Description:** Mengembangkan komponen kartu untuk berita (`news-card`), pengumuman (`announcement-card`), dan pintasan portal (`portal-card`).
- **Source Requirements:** `PRD.md` BRD-2, BRD-4, BRD-5, BRT-1, PGM-1; `DESIGN.md` Bab 6.2, 6.4, 6.5, 15.2.
- **Dependencies:** `FE-DS-001`
- **Implementation Notes:**
  - `news-card.blade.php`: Gambar rasio 16:9 (`object-fit: cover`), badge tanggal, judul (maksimal 2 baris truncate), ringkasan pendek, tautan detail.
  - `announcement-card.blade.php`: Desain informatif dengan ikon pengumuman, tanggal terbit, judul jelas, tautan detail.
  - `portal-card.blade.php`: Kartu akses cepat (CBT, Rapor, PPDB), ikon representatif, label besar, indikator panah tautan eksternal (target tab yang sama).
- **Acceptance Criteria:**
  - [ ] Kartu memiliki radius membulat 16-20px (`rounded-2xl`), border lembut (`border-border-soft`), dan shadow halus saat di-hover.
  - [ ] Kartu berita menangani ketiadaan gambar dengan fallback placeholder elegan.
- **Verification:** Uji render kartu dengan teks pendek dan teks panjang untuk memastikan konsistensi tinggi kartu (*grid stretch*).

---

#### [FE-DS-004] Directory Cards & Table Presentation Components
- **Phase:** Phase 1
- **Priority:** P1
- **Status:** Not Started
- **Category:** Design System
- **Description:** Membuat komponen kartu atau baris daftar direktori guru (`teacher-card.blade.php`) dan siswa (`student-card.blade.php`).
- **Source Requirements:** `PRD.md` DIR-1, DIR-2, DIR-8; `DESIGN.md` Bab 8.1, 8.2, 15.2.
- **Dependencies:** `FE-DS-001`
- **Implementation Notes:**
  - Guru: Menampilkan nama lengkap, badge mata pelajaran (jika ada), dan badge jabatan (jika ada). Tanpa foto atau NIP.
  - Siswa: Menampilkan nama lengkap dan badge kelas aktif. Tanpa foto, NIS, atau NISN.
- **Acceptance Criteria:**
  - [ ] Sama sekali tidak merender kolom NIP, NIK, NIS, NISN, tanggal lahir, kontak, maupun foto (kepatuhan privasi DIR-8).
  - [ ] Tampilan rapi dan mudah dipindai di perangkat mobile maupun desktop.
- **Verification:** Cek kode Blade untuk memastikan tidak ada slot atau variabel sensitif yang terpapar.

---

#### [FE-DS-005] Search, Filter, Pagination, & Empty State Components
- **Phase:** Phase 1
- **Priority:** P1
- **Status:** Not Started
- **Category:** Design System
- **Description:** Membuat komponen form pencarian (`search-input.blade.php`), dropdown filter (`filter-select.blade.php`), pagination ramah mobile (`pagination.blade.php`), dan tampilan data kosong (`empty-state.blade.php`).
- **Source Requirements:** `PRD.md` DIR-3, DIR-4, DIR-10; `DESIGN.md` Bab 8.1, 8.2, 15.2, 21.
- **Dependencies:** `FE-DS-001`
- **Implementation Notes:**
  - `search-input.blade.php`: Input teks dengan ikon pencarian, tombol clear, dan label aksesibel (sr-only atau visible).
  - `filter-select.blade.php`: Dropdown seleksi kelas atau mata pelajaran dengan styling konsisten.
  - `pagination.blade.php`: Tombol Sebelumnya/Berikutnya dan nomor halaman yang tidak rusak di layar kecil.
  - `empty-state.blade.php`: Ilustrasi/ikon netral, judul "Data tidak ditemukan", dan tombol reset pencarian/filter.
- **Acceptance Criteria:**
  - [ ] Komponen form memiliki visual focus ring (`focus-visible:outline-forest`).
  - [ ] Empty state muncul dengan pesan informatif ketika array data bernilai kosong.
- **Verification:** Cek interaksi keyboard (Tab, Enter) pada komponen form dan pagination.

---

### Phase 2: Global Layout & Navigation Shell

#### [FE-LAYOUT-001] Master Public Layout Blade Template
- **Phase:** Phase 2
- **Priority:** P0
- **Status:** Not Started
- **Category:** Layout
- **Description:** Membangun layout induk `resources/views/layouts/public.blade.php` yang memuat struktur HTML semantik, meta tags viewport, tag Vite, skip-to-content link, navbar, konten utama, dan footer.
- **Source Requirements:** `PRD.md` NAV-4, NAV-5; `DESIGN.md` Bab 4, 15.1, 21.
- **Dependencies:** `FE-SETUP-002`, `FE-DS-001`
- **Implementation Notes:**
  - Sisipkan `a.sr-only` ("Lewati ke konten utama") yang menjadi terlihat saat fokus untuk aksesibilitas keyboard.
  - Container utama dibatasi maksimal 1200–1280px (`max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`).
  - Latar belakang halaman default Soft Background (`#F7F9F6`) atau White.
- **Acceptance Criteria:**
  - [ ] Semua halaman publik dapat meng-extend layout induk ini dengan `@extends('layouts.public')` atau `<x-layouts.public>`.
  - [ ] Tag `@vite(['resources/css/app.css', 'resources/js/app.js'])` tersemat rapi di bagian `<head>`.
- **Verification:** Inspeksi DOM di browser untuk memastikan elemen `<header>`, `<main id="main-content">`, dan `<footer>` tersusun semantik.

---

#### [FE-LAYOUT-002] Global Responsive Navbar with Desktop Submenus
- **Phase:** Phase 2
- **Priority:** P1
- **Status:** Not Started
- **Category:** Layout
- **Description:** Mengembangkan komponen navbar (`resources/views/components/navbar.blade.php`) dengan logo identitas sekolah, menu horizontal desktop, submenu dropdown (Profil, Direktori, Kesiswaan), menu mandiri (Galeri, Berita, Pengumuman), dan tombol CTA "Login SIAKAD".
- **Source Requirements:** `PRD.md` NAV-1, NAV-2, NAV-6; `DESIGN.md` Bab 5.1, 5.2, 5.4, 13.1.
- **Dependencies:** `FE-LAYOUT-001`
- **Implementation Notes:**
  - Tinggi navbar desktop sekitar 76–88px dengan background putih dan border bawah lembut (`border-b border-border-soft`).
  - Menu aktif ditandai dengan warna Forest Green dan penanda visual jelas.
  - Tombol Login SIAKAD mengarah ke rute `/login` (placeholder atau URL terkonfigurasi).
- **Acceptance Criteria:**
  - [ ] Submenu Profil Sekolah mencakup: Tentang Sekolah, Kepala Sekolah, Struktur Organisasi, Sarana & Prasarana.
  - [ ] Submenu Direktori mencakup: Direktori Guru, Direktori Siswa.
  - [ ] Submenu Kesiswaan mencakup: Organisasi Siswa, Ekstrakurikuler, Prestasi Siswa.
  - [ ] Submenu dapat diakses baik melalui hover kursor maupun navigasi keyboard (Enter/Space).
- **Verification:** Uji navigasi submenu pada breakpoint desktop (>= 1024px) dengan mouse dan keyboard.

---

#### [FE-LAYOUT-003] Mobile Navigation Drawer / Hamburger Menu
- **Phase:** Phase 2
- **Priority:** P1
- **Status:** Not Started
- **Category:** Layout
- **Description:** Mengimplementasikan drawer / dropdown navigasi seluler yang responsif dan ringan pada navbar untuk layar di bawah 1024px.
- **Source Requirements:** `PRD.md` NAV-5; `DESIGN.md` Bab 5.3, 20.
- **Dependencies:** `FE-LAYOUT-002`
- **Implementation Notes:**
  - Gunakan Vanilla JavaScript minimal atau Alpine.js ringan (sesuai `ARCHITECTURE.md` Bagian 4).
  - Target sentuh tombol hamburger minimal 44x44 px dengan atribut `aria-expanded="false"`.
  - Panel menu dapat ditutup dengan tombol tutup khusus, menekan tombol `Esc`, atau memilih salah satu tautan menu.
- **Acceptance Criteria:**
  - [ ] Menu mobile tidak pernah menutupi konten halaman secara permanen.
  - [ ] Tombol Login SIAKAD tetap terlihat jelas di dalam menu mobile.
  - [ ] Tidak terjadi horizontal scrolling pada resolusi layar 320px–414px.
- **Verification:** Buka toggle menu pada DevTools responsive mode (ukuran 375px) dan uji buka-tutup drawer.

---

#### [FE-LAYOUT-004] Global 4-Column Footer Component
- **Phase:** Phase 2
- **Priority:** P1
- **Status:** Not Started
- **Category:** Layout
- **Description:** Membangun footer bersama (`resources/views/components/footer.blade.php`) dengan skema warna Forest Green / Dark Green dan teks kontras tinggi.
- **Source Requirements:** `PRD.md` NAV-3, NAV-4; `DESIGN.md` Bab 14.
- **Dependencies:** `FE-LAYOUT-001`
- **Implementation Notes:**
  - Kolom 1 (Identitas): Nama SMP Muhammadiyah 7 Medan, logo, deskripsi singkat.
  - Kolom 2 (Navigasi Utama): Tautan Beranda, Profil, Direktori, Kesiswaan, Galeri.
  - Kolom 3 (Informasi & Akses): Berita, Pengumuman, Pintasan Portal (CBT, Rapor, PPDB), Login SIAKAD.
  - Kolom 4 (Kontak): Alamat resmi (Jl. Pelita II No. 3-5), Telepon ((061) 6621557), Email (smpm7medan@gmail.com).
  - Bagian Bawah: Copyright dinamis (`© {{ date('Y') }} SMP Muhammadiyah 7 Medan. Hak Cipta Dilindungi.`).
- **Acceptance Criteria:**
  - [ ] Tata letak otomatis runtuh menjadi 1 kolom yang rapi pada layar mobile tanpa teks terpotong.
  - [ ] Semua tautan footer memiliki efek hover dan fokus keyboard yang jelas.
- **Verification:** Periksa rendering footer di desktop dan smartphone.

---

### Phase 3: Page Structure & Visual Implementation

#### [FE-PAGE-001] Halaman Beranda (Home Page Layout & Sections)
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun tampilan halaman Beranda lengkap (`resources/views/pages/home/index.blade.php`) dengan 6 seksi utama: Hero, Pintasan Portal, Sambutan Kepala Sekolah, Berita Terbaru, Pengumuman Terbaru, dan Statistik Sekolah.
- **Source Requirements:** `PRD.md` BRD-1 s.d. BRD-7; `DESIGN.md` Bab 6.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-003`
- **Implementation Notes:**
  - Hero 2-kolom desktop (teks + gambar sekolah asli), runtuh 1-kolom di mobile.
  - 3 kartu portal (CBT, Rapor, PPDB) dengan status link placeholder pada tab yang sama.
  - Seksi sambutan kepala sekolah (M. Reza Akbar, S.Pd.) terhubung ke satu sumber data dengan halaman profil kepala sekolah.
  - Statistik dummy (Siswa, Guru, Kelas) diberi komentar penanda jelas bahwa belum bersumber dari DB.
- **Acceptance Criteria:**
  - [ ] Rute `/` menampilkan halaman beranda dengan hierarki visual yang solid.
  - [ ] Gambar hero dioptimasi dengan `fetchpriority="high"`.
- **Verification:** Akses `http://localhost:8000/` dan periksa kelengkapan ke-6 seksi.

---

#### [FE-PAGE-002] Halaman Profil: Tentang Sekolah & Kontak
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun halaman Tentang Sekolah (`resources/views/pages/profile/index.blade.php`) yang menyajikan profil, visi & misi, sejarah singkat, dan panel kontak resmi sekolah.
- **Source Requirements:** `PRD.md` PRF-1, PRF-6, PRF-7; `DESIGN.md` Bab 7.1, 7.2, 7.6.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-002`
- **Implementation Notes:**
  - Rute: `/profil`.
  - Data statis profil dimuat dari `config/sekolah.php` (NPSN 10257325, alamat, email, telepon).
  - Tautan kontak interaktif: tombol `mailto:smpm7medan@gmail.com` dan tombol `tel:(061)6621557`.
- **Acceptance Criteria:**
  - [ ] Struktur visi dan misi tersusun dalam daftar/grid yang mudah dibaca.
  - [ ] Tautan email dan telepon dapat di-klik pada perangkat seluler.
- **Verification:** Buka rute `/profil` dan uji fungsionalitas klik pada kontak.

---

#### [FE-PAGE-003] Halaman Profil: Kepala Sekolah
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun halaman Kepala Sekolah (`resources/views/pages/profile/principal.blade.php`) dengan sambutan lengkap, foto resmi, dan profil ringkas.
- **Source Requirements:** `PRD.md` PRF-2, BRD-3; `DESIGN.md` Bab 7.3.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-002`
- **Implementation Notes:**
  - Rute: `/profil/kepala-sekolah`.
  - Menampilkan foto portrait proporsional, nama "M Reza Akbar, S.Pd.", jabatan "Kepala Sekolah", dan paragraf sambutan utuh berjarak nyaman.
- **Acceptance Criteria:**
  - [ ] Menggunakan data kepala sekolah dari sumber terpusat yang sama dengan beranda.
  - [ ] Teks sambutan panjang terformat dalam paragraf bersih dan mudah dibaca.
- **Verification:** Cocokkan kutipan sambutan beranda dengan sambutan lengkap di `/profil/kepala-sekolah`.

---

#### [FE-PAGE-004] Halaman Profil: Struktur Organisasi
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun halaman Struktur Organisasi (`resources/views/pages/profile/structure.blade.php`) dengan bagan vertikal/grid jabatan sekolah yang responsif.
- **Source Requirements:** `PRD.md` PRF-3, PRF-4; `DESIGN.md` Bab 7.4.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-002`
- **Implementation Notes:**
  - Rute: `/profil/struktur`.
  - Menampilkan bagan hierarki sederhana: Kepala Sekolah -> Wakil / Komite -> Tata Usaha / Guru / Staf.
  - Desain mobile runtuh menjadi susunan kartu vertikal dengan garis pembatas halus agar teks tidak mengecil.
- **Acceptance Criteria:**
  - [ ] Bagan tidak mengalami pemotongan teks atau horizontal overflow pada layar smartphone.
  - [ ] Data pejabat dummy diberi penanda komentar penggantian.
- **Verification:** Uji visual bagan organisasi pada viewport 360px hingga 1440px.

---

#### [FE-PAGE-005] Halaman Profil: Sarana dan Prasarana (Fasilitas)
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun halaman Fasilitas (`resources/views/pages/profile/facilities.blade.php`) yang menampilkan kartu sarana dan prasarana sekolah.
- **Source Requirements:** `PRD.md` PRF-5; `DESIGN.md` Bab 7.5.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-003`
- **Implementation Notes:**
  - Rute: `/profil/fasilitas`.
  - Grid kartu fasilitas (Ruang Kelas, Lab Komputer, Perpustakaan, Lapangan Olahraga, Musholla, dll.) dengan foto, nama, dan deskripsi singkat.
- **Acceptance Criteria:**
  - [ ] Grid responsif: 3 kolom di desktop, 2 kolom di tablet, 1 kolom di mobile.
  - [ ] Menggunakan gambar thumbnail teroptimasi dengan `loading="lazy"`.
- **Verification:** Akses `/profil/fasilitas` dan pastikan tata letak kartu fasilitas seragam.

---

#### [FE-PAGE-006] Halaman Direktori Guru
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun antarmuka Direktori Guru (`resources/views/pages/directory/teachers.blade.php`) dengan bilah pencarian nama, filter mapel, daftar kartu guru, dan pagination.
- **Source Requirements:** `PRD.md` DIR-1, DIR-3, DIR-5, DIR-6, DIR-7, DIR-8, DIR-10; `DESIGN.md` Bab 8.1.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-004`, `FE-DS-005`
- **Implementation Notes:**
  - Rute: `/guru`.
  - Menampilkan data guru (nama lengkap, mapel, jabatan bila ada) terurut alfabetis.
  - Bersih dari data sensitif (tanpa NIP, alamat, HP, foto).
  - Dilengkapi komponen pagination dan empty state saat pencarian tidak cocok.
- **Acceptance Criteria:**
  - [ ] Kolom pencarian dan filter tertata rapi di bagian atas daftar.
  - [ ] Tampilan responsif baik menggunakan kartu ringkas maupun tabel adaptif.
- **Verification:** Akses rute `/guru` dengan fixture data dummy lokal dan periksa penyajiannya.

---

#### [FE-PAGE-007] Halaman Direktori Siswa
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun antarmuka Direktori Siswa (`resources/views/pages/directory/students.blade.php`) dengan pencarian nama, filter kelas (mis. Kelas 7, Kelas 8, Kelas 9), dan pagination.
- **Source Requirements:** `PRD.md` DIR-2, DIR-3, DIR-4, DIR-7, DIR-8, DIR-10; `DESIGN.md` Bab 8.2.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-004`, `FE-DS-005`
- **Implementation Notes:**
  - Rute: `/siswa`.
  - Hanya menampilkan Nama Siswa dan Kelas Aktif.
  - Bersih mutlak dari NIS, NISN, NIK, tanggal lahir, kontak, nilai, atau foto siswa.
- **Acceptance Criteria:**
  - [ ] Filter kelas dapat dipilih dengan mudah di perangkat seluler.
  - [ ] Daftar siswa mudah dipindai dengan zebra striping atau kartu minimalis.
- **Verification:** Akses rute `/siswa` dan pastikan tidak ada kebocoran field data sensitif pada markup.

---

#### [FE-PAGE-008] Halaman Kesiswaan: Organisasi Siswa
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun halaman Organisasi Siswa (`resources/views/pages/student-life/organizations.blade.php`) yang menyajikan Ikatan Pelajar Muhammadiyah (IPM) dan organisasi kesiswaan lainnya.
- **Source Requirements:** `PRD.md` KSW-1; `DESIGN.md` Bab 9.1.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-002`
- **Implementation Notes:**
  - Rute: `/kesiswaan/organisasi`.
  - Kartu organisasi memuat: nama organisasi, logo/gambar, deskripsi, dan daftar kegiatan utama.
- **Acceptance Criteria:**
  - [ ] Grid 2-3 kolom di desktop, 1 kolom di mobile.
  - [ ] Data dummy diberi penanda transparan.
- **Verification:** Akses rute `/kesiswaan/organisasi` dan verifikasi visual kartu organisasi.

---

#### [FE-PAGE-009] Halaman Kesiswaan: Ekstrakurikuler
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun halaman Ekstrakurikuler (`resources/views/pages/student-life/extracurriculars.blade.php`) yang menampilkan ragam kegiatan ekskul sekolah.
- **Source Requirements:** `PRD.md` KSW-2, KSW-4; `DESIGN.md` Bab 9.2.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-002`
- **Implementation Notes:**
  - Rute: `/kesiswaan/ekstrakurikuler` (dan redirect/hub di `/kesiswaan`).
  - Menampilkan kartu ekskul: nama kegiatan, gambar representatif, deskripsi, dan jadwal (jika terkonfirmasi).
- **Acceptance Criteria:**
  - [ ] Tata letak visual seragam dengan aspek rasio foto yang konsisten.
  - [ ] Jadwal hanya ditampilkan pada kartu yang datanya telah terkonfirmasi.
- **Verification:** Buka `/kesiswaan/ekstrakurikuler` di browser.

---

#### [FE-PAGE-010] Halaman Kesiswaan: Prestasi Siswa
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun halaman Prestasi Siswa (`resources/views/pages/student-life/achievements.blade.php`) dengan badge tingkat kejuaraan dan label penanda data contoh.
- **Source Requirements:** `PRD.md` KSW-3, KSW-5; `DESIGN.md` Bab 9.3.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-002`
- **Implementation Notes:**
  - Rute: `/kesiswaan/prestasi`.
  - Kartu prestasi memuat: nama prestasi, jenis, tingkat kompetisi (Kota/Provinsi/Nasional), tahun, deskripsi, dan foto dokumentasi.
  - Terdapat banner / label peringatan bahwa data masih berupa data contoh/dummy.
- **Acceptance Criteria:**
  - [ ] Label data contoh terlihat jelas sesuai instruksi `PRD.md` KSW-3.
  - [ ] Foto prestasi menggunakan foto dokumentasi yang valid/disetujui.
- **Verification:** Periksa keberadaan badge penanda "Data Contoh" di `/kesiswaan/prestasi`.

---

#### [FE-PAGE-011] Halaman Galeri Dokumentasi Sekolah
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun halaman Galeri Foto Sekolah (`resources/views/pages/gallery/index.blade.php`) dengan layout masonry/grid teroptimasi.
- **Source Requirements:** `PRD.md` GAL-1, GAL-2, GAL-3; `DESIGN.md` Bab 10.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-002`
- **Implementation Notes:**
  - Rute: `/galeri`.
  - Grid foto responsif (3-4 kolom desktop, 2 kolom tablet, 1-2 kolom mobile).
  - Setiap foto dilengkapi judul, tanggal/kategori, dan atribut `loading="lazy"`.
  - Tanpa pustaka slider/lightbox eksternal berat yang memperlambat halaman.
- **Acceptance Criteria:**
  - [ ] Semua gambar menggunakan dimensi tetap dan `object-fit: cover` untuk menghindari layout shift (CLS).
  - [ ] Tidak terjadi pemblokiran unduhan gambar di awal rendering.
- **Verification:** Buka `/galeri` dan lakukan audit layout shift pada DevTools Performance panel.

---

#### [FE-PAGE-012] Halaman Berita: Indeks / Daftar Berita
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun halaman katalog daftar berita (`resources/views/pages/news/index.blade.php`) dengan pagination dan kartu berita konsisten.
- **Source Requirements:** `PRD.md` BRT-1, BRT-3; `DESIGN.md` Bab 11.1.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-003`, `FE-DS-005`
- **Implementation Notes:**
  - Rute: `/berita`.
  - Grid 3 kolom desktop, 2 kolom tablet, 1 kolom mobile.
  - Memanfaatkan `news-card.blade.php` dengan tautan detail menuju `/berita/{slug}`.
- **Acceptance Criteria:**
  - [ ] Pagination berfungsi mengontrol daftar berita per halaman.
  - [ ] Struktur data berita dummy konsisten (`title`, `slug`, `summary`, `content`, `image`, `date`).
- **Verification:** Buka `/berita` dan klik salah satu kartu untuk menuju halaman detail.

---

#### [FE-PAGE-013] Halaman Berita: Detail Berita
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun halaman baca detail berita (`resources/views/pages/news/show.blade.php`) dengan breadcrumb, tanggal terbit, gambar artikel utama, badan konten berformat rapi, dan tombol kembali ke daftar.
- **Source Requirements:** `PRD.md` BRT-2; `DESIGN.md` Bab 11.2.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-002`
- **Implementation Notes:**
  - Rute: `/berita/{slug}`.
  - Tipografi badan berita diatur dengan lebar nyaman (`max-w-3xl mx-auto`), *line-height* proporsional (`leading-relaxed`), dan paragraf terpisah rapi.
  - Tombol navigasi kembali yang jelas di bagian atas/bawah artikel.
- **Acceptance Criteria:**
  - [ ] Tautan kembali membawa pengunjung kembali ke `/berita`.
  - [ ] Halaman mengembalikan status 404 yang ramah jika slug berita tidak ditemukan di dataset.
- **Verification:** Akses berita contoh (mis. `/berita/smp-muhammadiyah-7-medan-juara-lagi`) dan periksa tipografinya.

---

#### [FE-PAGE-014] Halaman Pengumuman: Indeks & Detail Pengumuman
- **Phase:** Phase 3
- **Priority:** P1
- **Status:** Not Started
- **Category:** Page
- **Description:** Membangun halaman daftar pengumuman (`resources/views/pages/announcements/index.blade.php`) dan detail pengumuman (`resources/views/pages/announcements/show.blade.php`).
- **Source Requirements:** `PRD.md` PGM-1, PGM-2, PGM-3; `DESIGN.md` Bab 12.
- **Dependencies:** `FE-LAYOUT-001`, `FE-DS-003`
- **Implementation Notes:**
  - Rute: `/pengumuman` dan `/pengumuman/{slug}`.
  - Format visual berbeda secara halus dari berita (mengutamakan tipografi pengumuman resmi dan tanggal).
  - Tidak menerapkan logika expired date / status tanggal di tahap pertama sesuai PRD PGM-3.
- **Acceptance Criteria:**
  - [ ] Daftar pengumuman terpisah dari berita.
  - [ ] Detail pengumuman menyediakan tombol kembali ke daftar pengumuman.
- **Verification:** Buka `/pengumuman` dan lakukan navigasi ke detail pengumuman.

---

#### [FE-PAGE-015] Halaman / Pintasan Login SIAKAD
- **Phase:** Phase 3
- **Priority:** P2
- **Status:** Not Started
- **Category:** Page
- **Description:** Menyiapkan route dan tampilan sementara `/login` sebagai pintu gerbang menuju sistem akademik internal.
- **Source Requirements:** `PRD.md` NAV-6; `DESIGN.md` Bab 13.1; `ARCHITECTURE.md` Bagian 3.
- **Dependencies:** `FE-LAYOUT-001`
- **Implementation Notes:**
  - Rute: `/login`.
  - Menyajikan kartu informasi redirect / placeholder resmi bertema sekolah yang menjelaskan bahwa autentikasi SIAKAD berada pada modul terpisah, dengan tombol menuju portal terkait jika URL sudah disepakati.
- **Acceptance Criteria:**
  - [ ] Rute `/login` dapat diakses dan tidak menghasilkan error 500.
  - [ ] Tampilan konsisten dengan identitas sekolah tanpa form login palsu yang membingungkan pengunjung.
- **Verification:** Klik tombol "Login SIAKAD" di navbar dan pastikan mendarat di halaman redirect/informasi `/login`.

---

### Phase 4: Frontend Interactions and State

#### [FE-INTERACTION-001] Client-Side Mobile Navigation Interaction & Transitions
- **Phase:** Phase 4
- **Priority:** P1
- **Status:** Not Started
- **Category:** Interaction
- **Description:** Menyempurnakan interaksi pembukaan, penutupan, trap focus, dan transisi halus menu hamburger pada perangkat mobile menggunakan JavaScript ringan di `resources/js/app.js`.
- **Source Requirements:** `DESIGN.md` Bab 5.3, 20, 21; `ARCHITECTURE.md` Bagian 4.
- **Dependencies:** `FE-LAYOUT-003`
- **Implementation Notes:**
  - Hindari library berat (jQuery, Bootstrap js).
  - Tangani event listener tombol hamburger: toggle class CSS untuk drawer slide-in atau dropdown fade.
  - Kunci scrolling layar belakang saat drawer terbuka (`body.overflow-hidden`).
- **Acceptance Criteria:**
  - [ ] Menekan tombol Escape menutup drawer navigasi.
  - [ ] Navigasi drawer terbuka dengan transisi visual mulus tanpa freeze atau jank.
- **Verification:** Uji buka-tutup menu pada simulasi mobile Chrome/Firefox DevTools dengan opsi throttling CPU.

---

#### [FE-INTERACTION-002] Client/Server Search & Filtering Interaction untuk Direktori
- **Phase:** Phase 4
- **Priority:** P1
- **Status:** Not Started
- **Category:** Interaction
- **Description:** Mengaktifkan form pencarian dan filter pada halaman direktori guru dan siswa menggunakan mekanisme query string HTTP GET standar yang ramah SEO dan bookmarkable.
- **Source Requirements:** `PRD.md` DIR-3, DIR-4, DIR-5, DIR-6; `ARCHITECTURE.md` Bagian 4.
- **Dependencies:** `FE-PAGE-006`, `FE-PAGE-007`
- **Implementation Notes:**
  - URL format: `/guru?q=budi&mapel=matematika` dan `/siswa?q=ahmad&kelas=7A`.
  - Input form mempertahankan nilai aktif (`value="{{ request('q') }}"`).
  - Dropdown filter otomatis men-submit form saat berganti atau menyediakan tombol "Terapkan Filter".
- **Acceptance Criteria:**
  - [ ] Pengunjung dapat mencari nama guru/siswa dan menyaring kelas dengan URL yang dapat dibagikan (shareable).
  - [ ] Tersedia tombol "Reset Filter" yang mengembalikan daftar ke kondisi default saat filter aktif.
- **Verification:** Ketik nama di search bar, submit form, dan periksa perubahan parameter URL serta hasil daftar.

---

#### [FE-STATE-001] Data Fixtures & Repository Mock Layer
- **Phase:** Phase 4
- **Priority:** P1
- **Status:** Not Started
- **Category:** State Management
- **Description:** Menyusun kumpulan data dummy terstruktur di `database/dummy/` atau `app/Repositories/Dummy/` dan konfigurasi sekolah di `config/sekolah.php` sesuai kontrak data PRD & Arsitektur.
- **Source Requirements:** `PRD.md` Bab 8 (Model Data), BRD-7; `ARCHITECTURE.md` Bagian 7 & 8.
- **Dependencies:** `FE-SETUP-003`
- **Implementation Notes:**
  - Buat file: `sekolah.php` (profil, kontak, portal URLs), `news.php`, `announcements.php`, `facilities.php`, `organizations.php`, `extracurriculars.php`, `achievements.php`, `gallery.php`, `teachers_dummy.php`, `students_dummy.php`.
  - Berikan komentar penanda `/* DUMMY DATA - SIAP DIGANTI KE DATABASE PADA TAHAP B */` di setiap file.
- **Acceptance Criteria:**
  - [ ] Data dummy terisolasi penuh dari logika tampilan Blade.
  - [ ] Struktur data berita, direktori, dan pengumuman memiliki skema key-value yang identik dengan rencana tabel Eloquent masa depan.
- **Verification:** Periksa seluruh file dummy dan pastikan tidak ada data yang di-*hardcode* di dalam view Blade.

---

### Phase 5: Frontend Quality Assurance

#### [FE-QA-001] Responsive Cross-Breakpoint Visual Verification
- **Phase:** Phase 5
- **Priority:** P1
- **Status:** Not Started
- **Category:** Testing
- **Description:** Melakukan pengujian tampilan di berbagai ukuran breakpoint layar utama: Mobile (<640px), Tablet (768px - 1023px), Desktop (1024px - 1280px), dan Ultrawide (>1440px).
- **Source Requirements:** `DESIGN.md` Bab 4, 20.
- **Dependencies:** Phase 3 & 4.
- **Implementation Notes:**
  - Pastikan tidak ada horizontal scrollbar liar (overflow-x).
  - Periksa ukuran font, padding container, serta rasio aspek gambar di setiap breakpoint.
- **Acceptance Criteria:**
  - [ ] Halaman Beranda, Profil, Direktori, Kesiswaan, Galeri, Berita, dan Pengumuman tampil proporsional di seluruh breakpoint uji.
  - [ ] Tidak ada elemen teks atau tombol yang bertumpuk (*overlapping*).
- **Verification:** Uji menggunakan responsive design mode di Google Chrome dan Mozilla Firefox.

---

#### [FE-QA-002] Audit Aksesibilitas (WCAG AA) & Navigasi Keyboard
- **Phase:** Phase 5
- **Priority:** P1
- **Status:** Not Started
- **Category:** Testing
- **Description:** Memverifikasi kontras warna teks terhadap latar belakang, atribut alt pada gambar, heading semantik, dan navigasi penuh menggunakan keyboard.
- **Source Requirements:** `DESIGN.md` Bab 2.2, 21.
- **Dependencies:** Phase 3 & 4.
- **Implementation Notes:**
  - Uji kontras warna: Forest Green (`#344B39`) dan Dark Green (`#0F1D0F`) di atas latar putih (`#FFFFFF`) / soft (`#F7F9F6`).
  - Pastikan setiap link dan input memiliki visual focus indicator (`focus-visible`).
  - Pastikan `alt=""` deskriptif pada semua gambar konten, dan gambar dekoratif memakai `aria-hidden="true"`.
- **Acceptance Criteria:**
  - [ ] Rasio kontras teks utama minimal 4.5:1 (WCAG AA).
  - [ ] Seluruh menu dropdown dan tombol dapat dibuka dan ditutup menggunakan tombol Tab, Enter, Space, dan Escape.
- **Verification:** Jalankan audit Lighthouse Accessibility di Google Chrome DevTools dengan target skor >= 90.

---

#### [FE-QA-003] Audit Privasi Direktori & Keamanan Output Publik
- **Phase:** Phase 5
- **Priority:** P0
- **Status:** Not Started
- **Category:** Testing
- **Description:** Memeriksa bahwa tidak ada data sensitif yang terikutsertakan pada markup HTML, JSON embedded, atau source code halaman direktori.
- **Source Requirements:** `PRD.md` DIR-8; `DESIGN.md` Bab 8.3, 23; `ARCHITECTURE.md` Bagian 8.
- **Dependencies:** `FE-PAGE-006`, `FE-PAGE-007`
- **Implementation Notes:**
  - Lakukan pencarian string di View Source / Inspect Element: pastikan tidak ada kata kunci NIP, NIK, NIS, NISN, tgl_lahir, no_telp, no_hp, alamat, nama_ortu, nilai, rapor, atau presensi.
- **Acceptance Criteria:**
  - [ ] Tidak ada atribut tersembunyi (`data-*` atau input hidden) yang memuat data pribadi guru dan siswa.
- **Verification:** Lakukan *grep* atau regex check pada seluruh output HTML hasil render rute `/guru` dan `/siswa`.

---

#### [FE-QA-004] Audit Performa Aset & Core Web Vitals
- **Phase:** Phase 5
- **Priority:** P2
- **Status:** Not Started
- **Category:** Testing
- **Description:** Memeriksa ukuran berkas build CSS/JS, optimasi ukuran gambar WebP, dan meminimalkan Cumulative Layout Shift (CLS).
- **Source Requirements:** `PRD.md` Bab 9; `DESIGN.md` Bab 22; `ARCHITECTURE.md` Bagian 5 & 10.
- **Dependencies:** `FE-SETUP-001`, Phase 3.
- **Implementation Notes:**
  - Pastikan CSS hasil build Tailwind di `public/build` bersih dari utility class yang tidak digunakan.
  - Beri atribut `width` dan `height` eksplisit pada tag `<img>`.
  - Terapkan `loading="lazy"` pada gambar di luar viewport awal.
- **Acceptance Criteria:**
  - [ ] Total ukuran bundle CSS terkompresi < 100 KB.
  - [ ] Tidak ada layout jank (CLS < 0.1).
- **Verification:** Jalankan Lighthouse Performance audit dan periksa file size di `public/build/assets/`.

---

### Phase 6: Backend Integration & Data Layer Readiness

#### [BE-API-001] Pembuatan Antarmuka (Contracts) & Dummy Repository Binding
- **Phase:** Phase 6
- **Priority:** P1
- **Status:** Not Started
- **Category:** API Contract
- **Description:** Menyiapkan struktur Repository Pattern di `app/Repositories/Contracts/` dan implementasi dummy di `app/Repositories/Dummy/` agar Controller tidak terikat langsung dengan model database.
- **Source Requirements:** `ARCHITECTURE.md` Bagian 7; `PRD.md` Bab 8.
- **Dependencies:** `FE-STATE-001`
- **Implementation Notes:**
  - Buat interface: `DirektoriRepositoryInterface`, `BeritaRepositoryInterface`, `PengumumanRepositoryInterface`, `KontenStatisRepositoryInterface`.
  - Daftarkan binding di `AppServiceProvider`: mengarahkan interface ke implementasi Dummy terlebih dahulu.
- **Acceptance Criteria:**
  - [ ] Controller memanggil data melalui dependency injection Repository.
  - [ ] Semua rute halaman dapat beroperasi tanpa ketergantungan koneksi database aktif.
- **Verification:** Jalankan PHPUnit feature tests untuk memastikan controller merespons HTTP 200 dengan data dummy.

---

#### [BE-INTEGRATION-001] Integrasi Direktori Guru & Siswa ke Database RKM (Read-Only)
- **Phase:** Phase 6
- **Priority:** P1
- **Status:** Not Started
- **Category:** Integration
- **Description:** Mengganti binding `DirektoriRepository` ke implementasi `RkmDirektoriRepository` yang membaca tabel database RKM (`m_guru`, `m_siswa`, `m_kelas`, `t_kelas_siswa`) secara *read-only*.
- **Source Requirements:** `PRD.md` DIR-1 s.d. DIR-10; `ARCHITECTURE.md` Bagian 8 & 10.
- **Dependencies:** `BE-API-001`, dependensi verifikasi skema RKM (`BE-RKM-001`).
- **Implementation Notes:**
  - Buat model Eloquent read-only di `app/Models/Rkm/` tanpa `timestamps` dan tanpa permission create/update/delete.
  - Query hanya kolom yang diperlukan: `select('nama', ...)` dengan filter tahun ajaran aktif dan status aktif.
  - Terapkan rate limiting ringan pada route pencarian untuk melindungi database shared hosting.
- **Acceptance Criteria:**
  - [ ] Data guru dan siswa yang muncul di web berasal dari tabel RKM.
  - [ ] Kueri menggunakan pagination (mis. 12-20 data per halaman) dan tidak mengambil seluruh baris sekaligus.
  - [ ] Kegagalan koneksi DB RKM ditangani dengan elegan (menampilkan pesan ramah pengguna tanpa memunculkan stack trace debug).
- **Verification:** Jalankan kueri pencarian di `/guru` dan `/siswa`, periksa log query Laravel untuk memastikan query `SELECT` hemat kolom.

---

#### [BE-INTEGRATION-002] Pengujian Alur Penuh (End-to-End Smoke Test) & Caching
- **Phase:** Phase 6
- **Priority:** P2
- **Status:** Not Started
- **Category:** Integration
- **Description:** Menjalankan automated feature test untuk seluruh 16 rute halaman publik, memastikan HTTP 200 OK, memverifikasi caching file pada query direktori, dan memeriksa kestabilan sistem.
- **Source Requirements:** `ARCHITECTURE.md` Bagian 10 & 11; `PRD.md` Bab 11.
- **Dependencies:** `BE-INTEGRATION-001`
- **Implementation Notes:**
  - Tulis test suite: `tests/Feature/PublicPagesTest.php` dan `tests/Feature/DirectoryTest.php`.
  - Aktifkan file cache TTL pendek untuk data beranda dan direktori untuk meringankan beban CPU 1-core hosting.
- **Acceptance Criteria:**
  - [ ] Seluruh automated test lulus (`php artisan test`).
  - [ ] Perintah `php artisan optimize` (config, route, view cache) berjalan tanpa error.
- **Verification:** Jalankan `php artisan test` di console sandbox.

---

## 6. Requirement Traceability

Tabel berikut memetakan relasi kebutuhan bisnis (`PRD.md`), panduan desain (`DESIGN.md`), dan arsitektur teknis (`ARCHITECTURE.md`) terhadap task implementasi frontend:

| Source Document | Requirement / Section | Related Task IDs | Coverage | Catatan Implementasi |
|---|---|---|---|---|
| **PRD.md** | NAV-1 s.d. NAV-6 (Navbar, Submenu, Footer, Mobile, Login) | `FE-LAYOUT-001`, `FE-LAYOUT-002`, `FE-LAYOUT-003`, `FE-LAYOUT-004`, `FE-PAGE-015` | Covered | Layout dan navigasi terpusat |
| **PRD.md** | NAV-7 (Reusable Blade & Tailwind Components) | `FE-SETUP-002`, `FE-DS-001` s.d. `FE-DS-005` | Covered | Sistem komponen mandiri |
| **PRD.md** | BRD-1 s.d. BRD-7 (Beranda & Seksi-seksinya) | `FE-PAGE-001`, `FE-DS-003`, `FE-STATE-001` | Covered | Hero, 3 portal, sambutan, berita, statistik |
| **PRD.md** | PRF-1 s.d. PRF-7 (Halaman Profil Sekolah) | `FE-PAGE-002`, `FE-PAGE-003`, `FE-PAGE-004`, `FE-PAGE-005` | Covered | Tentang, Kepsek, Struktur, Fasilitas |
| **PRD.md** | DIR-1 s.d. DIR-10 (Direktori Guru & Siswa, Filter, Privasi) | `FE-DS-004`, `FE-PAGE-006`, `FE-PAGE-007`, `FE-INTERACTION-002`, `FE-QA-003`, `BE-INTEGRATION-001` | Covered | Filter nama & kelas, proteksi privasi DIR-8 |
| **PRD.md** | KSW-1 s.d. KSW-5 (Kesiswaan: Organisasi, Ekskul, Prestasi) | `FE-PAGE-008`, `FE-PAGE-009`, `FE-PAGE-010`, `FE-STATE-001` | Covered | Penandaan data contoh prestasi |
| **PRD.md** | GAL-1 s.d. GAL-3 (Galeri & Lazy Loading) | `FE-PAGE-011`, `FE-QA-004` | Covered | Grid thumbnail, lazy loading |
| **PRD.md** | BRT-1 s.d. BRT-3 (Halaman Indeks & Detail Berita) | `FE-DS-003`, `FE-PAGE-012`, `FE-PAGE-013`, `FE-STATE-001` | Covered | Struktur slug konsisten |
| **PRD.md** | PGM-1 s.d. PGM-3 (Halaman Indeks & Detail Pengumuman) | `FE-DS-003`, `FE-PAGE-014`, `FE-STATE-001` | Covered | Terpisah dari berita, tanpa expired date |
| **PRD.md** | Bab 9 (Non-Fungsional: Ringan, Mobile-First, Privasi) | `FE-QA-001`, `FE-QA-002`, `FE-QA-003`, `FE-QA-004` | Covered | WCAG AA, performa hosting hemat |
| **DESIGN.md** | Bab 2 (Color Palette & Ratios) | `FE-SETUP-002` | Covered | Forest, Sage, Mint, Dark Green tokens |
| **DESIGN.md** | Bab 3 (Tipografi) & Bab 4 (Layout/Grid) | `FE-SETUP-002`, `FE-LAYOUT-001` | Covered | Max 1280px, system font stack |
| **DESIGN.md** | Bab 5 s.d. 14 (Desain Spesifik Tiap Halaman) | `FE-PAGE-001` s.d. `FE-PAGE-015` | Covered | Sesuai rute dan hierarki visual |
| **DESIGN.md** | Bab 15 (Design System Components) | `FE-DS-001` s.d. `FE-DS-005` | Covered | 14+ reusable Blade UI components |
| **DESIGN.md** | Bab 18 (Aturan Tailwind CSS v4) | `FE-SETUP-001`, `FE-SETUP-002` | Covered | Kompilasi lokal Vite, tanpa CDN |
| **DESIGN.md** | Bab 21 (Aksesibilitas) & Bab 22 (Performa) | `FE-QA-002`, `FE-QA-004` | Covered | Keyboard nav, CLS, lazy loading |
| **ARCHITECTURE.md**| Bagian 4 (Stack: Laravel 12, PHP 8.4, Blade, Vite) | `FE-SETUP-001`, `FE-LAYOUT-001` | Covered | Server-side rendering, tanpa SPA |
| **ARCHITECTURE.md**| Bagian 7 & 8 (Arsitektur Folder & Lapisan Repository) | `FE-SETUP-003`, `FE-STATE-001`, `BE-API-001` | Covered | Controller tipis, Repository pattern |
| **ARCHITECTURE.md**| Bagian 10 (Keamanan, DB Read-Only, File Cache) | `BE-INTEGRATION-001`, `BE-INTEGRATION-002` | Covered | SELECT-only, rate limit, file cache |
| **ARCHITECTURE.md**| Bagian 12 (Risiko A-1 s.d. A-9: Skema RKM & Hosting) | `BE-RKM-001` (External Backend Task) | Covered (External Dep) | Memerlukan verifikasi skema database RKM |

---

## 7. Frontend Definition of Done (DoD)

Sebuah task frontend baru dapat dinyatakan **Done** apabila memenuhi seluruh kriteria berikut:

1. **Acceptance Criteria Terpenuhi:** Seluruh poin kriteria fungsional dan visual yang tercantum dalam task telah terwujud secara nyata.
2. **Kesesuaian Design System:** Menggunakan design tokens resmi (warna palette hijau sekolah, tipografi, radius tombol/kartu, container) dari `DESIGN.md` tanpa utility class arbitrer (*ad-hoc*).
3. **Respon Responsif Teruji:** Antarmuka terbukti tidak menghasilkan *horizontal overflow* dan mudah digunakan pada resolusi mobile (360px–414px), tablet (768px), dan desktop (1280px).
4. **Semantik & Aksesibilitas:** Menggunakan elemen HTML semantik (`<header>`, `<nav>`, `<main>`, `<article>`, `<section>`, `<footer>`), memiliki *focus-visible* yang jelas, dan rasio kontras teks memenuhi WCAG AA.
5. **State Handling Lengkap:** Komponen atau halaman menangani seluruh kondisi data: *loading/render state*, *data populated state*, dan *empty state* (jika pencarian/filter tidak menghasilkan data).
6. **Bebas Error Konsol:** Tidak ada pesan galat JavaScript, *unresolved assets*, atau stylesheet failure di browser DevTools Console.
7. **Kepatuhan Privasi Data:** Pada komponen direktori, tidak terdapat celah atau atribut yang menampilkan field sensitif (DIR-8).
8. **Build & Lint Lolos:** Aset berhasil dikompilasi oleh Vite (`npm run build`) tanpa peringatan *syntax error* atau *broken imports*.
9. **Isolasi Logika:** Data statis/dummy ditempatkan pada konfigurasi/repository mock, tidak ada teks data panjang yang di-*hardcode* serampangan di dalam markup view.

---

## 8. Backend Dependencies and Integration Readiness

Pengembangan frontend dirancang dapat berjalan mandiri menggunakan *mock fixtures*. Namun, untuk penyelesaian integrasi penuh pada Phase 6, terdapat beberapa pekerjaan persiapan backend yang menjadi dependensi:

### [BE-RKM-001] Verifikasi Skema Database RKM dan Kredensial Read-Only
- **Category:** Backend Dependency
- **Priority:** P1
- **Status:** Needs Clarification (Rujuk `ARCHITECTURE.md` Bagian 12 Risiko A-1 & A-5; `PRD.md` TBD-2, 3, 4, 5)
- **Description:** 
  1. Melakukan inspeksi salinan database RKM untuk memverifikasi keberadaan dan nama kolom tabel: `m_guru` (nama, jabatan, status aktif), `m_siswa` (nama, status aktif), `m_kelas`, `t_kelas_siswa` (relasi kelas siswa dan tahun ajaran aktif), serta aturan tahun ajaran aktif.
  2. Menyiapkan akun database MySQL dengan hak akses **hanya SELECT** (`GRANT SELECT ON ...`) agar Laravel tidak dapat mengubah isi database RKM.
- **Frontend Impact:** Diperlukan saat mengaktifkan `BE-INTEGRATION-001`. Sebelum task ini tuntas, frontend direktori beroperasi menggunakan mock fixtures `FE-STATE-001`.

### [BE-CONFIG-001] Konfirmasi URL Resmi Portal CBT, Rapor, PPDB, dan Login SIAKAD
- **Category:** Backend / External Configuration
- **Priority:** P2
- **Status:** Needs Clarification (Rujuk `PRD.md` Bab 5 & 12 TBD-1)
- **Description:** Memperoleh tautan URL resmi yang aktif untuk CBT, Rapor, PPDB, dan Login SIAKAD dari pihak sekolah/klien untuk dimasukkan ke dalam `config/sekolah.php`.
- **Frontend Impact:** Tombol portal pada beranda dan tombol login navbar saat ini menggunakan konfigurasi tautan placeholder.

---

## 9. Risks, Blockers, and Open Questions

| ID | Kategori | Deskripsi Risiko / Pertanyaan Terbuka | Dampak Frontend | Tindakan Mitigasi Frontend |
|---|---|---|---|---|
| **R-1** | Backend / Hosting | **Versi PHP & URL RKM di hPanel belum pasti (`ARCHITECTURE.md` A-1).** Domain utama sudah PHP 8.4.26. Jika RKM (CI3) berbagi domain yang sama, ada risiko kompatibilitas. | Rendah ke UI, Tinggi ke rilis akhir | Frontend tetap dikembangkan lokal dengan PHP 8.4 + Vite. Deployment menunggu kepastian subdomain terpisah (`siakad.<domain>`). |
| **R-2** | Skema Data | **Nama kolom jabatan guru & penentu tahun ajaran aktif belum terverifikasi (`PRD.md` TBD-3, 4).** | Sedang | Komponen direktori guru dirancang kondisional: badge jabatan/mapel hanya tampil jika data tersedia di sumber data. |
| **R-3** | Privasi & Kebijakan | **Izin publikasi foto siswa dan daftar prestasi resmi (`PRD.md` TBD-9, KSW-3).** | Rendah | Menggunakan data dummy contoh prestasi dengan label transparan "Data Contoh" dan tidak menampilkan foto siswa pada direktori. |
| **R-4** | Pemilihan JS | **Pilihan Vanilla JS vs Alpine.js untuk drawer mobile (`PRD.md` TBD-16).** | Rendah | Menggunakan Vanilla JS ringan terlebih dahulu di `resources/js/app.js` tanpa menambah dependensi npm eksternal. |
| **R-5** | Aset Asli | **Ketersediaan foto asli sekolah, fasilitas, dan kegiatan galeri.** | Rendah | Menyiapkan komponen `ImagePlaceholder` dengan rasio aspek standar sehingga UI tidak bergeser ketika foto asli dimasukkan. |

---

## 10. Recommended Next Task

👉 **Rekomendasi Task Pertama:** **`[FE-SETUP-001] Validasi Lingkungan Proyek dan Konfigurasi Build Tools`**  
Dilanjutkan langsung dengan **`[FE-SETUP-002] Definisi Design Tokens dan Tema Tailwind CSS v4`**.

**Alasan Pemilihan:**  
Codebase saat ini baru berupa instalasi dasar Laravel 12 dengan konfigurasi awal Tailwind CSS v4 (`@tailwindcss/vite`). Memvalidasi bahwa Vite build berjalan mulus dan mendaftarkan seluruh token warna hijau sekolah (`--color-forest`, `--color-sage`, `--color-mint`, dll.) di `resources/css/app.css` adalah langkah mutlak sebelum komponen Blade apa pun dapat dibangun dengan benar dan konsisten.

