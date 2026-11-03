# ARCHITECTURE — SIAKAD SMP Muhammadiyah 7 Medan

| | |
| --- | --- |
| **Versi Dokumen** | v0.2 (Draft) |
| **Tanggal** | 3 November 2026 |
| **Dokumen Terkait** | PRD v0.4 (PRD.md); DESIGN.md v0.1; schema.sql (struktur database RKM); Panduan Eksekusi Query SIAKAD + RKM (rancangan awal 25 tabel `siakad_*`) |
| **Cakupan** | Seluruh SIAKAD (prioritas Wajib dan Penting pada PRD), dengan penekanan pada arsitektur basis data karena penilaian tugas besar difokuskan pada manajemen basis data |

> **Cara membaca dokumen ini.** Setiap keputusan diberi status:
> - **[DITETAPKAN]** sudah disepakati atau terverifikasi (dari PRD, hasil pengecekan hosting, atau jawaban tim).
> - **[USULAN]** rekomendasi pengembang, belum dikonfirmasi.
> - **[TBD]** belum diputuskan atau perlu dicek (merujuk ke Bab 12 PRD atau Bagian 15 dokumen ini).
>
> Saat sebuah butir berubah status, ubah labelnya dan catat di **Riwayat Revisi** (Bagian 16).

---

## 1. Tujuan Dokumen

Dokumen ini menjelaskan arsitektur teknis SIAKAD: stack, struktur aplikasi, hubungan dengan RKM, rancangan basis data (tabel, view, trigger, stored procedure, hak akses, transaksi), autentikasi dan otorisasi, lingkungan hosting, aturan performa dan keamanan, serta alur rilis. Dokumen ini dirancang agar mudah diperbarui ketika keputusan baru dibuat atau ketika urutan pengerjaan PRD (Bab 4.3) berlanjut.

## 2. Ringkasan Keputusan Utama

| # | Keputusan | Status |
| --- | --- | --- |
| 1 | Backend: Laravel 12 | [DITETAPKAN] |
| 2 | Bahasa: PHP 8.4 (tersedia di hosting: 8.4.26) | [DITETAPKAN] |
| 3 | Frontend: Blade + Tailwind CSS v4.0.0 (Vite), tanpa SPA | [DITETAPKAN] |
| 4 | RKM tetap CodeIgniter 3; versi PHP RKM belum diketahui (A-1) | [DITETAPKAN] untuk framework; versi [TBD] |
| 5 | SIAKAD dan RKM memakai satu database; tidak membuat API untuk pertukaran data | [DITETAPKAN] |
| 6 | DBMS: **MySQL 8.0** (hasil query server: 8.0.30; lingkungan pengembangan juga MySQL 8.0) | [DITETAPKAN] |
| 7 | Hosting Niagahoster (panel hPanel) | [DITETAPKAN] |
| 8 | Fokus penilaian tugas besar: view, trigger, stored procedure, privilege, transaksi | [DITETAPKAN] |
| 9 | Tabel baru berprefiks `siakad_` pada database yang sama; rancangan awal 25 tabel (15 MVP, 10 fingerprint ditunda) | [DITETAPKAN] sebagai rancangan awal; perubahan pada 8.2 berstatus [USULAN] |
| 10 | Tidak ada perubahan kolom pada tabel RKM; soft delete memakai `stat_data` yang sudah ada | [DITETAPKAN] |
| 11 | Pengembangan di salinan `siakad_rkm_dev`, tidak pernah langsung di database produksi | [DITETAPKAN] |
| 12 | Demo tugas besar lokal (awal Desember 2026), target bersama mitra seluruhnya di hosting | [DITETAPKAN] |
| 13 | Pengguna database dipisah per fungsi (publik hanya membaca view, aplikasi hak terbatas, migrator khusus DDL); tidak ada hak tulis ke tabel nilai RKM | [USULAN] |
| 14 | Operasi multi-tabel dijalankan lewat stored procedure bertransaksi; validasi aturan juga dijaga trigger dan constraint | [USULAN] |
| 15 | Autentikasi: guard sesi Laravel pada `siakad_users`, hash bcrypt; wali kelas diturunkan dari `t_walikelas` | [DITETAPKAN] untuk tabel terpisah dan wali kelas; detail [USULAN] |
| 16 | Panel admin memakai Filament (tema mengikuti token DESIGN.md); panel guru, wali kelas, dan siswa serta halaman publik memakai Blade kustom | [USULAN] — menunggu persetujuan tim (A-8) |
| 17 | Migrasi Laravel hanya untuk tabel `siakad_*`; tidak ada tabel bawaan Laravel di database bersama (session, cache, queue memakai file/sync) | [USULAN] |
| 18 | Susunan host: domain utama untuk situs publik, `siakad.` untuk login dan panel, `rkm.` tetap | [USULAN] — A-9 |
| 19 | Materi pembelajaran disimpan di disk privat dan diunduh lewat controller beraturan akses | [USULAN] |
| 20 | Impor Excel memakai tabel staging dan prosedur validasi-lalu-commit; pustaka Excel belum dipilih | [USULAN]; pustaka [TBD] |

## 3. Konteks Sistem

```
        Pengunjung            Admin          Guru / Wali Kelas          Siswa
            │                   │                    │                    │
            │ HTTPS             │ HTTPS              │ HTTPS              │ HTTPS
            ▼                   ▼                    ▼                    ▼
   ┌──────────────┐   ┌─────────────────────────────────────────────────────────┐
   │ Situs publik │   │ SIAKAD (Laravel 12, PHP 8.4)                            │
   │ (Blade)      │   │ Login → Middleware role → Controller/Filament → Service │
   └──────┬───────┘   └───────────────┬─────────────────────────────────────────┘
          │ SELECT view               │ DML terbatas + EXECUTE procedure
          ▼ (siakad_public)           ▼ (siakad_app)
   ┌───────────────────────────────────────────────────────────────────────────┐
   │ Database MySQL 8.0 (satu database, dipakai bersama RKM)                   │
   │  • Tabel RKM (m_*, t_*, tahun, ...): master ditulis terbatas; nilai tidak │
   │  • Tabel SIAKAD (siakad_*): jadwal, absensi, materi, konten, akun         │
   │  • View v_*, trigger, stored procedure sp_*, function fn_*                │
   └───────────────▲───────────────────────────────────────────────────────────┘
                   │ baca-tulis seperti biasa
          ┌────────┴────────┐          Penyimpanan file privat (materi, gambar)
          │ RKM (CI3)       │          Tautan keluar: CBT, Rapor, PPDB, aplikasi RKM
          └─────────────────┘          Fase 2: mesin fingerprint → penerima data
```

**Prinsip utama**

- Halaman publik tetap hanya membaca dan hanya melalui view khusus; tidak ada CRUD atau login di halaman publik. [DITETAPKAN]
- SIAKAD boleh menulis ke tabel master bersama (guru, siswa, kelas, mata pelajaran, penempatan, penugasan, wali kelas, periode, ekstrakurikuler), tetapi **tidak** menulis ke tabel nilai, rapor, rekap absensi rapor, dan akun RKM. [DITETAPKAN — PRD Bab 5]
- Tidak ada ALTER kolom pada tabel RKM. Penambahan index, trigger, atau constraint pada tabel RKM hanya dengan persetujuan tim dan pengecekan kode RKM. [DITETAPKAN]
- ID master yang sama menjadi penghubung antara SIAKAD dan RKM; tabel SIAKAD menghubungkan data lewat ID bertipe INT bertanda (sama dengan RKM). [DITETAPKAN]
- Setiap bagian publik yang masih dummy harus dapat diganti ke database tanpa mengubah Blade. [DITETAPKAN — PRD BRD-7, BRT-3]

