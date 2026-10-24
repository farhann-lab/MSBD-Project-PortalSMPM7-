# ARCHITECTURE — SIAKAD SMP Muhammadiyah 7 Medan (Fitur Publik Tahap Pertama)

| | |
| --- | --- |
| **Versi Dokumen** | v0.1 (Draft) |
| **Tanggal** | 24 Oktober 2026 |
| **Dokumen Terkait** | PRD_SIAKAD_SMP_Muhammadiyah_7_Medan.md (v0.2) |
| **Cakupan** | Fitur berprioritas **Wajib** pada PRD (halaman publik, data dummy, direktori dari database RKM) |

> **Cara membaca dokumen ini.** Setiap keputusan diberi status:
> - **[DITETAPKAN]** sudah disepakati atau terverifikasi (dari PRD, atau dari hasil pengecekan hosting).
> - **[USULAN]** rekomendasi pengembang, belum dikonfirmasi.
> - **[TBD]** belum diputuskan atau perlu dicek (merujuk ke Bab 12 PRD atau Bagian 12 dokumen ini).
>
> Saat sebuah butir berubah status, ubah labelnya dan catat di **Riwayat Revisi** (Bagian 13).

---

## 1. Tujuan Dokumen

Dokumen ini menjelaskan arsitektur teknis untuk fitur publik SIAKAD tahap pertama: stack, struktur aplikasi, hubungan dengan RKM, lingkungan hosting, aturan performa dan keamanan, serta alur rilis. Dokumen ini dirancang agar mudah diperbarui ketika keputusan baru dibuat atau ketika Tahap A/B/C (PRD Bab 11) dimulai.

## 2. Ringkasan Keputusan Utama

| # | Keputusan | Status |
| --- | --- | --- |
| 1 | Backend: Laravel 12 | [DITETAPKAN] |
| 2 | Bahasa: PHP 8.4 (tersedia di hosting: 8.4.26) | [DITETAPKAN] |
| 3 | Frontend: Blade + Tailwind CSS, tanpa SPA | [DITETAPKAN] |
| 4 | RKM tetap CodeIgniter 3 pada PHP 7.4 | [DITETAPKAN] |
| 5 | SIAKAD dan RKM memakai database yang sama; tidak membuat API hanya untuk baca data | [DITETAPKAN] |
| 6 | Hosting Niagahoster (panel hPanel) | [DITETAPKAN] |
| 7 | Konten selain direktori memakai data dummy di tahap ini | [DITETAPKAN] |
| 8 | Laravel membaca database RKM dengan akun baca saja | [USULAN] |
| 9 | Build Tailwind/Vite dilakukan di komputer pengembang, bukan di server | [USULAN] |
| 10 | Session, cache, queue memakai driver file (tanpa tabel baru di database RKM) | [USULAN] |
| 11 | Lapisan Repository untuk memisahkan sumber data dari Blade | [USULAN] |
| 12 | SIAKAD berjalan di domain/subdomain terpisah dari RKM | [TBD] — perlu dicek (lihat Bagian 12) |

## 3. Konteks Sistem

```
                 Pengunjung (browser, seluler/desktop)
                               │ HTTPS
                               ▼
        ┌──────────────────────────────────────────┐
        │  SIAKAD (Laravel 12, PHP 8.4)            │
        │  Routes → Controller → Repository → View │
        │  Blade + Tailwind (aset hasil build)     │
        └───────┬───────────────────────┬──────────┘
                │ baca saja (SELECT)    │ data dummy (config/array/JSON)
                ▼                       ▼
        ┌───────────────────┐   ┌──────────────────────────┐
        │ Database RKM      │   │ Konten statis/dummy      │
        │ (MySQL/MariaDB)   │   │ berita, pengumuman, dll. │
        └───────▲───────────┘   └──────────────────────────┘
                │ baca-tulis (RKM tidak diubah)
        ┌───────┴───────────┐
        │ RKM (CI3, PHP 7.4)│  ← tetap berjalan seperti sekarang
        └───────────────────┘

   Tautan keluar: Portal CBT, Rapor, PPDB (URL placeholder, tab yang sama)
   Tautan internal: Login SIAKAD (hanya tautan; autentikasi di luar cakupan)
```