## 4. Stack Teknologi

| Lapisan | Pilihan | Status | Catatan |
| --- | --- | --- | --- |
| Bahasa | PHP 8.4 | [DITETAPKAN] | Laravel 12 membutuhkan PHP 8.2 atau lebih baru; 8.3 menjadi cadangan jika ada kendala ekstensi. |
| Framework | Laravel 12 | [DITETAPKAN] | |
| Templating | Blade | [DITETAPKAN] | Komponen Blade untuk elemen berulang (PRD NAV-7). |
| CSS | Tailwind CSS v4.0.0, build produksi | [DITETAPKAN] | Konfigurasi lewat CSS (`@theme`, `@source`), tanpa CDN. Tailwind v4 menargetkan peramban modern; perlu diuji pada ponsel lama (A-17). |
| Build aset | Vite (bawaan Laravel) | [USULAN] | Dijalankan lokal; `public/build` diunggah. Dua entry: publik dan panel. |
| Panel admin | Filament (v4 atau lebih baru, versi dipastikan saat instalasi) | [USULAN] | Lihat Bagian 11 dan A-8. |
| JavaScript | JS biasa untuk halaman publik; Alpine.js (termasuk bawaan Livewire/Filament) hanya di panel | [USULAN] | PRD TBD-16. |
| DBMS | MySQL 8.0 | [DITETAPKAN] | Fitur 8.0 yang dipakai: role, hak akses per kolom, `JSON_TABLE`, CHECK constraint, CTE. Rancangan awal 25 tabel ditulis untuk MariaDB 10.4, sehingga sintaks dan collation perlu diuji ulang di MySQL 8.0 (A-12). |
| Akses data RKM | Model Eloquent eksplisit (nama tabel, kunci, tanpa timestamps) | [USULAN] | Tabel `t_nilai*` hanya untuk pengecekan keberadaan, dibuat read-only di kode. |
| Autentikasi | Guard sesi Laravel, bcrypt | [DITETAPKAN] | Bagian 10. |
| Cache | Driver file, TTL pendek, dibersihkan saat data berubah | [USULAN] | Redis belum diperlukan. |
| Session/Queue | Driver file / sync | [USULAN] | Menghindari tabel `sessions`, `cache`, `jobs` di database bersama. Proses panjang dijalankan sinkron dengan chunk atau lewat stored procedure (A-14). |
| Impor Excel | PhpSpreadsheet atau OpenSpout (via paket Laravel) | [TBD] | Baca per potongan karena RAM 2 GB. |
| Pustaka UI berat (Bootstrap, jQuery, slider) | Tidak dipakai | [DITETAPKAN] | PRD Bab 5. Filament pada `/admin` adalah pengecualian [USULAN]. |

## 5. Lingkungan Hosting

**Hasil pengecekan** (hPanel, per 24 Okt 2026; database per 3 Nov 2026):

| Item | Nilai | Status |
| --- | --- | --- |
| Penyedia | Niagahoster (hPanel) | [DITETAPKAN] |
| Domain utama | smpmuhammadiyah7medan.sch.id | [DITETAPKAN] |
| Alamat RKM | rkm.smpmuhammadiyah7medan.sch.id | [DITETAPKAN] |
| Versi PHP pada domain utama | 8.4.26 (CloudLinux, `/opt/alt/php84`) | [DITETAPKAN] |
| Versi PHP subdomain RKM | Belum dicek | [TBD] — A-1 |
| CPU / RAM | 1 core / 2048 MB | [DITETAPKAN] |
| Proses maksimal / PHP worker | 80 / 40 | [DITETAPKAN] |
| Inode / Penyimpanan | 400.000 / 100 GB | [DITETAPKAN] |
| Akses SSH | Tersedia (Tingkat lanjut → SSH Access); koneksi pertama belum dicek | [DITETAPKAN] |
| Versi dan mode database | MySQL 8.0.30; `sql_mode` memuat ONLY_FULL_GROUP_BY, STRICT_TRANS_TABLES, NO_ZERO_IN_DATE (hasil query tampil terpotong); charset database utf8mb4 | [DITETAPKAN] |
| Charset tabel RKM | latin1 (semua tabel), engine InnoDB | [DITETAPKAN] — dari schema.sql |
| Ekstensi PHP aktif | Belum dicek (termasuk `zip` dan `gd` untuk impor Excel dan gambar) | [TBD] |
| Composer / Node.js di server | Belum dicek | [TBD] |
| Hak akses database per tabel, `CREATE ROUTINE`, `TRIGGER`, dan `log_bin_trust_function_creators` | Belum dicek | [TBD] — **risiko tinggi, A-4** |
| `upload_max_filesize`, `post_max_size`, `max_execution_time`, `memory_limit` | Belum dicek | [TBD] — A-14 |

**Implikasi**

- Sumber daya terbatas (1 core, 2 GB) dan dipakai bersama RKM. Halaman harus ringan: cache, pagination, query hemat kolom. Skala pengguna kecil (sekitar 34 guru, 471 siswa aktif), dengan puncak beban saat guru mengisi absensi di awal jam pelajaran. [DITETAPKAN]
- `node_modules` tidak boleh diunggah. Inode aman selama berkas upload materi tidak berjumlah puluhan ribu.
- Satu folder hanya bisa memakai satu versi PHP, sehingga SIAKAD tidak boleh berada di dalam folder RKM.
- Pada shared hosting, hak akses MySQL umumnya hanya per database, dan pembuatan trigger atau function bisa ditolak bila binary logging aktif tanpa `log_bin_trust_function_creators`. Karena itu objek DBMS diuji di hosting sedini mungkin, dan Bagian 8.7 menyediakan rencana cadangan.

## 6. Topologi Domain dan Versi PHP

Target [USULAN, bergantung hasil cek A-1 dan A-9]:

| Aplikasi | Alamat | PHP | Document root |
| --- | --- | --- | --- |
| Situs publik | `smpmuhammadiyah7medan.sch.id` (domain utama) | 8.4 | `public/` Laravel |
| Login dan panel SIAKAD | `siakad.smpmuhammadiyah7medan.sch.id` | 8.4 | `public/` Laravel yang sama |
| RKM | `rkm.smpmuhammadiyah7medan.sch.id` | Tidak diubah | Tetap |

Satu aplikasi Laravel melayani dua host: rute publik dan rute SIAKAD dipisah dengan `Route::domain()`, dan cookie sesi bersifat host-only sehingga tidak bercampur dengan situs publik. Tombol Login SIAKAD pada navbar mengarah ke host `siakad`.

Aturan:

- Kode Laravel disimpan **di luar** `public_html`; hanya isi `public/` yang terpapar web.
- `.env` tidak boleh dapat diakses dari web.
- Domain RKM tidak boleh dinaikkan ke PHP 8.x karena CodeIgniter 3 berisiko error.
- Jika subdomain `siakad` tidak dapat diarahkan ke folder `public/` yang sama, cadangan: satu host saja (domain utama) dengan rute `/login` dan panel di jalur terpisah.

## 7. Struktur Aplikasi (Laravel)

```
app/
  Http/
    Controllers/{Public,Auth,Teacher,Student}/   # Controller tipis (Admin/ hanya jika Blade kustom)
    Middleware/            # EnsureRole, EnsureHomeroom, ForcePasswordChange
    Requests/              # FormRequest per modul
  Filament/                # Resource dan halaman panel admin (jika Filament dipakai)
  Models/
    Rkm/                   # Guru, Siswa, Kelas, Mapel, KelasSiswa, GuruMapel, WaliKelas,
                           # Tahun, Ekstra, NaikKelas (read-only), NilaiRef (read-only)
    Siakad/                # Akun, Jadwal, Pertemuan, AbsensiSiswa, Materi, MateriKelas, Berita,
                           # Pengumuman, Fasilitas, EkstraPublik, PrestasiPublik, Galeri,
                           # GaleriFoto, LogAktivitas, ImporSiswa
  Policies/  Enums/        # Role, StatusAbsensi, StatusKonten
  Repositories/            # Pembacaan data publik: Contracts/ + Dummy/ (sementara) + Database/
  Services/                # AcademicPeriod, AccountGenerator, StudentImport, FileStorage,
                           # ProcedureRunner (pemanggil stored procedure + penanganan error)
  Imports/  Exports/       # Template impor siswa, daftar akun awal
config/sekolah.php         # Profil statis, kepala sekolah, struktur organisasi, tautan portal
database/
  migrations/              # HANYA tabel siakad_* (tidak menyentuh tabel RKM)
  sql/{views,functions,procedures,triggers,grants}/   # Objek DBMS sebagai berkas SQL berversi
  seeders/                 # Akun admin awal, data contoh DEV
resources/
  views/                   # Struktur pada Bagian 11
  css/{public.css,panel.css}   js/{public.js,panel.js}
routes/{web.php,siakad.php}
tests/                     # Feature test, Policy test, uji objek DBMS (Bagian 14)
```

**Aturan pokok**

- Blade hanya menerima data dari Controller/Repository/Service, tidak membaca file dummy atau database langsung.
- Data publik dibaca dari **view** `v_publik_*`; peralihan dummy → database (Urutan 2) = menambah implementasi `Database/` dan mengganti binding di Service Provider.
- Operasi multi-tabel dipanggil lewat `ProcedureRunner` (stored procedure bertransaksi); CRUD sederhana lewat Eloquent atau Filament.
- Model RKM wajib: `$table` eksplisit, `$primaryKey` benar (mis. `m_siswa` tidak punya primary key), `$timestamps = false`, tanpa cast tanggal pada kolom yang dapat bernilai `0000-00-00`.
- Objek DBMS disimpan sebagai berkas SQL di `database/sql/` dan dijalankan berurutan oleh satu migrasi atau skrip terpisah; hak akses (`grants`) dijalankan manual oleh pengelola database, bukan oleh aplikasi.
- Satu sumber data kepala sekolah dipakai bersama oleh beranda dan halaman kepala sekolah (PRD BRD-3, PRF-2).

## 8. Arsitektur Basis Data

Bagian ini adalah inti penilaian tugas besar. Prinsipnya: aturan integritas dan keamanan dijaga berlapis, yaitu constraint dan trigger di DBMS, stored procedure untuk operasi multi-langkah, hak akses per pengguna database, dan validasi di aplikasi sebagai lapisan terakhir.

### 8.1 Pembagian Tabel

| Kelompok | Tabel | Hak SIAKAD |
| --- | --- | --- |
| Master RKM yang ditulis SIAKAD | `m_guru`, `m_siswa`, `m_kelas`, `m_mapel`, `m_ekstra`, `t_kelas_siswa`, `t_guru_mapel`, `t_walikelas`, `tahun` | Baca; tambah dan ubah (hapus hanya untuk `t_guru_mapel` dan `t_walikelas` lewat prosedur) |
| Dibaca terbatas | `t_naikkelas` (hanya `id_siswa`, `ta`, `naik`; kolom `catatan_wali` tidak) | Baca per kolom |
| Hanya dicek keberadaannya | `t_nilai`, `t_nilai_ket`, `t_nilai_sikap_so`, `t_nilai_sikap_sp` (kolom `id_guru_mapel`) | Baca per kolom, untuk PGS-7 |
| Tanpa akses | `m_admin` (akun RKM), tabel nilai, rapor, dan rekap absensi rapor lainnya, `t_prestasi`, `m_sekolah` | Tidak ada |
| Tabel SIAKAD (MVP) | 15 tabel pada 8.2 | Penuh (DML) |
| Tabel SIAKAD (Fase 2) | 10 tabel fingerprint pada 8.2 | Tidak dibuat dulu |

### 8.2 Tabel SIAKAD

Rancangan awal berasal dari Panduan Eksekusi Query (25 tabel, 30 foreign key fisik dengan `ON DELETE RESTRICT`). Pembagian dan penyesuaian terhadap PRD v0.4:

| Tabel | Isi | Status | Penyesuaian [USULAN] |
| --- | --- | --- | --- |
| `siakad_users` | akun pengguna | MVP | Tambah `wajib_ganti_password` dan penanda admin utama (AUT-9, AKN-7) |
| `siakad_jadwal` | jadwal per penugasan (`id_guru_mapel`), `tasm`, hari, jam | MVP | Dijaga trigger bentrok (8.5) |
| `siakad_pertemuan` | sesi pertemuan per jadwal dan tanggal | MVP | UNIQUE (`jadwal_id`, `tanggal`, `sesi_ke`) |
| `siakad_absensi_siswa` | status kehadiran per siswa per pertemuan | MVP | UNIQUE (`pertemuan_id`, `id_siswa`); beri DEFAULT pada `updated_at` |
| `siakad_materi`, `siakad_materi_kelas` | materi dan kelas tujuan (lewat penugasan) | MVP | CHECK jenis file/tautan |
| `siakad_berita`, `siakad_pengumuman` | konten publik | MVP | Tambah kolom ringkasan (BRT-1, PGM-1) dan slug pengumuman |
| `siakad_fasilitas`, `siakad_galeri`, `siakad_galeri_foto` | konten publik | MVP | |
| `siakad_ekstra_publik` | deskripsi publik per `m_ekstra.id` | MVP | |
| `siakad_prestasi_publik` | prestasi publik | MVP | Hapus kolom `id_prestasi_rkm`: prestasi publik terpisah dari `t_prestasi` rapor |
| `siakad_log_aktivitas` | catatan aktivitas konten dan audit absensi | MVP | Beri DEFAULT pada `created_at` |
| `siakad_impor_siswa` | ringkasan proses impor | MVP | |
| `siakad_impor_siswa_baris` | baris hasil unggah (staging) | MVP — **baru** | Menyimpan baris sementara, status valid/duplikat/salah, dan alasan (SIS-6, SIS-7) |
| `siakad_staf`, `siakad_personel`, `siakad_fingerprint_perangkat`, `siakad_fingerprint_identitas`, `siakad_fingerprint_log`, `siakad_jam_kerja`, `siakad_presensi_harian`, `siakad_aturan_bonus`, `siakad_hari_libur`, `siakad_audit_presensi` | absensi guru fingerprint | **Ditunda (Fase 2, FL-14 sampai FL-18)** | Dibuat saat fitur dikerjakan |

### 8.3 Konvensi