**Prinsip utama**

- SIAKAD tidak mengubah apa pun di RKM pada tahap ini. [DITETAPKAN]
- Halaman publik tidak memiliki CRUD, form tulis, atau login. [DITETAPKAN]
- Setiap bagian yang masih dummy harus mudah diganti ke database tanpa mengubah Blade. [DITETAPKAN — PRD BRD-7, BRT-3]

## 4. Stack Teknologi

| Lapisan | Pilihan | Status | Catatan |
| --- | --- | --- | --- |
| Bahasa | PHP 8.4 | [DITETAPKAN] | Laravel 12 membutuhkan PHP 8.2 atau lebih baru; 8.3/8.2 menjadi cadangan jika ada kendala ekstensi. |
| Framework | Laravel 12 | [DITETAPKAN] | |
| Templating | Blade (render di server) | [DITETAPKAN] | Komponen Blade untuk elemen berulang (PRD NAV-7). |
| CSS | Tailwind CSS (build produksi) | [DITETAPKAN] | Tidak memakai CDN pada produksi. |
| Build aset | Vite (bawaan Laravel) | [USULAN] | Dijalankan lokal; folder `public/build` diunggah. |
| JavaScript | JS biasa atau Alpine.js, hanya untuk menu seluler dan interaksi ringan | [TBD] | PRD TBD-16. |
| Database | MySQL/MariaDB milik RKM (versi dicek) | [DITETAPKAN] untuk database bersama; versi [TBD] | |
| Akses data RKM | Query Builder atau model Eloquent read-only | [USULAN] | Nama tabel eksplisit, tanpa timestamps, kolom sensitif tidak diambil. |
| Cache | Driver file, TTL pendek | [USULAN] | Redis belum diperlukan. |
| Session/Queue | Driver file / sync | [USULAN] | Tidak membuat tabel di database bersama. |
| Pustaka UI berat (Bootstrap, jQuery, slider) | Tidak dipakai | [DITETAPKAN] | PRD Bab 5. |
| Livewire / Inertia / SPA | Tidak dipakai di tahap ini | [USULAN] | Filter dan pencarian lewat query string. |

## 5. Lingkungan Hosting

**Hasil pengecekan** (dari panel hPanel, per 24 Okt 2026):

| Item | Nilai | Status |
| --- | --- | --- |
| Penyedia | Niagahoster (hPanel) | [DITETAPKAN] |
| Domain utama | smpmuhammadiyah7medan.sch.id | [DITETAPKAN] |
| Versi PHP pada domain utama | 8.4.26 (CloudLinux, `/opt/alt/php84`) | [DITETAPKAN] |
| CPU | 1 core | [DITETAPKAN] |
| RAM | 2048 MB | [DITETAPKAN] |
| Proses maksimal | 80 | [DITETAPKAN] |
| PHP worker | 40 | [DITETAPKAN] |
| Inode | 400.000 | [DITETAPKAN] |
| Penyimpanan | 100 GB | [DITETAPKAN] |
| Akses SSH | Tersedia di menu Tingkat lanjut → SSH Access | [DITETAPKAN]; koneksi pertama belum selesai dicek |
| Ekstensi PHP aktif | Belum dicek | [TBD] |
| Composer / Node.js di server | Belum dicek | [TBD] |
| URL dan versi PHP RKM | Belum diketahui | [TBD] — **risiko tinggi, lihat Bagian 12** |

**Implikasi**