| Hal | Aturan | Status |
| --- | --- | --- |
| Tipe ID | `INT` bertanda agar cocok dengan ID RKM; di migrasi Laravel pakai `integer()`, **bukan** `foreignId()` (unsigned bigint) | [DITETAPKAN] |
| Relasi lintas charset | Tabel RKM latin1, tabel baru utf8mb4. Relasi dan join hanya lewat kolom integer, tidak lewat kolom teks (NIS, nama) agar tidak terjadi *illegal mix of collations* | [DITETAPKAN] |
| Pengecekan karakter | Uji nama dengan karakter khusus dari RKM untuk memastikan tidak terjadi mojibake akibat perbedaan charset koneksi | [TBD] — A-11 |
| Mode SQL koneksi RKM | Koneksi Laravel untuk tabel RKM mengatur `modes` agar mengikuti konvensi RKM (kolom kosong dan `0000-00-00`), tanpa melonggarkan koneksi untuk tabel `siakad_*` | [USULAN] — A-10 |
| Timestamp | Semua kolom `created_at`/`updated_at` NOT NULL diberi DEFAULT atau dipasok aplikasi; `tgl_update` RKM dipasok eksplisit | [USULAN] |
| Constraint | PK, FK (RESTRICT), UNIQUE, dan CHECK (MySQL 8.0.16+ memberlakukan CHECK) dipakai sejak CREATE TABLE | [USULAN] |
| Nama objek | `siakad_*` tabel, `v_*` view, `sp_*` prosedur, `fn_*` function, `trg_*` trigger, `uq_*`/`chk_*` constraint | [DITETAPKAN] |

### 8.4 View

View dibuat dengan `SQL SECURITY DEFINER` sehingga pengguna publik hanya perlu hak `SELECT` pada view, bukan pada tabel dasarnya.

| View | Isi | Dipakai oleh |
| --- | --- | --- |
| `v_periode_aktif` | `tasm` aktif dari `tahun` (aktif = 'Y', baris terbaru) dan `ta` (4 digit pertama) | Semua view dan prosedur yang butuh periode berjalan |
| `v_publik_guru` | `id`, `nama`, daftar mata pelajaran dari penugasan periode aktif; hanya `stat_data` aktif; tanpa NIP | Direktori guru (DIR-1, DIR-5, DIR-9) |
| `v_publik_siswa` | `id`, `nama`, nama kelas dari penempatan terbaru pada `ta` berjalan | Direktori siswa (DIR-2, DIR-4) |
| `v_publik_statistik` | jumlah siswa (berpenempatan), guru aktif, dan kelas | Beranda (BRD-9) |
| `v_publik_berita`, `v_publik_pengumuman`, `v_publik_fasilitas`, `v_publik_ekstra`, `v_publik_prestasi`, `v_publik_galeri` | konten berstatus `published` | Halaman publik (BRT-4, PGM-4, PRF-8, KSW-6, GAL-4) |
| `v_penugasan_aktif` | penugasan periode aktif lengkap dengan nama guru, mapel, kelas | Penugasan, jadwal, materi, pembatasan akses guru (PGS-5) |
| `v_jadwal_lengkap` | jadwal + penugasan + nama guru, mapel, kelas | Jadwal guru dan siswa (JDW-9, SSW-1, DSH-6) |
| `v_rekap_absensi_kelas` | jumlah hadir, sakit, izin, tanpa keterangan per siswa per kelas dan rentang | Wali kelas dan admin (WKL-5, PAS-4) |
| `v_pertemuan_tanpa_absensi` | pertemuan yang belum memiliki catatan absensi | Pemantauan admin (PAS-3) |
| `v_siswa_tanpa_akun`, `v_guru_tanpa_akun` | calon penerima akun | AKN-1, AKN-2, AKN-9 |

### 8.5 Trigger

Trigger hanya dipasang pada tabel `siakad_*`. **Tidak ada trigger pada tabel RKM pada MVP** karena trigger tersebut juga aktif saat RKM menulis dan dapat merusak proses RKM jika gagal (A-16).

| Trigger | Tabel | Fungsi |
| --- | --- | --- |
| `trg_jadwal_bi`, `trg_jadwal_bu` | `siakad_jadwal` | Menolak jika `jam_mulai >= jam_selesai`, atau jika guru atau kelas sudah punya jadwal aktif yang tumpang tindih pada `tasm` dan hari yang sama (`SIGNAL SQLSTATE '45000'`) (JDW-3) |
| `trg_absensi_bi` | `siakad_absensi_siswa` | Memastikan siswa tercatat pada kelas penugasan jadwal pada tahun terkait |
| `trg_absensi_au` | `siakad_absensi_siswa` | Mencatat perubahan status (sebelum, sesudah, pengubah) ke `siakad_log_aktivitas` (ABS-6) |
| `trg_users_bu`, `trg_users_bd` | `siakad_users` | Mencegah penonaktifan atau penghapusan admin utama terakhir |
| `trg_berita_bi`, `trg_berita_bu` | `siakad_berita`, `siakad_pengumuman` | Mengisi tanggal terbit saat status menjadi `published` (ADM-8) |
| `trg_materi_kelas_bi` | `siakad_materi_kelas` | Memastikan penugasan yang dipilih milik guru dan mata pelajaran materi (MTR-3) |
| CHECK constraint | `siakad_materi`, `siakad_jadwal`, status konten | Jenis file wajib punya `file_path`, jenis tautan wajib punya `tautan_url`; jam valid; nilai status terbatas |

### 8.6 Stored Procedure dan Function

Setiap prosedur memakai `START TRANSACTION`, `DECLARE EXIT HANDLER FOR SQLEXCEPTION` (ROLLBACK lalu `RESIGNAL`), dan mengembalikan ringkasan hasil.

| Objek | Fungsi | PRD |
| --- | --- | --- |
| `fn_ta_dari_tasm(tasm)` | Mengubah `20242` menjadi `2024` | Aturan periode |
| `fn_kelas_siswa(id_siswa, ta)` | Kelas berlaku siswa pada tahun tertentu (penempatan terbaru bila ganda) | DIR-2, SSW-1 |
| `sp_set_periode_aktif(tasm)` | Menonaktifkan periode lain dan mengaktifkan satu periode, dalam satu transaksi | TAJ-4 |
| `sp_tempatkan_siswa(ta, id_kelas, ids_json)` | Penempatan banyak siswa; menolak penempatan ganda pada tahun yang sama dengan lock | PNS-3, PNS-5 |
| `sp_pindah_kelas(id_siswa, ta, id_kelas_baru)` | Pemindahan kelas dengan pengecekan penempatan | PNS-4 |
| `sp_set_walikelas(tasm, id_guru, id_kelas)` | Menjaga satu wali per kelas dan satu kelas per guru per periode | PWK-2 s.d. PWK-4 |
| `sp_salin_walikelas(tasm_asal, tasm_tujuan)` | Menyalin penetapan wali kelas | PWK-6 |
| `sp_simpan_penugasan(tasm, id_guru, id_kelas, id_mapel)` | Menyimpan penugasan tanpa duplikat | PGS-3 |
| `sp_salin_penugasan(tasm_asal, tasm_tujuan)` | `INSERT ... SELECT` penugasan yang belum ada | PGS-6 |
| `sp_hapus_penugasan(id)` | Menolak bila dipakai `siakad_jadwal`, `siakad_materi_kelas`, atau `t_nilai*` | PGS-7 |
| `sp_nonaktifkan_guru(id)`, `sp_nonaktifkan_siswa(id)` | Memeriksa relasi lalu mengubah `stat_data` (nilai menunggu TBD-38 PRD) | GRU-5, SIS-12 |
| `sp_salin_jadwal(tasm_asal, tasm_tujuan)` | Memetakan jadwal ke penugasan periode baru (guru, mapel, kelas sama); yang tidak punya penugasan dilewati dan dilaporkan | JDW-7 |
| `sp_impor_siswa_validasi(impor_id)` | Menandai baris staging valid, duplikat, atau salah beserta alasan | SIS-5, SIS-6 |
| `sp_impor_siswa_commit(impor_id, mode)` | Memasukkan baris valid ke `m_siswa` dengan nilai default RKM (kolom kosong, `inputID` = 0), melewati duplikat, lalu mengisi ringkasan; satu transaksi | SIS-7 s.d. SIS-10 |
| `sp_akun_siswa_massal(data_json)` | Menerima daftar (id_siswa, username, hash) hasil bcrypt dari Laravel dan memasukkannya dengan `JSON_TABLE`; melewati NIS kosong atau ganda | AKN-9, AKN-10 |
| `sp_reset_password(user_id, hash)` | Mengganti hash dan menyalakan `wajib_ganti_password` | AKN-11 |
| `sp_rekap_absensi_kelas(id_kelas, tgl_awal, tgl_akhir)` | Rekap ketidakhadiran per siswa | WKL-5 |
| `sp_kenaikan_kelas_pratinjau`, `sp_kenaikan_kelas_commit` | Memetakan siswa naik ke kelas tujuan dan menyimpannya | KNK-1 s.d. KNK-9 (Urutan 5) [TBD] |

Catatan: bcrypt tidak dapat dihitung di SQL, sehingga hash password selalu dibuat di Laravel lalu dikirim ke prosedur. Itulah salah satu alasan memakai MySQL 8.0 (`JSON_TABLE` tidak ada di MariaDB 10.4).

### 8.7 Hak Akses (Privilege)

| Pengguna database | Dipakai oleh | Hak |
| --- | --- | --- |
| `siakad_migrator` | Pengelola, saat rilis saja | DDL (CREATE, ALTER, DROP, INDEX, REFERENCES, CREATE VIEW, CREATE ROUTINE, ALTER ROUTINE, TRIGGER) pada database SIAKAD. MySQL tidak mendukung pembatasan DDL per pola nama tabel, sehingga akun ini tidak dipakai aplikasi |
| `siakad_app` | Laravel dan Filament saat berjalan | `SELECT` pada master RKM; `INSERT`, `UPDATE` pada `m_guru`, `m_siswa`, `m_kelas`, `m_mapel`, `m_ekstra`, `t_kelas_siswa`, `t_guru_mapel`, `t_walikelas`, `tahun`; `SELECT (id_siswa, ta, naik)` pada `t_naikkelas`; `SELECT (id_guru_mapel)` pada `t_nilai*`; `SELECT/INSERT/UPDATE/DELETE` pada `siakad_*`; `EXECUTE` pada `sp_*` dan `fn_*`; tanpa DDL dan tanpa hak apa pun pada `m_admin` serta tabel nilai selain kolom di atas |
| `siakad_public` | Situs publik | Hanya `SELECT` pada `v_publik_*` |
| `siakad_readonly` | Pencadangan dan pemeriksaan | `SELECT` pada semua |

Di MySQL 8.0 hak dapat dikelompokkan dengan `CREATE ROLE` (mis. `role_publik`, `role_app`) lalu diberikan ke pengguna. Pembatasan per kolom memakai `GRANT SELECT (kolom) ON tabel`.

**Rencana cadangan jika hosting tidak mengizinkan hak per tabel atau role** [TBD, A-4]: dua pengguna pada tingkat database saja (`app` penuh, `public` hanya `SELECT`). Pada skenario ini perlindungan tabel nilai bergantung pada kode (model read-only dan tidak ada operasi tulis ke tabel tersebut), dan pembatasan per kolom serta per tabel didemonstrasikan pada lingkungan lokal. Jika `CREATE TRIGGER` atau `CREATE FUNCTION` ditolak di hosting (binary logging), objek tersebut dipindahkan ke validasi aplikasi di hosting, tetapi tetap dipertahankan pada versi lokal untuk demo.

### 8.8 Transaksi dan Konkurensi

| Operasi | Mekanisme | Catatan |
| --- | --- | --- |
| Impor siswa, akun massal, salin penugasan/jadwal/wali kelas, kenaikan kelas | Satu transaksi dalam stored procedure | Gagal di tengah = ROLLBACK penuh (SIS-9) |
| Penempatan, wali kelas, penugasan | `SELECT ... FOR UPDATE` pada baris terkait atau `GET_LOCK('nama:periode', 5)` | RKM tidak punya unique key untuk aturan ini, sehingga lock menjaga dari penulisan bersamaan |
| Validasi bentrok jadwal | `GET_LOCK('jadwal:<tasm>', 5)` di sekitar pengecekan dan penyimpanan | Gap lock saja tidak cukup untuk deteksi tumpang tindih |
| Pengisian absensi | UNIQUE (`pertemuan_id`, `id_siswa`) + `INSERT ... ON DUPLICATE KEY UPDATE` | Dua guru atau klik ganda tidak membuat baris ganda |
| Isolation level | Default InnoDB (REPEATABLE READ) | Deadlock ditangani dengan percobaan ulang sekali di `ProcedureRunner` |
| DDL | Implicit commit, tidak bisa di-ROLLBACK | Migrasi dicoba di DEV, dicadangkan sebelum ke hosting |

### 8.9 Indeks dan Pemetaan Periode

- Tabel RKM **tidak** diubah indeksnya; pemeriksaan memakai `EXPLAIN` pada salinan, dan penambahan index hanya dengan persetujuan (A-16).
- Indeks `siakad_*` mengikuti pola akses: `siakad_jadwal (tasm, hari, id_guru_mapel)`, `siakad_pertemuan (jadwal_id, tanggal)`, `siakad_absensi_siswa (pertemuan_id, id_siswa)` (unik), `siakad_users (username)` (unik), konten `(status, published_at)`.
- Aturan periode: `tasm` (5 karakter) dipakai oleh penugasan, wali kelas, dan jadwal; penempatan siswa memakai tahun (`fn_ta_dari_tasm`). `v_periode_aktif` menjadi satu-satunya sumber periode berjalan. Salinan database saat ini menandai 20242 aktif sedangkan periode berjalan 20261 (A-13).

### 8.10 Soft Delete

Penonaktifan memakai `stat_data` yang sudah ada (siswa: A, K, M, L; guru: A, P, M). Alumni masih berstatus A, sehingga "siswa aktif" ditentukan dari penempatan pada `ta` berjalan, bukan dari `stat_data`. Nilai yang dipakai untuk menonaktifkan guru menunggu pemeriksaan kode RKM (TBD-38 PRD). Siswa lulus tidak diubah statusnya. [DITETAPKAN]

### 8.11 Urutan Pembuatan Objek Basis Data

| Langkah | Isi | Lingkungan |
| --- | --- | --- |
| 0 | Backup data+struktur; precheck 21 tabel RKM (read-only) | DEV |
| 1 | `CREATE TABLE` 15 tabel MVP + penyesuaian 8.2 | DEV |
| 2 | Verifikasi struktur dan FK; pastikan fitur RKM tidak terganggu | DEV |
| 3 | View (8.4) dan function | DEV |
| 4 | Stored procedure (8.6) berurutan sesuai Urutan Pengerjaan PRD | DEV |
| 5 | Trigger dan CHECK (8.5) | DEV |
| 6 | Hak akses (8.7) dan uji tiap pengguna | DEV |
| 7 | Uji objek DBMS (Bagian 14), lalu coba pindah ke hosting | DEV → hosting |

## 9. Alur Data per Modul

Kolom "Jalur" menunjukkan cara aplikasi mengakses data: **View** (baca), **SP** (stored procedure), **ORM** (Eloquent atau Filament langsung).