- Sumber daya terbatas (1 core, 2 GB) dan dipakai bersama RKM. Karena itu halaman harus ringan: cache file, pagination, query hemat kolom, tanpa proses berat di server. [DITETAPKAN — PRD Bab 9]
- `node_modules` tidak boleh diunggah. Inode aman selama tidak ada puluhan ribu file tambahan.
- Satu folder hanya bisa memakai satu versi PHP, sehingga SIAKAD tidak boleh berada di dalam folder RKM.

## 6. Topologi Domain dan Versi PHP

Target [USULAN, bergantung hasil cek Bagian 12]:

| Aplikasi | Lokasi | PHP | Document root |
| --- | --- | --- | --- |
| RKM | URL RKM saat ini (belum diketahui) | 7.4 — tidak diubah | Tetap |
| SIAKAD | Domain atau subdomain terpisah, mis. `siakad.<domain>` | 8.4 | Folder `public/` Laravel |

Aturan:

- Kode Laravel disimpan **di luar** `public_html`; hanya isi `public/` yang terpapar web.
- `.env` tidak boleh dapat diakses dari web.
- Domain RKM tidak boleh dinaikkan ke PHP 8.x karena CodeIgniter 3 berisiko error.

## 7. Struktur Aplikasi (Laravel)

```
app/
  Http/Controllers/        # Controller tipis, hanya memanggil Repository
  Repositories/            # Lapisan akses data (satu antarmuka, implementasi bisa diganti)
    Contracts/             # Antarmuka: BeritaRepository, PengumumanRepository, DirektoriRepository, ...
    Dummy/                 # Implementasi dari data dummy (tahap sekarang)
    Rkm/                   # Implementasi dari database RKM (direktori)
    Database/              # (Tahap B) implementasi dari database konten
  Models/Rkm/              # Model read-only: Guru, Siswa, Kelas, Mapel (nama tabel eksplisit)
config/
  sekolah.php              # Profil statis, kepala sekolah, struktur organisasi, tautan portal
database/dummy/            # Data dummy (PHP/JSON) + komentar penanda penggantian
resources/
  views/
    layouts/               # Layout utama (navbar + footer, satu tempat)
    components/            # Komponen Blade: tombol, kartu, tag, pagination, form cari
    pages/                 # beranda, profil, direktori, kesiswaan, galeri, berita, pengumuman
  css/app.css              # Directive Tailwind
  js/app.js                # JS minimal
routes/web.php             # Rute publik
tests/                     # Feature test (direktori, halaman publik)
```

**Aturan pokok**

- Blade hanya menerima data dari Controller/Repository, tidak membaca file dummy atau database langsung.
- Peralihan dummy → database (Tahap B) = menambah implementasi `Database/` dan mengganti binding di Service Provider. Blade dan rute tidak berubah.
- Satu sumber data untuk kepala sekolah dipakai bersama oleh beranda dan halaman kepala sekolah (PRD BRD-3, PRF-2).

## 8. Alur Data per Modul

| Modul | Sumber data | Akses | Catatan |
| --- | --- | --- | --- |
| Navbar/Footer | `config/sekolah.php` | Statis | Komponen/layout tunggal (NAV-4). |
| Beranda | Dummy + config | Repository | Statistik masih dummy dan diberi penanda (BRD-6). |
| Profil, Kepala Sekolah, Struktur Organisasi | `config/sekolah.php` | Statis | |
| Sarana prasarana, Organisasi, Ekskul, Prestasi, Galeri | Dummy | Repository | Prestasi ditandai sebagai contoh (KSW-3). |
| Berita, Pengumuman | Dummy | Repository | Struktur: judul, slug, ringkasan, isi, gambar, tanggal. |
| Direktori guru/siswa | Database RKM | Repository + model read-only | Pencarian `LIKE`, filter kelas, pagination, urut nama. |

**Direktori — aturan akses data** [DITETAPKAN dari PRD DIR-8]