| Modul (PRD) | Sumber data | Jalur | Catatan |
| --- | --- | --- | --- |
| Navbar, footer, profil, kepala sekolah, struktur organisasi, organisasi siswa (NAV, PRF-1 s.d. PRF-7, KSW-1) | `config/sekolah.php` | Statis | Tetap statis; kontak sekolah dari `m_sekolah` masih opsi (PRD TBD-8) |
| Beranda, berita, pengumuman, fasilitas, ekskul, prestasi, galeri (publik) | `siakad_*` konten | View `v_publik_*` | Dummy sampai Urutan 2; hanya status `published` |
| Direktori guru dan siswa (DIR) | `m_guru`, `m_siswa`, `t_kelas_siswa`, `t_guru_mapel` | View `v_publik_guru`, `v_publik_siswa` | Tanpa NIP, NIS, NISN, tanggal lahir, alamat, telepon, foto |
| Login, ubah password, profil (AUT) | `siakad_users` + master | ORM | Bagian 10 |
| Akun (AKN) | `siakad_users`, `v_*_tanpa_akun` | ORM + SP (`sp_akun_siswa_massal`, `sp_reset_password`) | Hash dibuat di Laravel |
| Dashboard (DSH) | View ringkasan | View | Kartu admin dan guru; siswa |
| Data guru, siswa (GRU, SIS) | `m_guru`, `m_siswa` | ORM; SP untuk impor dan nonaktif | Impor lewat staging |
| Kelas, penempatan, wali kelas (KLS, PNS, PWK) | `m_kelas`, `t_kelas_siswa`, `t_walikelas` | ORM untuk kelas; SP untuk penempatan dan wali | Lock mencegah penulisan ganda |
| Mata pelajaran, penugasan (MPL, PGS) | `m_mapel`, `t_guru_mapel` | ORM; SP untuk simpan, salin, hapus | `sp_hapus_penugasan` memeriksa `t_nilai*` |
| Tahun ajaran (TAJ) | `tahun` | ORM; SP aktivasi | Hanya satu periode aktif |
| Kenaikan kelas (KNK) | `t_naikkelas` (kolom terbatas), `t_kelas_siswa`, `m_kelas` | SP (Urutan 5) | Hanya status naik dibaca |
| Ekstrakurikuler (EKS, ADM-5) | `m_ekstra`, `siakad_ekstra_publik` | ORM | Nama di RKM, deskripsi di tabel publik |
| Jadwal (JDW) | `siakad_jadwal` + `t_guru_mapel` | ORM + trigger; SP salin | Bentrok ditolak trigger dan lock |
| Absensi siswa (ABS, WKL, PAS, SSW) | `siakad_pertemuan`, `siakad_absensi_siswa` | ORM; View rekap | Audit lewat trigger |
| Materi (MTR, SSW) | `siakad_materi`, `siakad_materi_kelas` + file privat | ORM | Unduh lewat controller |
| Konten publik (ADM) | `siakad_berita`, dst. | ORM (Filament) | Log aktivitas konten |
| Akses RKM (RKM) | Tautan | Statis | Bukan SSO |

## 10. Autentikasi dan Otorisasi

| Aspek | Aturan | Status |
| --- | --- | --- |
| Tabel akun | `siakad_users`, terpisah dari `m_admin` (SHA-1 tidak dipakai) | [DITETAPKAN] |
| Guard | Guard sesi Laravel pada model `Akun`; kolom `is_active` diperiksa saat login | [DITETAPKAN] |
| Hash | bcrypt (bawaan Laravel); hash dibuat di aplikasi, termasuk untuk prosedur akun massal dan reset | [DITETAPKAN] |
| Role | `admin`, `guru`, `siswa`; **wali kelas bukan role**, melainkan hasil pengecekan `t_walikelas` untuk periode aktif (Policy `EnsureHomeroom`) | [DITETAPKAN] |
| Admin utama | Penanda pada akun; mengelola akun admin lain. Awalnya satu akun dengan hak penuh pada tabel master dan SIAKAD, tanpa hak tulis ke tabel nilai | [DITETAPKAN] — PRD TBD-29 |
| Otorisasi | Setiap permintaan diperiksa di backend (middleware role, Policy, dan pembatasan query), bukan hanya dengan menyembunyikan menu (AUT-8) | [DITETAPKAN] |
| Pembatasan data | Guru hanya melihat kelas dan mapel dari `v_penugasan_aktif`; siswa hanya data dengan `id_siswa` miliknya; wali kelas hanya kelas perwaliannya. Semua lewat *scope* query, bukan filter di tampilan | [DITETAPKAN] |
| Password awal | Acak per pengguna; ditampilkan atau diunduh sekali; `wajib_ganti_password` menyala (middleware `ForcePasswordChange`) | [DITETAPKAN] — PRD AKN-9, AKN-10, AUT-9 |
| Ganti password | Password lama + baru + konfirmasi; minimal 8 karakter; tidak sama dengan username atau NIS; sesi di perangkat lain keluar (`AuthenticateSession`) | [DITETAPKAN] — PRD AUT-6 |
| Reset password | Admin membuat password sementara acak; tidak ada reset mandiri (tidak ada data email) | [DITETAPKAN] — PRD AKN-11 |
| Admin utama lupa password | Perintah artisan darurat (mis. `siakad:reset-admin`) dijalankan di server; tidak tersedia lewat web | [USULAN] |
| Pembatasan percobaan login | `RateLimiter` per username dan IP | [USULAN] |
| Username | Siswa: NIS; guru: username lama RKM; admin: ditentukan (PRD TBD-26) | [DITETAPKAN] sebagian |
| Masa sesi dan logout | Mengikuti bawaan Laravel sampai PRD TBD-33 diputuskan | [TBD] |
| Integrasi Filament | `canAccessPanel()` pada model `Akun` hanya mengizinkan role admin; halaman login tunggal dipakai semua role | [USULAN] |

## 11. Arsitektur Frontend

Tiga permukaan UI dengan satu sistem desain (DESIGN.md):

| Permukaan | Pengguna | Teknologi | Status |
| --- | --- | --- | --- |
| Situs publik | Pengunjung | Blade + Tailwind v4, JS minimal | [DITETAPKAN] |
| Panel guru, wali kelas, siswa | Guru, wali kelas, siswa | Blade kustom, mobile-first, Alpine.js seperlunya | [USULAN] |
| Panel admin | Admin | Filament dengan tema kustom (warna, font, radius dari token DESIGN.md) | [USULAN] — A-8 |

**Alasan panel admin memakai Filament** [USULAN]: penilaian tugas besar difokuskan ke basis data, tenggat dekat, dan admin memiliki sekitar 12 modul CRUD. Filament mempercepat bagian tersebut sehingga waktu dapat dialihkan ke objek basis data dan panel guru dan siswa. Konsekuensi yang harus disepakati tim: (a) bagian 15 DESIGN.md (tanpa pustaka UI atau JS berat) direvisi menjadi berlaku untuk situs publik dan panel guru dan siswa, dengan pengecualian `/admin`; (b) tata letak admin tidak persis sama dengan DESIGN.md; (c) Filament berbasis Livewire sehingga aset hanya dimuat pada rute `/admin`; (d) tabel RKM yang tidak standar (`m_siswa` tanpa primary key, tanggal nol) membutuhkan model yang ditulis hati-hati. Alternatif jika tim ingin kesesuaian penuh dengan DESIGN.md: Blade kustom untuk admin dengan risiko cakupan demo lebih sempit.

**Struktur `resources/views`** [USULAN]:

```
resources/views/
  public/
    layouts/app.blade.php          (dari layouts/public.blade.php)
    pages/{home,profile,directory,student-life,gallery,news,announcements}.blade.php
    components/                    navbar, footer, news-card, announcement-card, gallery-card,
                                   teacher-card, student-card, portal-card, stat-card,
                                   contact-item, school-logo
  components/ui/                   button, badge, icon, pagination, empty-state, breadcrumb,
                                   search-input, filter-select, section-heading, image-placeholder
  auth/login.blade.php             (dari pages/login yang saat ini placeholder)
  panel/                           (guru, wali kelas, siswa; admin hanya jika Blade kustom)
    layouts/{app,guest}.blade.php  sidebar, topbar, drawer seluler
    components/                    sidebar, topbar, modal, table, form-input, form-select, status-badge
    teacher/  student/
  dev/                             components-preview, layout-preview, placeholder (hanya lokal)
```

- `welcome.blade.php` dihapus; beranda tetap `public/pages/home` (route sudah mengarah ke sana).
- Tag komponen berubah (mis. `<x-ui.button>`, `<x-public.news-card>`); komponen dasar ditempatkan di `components/ui` karena dipakai juga oleh panel.
- Tailwind v4: token DESIGN.md didefinisikan di CSS dengan `@theme`; `@source` ditambahkan bila ada folder di luar deteksi otomatis.
- Dua entry Vite (`public`, `panel`) agar halaman publik tidak memuat CSS atau JS panel. Filament memakai tema terpisah.
- Halaman yang belum ada di DESIGN.md dan perlu ditambahkan: ubah password dan wajib ganti saat login pertama, pembuatan akun massal beserta unduhan daftar akun awal, salin penugasan/jadwal/wali kelas, dan halaman profil.

## 12. Impor Excel, File, dan Proses Panjang

**Impor siswa (SIS-4 s.d. SIS-10)** [USULAN]

1. Admin mengunduh template (seluruh kolom `m_siswa`; hanya NIS dan nama wajib).
2. Unggah file; Laravel membaca **per potongan** dan menyimpan baris ke `siakad_impor_siswa_baris` (staging) dengan `impor_id`.
3. `sp_impor_siswa_validasi` menandai tiap baris: valid, duplikat (terhadap `m_siswa` dan antarbaris), atau salah beserta alasan.
4. Layar pratinjau menampilkan ringkasan; admin mengonfirmasi (kebijakan baris bermasalah: PRD TBD-25).
5. `sp_impor_siswa_commit` memasukkan baris valid dalam satu transaksi ke `m_siswa` dengan nilai default RKM; duplikat tidak menimpa data lama.
6. Baris staging dibersihkan sesudah selesai atau setelah kedaluwarsa (pembersihan manual atau perintah terjadwal; `EVENT` MySQL hanya jika `event_scheduler` tersedia).
7. Isi sel yang diawali `=`, `+`, `-`, `@` dinetralkan untuk mencegah formula injection.

**File materi dan gambar** [USULAN]

- Disimpan pada disk privat di luar `public/` dengan nama acak; hanya gambar konten publik yang dipublikasikan melalui `storage:link` (atau salinan ke `public/` bila symlink tidak tersedia di hosting).
- Materi diunduh melalui controller yang memeriksa hak akses (siswa hanya untuk kelas tujuan; guru hanya miliknya).
- Validasi MIME dan ukuran di server (rekomendasi awal 15 MB, PRD TBD-24); tautan eksternal hanya `http` atau `https`.
- `upload_max_filesize` dan `post_max_size` harus lebih besar dari batas aplikasi (A-14).

**Proses panjang** [USULAN]: tidak memakai queue worker. Operasi berat dijalankan sinkron lewat stored procedure atau chunk, dengan indikator proses di layar. Jika `max_execution_time` hosting terlalu kecil, impor dipecah per potongan.

## 13. Performa, Cache, dan Keamanan

**Performa**

| Aspek | Aturan | Status |
| --- | --- | --- |
| Query | Pilih kolom seperlunya, pagination, hindari query berulang, eager loading | [DITETAPKAN] |
| Indeks | Tabel RKM tidak diubah; `EXPLAIN` pada salinan; indeks hanya pada tabel `siakad_*` | [DITETAPKAN] |
| Cache | Data direktori dan beranda di-cache TTL pendek dan **dibersihkan saat admin mengubah data terkait** | [USULAN] |
| Aset | Berkas ber-hash, header cache, gzip/brotli bila server mendukung | [DITETAPKAN] |
| Laravel produksi | `config:cache`, `route:cache`, `view:cache`, `composer install --no-dev --optimize-autoloader` | [USULAN] |
| Target numerik | Ditetapkan setelah pengukuran awal | [TBD] — PRD TBD-11 |

**Keamanan**

| Aspek | Aturan | Status |
| --- | --- | --- |
| Pengguna database | Dipisah per fungsi (Bagian 8.7) | [USULAN] |
| Skema RKM | Tidak ada perubahan kolom; migrasi Laravel hanya untuk `siakad_*` | [DITETAPKAN] |
| `.env` dan kode | Di luar folder web; `APP_DEBUG=false` di produksi | [DITETAPKAN] |
| HTTPS dan cookie | HTTPS wajib; cookie `Secure`, `HttpOnly`, `SameSite=Lax` | [USULAN] |
| CSRF dan XSS | Token CSRF Laravel; isi berita dan pengumuman berupa teks biasa yang di-escape | [DITETAPKAN] |
| Rate limiting | Login dan pencarian direktori | [USULAN] |
| Unggahan | Validasi MIME dan ukuran, nama acak, disk privat | [USULAN] |
| Privasi | Publikasi nama, kelas, foto siswa mengikuti kebijakan sekolah | [TBD] — PRD TBD-9 |
| Audit | Catatan aktivitas konten (wajib); log audit data master (FL-24, opsional); audit absensi lewat trigger | [USULAN] |

## 14. Pengujian dan Rilis

**Pengujian** [USULAN]

- Feature test (PHPUnit atau Pest) untuk halaman publik, login, dan setiap role; **Policy test** untuk pembatasan data (guru, wali kelas, siswa).
- **Uji objek DBMS** pada database uji: tiap view (jumlah dan kolom yang keluar, tidak ada kolom sensitif), trigger (kasus bentrok jadwal, jam salah, admin terakhir), stored procedure (kasus sukses, duplikat, dan ROLLBACK saat error), dan hak akses (masing-masing pengguna database mencoba operasi yang tidak boleh dan harus ditolak).
- Uji regresi RKM sesudah operasi tulis dari SIAKAD: login, data siswa, data guru, nilai, dan rapor masih berjalan.
- Uji karakter khusus dan tanggal `0000-00-00` pada data RKM (A-10, A-11).
- Laravel Pint, Lighthouse, dan pengujian koneksi lambat atau seluler.
- Data uji berasal dari salinan RKM yang dianonimkan; tidak ada pengujian langsung di database produksi.

**Alur rilis** [USULAN]

1. Kembangkan dan uji di lokal dengan MySQL 8.0 dan salinan `siakad_rkm_dev`.
2. Jalankan `npm run build` secara lokal.
3. Siapkan lingkungan uji di hosting (subdomain `staging` dengan database salinan) sebelum menyentuh database produksi.
4. Cadangkan database dan berkas; jalankan `migrate --pretend`, lalu migrasi dengan pengguna `siakad_migrator`; jalankan berkas `database/sql/` berurutan.
5. Unggah kode dan `public/build` (tanpa `node_modules`); di server `composer install` mode produksi dan perintah cache Laravel.
6. Terapkan hak akses (8.7) oleh pengelola database; ganti `.env` ke pengguna `siakad_app`.
7. Uji asap: login tiap role, halaman publik, dan fitur RKM; periksa log error.
8. Rencana rollback: pulihkan cadangan database; migrasi `siakad_*` dapat dihapus tanpa menyentuh tabel RKM.
9. **Larangan:** `migrate:fresh`, `migrate:refresh`, `db:wipe`, dan `php artisan migrate` tanpa `--pretend` terhadap database operasional; diblokir pada `APP_ENV=production`.