- Hanya kolom yang diperlukan yang diambil (nama, kelas, dan jabatan/mapel jika tersedia).
- Tidak mengambil NIP, NIK, NIS, NISN, tanggal lahir, alamat, nomor telepon, data orang tua, nilai, absensi, dan foto.
- Tahun ajaran aktif dan status aktif mengikuti aturan RKM setelah diverifikasi (PRD TBD-3, TBD-4).

## 9. Arsitektur Frontend

- Halaman dirender di server (Blade); tanpa SPA dan tanpa framework JS. [DITETAPKAN]
- Mobile First; navigasi tidak menutupi konten di layar kecil. [DITETAPKAN]
- Tema Tailwind (warna, font, jarak, breakpoint) didefinisikan sekali di konfigurasi. [DITETAPKAN]
- Konfigurasi `content` Tailwind harus mencakup semua templat dan komponen Blade; nama kelas tidak boleh disusun dinamis. [DITETAPKAN]
- Pengulangan gaya diselesaikan dengan komponen Blade, bukan `@apply` berlebihan. [DITETAPKAN]
- Ikon memakai SVG sebaris atau sprite; tidak memakai icon font. [DITETAPKAN]
- Font: font sistem atau maksimal satu keluarga yang dihosting sendiri; tidak memakai layanan font eksternal. [DITETAPKAN]
- Gambar: format WebP, ukuran tetap, thumbnail untuk daftar dan galeri, `loading="lazy"`. [USULAN]
- JavaScript: satu berkas kecil, `defer`, hanya untuk kebutuhan nyata. [DITETAPKAN]

## 10. Performa, Cache, dan Keamanan

**Performa**

| Aspek | Aturan | Status |
| --- | --- | --- |
| Query | Pilih kolom seperlunya, pagination, hindari query berulang per item, eager loading bila perlu | [DITETAPKAN] |
| Indeks | Periksa indeks kolom pencarian/filter pada salinan data sebelum mengubah skema | [DITETAPKAN] |
| Cache | Data direktori dan beranda di-cache dengan TTL pendek (nilai TTL belum ditentukan) | [USULAN] |
| Aset | Berkas ber-hash, header cache, gzip/brotli bila server mendukung | [DITETAPKAN] |
| Laravel produksi | `config:cache`, `route:cache`, `view:cache`, `composer install --no-dev --optimize-autoloader` | [USULAN] |
| Target numerik (waktu muat, ukuran halaman, ukuran CSS/JS) | Ditetapkan setelah pengukuran awal | [TBD] — PRD TBD-11 |

**Keamanan**

| Aspek | Aturan | Status |
| --- | --- | --- |
| Akun database | Akun khusus SIAKAD dengan hak `SELECT` saja | [USULAN] |
| Skema RKM | Tidak ada migrasi atau perubahan tabel RKM pada tahap ini | [DITETAPKAN] |
| `.env` dan kode | Di luar folder web atau tidak terpapar; `APP_DEBUG=false` di produksi | [DITETAPKAN] |
| HTTPS | Wajib | [USULAN] |
| Header keamanan dan rate limiting | Header dasar; batasi laju pada pencarian direktori | [USULAN] |
| Privasi | Publikasi nama/kelas/foto siswa mengikuti kebijakan sekolah | [TBD] — PRD TBD-9 |

## 11. Pengujian dan Rilis

**Pengujian** [USULAN]

- Feature test (PHPUnit atau Pest) untuk halaman publik dan direktori, termasuk memastikan kolom sensitif tidak ikut terambil.
- Laravel Pint untuk gaya kode.
- Lighthouse dan pengujian koneksi lambat/seluler untuk performa.
- Query diuji pada **salinan** data RKM (database uji), bukan produksi.

**Alur rilis** [USULAN]

1. Kembangkan dan uji di komputer lokal dengan salinan database RKM.
2. Jalankan `npm run build` secara lokal.
3. Unggah kode dan `public/build` ke server (Git, SSH/rsync, atau pengelola berkas), tanpa `node_modules`.
4. Di server: `composer install` dengan opsi produksi (atau unggah `vendor` jika Composer tidak tersedia), lalu perintah cache Laravel.
5. Cek halaman utama dan direktori; periksa log error.
6. Cadangkan database dan berkas sebelum rilis pertama dan sebelum perubahan besar.

## 12. Risiko dan Pertanyaan Terbuka

| ID | Hal | Dampak | Tindakan | Rujukan |
| --- | --- | --- | --- | --- |
| A-1 | **URL dan versi PHP RKM belum diketahui; domain utama saat ini sudah PHP 8.4.26.** Jika RKM berada di domain ini, RKM mungkin sudah berjalan di PHP 8.4 atau berisiko rusak. | Tinggi | Cek lokasi RKM, versi PHP-nya, dan apakah RKM berfungsi normal. Cek apakah subdomain bisa diberi versi PHP sendiri. | PRD Bab 5 |
| A-2 | Ekstensi PHP 8.4 yang aktif belum dicek (`pdo_mysql`, `mbstring`, `openssl`, `xml`, `ctype`, `tokenizer`, `fileinfo`, `bcmath`, `curl`, `intl`). | Sedang | Cek di Tingkat lanjut → Konfigurasi PHP → Ekstensi PHP. | — |
| A-3 | Composer dan Node.js di server belum dicek (`composer -V`, `node -v`). | Sedang | Jalankan lewat SSH. Build tetap lokal. | PRD TBD-17 |
| A-4 | Akun database baca saja belum jelas bisa dibuat di hPanel; dan apakah bisa diberi akses ke database RKM yang sudah ada. | Sedang | Cek menu Database → Manajemen dan phpMyAdmin; tanya dukungan bila perlu. | PRD TBD-5 |
| A-5 | Versi MySQL/MariaDB, nama kolom jabatan, status aktif, dan aturan tahun ajaran aktif belum diverifikasi. | Sedang | Cek struktur tabel di salinan database. | PRD TBD-2, 3, 4 |
| A-6 | Pilihan JS (biasa atau Alpine.js) belum diputuskan. | Rendah | Putuskan saat menu seluler dibuat. | PRD TBD-16 |
| A-7 | Target performa numerik belum ada. | Rendah | Tetapkan setelah build pertama diukur. | PRD TBD-11 |
| A-8 | Panel admin Tahap A: dashboard buatan sendiri (Blade/Tailwind) atau paket seperti Filament. | Rendah (belum berdampak) | Putuskan sebelum Tahap A dimulai. | PRD Bab 11 (FL-8) |
| A-9 | Struktur domain/subdomain untuk SIAKAD dan format slug berita/pengumuman. | Rendah | Putuskan sebelum rilis. | — |

## 13. Riwayat Revisi

| **Versi** | **Tanggal** | **Penyusun** | **Ringkasan Perubahan** |
| --- | --- | --- | --- |
| v0.1 | 24 Okt 2026 | [TBD] | Draf pertama, berdasarkan PRD v0.2 dan hasil pengecekan hosting Niagahoster (hPanel). |

## Lampiran A. Panduan Memperbarui Dokumen Ini

1. **Keputusan baru:** ubah label di tabel terkait ([USULAN]/[TBD] → [DITETAPKAN]) dan tulis di Riwayat Revisi.
2. **Hasil pengecekan hosting:** perbarui tabel di Bagian 5 dan tutup risiko di Bagian 12.
3. **Fitur baru (Tahap A/B/C):** tambahkan modul di Bagian 8, tambahkan implementasi Repository di Bagian 7, dan perbarui Bagian 3 jika ada sistem baru (mis. panel admin).
4. **Perubahan PRD:** selaraskan ID PRD yang dirujuk dan nomor versi di header.
5. **Ubah, bukan hapus:** keputusan yang berubah ditulis ulang dengan catatan alasan di Riwayat Revisi.