## 15. Risiko dan Pertanyaan Terbuka

| ID | Hal | Dampak | Tindakan | Rujukan |
| --- | --- | --- | --- | --- |
| A-1 | Alamat RKM diketahui (`rkm.`), tetapi **versi PHP-nya belum**; domain utama PHP 8.4.26. RKM bisa berisiko rusak bila subdomain mewarisi versi tersebut. | Tinggi | Cek versi PHP subdomain `rkm` dan pastikan RKM normal. | PRD TBD-44 |
| A-2 | Ekstensi PHP belum dicek (`pdo_mysql`, `mbstring`, `openssl`, `xml`, `ctype`, `tokenizer`, `fileinfo`, `bcmath`, `curl`, `intl`, **`zip`, `gd`**). | Sedang | Cek di Konfigurasi PHP. | — |
| A-3 | Composer dan Node.js di server belum dicek. | Sedang | Cek lewat SSH; build tetap lokal. | PRD TBD-17 |
| A-4 | Hak akses per tabel/kolom, role, `CREATE ROUTINE`, `CREATE TRIGGER`, dan `log_bin_trust_function_creators` belum diketahui di hosting. | **Tinggi** | Coba `SHOW GRANTS`, buat pengguna uji, dan `CREATE TRIGGER`/`FUNCTION` sederhana di hosting sedini mungkin; siapkan rencana cadangan 8.7. | PRD TBD-5 |
| A-5 | Struktur tabel, versi MySQL, dan status aktif. | — | **Tertutup (v0.2):** schema.sql dan hasil query sudah ada; sisa pada A-18, A-20. | PRD TBD-2, 4 |
| A-6 | Pilihan JS: JS biasa di publik, Alpine.js di panel. | Rendah | Terjawab sebagian pada Bagian 4. | PRD TBD-16 |
| A-7 | Target performa numerik belum ada. | Rendah | Tetapkan setelah build pertama diukur. | PRD TBD-11 |
| A-8 | Panel admin: Filament atau Blade kustom; pengecualian DESIGN.md bagian 15. | Sedang | Tim memutuskan sebelum Urutan 1 selesai. | Bagian 11 |
| A-9 | Susunan host (domain utama dan subdomain `siakad`), document root, isi domain utama saat ini. | Sedang | Cek arah subdomain ke `public/`; fallback satu host. | PRD TBD-44 |
| A-10 | RKM menyimpan tanggal kosong sebagai `0000-00-00` dan kolom kosong sebagai string; server strict menolaknya. | Tinggi | Atur `modes` pada koneksi RKM; uji insert/impor `m_siswa`; model tanpa cast tanggal. | PRD TBD-45 |
| A-11 | Tabel RKM latin1, tabel baru utf8mb4; kemungkinan mojibake atau *illegal mix of collations*. | Sedang | Join hanya lewat integer; uji nama berkarakter khusus. | Bagian 8.3 |
| A-12 | Dump RKM dari MariaDB 10.4 dan panduan SQL ditulis untuk MariaDB; server dan DEV memakai MySQL 8.0. | Sedang | Jalankan ulang precheck dan CREATE TABLE di MySQL 8.0; perhatikan collation dan sintaks. | — |
| A-13 | Periode berjalan 20261 tetapi `tahun` pada salinan menandai 20242 aktif dan tidak memuat 20251 sampai 20261. | Sedang | Pastikan kondisi data; tambahkan periode lewat TAJ pada DEV. | PRD TBD-43 |
| A-14 | Batas PHP hosting (`upload_max_filesize`, `post_max_size`, `max_execution_time`, `memory_limit`) belum dicek; impor Excel dan unggah materi bergantung padanya. | Sedang | Cek dan sesuaikan; impor per potongan. | PRD TBD-24 |
| A-15 | `php artisan migrate` pada database bersama dapat membuat tabel bawaan atau, bila salah perintah (`migrate:fresh`), menghapus tabel RKM. | **Tinggi** | Larangan pada Bagian 14; migrasi hanya `siakad_*`; backup sebelum rilis. | — |
| A-16 | Trigger atau index pada tabel RKM akan aktif juga untuk RKM dan dapat mengganggunya. | Sedang | Tidak dipasang pada MVP; butuh persetujuan dan pengecekan kode RKM. | Bagian 8.5 |
| A-17 | Tailwind v4 menargetkan peramban modern; ponsel lama siswa dan guru dapat menampilkan gaya yang rusak. | Sedang | Uji pada perangkat atau peramban lama; pertimbangkan Tailwind v3.4 bila bermasalah. | — |
| A-18 | Data ganda: NIS 4742, 4846, 5119 muncul dua kali; penempatan ganda pada `t_kelas_siswa` (banyak pada 2021). | Sedang | Periksa penyebab; aturan memilih penempatan berlaku. | PRD TBD-39, 40 |
| A-19 | Tenggat tugas besar awal Desember 2026 dengan 5 orang; cakupan penuh tidak realistis. | **Tinggi** | Tetapkan cakupan demo (Opsi A/B/C). | PRD TBD-37 |
| A-20 | Makna `stat_data` guru (P, M) dan siswa (K, M) dan apakah RKM memfilter nilai A. | Sedang | Cari `stat_data` pada kode RKM. | PRD TBD-38 |

## 16. Riwayat Revisi

| **Versi** | **Tanggal** | **Penyusun** | **Ringkasan Perubahan** |
| --- | --- | --- | --- |
| v0.1 | 24 Okt 2026 | [TBD] | Draf pertama, berdasarkan PRD v0.2 dan hasil pengecekan hosting Niagahoster (hPanel). |
| v0.2 | 3 Nov 2026 | [TBD] | Cakupan diperluas ke seluruh SIAKAD berdasarkan PRD v0.4. DBMS ditetapkan MySQL 8.0. Menambah Bagian 8 (arsitektur basis data: pembagian tabel, tabel SIAKAD, konvensi, view, trigger, stored procedure, hak akses, transaksi, soft delete, urutan pembuatan objek), Bagian 10 (autentikasi dan otorisasi), Bagian 12 (impor Excel, file, proses panjang), struktur views baru, serta risiko A-10 sampai A-20. Keputusan lama berubah: akun database tidak lagi baca saja; "tanpa tabel baru" menjadi "tabel `siakad_*` saja"; Livewire diizinkan hanya di panel admin; alur data per modul diperluas. A-5 ditutup. |

## Lampiran A. Panduan Memperbarui Dokumen Ini

1. **Keputusan baru:** ubah label di tabel terkait ([USULAN]/[TBD] → [DITETAPKAN]) dan tulis di Riwayat Revisi.
2. **Hasil pengecekan hosting atau database:** perbarui tabel di Bagian 5 dan tutup risiko di Bagian 15.
3. **Objek DBMS baru:** tambahkan di Bagian 8.4 sampai 8.7, simpan berkas SQL-nya di `database/sql/`, dan tambahkan kasus uji di Bagian 14.
4. **Fitur baru (urutan pengerjaan atau Fase 2):** tambahkan modul di Bagian 9; tambahkan tabel di Bagian 8.2 (mis. 10 tabel fingerprint saat FL-14 dikerjakan); perbarui Bagian 3 bila ada sistem baru.
5. **Perubahan PRD:** selaraskan ID PRD yang dirujuk dan nomor versi di header.
6. **Ubah, bukan hapus:** keputusan yang berubah ditulis ulang dengan catatan alasan di Riwayat Revisi.
