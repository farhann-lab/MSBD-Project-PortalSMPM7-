# PRODUCT REQUIREMENTS DOCUMENT (PRD)

## SIAKAD SMP Muhammadiyah 7 Medan

**STATUS: DRAFT SEMENTARA**

| | |
| --- | --- |
| **Nama Produk** | SIAKAD SMP Muhammadiyah 7 Medan (Sistem Informasi Akademik, mencakup Website Informasi Publik dan Panel Admin, Guru, Wali Kelas, dan Siswa) |
| **Versi Dokumen** | v0.4 (Draft Sementara) |
| **Disusun oleh** | [TBD — nama pengembang, lihat Bab 12] (Pengembang) |
| **Untuk** | SMP Muhammadiyah 7 Medan (Klien) |
| **Tanggal** | 3 November 2026 |
| **Dokumen Terkait** | (1) Rancangan Fitur Publik SIAKAD SMP Muhammadiyah 7 Medan (RancanganWebSMPM7.md) — rancangan awal, belum permanen. (2) Rincian Alur dan Mekanisme Fitur SIAKAD SMP Muhammadiyah 7 Medan (alur_fitur_fix.md) — dasar pembaruan v0.3. (3) schema.sql (struktur database RKM), DESIGN.md v0.1, dan Panduan Eksekusi Query SIAKAD + RKM (rancangan awal 25 tabel `siakad_*`) — dasar pembaruan v0.4 |

## Riwayat Revisi

> Setiap perubahan pada dokumen ini wajib dicatat di sini. Cara memperbarui dokumen dijelaskan pada **Lampiran A**.

| **Versi** | **Tanggal** | **Penyusun** | **Ringkasan Perubahan** | **Bab Terdampak** |
| --- | --- | --- | --- | --- |
| v0.1 | 22 Okt 2026 | [TBD] | Draf pertama, disusun dari Rancangan Fitur Publik SIAKAD SMP Muhammadiyah 7 Medan. | Semua |
| v0.2 | 22 Okt 2026 | [TBD] | Frontend ditetapkan menggunakan Tailwind CSS dengan syarat website tetap ringan dan optimal. Menambah NAV-7, aturan Tailwind dan batas aset pada Bab 9, TBD-15 sampai TBD-17, dan istilah glosarium. | 1, 4.1, 5, 6.1, 9, 12, 13 |
| v0.3 | 31 Okt 2026 | [TBD] | Cakupan diperluas dari fitur publik ke seluruh SIAKAD berdasarkan dokumen Rincian Alur dan Mekanisme Fitur. **Modul baru (Bab 6):** AUT, AKN, DSH, GRU, SIS, KLS, PNS, PWK, MPL, PGS, TAJ, KNK, EKS, JDW, ADM, PAS, GSW, ABS, MTR, RKM, WKL, SSW. **Kebutuhan baru pada modul lama:** BRD-8, BRD-9, PRF-8, DIR-11, KSW-6, GAL-4, BRT-4, PGM-4. **Teks diubah:** BRD-6, KSW-1 sampai KSW-3 (organisasi siswa ditetapkan tetap statis), batasan "hanya baca" dan "tanpa perubahan skema RKM" pada Bab 5, serta kebutuhan keamanan Bab 9. **Bab 11:** FL-1 sampai FL-3 dan FL-5 sampai FL-10 dipindahkan ke Bab 6; FL-4 dibatalkan; FL-14 sampai FL-22 ditambahkan (Fase 2). **Bab 12:** TBD-14 terjawab; TBD-5, TBD-7, TBD-8, TBD-9, TBD-10, TBD-13 diperbarui; TBD-18 sampai TBD-36 ditambahkan. Menambah Bab 4.3 (urutan pengerjaan), alur baru Bab 7.6 sampai 7.20, matriks hak akses pada Bab 9, dan istilah glosarium. | Semua |
| v0.4 | 3 Nov 2026 | [TBD] | Disesuaikan dengan struktur database RKM (schema.sql), hasil pemeriksaan data, dan keputusan tim. **Istilah:** "tahun ajaran" pada SIAKAD berarti periode `tasm` (20242 = tahun ajaran 2024/2025 semester 2); penempatan siswa per tahun, sedangkan penugasan guru, wali kelas, dan jadwal per semester. **Soft delete** memakai kolom `stat_data` yang sudah ada (tanpa kolom baru). **Kebutuhan baru:** AUT-9, AKN-9 sampai AKN-11, PWK-6, PGS-6, PGS-7, KNK-9. **Teks diubah:** AUT-6, AKN-1, AKN-2, AKN-7, GRU-2, GRU-5, SIS-2 sampai SIS-4, SIS-12, KLS-2, PNS-5, PNS-6, PWK-1 sampai PWK-5, MPL-2, PGS-1, PGS-3, TAJ-2, KNK-1, KNK-3, EKS-2, EKS-3, JDW-1, JDW-7, JDW-8, ADM-2, ADM-3, ADM-6, DIR-1, DIR-2, DIR-6, DIR-9, DIR-11. **Dipindahkan ke Bab 11:** KNK-5 menjadi FL-23 (penanganan siswa tidak naik kelas ditunda). **Bab 11:** FL-23 dan FL-24 (log audit, opsional) ditambahkan. **Bab 12:** TBD-2, TBD-4, TBD-27 sampai TBD-29 terjawab; TBD-3, TBD-5, TBD-8, TBD-12, TBD-23, TBD-26, TBD-31, TBD-33, TBD-35 diperbarui; TBD-37 sampai TBD-45 ditambahkan. Menambah target rilis pada Bab 4.3, pendekatan basis data (view, trigger, stored procedure, privilege, transaksi) pada Bab 5 dan Bab 9, kolom aktual RKM pada Bab 8, dan istilah glosarium. | Semua |

---

# 1. Ringkasan Produk (Overview)

SMP Muhammadiyah 7 Medan sedang mengembangkan SIAKAD (Sistem Informasi Akademik) baru. Sistem yang sudah berjalan, yaitu RKM (dibangun dengan CodeIgniter 3), tetap digunakan untuk proses penilaian dan pengelolaan rapor. Namun, pengelolaan data master, jadwal pelajaran, absensi, materi pembelajaran, dan informasi publik sekolah belum terpusat pada satu sistem operasional. Sekolah juga belum memiliki satu pintu informasi publik yang terpadu: profil sekolah, berita, pengumuman, dan kegiatan siswa belum terpusat, dan akses ke portal sekolah lain (CBT, Rapor, PPDB) belum tersedia dari satu halaman.

Solusi yang dibangun adalah **SIAKAD berbasis Laravel** yang berbagi database dengan RKM. SIAKAD menjadi pusat pengelolaan data master (guru, siswa, kelas, mata pelajaran, penempatan siswa, penugasan guru, wali kelas, tahun ajaran, ekstrakurikuler) dan aktivitas operasional akademik, yaitu akun dan hak akses, jadwal pelajaran, absensi siswa per pertemuan, materi pembelajaran, serta informasi publik. SIAKAD memiliki empat role pengguna (Admin, Guru Mata Pelajaran, Wali Kelas, Siswa) dan website informasi publik berantarmuka Tailwind CSS yang dapat diakses tanpa login. RKM tetap menangani nilai, rekap absensi rapor, kenaikan kelas, dan pencetakan rapor, sehingga SIAKAD tidak mengulang fungsi RKM yang sudah berjalan. Tujuan besarnya adalah menjadikan SIAKAD sistem operasional utama sekolah yang ringan, responsif, dan kompatibel dengan RKM.

# 2. Tujuan & Sasaran (Goals)

- Memusatkan pengelolaan data master sekolah (guru, siswa, kelas, mata pelajaran, penempatan, penugasan, wali kelas, tahun ajaran) pada SIAKAD tanpa merusak proses RKM yang berjalan.
- Memusatkan informasi publik SMP Muhammadiyah 7 Medan dalam satu website yang konsisten dan mudah dinavigasi, serta menjadi pintu masuk tunggal menuju portal CBT, Rapor, PPDB, dan login SIAKAD.
- Menyediakan akun dan hak akses berbasis role (Admin, Guru, Wali Kelas, Siswa) yang aman, dengan pemeriksaan hak akses di sisi backend.
- Menyediakan pencatatan absensi siswa per pertemuan oleh guru, beserta pemantauannya oleh wali kelas dan admin, serta transparansi catatan kehadiran bagi siswa.
- Memudahkan penyusunan jadwal pelajaran tanpa bentrok, penyediaan materi pembelajaran sesuai kelas, dan pengelolaan pergantian tahun ajaran dengan riwayat yang tetap terjaga.
- Menyediakan direktori guru dan siswa yang bersumber dari data master tanpa membuka data pribadi.
- Menghadirkan website dan aplikasi yang ringan dan responsif di komputer maupun perangkat seluler, termasuk pada koneksi lambat.

# 3. Pengguna & Peran (Users & Roles)

- **Pengunjung Publik :** orang tua, calon siswa, alumni, dan masyarakat umum. Mengakses halaman publik tanpa login (hanya membaca), mencari direktori, dan menuju portal lain.
- **Admin :** mengelola sistem, data master, akun, jadwal, penugasan guru, tahun ajaran, konten publik, serta memantau absensi siswa. Admin tidak mengubah catatan absensi yang dibuat guru pengampu. Pengelolaan akun admin lain dibatasi untuk **admin utama**, yang merupakan izin tambahan pada role Admin (bukan role kelima).
- **Guru Mata Pelajaran :** mengakses jadwal mengajar, melihat data siswa pada kelas yang diajar, mengelola absensi siswa per pertemuan, mengelola materi pembelajaran, dan mengakses RKM melalui tautan. Hanya dapat mengelola kelas dan mata pelajaran sesuai penugasannya.
- **Wali Kelas :** guru yang ditetapkan sebagai wali kelas pada tahun ajaran aktif. Memakai satu akun dan satu dashboard yang sama dengan guru, dengan menu tambahan untuk memantau siswa dan absensi kelas perwaliannya (hanya melihat). Status wali kelas berasal dari penetapan wali kelas, bukan dari pilihan role saat pembuatan akun.
- **Siswa :** melihat jadwal pelajaran, materi pembelajaran, absensi pribadi, dan profilnya. Tidak dapat mengubah data akademik.
- **Pengembang :** mengganti data dummy dengan data resmi, memverifikasi skema RKM, dan menjaga agar penggunaan database bersama tidak mengganggu RKM.

# 4. Ruang Lingkup (Scope)

## 4.1 Termasuk (MVP)

Rangkuman level tinggi; detail dan prioritas tiap kebutuhan ada di Bab 6.

- **Website publik:** komponen bersama navbar dan footer; beranda; profil sekolah (statis); direktori guru dan siswa dari database master; kesiswaan; galeri; berita; pengumuman; antarmuka Tailwind CSS dengan komponen UI bersama; optimasi performa dan tampilan responsif.
- **Autentikasi dan akun:** login, pembatasan akses berbasis role di backend, ubah password, profil, serta manajemen akun admin, guru, dan siswa (termasuk pembuatan akun siswa massal dan reset password oleh admin).
- **Dashboard berdasarkan role:** dashboard admin, guru (termasuk wali kelas), dan siswa.
- **Data master:** data guru, data siswa (termasuk impor Excel), manajemen kelas (data kelas, penempatan siswa, penetapan wali kelas), mata pelajaran, penugasan guru ke mata pelajaran dan kelas, serta pengelolaan tahun ajaran.
- **Pengelolaan konten publik oleh admin:** berita, pengumuman, fasilitas, ekstrakurikuler publik, prestasi, dan galeri dengan status Draft, Published, dan Archived, serta integrasi backend untuk halaman publik.
- **Operasional pembelajaran:** jadwal pelajaran (termasuk salin jadwal tahun sebelumnya), absensi siswa per pertemuan beserta riwayatnya, pemantauan absensi oleh wali kelas dan admin, materi pembelajaran, serta tampilan jadwal, materi, dan absensi untuk siswa.
- **Kenaikan kelas dan penempatan tahun ajaran baru:** SIAKAD membaca keputusan kenaikan kelas dari RKM, menyiapkan penempatan siswa tahun ajaran baru, dan admin memeriksa serta mengonfirmasinya.
- **Pengelolaan ekstrakurikuler** pada tabel yang digunakan bersama RKM.
- **Tautan menuju aplikasi RKM** pada sidebar guru dan wali kelas.
- Seluruh data dummy pada halaman publik diberi komentar penanda dan dipisahkan dari logika tampilan sampai integrasi backend selesai.

## 4.2 Di Luar Lingkup Awal / Fase Lanjutan

Fitur yang ditunda (rincian pada Bab 11):

- Absensi guru melalui mesin fingerprint: integrasi mesin, rekap bulanan, perhitungan bonus ketepatan waktu, pengaturan jam kerja guru, pencetakan rekap, dan menu Absensi Saya untuk guru (FL-14 sampai FL-18).
- Status Draft, Published, dan Archived untuk materi pembelajaran (opsional, FL-19).
- Ringkasan nilai dari RKM, penyempurnaan laporan statistik, dan notifikasi informasi akademik (FL-20 sampai FL-22), penanganan siswa tidak naik kelas (FL-23), serta log audit perubahan data master (FL-24, opsional).
- Penyempurnaan pencarian dan optimasi, sinkronisasi lanjutan dengan RKM, serta fitur tambahan lain (FL-11 sampai FL-13).

Hal berikut **tidak termasuk dalam rancangan saat ini** dan tidak dijadwalkan:

- Pengumpulan tugas oleh siswa.
- Pembaruan otomatis rekap absensi rapor RKM dari absensi per pertemuan SIAKAD (guru tetap memvalidasi dan mengisi sakit, izin, dan tanpa keterangan secara manual di RKM).
- Login otomatis ke RKM menggunakan akun SIAKAD (akses RKM hanya berupa tautan).
- Penilaian, rapor, dan catatan wali kelas di SIAKAD (tetap di RKM).
- Pengelolaan organisasi siswa melalui database (ditetapkan tetap statis).

## 4.3 Urutan Pengerjaan

Urutan berikut merupakan rencana awal berdasarkan prioritas pada rancangan alur dan arahan klien. Urutan ini berbeda dari kolom Prioritas pada Bab 6: pengelolaan konten dan integrasi backend website publik berprioritas **Penting**, tetapi dikerjakan lebih awal setelah login admin berhasil.

| **Urutan** | **Cakupan** | **Modul (Bab 6)** |
| --- | --- | --- |
| **1** | Sistem login dan autentikasi, akun admin, serta kerangka dashboard admin | AUT, AKN-3 sampai AKN-8, DSH-1, DSH-2 |
| **2** | Integrasi backend website publik: pengelolaan konten publik oleh admin dan penggantian data dummy dengan data database | ADM, BRD-8, BRD-9, PRF-8, DIR, KSW-6, GAL-4, BRT-4, PGM-4, DSH-4 |
| **3** | Fondasi data master dan akademik | AKN-1, AKN-2, AKN-9 sampai AKN-11, GRU, SIS, KLS, PNS, PWK, MPL, PGS, TAJ, DSH-5, DSH-7 |
| **4** | Operasional pembelajaran | JDW, ABS, WKL, MTR, SSW, GSW, RKM, DSH-6, DSH-8 |
| **5** | Fitur yang memerlukan pemeriksaan teknis atau koordinasi tambahan | KNK, EKS, PAS, DSH-3 |
| **Fase 2** | Fitur Bab 11 (fingerprint, status materi, ringkasan nilai, dan lainnya) | FL-11 sampai FL-24 |

**Target rilis.** Demo tugas besar (awal Desember 2026) dijalankan secara lokal dan sebaiknya juga di hosting. Penilaian tugas besar difokuskan pada penerapan manajemen basis data (view, trigger, stored procedure, privilege, dan transaksi). Cakupan fitur yang harus selesai pada demo belum diputuskan (TBD-37). Target bersama mitra sekolah adalah seluruh sistem berjalan di hosting (jadwal belum ditetapkan, TBD-12).

# 5. Asumsi & Batasan (Assumptions & Constraints)

- **Teknologi (sudah ditetapkan):** SIAKAD baru menggunakan Laravel 12, Tailwind CSS v4 (melalui Vite), dan MySQL 8.0; RKM menggunakan CodeIgniter 3. Dump struktur RKM (schema.sql) dibuat dari MariaDB 10.4, sedangkan server dan lingkungan pengembangan memakai MySQL 8.0.
- **Pembagian tanggung jawab sistem (sudah ditetapkan):** SIAKAD mengelola data master, akun pengguna, jadwal, absensi siswa per pertemuan, materi, dan informasi publik (absensi guru fingerprint pada Fase 2). RKM tetap mengelola nilai pengetahuan, keterampilan, dan sikap, rekap absensi rapor, nilai ekstrakurikuler, prestasi untuk rapor, catatan wali kelas, keputusan kenaikan kelas, dan pencetakan rapor.
- **Frontend (sudah ditetapkan):** antarmuka dibangun dengan Tailwind CSS. Syarat utamanya: website tetap optimal dan tidak berat (aturan teknis pada Bab 9).
- **Database bersama (sudah ditetapkan):** SIAKAD dan RKM memakai satu database yang sama sehingga API tidak diperlukan hanya untuk pertukaran data. Data guru, siswa, kelas, mata pelajaran, penempatan siswa, penugasan guru, wali kelas, tahun ajaran, dan ekstrakurikuler dikelola melalui SIAKAD pada tabel yang digunakan bersama RKM. Tabel master kedua yang berisi data sama tidak dibuat; ID master yang sama dipakai sebagai penghubung.
- **Pendekatan basis data (sudah ditetapkan):** penilaian tugas besar difokuskan pada manajemen basis data, sehingga SIAKAD memanfaatkan fitur DBMS: view (mis. direktori publik), trigger (audit dan validasi), stored procedure dengan transaksi (impor siswa, pembuatan akun massal, salin penugasan, kenaikan kelas), dan pengaturan hak akses pengguna database. Rancangan awal berupa 25 tabel berprefiks `siakad_` yang dibuat pada database salinan `siakad_rkm_dev` (bukan database produksi); tabel fingerprint di dalamnya ditunda ke Fase 2.
- **Mode SQL dan data RKM:** RKM menyimpan data dengan konvensi non-strict (kolom kosong berupa string kosong, tanggal kosong berupa `0000-00-00`), sedangkan server memakai mode strict. Koneksi Laravel untuk tabel RKM harus menyesuaikan perilaku ini, dan kolom `inputID` pada data siswa selalu diisi 0 seperti pada RKM (asumsi pengembang, TBD-45).
- **Periode akademik (sudah ditetapkan):** "tahun ajaran" pada SIAKAD berarti periode `tasm` berformat lima karakter (tahun ajaran dan semester); 20242 berarti tahun ajaran 2024/2025 semester 2. Penempatan siswa disimpan per tahun (empat digit pertama kode periode, kolom `ta` bertipe YEAR), sedangkan penugasan guru, wali kelas, dan jadwal disimpan per periode (semester). Periode berjalan saat ini adalah 20261, sedangkan tabel `tahun` pada salinan database masih menandai 20242 sebagai aktif dan tidak memuat 20251 sampai 20261 (TBD-43).
- **Perubahan batasan dari v0.2 (disetujui klien):** batasan "Laravel hanya membaca" dan "tanpa perubahan skema RKM" diganti menjadi: (a) Laravel diperbolehkan menulis ke tabel master bersama; (b) skema tabel RKM tidak diubah tanpa audit dan pemeriksaan ketergantungan kode RKM; (c) tabel baru dibuat khusus untuk SIAKAD; (d) soft delete memakai kolom `stat_data` yang sudah ada pada tabel guru dan siswa (tanpa kolom baru).
- **Batasan keamanan data:** tabel nilai, rapor, rekap absensi rapor, dan tabel keputusan kenaikan kelas RKM tidak diubah oleh SIAKAD (hanya dibaca bila diperlukan). Akses aplikasi ke tabel dibatasi sesuai kebutuhan masing-masing sistem. Operasi yang melibatkan beberapa tabel memakai transaksi database.
- **Akun dan autentikasi (sudah ditetapkan):** tabel autentikasi SIAKAD terpisah dari tabel akun RKM; password SHA-1 dari RKM tidak dipakai; akses ke RKM hanya berupa tautan (bukan login terpadu).
- **Riwayat akademik:** riwayat penempatan siswa, penetapan wali kelas, dan jadwal tahun ajaran sebelumnya selalu dipertahankan. Data master yang punya relasi tidak dihapus fisik.
- **Konten statis:** profil sekolah, visi dan misi, sambutan kepala sekolah, struktur kepemimpinan, dan organisasi siswa tetap berupa konten statis. Direktori guru dan siswa diambil dari tabel master dengan data yang dibatasi (Bab 9).
- **Ekstrakurikuler:** data akademik ekstrakurikuler (tabel bersama RKM) dan konten publik ekstrakurikuler dipisahkan agar data akademik tidak tercampur dengan informasi promosi sekolah. Penilaian ekstrakurikuler untuk rapor tetap di RKM.
- **Status pengerjaan (per 3 Nov 2026):** frontend halaman publik sudah selesai menggunakan data dummy (beranda memakai `pages/home`) dan belum terintegrasi ke backend. Halaman login hanya placeholder, dan frontend dashboard (admin, guru, wali kelas, siswa) belum ada. Backend dimulai dari sistem login admin, lalu integrasi backend website publik (Bab 4.3).
- **Data dummy sementara:** sampai integrasi backend selesai, konten publik selain direktori memakai data dummy. Data dummy tidak boleh tampil seolah-olah data resmi (khususnya statistik dan prestasi).
- **Fingerprint:** detail teknis mesin fingerprint dikembangkan dan diuji secara terpisah sebelum diintegrasikan ke SIAKAD; seluruh fiturnya berada di Fase 2.
- **Tautan portal:** URL CBT, Rapor, dan PPDB memakai placeholder dan dibuka pada tab yang sama.
- **Struktur RKM (diverifikasi dari schema.sql):** 21 tabel InnoDB berkarakter latin1; semua ID bertipe INT bertanda; `m_siswa` tidak memiliki primary key (hanya UNIQUE pada `id`); tabel RKM tidak memiliki unique key untuk penempatan siswa, wali kelas, maupun NIS, dan sebagian data historis penempatan sudah ganda. Nama kolom aktual ada pada Bab 8.
- **Siswa aktif:** ditentukan dari penempatan kelas pada tahun berjalan, bukan dari `stat_data`, karena alumni pada data RKM masih berstatus A. Siswa kelas 9 yang lulus tidak diubah statusnya (nilai L belum dipakai pada data RKM).
- **Asumsi pengembang:** fitur yang tidak disebut eksplisit dalam daftar prioritas rancangan alur (Data Siswa guru, menu wali kelas, tautan RKM) diberi prioritas **Penting** mengikuti fitur induknya; akun admin, pengaturan sistem, profil, dan ubah password diberi prioritas **Wajib**.
- **Asumsi pengembang:** pendekatan desain mengutamakan tampilan seluler (Mobile First) dan halaman dirender di sisi server melalui Laravel Blade.
- **Asumsi pengembang:** Tailwind CSS dipasang sebagai dependensi proyek dan dikompilasi melalui proses build (Vite bawaan Laravel), bukan dimuat dari CDN pada produksi.
- **Asumsi pengembang:** tidak memakai pustaka UI atau JavaScript berat (mis. Bootstrap, jQuery, pustaka slider atau galeri besar); interaktivitas dibuat seminimal mungkin. Pilihan pustaka JavaScript ringan dicatat pada TBD-16.
- **Batasan:** proses build memerlukan Node.js pada lingkungan pengembangan atau penerapan; lokasi proses build ditentukan pada TBD-17.
- **Asumsi pengembang:** perubahan rencana atau penambahan fitur dicatat melalui revisi dokumen ini (Lampiran A).
- **Biaya:** belum dibahas; potensi biaya hosting, domain, dan penyimpanan file (gambar, materi) dicatat pada Bab 12.

# 6. Kebutuhan Fungsional (Functional Requirements)

> Catatan pembaruan: ID tidak boleh dipakai ulang. Kebutuhan baru diberi nomor berikutnya pada modul terkait, atau modul baru dengan prefix baru (lihat Lampiran A). Kebutuhan yang dihapus ditandai ~~dicoret~~ dan dicatat di Riwayat Revisi.
>
> Prioritas mengikuti rancangan alur: fondasi sistem → **Wajib**; fitur operasional pembelajaran, pengelolaan konten publik, dan fitur yang memerlukan pemeriksaan teknis → **Penting**; fitur yang ditunda → **Fase 2** (ditempatkan di Bab 11). Urutan pengerjaan ada pada Bab 4.3.

## 6.1 Pengunjung — Navbar dan Footer

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **NAV-1** | Sistem menampilkan navbar bersama yang memuat tautan Beranda, Profil Sekolah, Direktori, Kesiswaan, Galeri, Berita, Pengumuman, dan Login SIAKAD. | **Wajib** |
| **NAV-2** | Sistem menyediakan submenu pada Profil Sekolah, Direktori, dan Kesiswaan. | **Penting** |
| **NAV-3** | Sistem menampilkan footer bersama berisi nama sekolah, alamat atau kontak resmi (jika tersedia), tautan navigasi penting, dan informasi hak cipta. | **Wajib** |
| **NAV-4** | Navbar dan footer dibuat sebagai komponen/layout Blade tunggal sehingga konsisten di semua halaman publik tanpa duplikasi markup. | **Wajib** |
| **NAV-5** | Navigasi responsif dan tidak menghalangi konten pada layar kecil. | **Wajib** |
| **NAV-6** | Pengunjung dapat menuju halaman login SIAKAD melalui tombol Login SIAKAD. | **Wajib** |
| **NAV-7** | Elemen antarmuka yang berulang (tombol, kartu berita/pengumuman, kartu direktori, tag, pagination, kolom pencarian dan filter) dibuat sebagai komponen Blade berbasis Tailwind CSS yang dipakai ulang di semua halaman publik. | **Wajib** |

## 6.2 Pengunjung — Beranda

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **BRD-1** | Sistem menampilkan hero berisi nama sekolah, slogan, deskripsi singkat, gambar banner (dioptimasi), tombol ke profil sekolah, dan tombol ke berita atau pengumuman. | **Wajib** |
| **BRD-2** | Sistem menampilkan satu baris tiga ikon portal (CBT, Rapor, PPDB) dengan tautan placeholder yang dibuka pada tab yang sama. | **Wajib** |
| **BRD-3** | Sistem menampilkan ringkasan sambutan kepala sekolah (nama, jabatan, ringkasan, tombol ke halaman kepala sekolah) dari sumber data statis yang sama dengan PRF-2. | **Wajib** |
| **BRD-4** | Sistem menampilkan beberapa berita terbaru (judul, gambar, ringkasan, tanggal, tautan detail). | **Wajib** |
| **BRD-5** | Sistem menampilkan beberapa pengumuman terbaru (judul, ringkasan, tanggal, tautan detail). | **Wajib** |
| **BRD-6** | Sistem menampilkan statistik jumlah siswa, guru, dan kelas. Sampai BRD-9 diterapkan, angka berupa data dummy yang ditandai belum bersumber dari database. | **Wajib** |
| **BRD-7** | Seluruh data dummy beranda diletakkan terpisah dari logika tampilan dengan komentar penanda lokasi penggantian. | **Wajib** |
| **BRD-8** | Setelah integrasi backend, berita dan pengumuman terbaru pada beranda diambil dari database dan hanya yang berstatus Published (menggantikan data dummy BRD-4 dan BRD-5). | **Penting** |
| **BRD-9** | Setelah integrasi backend, statistik jumlah siswa, guru, dan kelas pada beranda diambil dari tabel master (definisi "aktif" mengikuti TBD-4), menggantikan angka dummy BRD-6. | **Penting** |

## 6.3 Pengunjung — Profil Sekolah

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PRF-1** | Pengunjung melihat halaman Tentang Sekolah berisi nama, alamat, deskripsi, serta visi dan misi (data statis). | **Wajib** |
| **PRF-2** | Pengunjung melihat halaman Kepala Sekolah berisi nama, jabatan, foto, dan sambutan lengkap (data statis). | **Wajib** |
| **PRF-3** | Pengunjung melihat struktur organisasi sekolah (nama jabatan dan nama pejabat) dalam bentuk daftar atau bagan sederhana (data statis). | **Wajib** |
| **PRF-4** | Struktur organisasi dapat menampilkan foto dan keterangan tugas jika datanya tersedia. | **Penting** |
| **PRF-5** | Pengunjung melihat daftar sarana dan prasarana (nama fasilitas, foto, deskripsi singkat) menggunakan data dummy sampai PRF-8 diterapkan. | **Wajib** |
| **PRF-6** | Halaman Tentang Sekolah dapat memuat sejarah sekolah dan kontak resmi jika telah disetujui untuk dipublikasikan. | **Penting** |
| **PRF-7** | Data profil dapat diganti tanpa mengubah struktur layout halaman. | **Wajib** |
| **PRF-8** | Setelah integrasi backend, daftar sarana dan prasarana diambil dari database dan hanya fasilitas berstatus Published yang tampil (menggantikan data dummy PRF-5). | **Penting** |

## 6.4 Pengunjung — Direktori Guru dan Siswa

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **DIR-1** | Pengunjung melihat direktori guru berisi nama guru, serta jabatan dan mata pelajaran yang diampu hanya jika tersedia di sumber data (tabel guru tidak memiliki kolom jabatan sehingga jabatan tidak ditampilkan; mata pelajaran diambil dari penugasan guru). | **Wajib** |
| **DIR-2** | Pengunjung melihat direktori siswa berisi nama siswa dan kelas aktif, dari siswa yang memiliki penempatan kelas pada tahun ajaran berjalan (bukan dari `stat_data`, karena alumni masih berstatus A pada data RKM). Jika ada lebih dari satu penempatan pada tahun yang sama, penempatan terbaru yang dipakai (TBD-40). | **Wajib** |
| **DIR-3** | Pengunjung mencari guru atau siswa berdasarkan nama. | **Wajib** |
| **DIR-4** | Pengunjung memfilter direktori siswa berdasarkan kelas. | **Wajib** |
| **DIR-5** | Pengunjung memfilter direktori guru berdasarkan mata pelajaran jika relasi penugasan mendukung. | **Penting** |
| **DIR-6** | Pengunjung memfilter direktori guru berdasarkan jabatan hanya jika sumber data menyediakan jabatan; saat ini tidak berlaku karena tabel guru tidak memiliki kolom jabatan. | **Penting** |
| **DIR-7** | Sistem mengurutkan direktori berdasarkan nama. | **Wajib** |
| **DIR-8** | Sistem tidak menampilkan data sensitif: NIP, NIK, NIS, NISN, tanggal lahir, alamat, nomor telepon pribadi, data orang tua, nilai, absensi, maupun foto guru dan siswa. | **Wajib** |
| **DIR-9** | Sistem hanya menampilkan guru berstatus aktif pada `stat_data` (nilai A; makna nilai lain menunggu TBD-38). | **Penting** |
| **DIR-10** | Sistem membatasi jumlah data per halaman (pagination) dan hanya mengambil kolom yang diperlukan. | **Penting** |
| **DIR-11** | Sistem tidak menampilkan guru atau siswa yang telah dinonaktifkan melalui SIAKAD (perubahan `stat_data`), dan sumber direktori adalah tabel master yang sama dengan yang dikelola admin. | **Wajib** |

## 6.5 Pengunjung — Kesiswaan

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **KSW-1** | Pengunjung melihat daftar organisasi siswa (nama, logo/gambar, deskripsi, kegiatan utama) dari data statis. Organisasi siswa tidak memiliki CRUD maupun tabel database. | **Wajib** |
| **KSW-2** | Pengunjung melihat daftar ekstrakurikuler (nama, gambar, deskripsi). Sampai KSW-6 diterapkan, data berupa data dummy. | **Wajib** |
| **KSW-3** | Pengunjung melihat prestasi siswa (nama prestasi, jenis, tingkat, tahun, deskripsi). Sampai KSW-6 diterapkan, data berupa data dummy yang jelas ditandai sebagai contoh, bukan pencapaian resmi. | **Wajib** |
| **KSW-4** | Ekstrakurikuler menampilkan jadwal hanya jika sudah dikonfirmasi sekolah. | **Penting** |
| **KSW-5** | Prestasi menampilkan nama siswa atau tim dan foto dokumentasi hanya jika diizinkan untuk dipublikasikan. | **Penting** |
| **KSW-6** | Setelah integrasi backend, ekstrakurikuler dan prestasi diambil dari database dan hanya konten berstatus Published yang tampil. | **Penting** |

## 6.6 Pengunjung — Galeri

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **GAL-1** | Pengunjung melihat galeri berisi judul, foto, dan keterangan (gambar dummy atau dokumentasi yang telah disetujui). | **Wajib** |
| **GAL-2** | Sistem memakai thumbnail dan lazy loading serta tidak memuat seluruh gambar beresolusi tinggi sekaligus. | **Wajib** |
| **GAL-3** | Galeri dapat menampilkan tanggal kegiatan dan kategori jika tersedia. | **Penting** |
| **GAL-4** | Setelah integrasi backend, galeri diambil dari database dan hanya foto berstatus Published yang tampil. | **Penting** |

## 6.7 Pengunjung — Berita

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **BRT-1** | Pengunjung melihat halaman daftar berita (judul, gambar utama, ringkasan, tanggal, tautan detail). | **Wajib** |
| **BRT-2** | Pengunjung melihat halaman detail berita (judul, gambar utama, tanggal, isi) dengan navigasi kembali ke daftar. | **Wajib** |
| **BRT-3** | Data berita dummy memakai struktur konsisten (judul, slug, ringkasan, isi, gambar, tanggal) agar mudah dialihkan ke database. | **Wajib** |
| **BRT-4** | Setelah integrasi backend, halaman publik hanya menampilkan berita berstatus Published dari database. Berita Draft hanya terlihat pada halaman pengelolaan admin dan berita Archived tidak ditampilkan sebagai konten aktif. | **Penting** |

## 6.8 Pengunjung — Pengumuman

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PGM-1** | Pengunjung melihat halaman daftar pengumuman (judul, ringkasan, tanggal, tautan detail). | **Wajib** |
| **PGM-2** | Pengunjung melihat halaman detail pengumuman (judul, tanggal, isi) dengan navigasi kembali ke daftar. | **Wajib** |
| **PGM-3** | Halaman pengumuman terpisah dari berita dan tidak menerapkan tanggal mulai atau tanggal berakhir pada tahap ini. | **Wajib** |
| **PGM-4** | Setelah integrasi backend, halaman publik hanya menampilkan pengumuman berstatus Published dari database. | **Penting** |

## 6.9 Semua Pengguna — Autentikasi dan Profil

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **AUT-1** | Pengguna (Admin, Guru, Wali Kelas, Siswa) login ke SIAKAD dengan memasukkan username dan password pada halaman login. | **Wajib** |
| **AUT-2** | Sistem mencari akun berdasarkan username dan memeriksa status akun; akun nonaktif tidak dapat login. | **Wajib** |
| **AUT-3** | Sistem memverifikasi password terhadap hash yang tersimpan, membuat sesi login, lalu membaca role dan identitas master yang terhubung (ID guru atau ID siswa). | **Wajib** |
| **AUT-4** | Sistem mengarahkan pengguna ke dashboard sesuai role setelah login berhasil. | **Wajib** |
| **AUT-5** | Jika username atau password tidak valid, sistem menolak login dan menampilkan pesan kesalahan yang tidak membocorkan informasi sensitif. | **Wajib** |
| **AUT-6** | Pengguna mengganti password melalui menu profil atau pengaturan akun dengan memasukkan password lama dan password baru; sistem memverifikasi password lama, memvalidasi password baru, menyimpan hash terbaru, dan menampilkan notifikasi keberhasilan. Password baru minimal 8 karakter dan tidak boleh sama dengan username atau NIS; setelah berhasil, sesi di perangkat lain dikeluarkan. Verifikasi email tidak diperlukan karena data guru dan siswa tidak memuat email. | **Wajib** |
| **AUT-7** | Guru, wali kelas, dan siswa melihat profil berisi informasi yang diizinkan, diambil dari data master berdasarkan ID yang terhubung dengan akun. | **Wajib** |
| **AUT-8** | Sistem memeriksa hak akses pada backend untuk setiap permintaan, bukan hanya dengan menyembunyikan menu atau tombol pada tampilan. | **Wajib** |
| **AUT-9** | Pengguna yang akunnya bertanda wajib ganti password diarahkan ke halaman ubah password setelah login dan tidak dapat mengakses fitur lain sebelum menggantinya. | **Wajib** |

## 6.10 Admin — Manajemen Akun

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **AKN-1** | Admin membuat akun guru: sistem menampilkan guru yang belum memiliki akun, admin memilih guru, menentukan username (username lama di RKM boleh dipakai ulang; password RKM tidak dimigrasikan) dan password awal acak, lalu menyimpan. Role akun adalah Guru; status wali kelas berasal dari penetapan wali kelas (PWK), bukan dari pilihan role. | **Wajib** |
| **AKN-2** | Admin membuat akun siswa: sistem menampilkan siswa yang belum memiliki akun, admin memilih siswa, sistem memakai NIS sebagai username dan membuat password awal acak, lalu menyimpan dengan role Siswa. | **Wajib** |
| **AKN-3** | Admin yang berwenang membuat akun admin dengan mengisi identitas akun; sistem memvalidasi username dan kelengkapan data lalu menyimpan akun dengan role Admin. | **Wajib** |
| **AKN-4** | Admin mengaktifkan atau menonaktifkan akun dari daftar akun setelah konfirmasi; akun nonaktif tidak dapat login. | **Wajib** |
| **AKN-5** | Username bersifat unik, dan setiap akun terhubung dengan ID master guru atau siswa sebagai penghubung identitas. | **Wajib** |
| **AKN-6** | Password disimpan dalam bentuk hash dengan mekanisme hashing yang kuat, tidak pernah dalam teks biasa, dan admin tidak dapat melihat password yang sudah tersimpan. Tabel autentikasi SIAKAD terpisah dari tabel akun RKM dan password SHA-1 RKM tidak dipakai. | **Wajib** |
| **AKN-7** | Pengelolaan akun admin lain (menambah, mengubah informasi, mengaktifkan, dan menonaktifkan) dibatasi untuk admin utama dan tidak diberikan tanpa batas kepada seluruh admin. Pada tahap awal hanya satu akun admin (admin utama) dibuat, dengan hak penuh pada tabel master dan tabel SIAKAD tetapi tanpa hak tulis ke tabel nilai RKM. | **Wajib** |
| **AKN-8** | Admin melihat daftar akun dan mencari akun yang ingin dikelola. | **Wajib** |
| **AKN-9** | Admin membuat akun siswa secara massal: sistem hanya memproses siswa yang memiliki penempatan kelas pada periode aktif dan belum memiliki akun, memakai NIS sebagai username, membuat password awal acak untuk setiap siswa, lalu menampilkan ringkasan hasil (berhasil dan dilewati beserta alasan, mis. NIS kosong atau ganda). | **Penting** |
| **AKN-10** | Password awal acak ditampilkan atau diunduh satu kali dalam daftar akun awal (Excel) untuk dibagikan melalui wali kelas. Sistem hanya menyimpan hash, dan setiap akun baru wajib mengganti password saat login pertama (AUT-9). | **Penting** |
| **AKN-11** | Admin mereset paksa password guru atau siswa (mis. saat lupa password): sistem membuat password sementara acak yang tampil satu kali, menyimpan hash-nya, dan mewajibkan pengguna menggantinya saat login berikutnya. Admin tidak dapat melihat password yang tersimpan. | **Wajib** |

## 6.11 Semua Role — Dashboard

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **DSH-1** | Dashboard admin menampilkan ringkasan jumlah guru, siswa, kelas, mata pelajaran, dan tahun ajaran aktif yang diambil dari tabel master. | **Wajib** |
| **DSH-2** | Kartu ringkasan dashboard admin dapat dipilih untuk membuka halaman detail terkait (mis. kartu jumlah siswa menuju Data Siswa). | **Wajib** |
| **DSH-3** | Dashboard admin menampilkan ringkasan absensi siswa jika data absensi SIAKAD sudah tersedia; kartunya menuju halaman Pemantauan Absensi. | **Penting** |
| **DSH-4** | Dashboard admin menampilkan aktivitas pengelolaan konten dari catatan aktivitas konten (ADM-11). | **Penting** |
| **DSH-5** | Dashboard guru menampilkan daftar kelas dan mata pelajaran yang diampu berdasarkan penugasan guru. | **Wajib** |
| **DSH-6** | Dashboard guru menampilkan jadwal mengajar, informasi absensi siswa yang perlu dilengkapi, dan materi pembelajaran yang dikelola. Informasi absensi pribadi (fingerprint) menjadi bagian Fase 2 (FL-18). | **Penting** |
| **DSH-7** | Dashboard siswa menampilkan informasi kelas dan tahun ajaran aktif siswa. | **Wajib** |
| **DSH-8** | Dashboard siswa menampilkan jadwal pelajaran, materi terbaru, dan ringkasan absensi pribadi. | **Penting** |

## 6.12 Admin — Data Guru

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **GRU-1** | Admin melihat daftar guru dari database dan mencari guru yang ingin dikelola. | **Wajib** |
| **GRU-2** | Admin menambah guru melalui formulir berisi nama guru, NIP (jika ada), jenis kelamin, dan penanda guru BK. | **Wajib** |
| **GRU-3** | Sistem memvalidasi kelengkapan dan format data, memeriksa apakah identitas guru sudah terdaftar, lalu menyimpan ke tabel master guru yang digunakan bersama RKM dan menampilkan notifikasi keberhasilan. | **Wajib** |
| **GRU-4** | Admin mengubah data guru melalui formulir berisi data saat ini; sistem memvalidasi perubahan dan memperbarui tabel master sumber data bersama. | **Wajib** |
| **GRU-5** | Admin menghapus guru dengan konfirmasi; sistem memeriksa relasi guru dengan penugasan mengajar, wali kelas, absensi, atau data lain. Jika masih ada relasi penting, penghapusan ditolak atau admin diminta menonaktifkan data; jika aman, sistem hanya mengubah status data menjadi nonaktif melalui kolom `stat_data` yang sudah ada (tanpa penghapusan fisik dan tanpa kolom baru); nilai status yang dipakai mengikuti TBD-38. | **Wajib** |

## 6.13 Admin — Data Siswa

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **SIS-1** | Admin melihat daftar siswa dan mencari siswa berdasarkan nama, NIS, atau NISN. | **Wajib** |
| **SIS-2** | Admin menambah satu siswa melalui formulir yang mencakup seluruh data siswa yang dipakai RKM: identitas (NIS, NISN, nama, jenis kelamin, tempat dan tanggal lahir, agama, status dalam keluarga, anak ke, alamat, nomor telepon), data sekolah asal dan penerimaan (sekolah asal dan alamatnya, kelas, tanggal, dan semester diterima, nomor dan tahun ijazah serta SKHUN), serta data orang tua dan wali. Hanya NIS dan nama yang wajib diisi (TBD-45); kolom lain boleh kosong dan disimpan mengikuti konvensi RKM. Foto tidak dikelola dari SIAKAD. | **Wajib** |
| **SIS-3** | Sistem memvalidasi data, memeriksa kemungkinan duplikasi berdasarkan identitas siswa, lalu menyimpan ke tabel master siswa yang digunakan bersama RKM (kolom penanda penginput `inputID` diisi 0 seperti RKM) dan menampilkan notifikasi keberhasilan. | **Wajib** |
| **SIS-4** | Admin mengimpor banyak siswa melalui Excel: sistem menyediakan file template (memuat seluruh kolom data siswa pada formulir) yang wajib digunakan; admin mengunduh, mengisi sesuai kolom dan format, lalu mengunggahnya; sistem memeriksa format file dan nama kolom. | **Wajib** |
| **SIS-5** | Sistem membaca setiap baris file impor dan memvalidasi data wajib, format tanggal, identitas, serta kemungkinan duplikat berdasarkan NIS atau NISN, baik terhadap data yang sudah tersimpan maupun antarbaris dalam file. | **Wajib** |
| **SIS-6** | Sebelum menyimpan, sistem menampilkan hasil pemeriksaan: jumlah data valid beserta nomor barisnya, jumlah data yang memiliki kesalahan beserta keterangan per baris, dan data yang terindikasi duplikat beserta alasannya. | **Wajib** |
| **SIS-7** | Impor hanya dilanjutkan setelah admin mengonfirmasi hasil validasi. Admin memperbaiki file atau memproses hanya baris yang lolos validasi sesuai kebijakan impor yang ditetapkan (TBD-25). | **Wajib** |
| **SIS-8** | Data duplikat tidak boleh menimpa data siswa yang sudah tersimpan secara otomatis atau diam-diam. | **Wajib** |
| **SIS-9** | Penyimpanan hasil impor memakai transaksi database; jika terjadi kegagalan saat penyimpanan, seluruh proses dibatalkan agar tidak ada data tersimpan setengah-setengah. | **Penting** |
| **SIS-10** | Setelah impor selesai, sistem menampilkan ringkasan hasil: jumlah data yang berhasil disimpan dan data yang tidak diproses. | **Wajib** |
| **SIS-11** | Admin mengubah data siswa melalui formulir berisi data saat ini; sistem memvalidasi perubahan, menyimpan ke tabel master, dan menampilkan data terbaru pada daftar. | **Wajib** |
| **SIS-12** | Admin menonaktifkan atau mengubah status siswa sesuai ketentuan sekolah. Sistem memeriksa relasi siswa dengan penempatan kelas, absensi, dan riwayat rapor; siswa yang memiliki riwayat akademik tidak dihapus permanen. Status diubah melalui kolom `stat_data` yang sudah ada (nilai K untuk siswa keluar). Siswa lulus tidak diubah statusnya. | **Wajib** |

## 6.14 Admin — Data Kelas (Manajemen Kelas)

> Manajemen Kelas adalah menu utama yang menaungi tiga subfitur: Data Kelas (6.14), Penempatan Siswa (6.15), dan Penetapan Wali Kelas (6.16).

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **KLS-1** | Admin membuka menu Manajemen Kelas, memilih subfitur Data Kelas, dan melihat daftar kelas yang tersedia. | **Wajib** |
| **KLS-2** | Admin menambah kelas dengan mengisi tingkat kelas (7, 8, atau 9) dan nama kelas (pola pada data: VII-1 (Fullday), VII-4 (Reguler)); sistem memvalidasi kelengkapan dan kemungkinan duplikasi, lalu menyimpan ke tabel kelas yang digunakan bersama RKM. | **Wajib** |
| **KLS-3** | Admin mengubah tingkat atau nama kelas; sistem memvalidasi dan menyimpan perubahan. | **Wajib** |
| **KLS-4** | Admin menghapus kelas; sistem memeriksa apakah kelas masih memiliki siswa, penugasan guru, wali kelas, atau riwayat akademik. Jika masih digunakan, penghapusan ditolak dan admin menyelesaikan atau mengubah relasi terkait terlebih dahulu. | **Wajib** |
| **KLS-5** | Data kelas dapat digunakan untuk penempatan siswa, jadwal, penugasan guru, dan penetapan wali kelas. | **Wajib** |

## 6.15 Admin — Penempatan Siswa ke Kelas

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PNS-1** | Admin memilih tahun ajaran dan kelas tujuan; sistem menampilkan daftar siswa yang dapat ditempatkan. | **Wajib** |
| **PNS-2** | Admin memilih satu atau beberapa siswa lalu mengonfirmasi penempatan. | **Wajib** |
| **PNS-3** | Sistem memeriksa apakah siswa sudah memiliki penempatan aktif pada tahun ajaran tersebut; jika valid, sistem menyimpan hubungan siswa, kelas, dan tahun ajaran, lalu menampilkan daftar anggota kelas terbaru. | **Wajib** |
| **PNS-4** | Admin memindahkan siswa dari daftar anggota kelas dengan memilih siswa dan kelas tujuan; sistem memeriksa penempatan pada tahun ajaran tersebut, meminta konfirmasi, memperbarui penempatan, dan menampilkan kelas terbaru siswa. | **Wajib** |
| **PNS-5** | Sistem mencegah penempatan ganda baru untuk satu siswa pada tahun yang sama melalui validasi dalam transaksi (tabel RKM tidak memiliki unique key, dan sebagian data historis sudah ganda; data historis tidak diubah, TBD-40). | **Wajib** |
| **PNS-6** | Riwayat penempatan tahun ajaran sebelumnya dipertahankan dan tidak ditimpa. Siswa baru dan siswa pindahan ditempatkan secara manual oleh admin. Perubahan penempatan memperhatikan cara RKM membaca data kelas siswa. Penempatan disimpan per tahun (empat digit pertama kode periode) sehingga berlaku untuk kedua semester pada tahun ajaran yang sama. | **Wajib** |

## 6.16 Admin — Penetapan Wali Kelas

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PWK-1** | Admin memilih periode (semester), kelas, lalu guru yang akan menjadi wali kelas dari daftar guru. | **Wajib** |
| **PWK-2** | Sistem memeriksa bahwa kelas belum memiliki wali kelas pada periode tersebut (satu kelas satu wali kelas). | **Wajib** |
| **PWK-3** | Sistem memastikan guru yang dipilih belum menjadi wali kelas di kelas lain pada periode yang sama (satu guru satu kelas). | **Wajib** |
| **PWK-4** | Setelah admin mengonfirmasi, sistem menyimpan penetapan dan memberikan akses tambahan wali kelas pada akun guru tersebut. | **Wajib** |
| **PWK-5** | Wali kelas ditetapkan per periode (semester). Pada periode baru, penetapan dimulai dalam keadaan kosong dan diisi admin, dan riwayat periode sebelumnya tetap disimpan. | **Wajib** |
| **PWK-6** | Admin dapat menyalin penetapan wali kelas dari periode sebelumnya sebagai dasar lalu menyesuaikannya; salinan tidak mengubah data periode sebelumnya. | **Penting** |

## 6.17 Admin — Mata Pelajaran

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **MPL-1** | Admin melihat daftar mata pelajaran. | **Wajib** |
| **MPL-2** | Admin menambah mata pelajaran dengan mengisi nama, kelompok (A, B, atau C), jenis tambahan (NO, PAI, atau MULOK), kode singkat (maksimal 5 karakter), dan penanda mata pelajaran sikap; sistem memvalidasi lalu menyimpan ke tabel mata pelajaran yang digunakan RKM sehingga tersedia untuk penugasan guru dan pembuatan jadwal. | **Wajib** |
| **MPL-3** | Admin mengubah informasi mata pelajaran; sistem memvalidasi dan menyimpan perubahan. | **Wajib** |
| **MPL-4** | Admin menghapus mata pelajaran; sistem memeriksa relasi dengan penugasan guru, jadwal, materi, dan riwayat nilai. Jika masih digunakan, penghapusan langsung dibatasi. | **Wajib** |

## 6.18 Admin — Penugasan Guru ke Mata Pelajaran dan Kelas

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PGS-1** | Admin memilih periode (semester), guru, mata pelajaran, dan kelas yang diajar. | **Wajib** |
| **PGS-2** | Sistem menampilkan ringkasan penugasan dan meminta konfirmasi admin sebelum menyimpan. | **Wajib** |
| **PGS-3** | Sistem memeriksa apakah penugasan yang sama sudah terdaftar; jika valid, sistem menyimpan hubungan guru, mata pelajaran, kelas, dan periode. | **Wajib** |
| **PGS-4** | Penugasan yang tersimpan muncul pada daftar mengajar guru terkait. | **Wajib** |
| **PGS-5** | Akses guru terhadap absensi dan materi dibatasi pada penugasan yang dimilikinya; guru tidak dapat mengakses kelas atau mata pelajaran yang tidak ditugaskan kepadanya. | **Wajib** |
| **PGS-6** | Karena penugasan dibuat per semester dan jumlahnya ratusan baris per periode, admin dapat menyalin seluruh penugasan dari periode sebelumnya lalu menyesuaikannya; salinan tidak mengubah penugasan periode sebelumnya. | **Penting** |
| **PGS-7** | Penugasan yang sudah dipakai jadwal, absensi, materi, atau nilai di RKM tidak dapat dihapus langsung (tabel nilai RKM mengacu ke penugasan tanpa foreign key); sistem menolak dan menjelaskan relasi yang menghalangi. | **Wajib** |

## 6.19 Admin — Tahun Ajaran

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **TAJ-1** | Admin melihat daftar tahun ajaran. | **Wajib** |
| **TAJ-2** | Admin menambah tahun ajaran dengan mengisi kode periode (tahun ajaran dan semester yang disatukan, mis. 20242 berarti tahun ajaran 2024/2025 semester 2), nama kepala sekolah, NIP kepala sekolah, tanggal tanda tangan rapor untuk kelas 7 dan 8, serta tanggal tanda tangan rapor khusus kelas 9. | **Wajib** |
| **TAJ-3** | Sistem memvalidasi format dan kemungkinan duplikasi sebelum menyimpan tahun ajaran baru ke daftar. | **Wajib** |
| **TAJ-4** | Admin mengaktifkan tahun ajaran: sistem menampilkan konfirmasi pergantian, admin memeriksa data tahun ajaran tujuan dan mengonfirmasi, lalu sistem mengubah status tahun ajaran aktif. | **Wajib** |
| **TAJ-5** | Tahun ajaran aktif menjadi konteks bagi penempatan siswa, penugasan guru, wali kelas, jadwal, dan aktivitas akademik berikutnya. | **Wajib** |

## 6.20 Admin — Kenaikan Kelas dan Penempatan Tahun Ajaran Baru

> Keputusan naik atau tidak naik kelas tetap ditetapkan melalui RKM. SIAKAD hanya membacanya sebagai dasar penempatan.

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **KNK-1** | Keputusan kenaikan diisi oleh wali kelas masing-masing di RKM. Sistem membaca status kenaikan setiap siswa dari tabel `t_naikkelas` (hanya status; kolom catatan wali kelas tidak dibaca) setelah struktur dan relasinya diverifikasi (TBD-23). Sistem tidak menaikkan semua siswa otomatis hanya karena pergantian tahun ajaran. | **Penting** |
| **KNK-2** | Sistem mengidentifikasi siswa berdasarkan kelas asal dan status kenaikannya, lalu menyiapkan pratinjau penempatan. | **Penting** |
| **KNK-3** | Untuk siswa yang naik kelas, sistem memetakan ke tingkat berikutnya dengan mempertahankan kelompok rombel asal (pola nama kelas pada data: VII-1 (Fullday) menjadi VIII-1 (Fullday), VII-4 (Reguler) menjadi VIII-4 (Reguler); daftar lengkap kelas pada TBD-42). | **Penting** |
| **KNK-4** | Jumlah rombel pada tingkat tujuan mengikuti jumlah rombel angkatan yang naik; jika rombel tujuan belum tersedia, sistem menyiapkan rombel tambahan sebelum penempatan disimpan dengan tetap menjaga identitas dan riwayat kelas tahun sebelumnya. | **Penting** |
| ~~**KNK-5**~~ | ~~Siswa yang tidak naik kelas ditempatkan tetap pada tingkat dan rombel asalnya untuk tahun ajaran baru.~~ *Dipindahkan ke Bab 11 (FL-23) pada v0.4: penanganan siswa tidak naik kelas ditunda.* | ~~Penting~~ |
| **KNK-6** | Admin memeriksa daftar siswa, kelas asal, kelas tujuan, jumlah rombel, dan status kenaikan, serta dapat memperbaiki pemetaan sebelum konfirmasi. | **Penting** |
| **KNK-7** | Setelah admin mengonfirmasi, sistem menyimpan penempatan siswa untuk tahun ajaran baru tanpa menghapus atau menimpa riwayat tahun sebelumnya. | **Penting** |
| **KNK-8** | Setelah proses kenaikan kelas selesai, penetapan wali kelas tahun ajaran baru dikosongkan untuk diisi admin (PWK-5), dan siswa baru atau siswa pindahan ditempatkan manual oleh admin (PNS-6). | **Penting** |
| **KNK-9** | Siswa kelas 9 yang lulus tidak diubah statusnya dan hanya tidak ditempatkan pada periode berikutnya. | **Penting** |

## 6.21 Admin — Ekstrakurikuler

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **EKS-1** | Admin melihat daftar ekstrakurikuler yang tersimpan pada tabel yang digunakan bersama RKM. | **Penting** |
| **EKS-2** | Admin menambah ekstrakurikuler dengan mengisi nama ekstrakurikuler (tabel hanya menyimpan nama; deskripsi dan gambar dikelola pada konten publik, ADM-5); sistem memvalidasi dan menyimpan ke tabel ekstrakurikuler bersama RKM. | **Penting** |
| **EKS-3** | Admin mengubah nama ekstrakurikuler; sistem memvalidasi dan menyimpan perubahan. | **Penting** |
| **EKS-4** | Admin menghapus ekstrakurikuler; sistem memeriksa apakah data sudah digunakan pada catatan atau penilaian ekstrakurikuler di RKM. Jika masih digunakan, penghapusan fisik dibatasi. | **Penting** |

## 6.22 Admin — Jadwal Pelajaran

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **JDW-1** | Admin memilih periode (semester) aktif dan melihat daftar jadwal pelajaran. | **Penting** |
| **JDW-2** | Admin menambah jadwal dengan memilih kelas, hari, jam mulai, jam selesai, dan mata pelajaran; sistem menampilkan hanya guru yang memiliki penugasan mata pelajaran tersebut pada kelas terkait, lalu admin memilih guru pengampu dan menyimpan. | **Penting** |
| **JDW-3** | Sistem memvalidasi jadwal: jam mulai lebih awal daripada jam selesai; guru ditugaskan mengajar mata pelajaran dan kelas tersebut; guru tidak memiliki jadwal lain pada waktu yang sama; kelas tidak memiliki jadwal lain pada waktu yang sama. Jadwal hanya disimpan jika tidak ada bentrok. | **Penting** |
| **JDW-4** | Admin mengubah hari, jam, kelas, mata pelajaran, atau guru pada jadwal; sistem memeriksa ulang validitas dan bentrok, lalu perubahan terlihat pada halaman pengguna terkait. | **Penting** |
| **JDW-5** | Admin menghapus jadwal dengan konfirmasi; jika jadwal sudah memiliki catatan pertemuan atau absensi, sistem mempertahankan riwayat dan hanya menonaktifkan jadwal untuk periode berikutnya. | **Penting** |
| **JDW-6** | Jadwal yang dinonaktifkan atau dihapus tidak lagi muncul sebagai jadwal aktif. | **Penting** |
| **JDW-7** | Jika jadwal periode sebelumnya tersedia, admin dapat menyalinnya sebagai dasar jadwal baru; salinan tidak mengubah jadwal periode sebelumnya, dan admin menyesuaikan kelas, hari, jam, mata pelajaran, serta guru pada salinan. | **Penting** |
| **JDW-8** | Jadwal setiap periode tersimpan sebagai riwayat dan tidak ikut berubah ketika jadwal periode baru diedit. | **Penting** |
| **JDW-9** | Jadwal yang tersimpan tersedia pada halaman jadwal mengajar guru dan halaman jadwal siswa. | **Penting** |

## 6.23 Admin — Pengelolaan Konten Publik

> Modul ini menggantikan FL-1 sampai FL-3, FL-5 sampai FL-9 pada v0.2. Prioritas Penting, tetapi dikerjakan pada Urutan 2 (Bab 4.3).

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **ADM-1** | Admin mengelola konten publik melalui menu Berita, Pengumuman, Fasilitas, Ekstrakurikuler, Prestasi, dan Galeri pada dashboard; fitur ini hanya dapat diakses role Admin. | **Penting** |
| **ADM-2** | Admin menambah berita dengan mengisi judul, isi (teks biasa dengan baris baru; kode HTML tidak diizinkan), gambar utama, dan informasi pendukung; sistem memvalidasi isi dan file gambar sebelum menyimpan. | **Penting** |
| **ADM-3** | Admin menambah pengumuman dengan mengisi judul, isi (teks biasa dengan baris baru), tanggal, dan informasi pendukung, serta menentukan status publikasi. | **Penting** |
| **ADM-4** | Admin menambah fasilitas sekolah dengan mengisi nama, deskripsi, dan gambar (jika tersedia), serta menentukan status publikasi. | **Penting** |
| **ADM-5** | Pada menu Ekstrakurikuler publik, sistem mengambil data ekstrakurikuler yang sudah ada pada tabel melalui id; admin mengisi deskripsi, gambar, dan informasi pendukung, lalu menentukan status publikasi. Konten publik dipisahkan dari data akademik ekstrakurikuler. | **Penting** |
| **ADM-6** | Admin menambah prestasi siswa dengan mengisi nama atau deskripsi prestasi, tingkat kegiatan, tanggal, informasi pendukung, dan dokumentasi (jika tersedia), serta menentukan status publikasi. Publikasi nama atau foto siswa mengikuti kebijakan sekolah (TBD-9). Prestasi publik disimpan terpisah dari tabel prestasi untuk rapor di RKM. | **Penting** |
| **ADM-7** | Admin menambah foto atau kelompok galeri dengan mengunggah gambar, mengisi judul atau keterangan, dan menentukan status publikasi; sistem memeriksa format dan ukuran file serta menyimpan metadata dan lokasi file. | **Penting** |
| **ADM-8** | Setiap konten memiliki status Draft, Published, atau Archived: Draft hanya terlihat pada halaman pengelolaan admin, Published tampil pada website publik, dan Archived tidak ditampilkan sebagai konten aktif. | **Penting** |
| **ADM-9** | Admin membuka daftar konten dan mengubah informasi konten; sistem memvalidasi perubahan lalu memperbarui konten. | **Penting** |
| **ADM-10** | Penghapusan konten meminta konfirmasi dan mempertimbangkan penggunaan status Archived agar riwayat konten tetap dapat dipertahankan. | **Penting** |
| **ADM-11** | Sistem mencatat aktivitas pengelolaan konten (siapa, jenis konten, aksi, dan waktu) sebagai sumber informasi dashboard admin (DSH-4). | **Penting** |

## 6.24 Admin — Pemantauan Absensi Siswa

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PAS-1** | Admin memilih filter berupa kelas, guru, mata pelajaran, atau tanggal pada menu Pemantauan Absensi Siswa. | **Penting** |
| **PAS-2** | Sistem mengambil data absensi per pertemuan dan menampilkan daftar catatan absensi sesuai filter. | **Penting** |
| **PAS-3** | Sistem menunjukkan pertemuan yang sudah dan belum memiliki catatan absensi. | **Penting** |
| **PAS-4** | Sistem menampilkan ringkasan absensi sesuai filter yang dipilih. | **Penting** |
| **PAS-5** | Admin hanya melihat dan memantau; admin tidak mengubah catatan absensi guru pengampu. Kekurangan pengisian ditindaklanjuti dengan menghubungi guru terkait, dan koreksi tetap menjadi tanggung jawab guru yang mencatat. | **Penting** |

## 6.25 Guru — Data Siswa

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **GSW-1** | Guru membuka menu Data Siswa atau memilih kelas dari dashboard; sistem menampilkan daftar kelas yang ditugaskan kepada guru. | **Penting** |
| **GSW-2** | Setelah guru memilih kelas, sistem menampilkan anggota kelas pada tahun ajaran aktif dan guru dapat mencari siswa berdasarkan nama atau identitas yang tersedia. | **Penting** |
| **GSW-3** | Guru memilih siswa untuk melihat informasi dasar yang diperbolehkan (TBD-34). | **Penting** |
| **GSW-4** | Guru hanya melihat siswa pada kelas yang diajarnya dan tidak dapat menambah, mengubah, atau menghapus data master siswa. Pengelolaan nilai tetap melalui RKM. | **Penting** |

## 6.26 Guru — Absensi Siswa per Pertemuan

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **ABS-1** | Guru membuka menu Absensi Siswa; sistem menampilkan jadwal mengajar guru dan guru memilih jadwal atau kelas yang akan diabsen. | **Penting** |
| **ABS-2** | Sistem menampilkan informasi kelas, mata pelajaran, dan waktu pertemuan, lalu guru memilih tanggal atau pertemuan yang sesuai (aturan tanggal pada TBD-30). | **Penting** |
| **ABS-3** | Sistem memeriksa apakah absensi untuk jadwal dan tanggal tersebut sudah pernah dibuat; jika belum, guru memulai pembuatan absensi dan sistem membuat catatan sesi pertemuan. | **Penting** |
| **ABS-4** | Sistem menampilkan seluruh siswa yang terdaftar pada kelas tersebut, dan guru menentukan status kehadiran setiap siswa: Hadir, Sakit, Izin, atau Tanpa Keterangan. | **Penting** |
| **ABS-5** | Saat guru menekan simpan, sistem memvalidasi agar setiap siswa memiliki status yang sesuai, menyimpan catatan absensi setiap siswa pada sesi pertemuan tersebut, dan menampilkan notifikasi keberhasilan. | **Penting** |
| **ABS-6** | Guru mengubah absensi melalui riwayat: memilih sesi pertemuan miliknya, mengubah status siswa yang perlu diperbaiki, dan menyimpan; sistem memvalidasi hak akses dan data sebelum memperbarui catatan. | **Penting** |
| **ABS-7** | Guru melihat riwayat absensi dengan memilih kelas, mata pelajaran, atau periode; sistem menampilkan daftar pertemuan dan detail kehadiran siswa pada pertemuan yang dipilih. | **Penting** |
| **ABS-8** | Absensi dicatat per pertemuan (bukan satu kali untuk seluruh hari), dan guru hanya mengisi absensi untuk kelas dan mata pelajaran yang menjadi tanggung jawabnya. | **Penting** |
| **ABS-9** | Data absensi SIAKAD tidak otomatis mengubah rekap absensi rapor RKM; guru tetap memvalidasi dan mengisi jumlah sakit, izin, dan tanpa keterangan secara manual di RKM. | **Penting** |

## 6.27 Guru — Materi Pembelajaran

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **MTR-1** | Guru melihat daftar materi yang dikelolanya (judul, kelas, mata pelajaran, tanggal dibuat, jenis materi) dan dapat mencari atau memfilter materi. | **Penting** |
| **MTR-2** | Guru menambah materi dengan mengisi judul, deskripsi atau keterangan, satu atau beberapa kelas tujuan, mata pelajaran, dan jenis materi (mengunggah file atau memasukkan tautan eksternal). | **Penting** |
| **MTR-3** | Sistem hanya menampilkan kelas yang memang diajar guru untuk mata pelajaran terkait dan memvalidasi kesesuaian kelas dan mata pelajaran dengan penugasan guru. | **Penting** |
| **MTR-4** | Sistem memvalidasi jenis file, ukuran file, dan tautan; jika valid, metadata materi disimpan dan file disimpan pada penyimpanan aplikasi. | **Penting** |
| **MTR-5** | Rekomendasi awal format file: PDF, DOC/DOCX, PPT/PPTX, XLS/XLSX, JPG/JPEG, dan PNG dengan batas maksimal 15 MB per file. Video dibagikan melalui tautan eksternal. Ketentuan ini dapat disesuaikan dengan kapasitas server dan kebijakan sekolah (TBD-24). | **Penting** |
| **MTR-6** | Guru mengubah judul, deskripsi, kelas, mata pelajaran, file, atau tautan pada materi miliknya; sistem memvalidasi dan memperbarui materi. | **Penting** |
| **MTR-7** | Guru menghapus materi miliknya setelah konfirmasi; sistem memeriksa kepemilikan lalu menghapus atau menonaktifkan materi sehingga tidak lagi ditampilkan kepada siswa. | **Penting** |
| **MTR-8** | Guru hanya dapat mengelola materi miliknya sendiri, dan siswa hanya dapat melihat materi yang ditujukan kepada kelasnya. | **Penting** |

## 6.28 Guru dan Wali Kelas — Akses RKM

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **RKM-1** | Sidebar guru dan wali kelas menyediakan menu atau tautan RKM yang mengarahkan pengguna ke alamat aplikasi RKM (TBD-7). | **Penting** |
| **RKM-2** | Login ke RKM mengikuti mekanisme autentikasi RKM; akun SIAKAD tidak otomatis dapat digunakan untuk login RKM. | **Penting** |

## 6.29 Wali Kelas — Pemantauan Kelas

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **WKL-1** | Saat guru login, sistem memeriksa penetapan wali kelas guru tersebut pada tahun ajaran aktif; jika ada, sistem memberikan akses tambahan wali kelas dan menampilkan menu tambahan pada sidebar, sementara fitur guru mata pelajaran tetap tersedia dalam satu akun dan satu dashboard. | **Penting** |
| **WKL-2** | Jika guru tidak lagi menjadi wali kelas pada tahun ajaran aktif, menu tambahan wali kelas tidak ditampilkan sebagai fitur aktif. | **Penting** |
| **WKL-3** | Pada menu Data Siswa Kelas, wali kelas melihat daftar siswa kelas perwaliannya untuk tahun ajaran aktif, dapat mencari siswa, dan melihat informasi dasar yang diperbolehkan. Wali kelas tidak mengubah data master siswa. | **Penting** |
| **WKL-4** | Pada menu Absensi Kelas, wali kelas memilih periode; sistem mengambil catatan absensi seluruh mata pelajaran pada kelas tersebut, menggabungkannya berdasarkan siswa dan pertemuan, lalu menampilkan riwayat absensi. | **Penting** |
| **WKL-5** | Wali kelas memilih rentang tanggal; sistem menghitung jumlah status hadir, sakit, izin, dan tanpa keterangan, mengelompokkannya per siswa, dan menampilkan rekap ketidakhadiran. | **Penting** |
| **WKL-6** | Wali kelas memilih siswa atau pertemuan untuk melihat detail: tanggal, mata pelajaran, guru pengampu, dan status kehadiran. | **Penting** |
| **WKL-7** | Wali kelas hanya melihat dan memantau; wali kelas tidak melakukan absensi harian di kelas perwaliannya dan tidak mengubah catatan guru lain. Kesalahan dikoreksi oleh guru yang mencatat. | **Penting** |
| **WKL-8** | Validasi rekap absensi rapor oleh wali kelas tetap dilakukan melalui RKM. | **Penting** |

## 6.30 Siswa — Jadwal, Materi, dan Absensi Pribadi

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **SSW-1** | Siswa membuka menu Jadwal Pelajaran; sistem mengidentifikasi kelas siswa pada tahun ajaran aktif, mengambil jadwal kelas, dan mengelompokkannya berdasarkan hari. | **Penting** |
| **SSW-2** | Siswa memilih hari dan melihat jam mulai, jam selesai, mata pelajaran, dan guru pengampu, serta dapat berpindah ke hari lain. | **Penting** |
| **SSW-3** | Siswa membuka menu Materi Pembelajaran dan melihat daftar materi yang ditujukan untuk kelasnya, dengan filter mata pelajaran jika disediakan. | **Penting** |
| **SSW-4** | Siswa memilih materi untuk melihat judul dan deskripsi; materi berupa file dapat dibuka atau diunduh, dan materi berupa tautan membuka tautan eksternal. Siswa tidak dapat menambah, mengubah, atau menghapus materi. | **Penting** |
| **SSW-5** | Siswa membuka menu Absensi Saya dan melihat riwayat absensi miliknya berdasarkan tanggal dan mata pelajaran, dengan filter rentang tanggal atau periode jika tersedia. | **Penting** |
| **SSW-6** | Sistem menampilkan status kehadiran setiap pertemuan (Hadir, Sakit, Izin, Tanpa Keterangan) beserta detail pertemuan jika diperlukan. | **Penting** |
| **SSW-7** | Siswa hanya dapat melihat absensinya sendiri dan tidak dapat mengubah status kehadiran; pengisian dan koreksi menjadi tanggung jawab guru pengampu. | **Penting** |

# 7. Alur Pengguna Utama (Key User Flows)

> Alur 7.1 sampai 7.5 adalah alur website publik. Alur 7.6 sampai 7.20 adalah alur SIAKAD berdasarkan rincian alur fitur. Jumlah alur melebihi anjuran umum karena cakupan dokumen mencakup seluruh SIAKAD.

## 7.1 Pengunjung Menjelajahi Beranda dan Menuju Portal Lain

1. Pengunjung membuka website sekolah tanpa login.
2. Sistem menampilkan beranda: hero, tiga ikon portal, sambutan kepala sekolah, berita, pengumuman, dan statistik.
3. Pengunjung mengetuk ikon CBT, Rapor, atau PPDB.
4. Sistem membuka portal tujuan pada tab yang sama.
5. *Pengecualian:* jika URL resmi belum tersedia, ikon menuju tautan placeholder yang jelas, bukan halaman yang tidak berkaitan.

## 7.2 Pengunjung Membaca Berita atau Pengumuman

1. Pengunjung memilih berita atau pengumuman dari beranda atau menu Berita/Pengumuman.
2. Sistem menampilkan halaman daftar (setelah integrasi backend: hanya konten berstatus "Published").
3. Pengunjung memilih satu item.
4. Sistem menampilkan halaman detail.
5. Pengunjung memilih navigasi kembali; sistem menampilkan daftar semula.

## 7.3 Pengunjung Mencari Guru atau Siswa di Direktori

1. Pengunjung membuka menu Direktori dan memilih Guru atau Siswa.
2. Sistem membaca data aktif dari tabel master dan menampilkan daftar terurut berdasarkan nama.
3. Pengunjung mengetik nama dan/atau memilih filter (kelas untuk siswa; mata pelajaran untuk guru bila tersedia).
4. Sistem menampilkan hasil yang cocok.
5. *Pengecualian:* jika tidak ada hasil, sistem menampilkan pesan "data tidak ditemukan". Jika data jabatan atau mata pelajaran tidak tersedia, kolom tersebut tidak ditampilkan.

## 7.4 Pengunjung Menuju Login SIAKAD

1. Pengunjung memilih tombol Login SIAKAD pada navbar.
2. Sistem mengarahkan ke halaman login SIAKAD.
3. Proses autentikasi selanjutnya dijelaskan pada alur 7.6.

## 7.5 Pengembang Mengganti Data Dummy dengan Data Resmi

1. Pengembang menerima konten atau URL resmi dari sekolah.
2. Pengembang mencari komentar penanda pada berkas data dummy yang terkait.
3. Pengembang mengganti isi data tanpa mengubah layout halaman.
4. Pengembang mencatat penggantian pada Riwayat Revisi dan menutup butir terkait di Bab 12.
5. Pada integrasi backend (Urutan 2), sumber data dialihkan dari berkas statis ke database melalui struktur data yang sama.

## 7.6 Pengguna Login dan Masuk ke Dashboard

1. Pengguna membuka halaman login SIAKAD dan memasukkan username dan password.
2. Sistem mencari akun berdasarkan username dan memeriksa status akun.
3. Sistem memverifikasi password terhadap hash yang tersimpan.
4. Jika valid, sistem membuat sesi login dan membaca role serta identitas master yang terhubung (ID guru atau ID siswa).
5. Sistem mengarahkan pengguna ke dashboard sesuai role:
   - **Admin:** dashboard admin berisi ringkasan jumlah guru, siswa, kelas, mata pelajaran, tahun ajaran aktif, ringkasan absensi siswa (jika tersedia), dan aktivitas konten. Admin dapat memilih kartu untuk membuka halaman detail.
   - **Guru:** dashboard guru berisi kelas dan mata pelajaran yang diampu, jadwal mengajar, absensi yang perlu dilengkapi, dan materi yang dikelola.
   - **Wali kelas:** dashboard guru yang sama; sistem memeriksa penetapan wali kelas pada tahun ajaran aktif dan menambahkan menu wali kelas pada sidebar.
   - **Siswa:** dashboard siswa berisi kelas, tahun ajaran, jadwal, materi terbaru, dan ringkasan absensi pribadi.
6. *Pengecualian:* jika username atau password tidak valid, sistem menolak login dengan pesan kesalahan yang tidak membocorkan informasi sensitif.
7. *Pengecualian:* jika akun berstatus "Nonaktif", sistem menolak login.
8. *Pengecualian:* jika akun bertanda "wajib ganti password" (akun baru atau hasil reset admin), sistem mengarahkan pengguna ke halaman ubah password (alur 7.8) sebelum mengizinkan akses lain.
9. *Pengecualian:* jika pengguna membuka halaman di luar hak aksesnya (mis. guru membuka halaman admin), backend menolak permintaan walaupun menu tidak ditampilkan.

## 7.7 Admin Membuat dan Mengelola Akun Pengguna

1. Admin membuka menu Manajemen Akun.
2. Untuk **akun guru**: admin memilih tambah akun guru, sistem menampilkan guru yang belum memiliki akun, admin memilih guru, sistem mengambil identitas guru dari tabel master, admin menentukan username, sistem menyediakan password awal atau admin menetapkannya, lalu admin menyimpan.
3. Untuk **akun siswa**: alurnya sama dengan akun guru, tetapi dari daftar siswa yang belum memiliki akun, dan sistem menetapkan role Siswa.
4. Untuk **akun admin**: admin yang berwenang (admin utama) mengisi identitas akun; sistem memvalidasi username dan kelengkapan data lalu menetapkan role Admin.
5. Sistem membuat akun pada tabel autentikasi SIAKAD, menghubungkannya dengan ID master, dan menyimpan password dalam bentuk hash. Status akun menjadi "Aktif" dan dapat digunakan untuk login.
6. Untuk mengubah status: admin mencari akun di daftar, memilih aktif atau nonaktif, lalu sistem meminta konfirmasi. Setelah dikonfirmasi, status akun berubah menjadi "Aktif" atau "Nonaktif"; akun nonaktif tidak dapat login.
7. **Akun siswa massal:** admin memilih Buat Akun Siswa Massal; sistem memilih siswa yang memiliki penempatan pada periode aktif dan belum memiliki akun, memakai NIS sebagai username, membuat password acak, menyimpan hash, dan menandai akun "wajib ganti password". Sistem menampilkan ringkasan (berhasil dan dilewati beserta alasan, mis. NIS kosong atau ganda) serta daftar akun awal yang dapat diunduh satu kali (Excel) untuk dibagikan melalui wali kelas.
8. **Reset paksa password:** admin mencari akun guru atau siswa, memilih Reset Password, lalu sistem membuat password sementara acak yang tampil satu kali, menyimpan hash-nya, dan menandai akun "wajib ganti password". Admin tidak dapat melihat password lama.
9. *Pengecualian:* jika username sudah dipakai, sistem menolak dan meminta username lain; siswa dengan NIS kosong atau ganda dilewati pada pembuatan massal dan ditangani admin secara manual.
10. *Pengecualian:* jika admin yang bukan admin utama mencoba mengelola akun admin lain, sistem menolak.

## 7.8 Pengguna Mengganti Password

1. Pengguna login lalu membuka menu profil atau pengaturan akun dan memilih ubah password.
2. Pengguna memasukkan password lama dan password baru.
3. Sistem memverifikasi password lama dan memvalidasi password baru.
4. Sistem melakukan hashing password baru, menyimpan hash terbaru, mengeluarkan sesi di perangkat lain, dan menampilkan notifikasi keberhasilan. Jika akun bertanda "wajib ganti password", penanda dilepas dan pengguna baru dapat mengakses fitur lain.
5. *Pengecualian:* jika password lama salah atau password baru tidak memenuhi validasi, sistem menolak perubahan dan menampilkan alasan.

## 7.9 Admin Mengelola Data Guru

1. Admin membuka menu Data Guru; sistem menampilkan daftar guru.
2. **Tambah:** admin memilih Tambah Guru, mengisi formulir (nama, NIP atau identitas guru, jenis kelamin, status atau informasi tambahan), lalu menyimpan. Sistem memvalidasi kelengkapan dan format, memeriksa apakah identitas sudah terdaftar, menyimpan ke tabel master guru bersama RKM, dan menampilkan notifikasi. Guru muncul pada daftar dan dapat dipakai untuk penugasan serta pembuatan akun.
3. **Ubah:** admin mencari guru, memilih edit, mengubah data pada formulir, lalu sistem memvalidasi dan memperbarui tabel master.
4. **Hapus:** admin memilih guru dan sistem meminta konfirmasi. Setelah disetujui, sistem memeriksa relasi guru dengan penugasan mengajar, wali kelas, absensi, atau data lain.
5. Jika masih memiliki relasi penting, sistem menolak penghapusan atau meminta admin menonaktifkan data. Jika aman, sistem mengubah status data menjadi nonaktif melalui kolom `stat_data` (tanpa penghapusan fisik, riwayat tetap tersimpan) dan menampilkan hasil proses.
6. *Pengecualian:* jika identitas guru sudah terdaftar, sistem menolak penyimpanan dan menampilkan pesan duplikasi.

## 7.10 Admin Mengelola Data Siswa dan Impor Excel

1. Admin membuka menu Data Siswa; sistem menampilkan daftar siswa.
2. **Tambah satu siswa:** admin memilih Tambah Siswa, mengisi formulir yang mencakup seluruh data siswa RKM (hanya NIS dan nama yang wajib), lalu menyimpan. Sistem memvalidasi, memeriksa kemungkinan duplikasi, menyimpan ke tabel master siswa bersama RKM, dan menampilkan notifikasi.
3. **Impor Excel:**
   1. Admin memilih Impor Excel, lalu mengunduh template yang disediakan sistem.
   2. Admin mengisi data siswa sesuai kolom dan format template, lalu mengunggah file.
   3. Sistem memeriksa format file dan nama kolom, lalu membaca setiap baris.
   4. Sistem memvalidasi data wajib, format tanggal, identitas, dan duplikat (NIS atau NISN) terhadap data tersimpan maupun antarbaris dalam file.
   5. Sistem menampilkan hasil pemeriksaan sebelum penyimpanan: jumlah dan nomor baris valid, jumlah dan keterangan baris bermasalah, serta baris terindikasi duplikat. Status proses impor: "Menunggu Konfirmasi".
   6. Admin meninjau hasil, lalu memperbaiki file atau memilih memproses hanya baris yang lolos validasi sesuai kebijakan impor (TBD-25).
   7. Admin mengonfirmasi impor; sistem menyimpan data yang lolos dalam satu transaksi database. Data duplikat tidak menimpa data lama.
   8. Sistem menampilkan ringkasan hasil: jumlah data berhasil disimpan dan jumlah data tidak diproses.
4. **Ubah:** admin mencari siswa berdasarkan nama, NIS, atau NISN, memilih edit, mengubah data, lalu sistem memvalidasi dan menyimpan ke tabel master.
5. **Hapus atau nonaktifkan:** admin memilih siswa; sistem memeriksa relasi dengan penempatan kelas, absensi, dan riwayat rapor. Jika memiliki riwayat akademik, data tidak dihapus permanen; admin menonaktifkan atau mengubah status siswa sesuai ketentuan sekolah (`stat_data`, nilai K untuk siswa keluar), dan sistem menyimpan perubahan status.
6. *Pengecualian:* jika terjadi kegagalan saat penyimpanan impor, seluruh proses dibatalkan sehingga tidak ada data tersimpan setengah-setengah.

## 7.11 Admin Menyiapkan Struktur Akademik: Kelas, Penempatan, Wali Kelas, Mata Pelajaran, dan Penugasan Guru

1. Admin memastikan tahun ajaran aktif tersedia (alur 7.12).
2. **Data kelas:** admin membuka Manajemen Kelas lalu Data Kelas, menambah kelas dengan tingkat dan nama kelas; sistem memvalidasi duplikasi dan menyimpan ke tabel kelas bersama RKM. Untuk menghapus kelas, sistem memeriksa siswa, penugasan guru, wali kelas, dan riwayat akademik; jika masih digunakan, penghapusan ditolak.
3. **Penempatan siswa:** admin membuka subfitur Penempatan Siswa, memilih tahun ajaran dan kelas tujuan, memilih satu atau beberapa siswa dari daftar yang dapat ditempatkan, lalu mengonfirmasi. Sistem memeriksa bahwa siswa belum memiliki penempatan aktif pada tahun ajaran itu dan menyimpan hubungan siswa, kelas, dan tahun ajaran. Untuk memindahkan siswa, admin memilih siswa dari daftar anggota kelas dan kelas tujuan, lalu sistem memeriksa dan memperbarui penempatan setelah konfirmasi.
4. **Mata pelajaran:** admin membuka menu Mata Pelajaran, menambah mata pelajaran (nama, kelompok, kode singkat); sistem memvalidasi dan menyimpan ke tabel mata pelajaran bersama RKM. Untuk menghapus, sistem memeriksa relasi dengan penugasan, jadwal, materi, dan riwayat nilai.
5. **Penugasan guru:** admin membuka menu Penugasan Guru, memilih periode (semester), guru, mata pelajaran, dan kelas. Sistem menampilkan ringkasan; setelah konfirmasi, sistem memeriksa duplikasi dan menyimpan hubungan guru, mata pelajaran, kelas, dan periode. Penugasan muncul pada daftar mengajar guru. Untuk periode baru, admin dapat menyalin seluruh penugasan dari periode sebelumnya lalu menyesuaikannya.
6. **Penetapan wali kelas:** admin membuka subfitur Penetapan Wali Kelas, memilih periode (semester), kelas, dan guru. Sistem memeriksa bahwa kelas belum memiliki wali kelas pada periode itu dan guru belum menjadi wali kelas di kelas lain. Setelah konfirmasi, sistem menyimpan penetapan dan memberikan akses tambahan wali kelas pada akun guru. Penetapan wali kelas dapat disalin dari periode sebelumnya lalu disesuaikan.
7. *Pengecualian:* jika siswa sudah memiliki penempatan aktif pada tahun ajaran yang sama, penempatan ditolak (gunakan fitur pindah kelas).
8. *Pengecualian:* jika kelas sudah memiliki wali kelas atau guru sudah menjadi wali kelas di kelas lain, penetapan ditolak.

## 7.12 Admin Mengelola Tahun Ajaran dan Mengaktifkannya

1. Admin membuka menu Tahun Ajaran dan memilih tambah tahun ajaran.
2. Admin mengisi kode periode (mis. 20261 untuk tahun ajaran 2026/2027 semester 1), nama kepala sekolah, NIP kepala sekolah, tanggal tanda tangan rapor kelas 7 dan 8, serta tanggal tanda tangan rapor kelas 9.
3. Sistem memvalidasi format dan kemungkinan duplikasi, lalu menyimpan; tahun ajaran baru masuk ke daftar.
4. Untuk mengaktifkan, admin memilih tahun ajaran dari daftar; sistem menampilkan konfirmasi pergantian. Admin memeriksa kembali data tahun ajaran tujuan dan mengonfirmasi.
5. Sistem mengubah status tahun ajaran aktif. Tahun ajaran baru menjadi konteks penempatan siswa dan penugasan akademik berikutnya.
6. *Pengecualian:* jika periode sudah ada atau formatnya tidak sesuai, sistem menolak penyimpanan.

## 7.13 Admin Memproses Kenaikan Kelas dan Penempatan Tahun Ajaran Baru

1. Wali kelas masing-masing mengisi keputusan kenaikan kelas siswanya di RKM.
2. Admin membuat atau memilih tahun ajaran baru di SIAKAD (alur 7.12).
3. Sistem membaca status kenaikan setiap siswa dari data RKM (termasuk `t_naikkelas`, setelah struktur dan relasinya diverifikasi).
4. Sistem mengidentifikasi siswa berdasarkan kelas asal dan status kenaikan, lalu menyiapkan pratinjau:
   - siswa yang naik dipetakan ke tingkat berikutnya dengan mempertahankan nomor rombel dan programnya (VII-1 (Fullday) menjadi VIII-1 (Fullday), VII-4 (Reguler) menjadi VIII-4 (Reguler));
   - jumlah rombel tingkat tujuan mengikuti jumlah rombel angkatan yang naik (mis. lima rombel kelas 8 menghasilkan lima rombel kelas 9, walaupun sebelumnya kelas 9 hanya tiga rombel); rombel tambahan disiapkan bila belum ada;
   - siswa tidak naik kelas: penanganannya ditunda (FL-23); siswa kelas 9 yang lulus tidak ditempatkan lagi dan statusnya tidak diubah.
5. Admin memeriksa daftar siswa, kelas asal, kelas tujuan, jumlah rombel, dan status kenaikan, serta memperbaiki pemetaan jika perlu.
6. Admin mengonfirmasi; sistem menyimpan penempatan tahun ajaran baru tanpa menghapus atau menimpa riwayat tahun sebelumnya (dalam satu transaksi database).
7. Penetapan wali kelas tahun ajaran baru dikosongkan; admin menetapkan satu wali kelas untuk setiap kelas (alur 7.11).
8. Admin menempatkan siswa baru dan siswa pindahan secara manual ke rombel yang tersedia.
9. *Pengecualian:* jika status kenaikan siswa belum tersedia di RKM, siswa tersebut tidak dipetakan otomatis dan ditandai untuk ditangani admin. *(Perilaku ini adalah asumsi pengembang; lihat TBD-23.)*

## 7.14 Admin Menyusun Jadwal Pelajaran

1. Admin membuka menu Jadwal Pelajaran dan memilih periode (semester) aktif.
2. *(Opsional)* Jika jadwal periode sebelumnya tersedia, admin memilih menyalinnya; sistem membuat salinan untuk periode baru tanpa mengubah jadwal lama, lalu admin memeriksa dan menyesuaikan kelas, hari, jam, mata pelajaran, dan guru.
3. Untuk jadwal baru: admin memilih kelas, hari, jam mulai, jam selesai, dan mata pelajaran.
4. Sistem menampilkan guru yang memiliki penugasan mata pelajaran tersebut pada kelas terkait; admin memilih guru pengampu dan menyimpan.
5. Sistem memvalidasi: jam mulai lebih awal daripada jam selesai; guru ditugaskan pada mata pelajaran dan kelas tersebut; guru dan kelas tidak memiliki jadwal lain pada waktu yang sama.
6. Jika tidak ada bentrok, jadwal tersimpan dan tersedia pada halaman guru dan siswa.
7. **Ubah:** admin memilih jadwal, mengubah hari, jam, kelas, mata pelajaran, atau guru; sistem memeriksa ulang validitas dan bentrok sebelum memperbarui.
8. **Hapus:** admin memilih jadwal dan mengonfirmasi. Jika sudah ada catatan pertemuan atau absensi, jadwal berstatus "Nonaktif" untuk periode berikutnya dan riwayat tetap dipertahankan; jika belum, jadwal dihapus atau dinonaktifkan.
9. *Pengecualian:* jika terjadi bentrok guru atau kelas, atau guru tidak memiliki penugasan, sistem menolak penyimpanan dan menampilkan alasan.

## 7.15 Admin Mengelola Konten Publik

1. Admin membuka menu konten (Berita, Pengumuman, Fasilitas, Ekstrakurikuler, Prestasi, atau Galeri); sistem menampilkan daftar konten.
2. Admin memilih tambah dan mengisi formulir sesuai jenis konten:
   - **Berita:** judul, isi (teks biasa dengan baris baru), gambar utama, informasi pendukung.
   - **Pengumuman:** judul, isi, tanggal, informasi pendukung.
   - **Fasilitas:** nama, deskripsi, gambar jika tersedia.
   - **Ekstrakurikuler:** sistem mengambil data ekstrakurikuler dari tabel melalui id; admin mengisi deskripsi, gambar, dan informasi pendukung.
   - **Prestasi:** nama atau deskripsi prestasi, tingkat kegiatan, tanggal, informasi pendukung, dokumentasi jika tersedia.
   - **Galeri:** unggah gambar, judul atau keterangan.
3. Admin menentukan status konten: "Draft", "Published", atau "Archived".
4. Admin menyimpan; sistem memvalidasi isi serta format dan ukuran file gambar, lalu menyimpan data dan metadata/lokasi file.
5. Hasil status: "Draft" hanya terlihat pada halaman pengelolaan admin; "Published" tampil pada website publik (beranda, berita, pengumuman, fasilitas, ekstrakurikuler, prestasi, galeri); "Archived" tidak tampil sebagai konten aktif.
6. **Ubah:** admin membuka daftar konten, memilih konten, mengubah informasi; sistem memvalidasi dan memperbarui.
7. **Hapus:** sistem meminta konfirmasi dan menyarankan status "Archived" agar riwayat konten dapat dipertahankan.
8. Sistem mencatat aktivitas pengelolaan konten yang kemudian tampil pada dashboard admin.
9. *Pengecualian:* jika format atau ukuran gambar tidak sesuai (TBD-32), sistem menolak unggahan dan menampilkan alasan.

## 7.16 Guru Mencatat dan Mengoreksi Absensi Siswa per Pertemuan

1. Guru login dan membuka menu Absensi Siswa; sistem menampilkan jadwal mengajar guru.
2. Guru memilih jadwal atau kelas; sistem menampilkan kelas, mata pelajaran, dan waktu pertemuan.
3. Guru memilih tanggal atau pertemuan; sistem memeriksa apakah absensi untuk jadwal dan tanggal itu sudah pernah dibuat.
4. Jika belum ada, guru memilih mulai membuat absensi dan sistem membuat catatan sesi pertemuan.
5. Sistem menampilkan seluruh siswa kelas; guru menentukan status setiap siswa: "Hadir", "Sakit", "Izin", atau "Tanpa Keterangan".
6. Guru memeriksa kembali lalu menekan simpan; sistem memvalidasi bahwa setiap siswa memiliki status, menyimpan catatan absensi per siswa pada sesi pertemuan tersebut, dan menampilkan notifikasi.
7. **Koreksi:** guru membuka riwayat absensi, memilih sesi miliknya, mengubah status siswa, dan menyimpan; sistem memvalidasi hak akses dan data, lalu memperbarui catatan.
8. **Riwayat:** guru memilih kelas, mata pelajaran, atau periode; sistem menampilkan daftar pertemuan, dan guru membuka detail kehadiran siswa.
9. *Pengecualian:* jika absensi untuk jadwal dan tanggal itu sudah ada, sistem membuka catatan yang ada (bukan membuat sesi baru).
10. *Pengecualian:* guru tidak dapat mengisi atau mengubah absensi untuk kelas atau mata pelajaran di luar penugasannya.
11. *Catatan:* data ini tidak otomatis mengubah rekap absensi rapor di RKM; guru memvalidasi dan mengisinya manual di RKM.

## 7.17 Guru Mengelola Materi Pembelajaran

1. Guru membuka menu Materi Pembelajaran; sistem menampilkan daftar materi milik guru.
2. Guru memilih tambah materi dan mengisi judul serta deskripsi.
3. Guru memilih satu atau beberapa kelas tujuan (sistem hanya menampilkan kelas yang diajar guru untuk mata pelajaran terkait) dan mata pelajaran sesuai penugasan.
4. Guru memilih jenis materi: mengunggah file dari perangkat atau memasukkan tautan eksternal.
5. Guru menyimpan; sistem memvalidasi data, jenis dan ukuran file, tautan, serta kesesuaian kelas dan mata pelajaran dengan penugasan.
6. Jika valid, sistem menyimpan metadata (dan file pada penyimpanan aplikasi); materi muncul pada daftar guru dan dapat dilihat siswa di seluruh kelas yang dipilih.
7. **Ubah:** guru memilih materi miliknya, mengubah judul, deskripsi, kelas, mata pelajaran, file, atau tautan; sistem memvalidasi dan memperbarui.
8. **Hapus:** guru memilih materi dan menyetujui konfirmasi; sistem memeriksa kepemilikan lalu menghapus atau menonaktifkan materi sehingga tidak tampil lagi kepada siswa.
9. *Pengecualian:* jika jenis atau ukuran file tidak sesuai ketentuan (rekomendasi awal 15 MB, TBD-24) atau kelas yang dipilih tidak diajar guru untuk mata pelajaran tersebut, sistem menolak penyimpanan.

## 7.18 Wali Kelas dan Admin Memantau Absensi Siswa

1. **Wali kelas:** setelah login, sistem memeriksa penetapan wali kelas pada tahun ajaran aktif dan menampilkan menu Data Siswa Kelas dan Absensi Kelas.
2. Wali kelas membuka Absensi Kelas dan memilih periode; sistem mengambil catatan absensi seluruh mata pelajaran pada kelas tersebut dan menggabungkannya berdasarkan siswa dan pertemuan.
3. Wali kelas memilih rentang tanggal untuk melihat rekap ketidakhadiran: jumlah "Hadir", "Sakit", "Izin", dan "Tanpa Keterangan" per siswa, sehingga siswa yang sering tidak hadir dapat diidentifikasi.
4. Wali kelas memilih siswa atau pertemuan untuk melihat tanggal, mata pelajaran, guru pengampu, dan status kehadiran.
5. Jika ada kesalahan, wali kelas menghubungi guru yang mencatat; wali kelas tidak mengubah catatan.
6. **Admin:** membuka Pemantauan Absensi Siswa, memilih filter (kelas, guru, mata pelajaran, tanggal); sistem menampilkan catatan per pertemuan, pertemuan yang sudah dan belum memiliki catatan, serta ringkasan absensi.
7. Admin menindaklanjuti kekurangan pengisian dengan menghubungi guru terkait dan tidak mengubah catatan absensi.

## 7.19 Siswa Melihat Jadwal, Materi, dan Absensi Pribadi

1. Siswa login; sistem mengidentifikasi ID siswa serta kelas dan tahun ajaran aktif, lalu menampilkan dashboard siswa.
2. **Jadwal:** siswa membuka Jadwal Pelajaran; sistem mengambil jadwal kelas, mengelompokkannya per hari, dan menampilkan jam mulai, jam selesai, mata pelajaran, dan guru pengampu pada hari yang dipilih.
3. **Materi:** siswa membuka Materi Pembelajaran; sistem menampilkan materi yang ditujukan untuk kelasnya. Siswa memilih materi, lalu membuka atau mengunduh file, atau membuka tautan eksternal.
4. **Absensi:** siswa membuka Absensi Saya; sistem menampilkan riwayat berdasarkan tanggal dan mata pelajaran beserta status setiap pertemuan.
5. *Pengecualian:* jika belum ada jadwal, materi, atau catatan absensi, sistem menampilkan pesan data belum tersedia.
6. Siswa tidak dapat mengubah data apa pun pada fitur ini.

## 7.20 Guru dan Wali Kelas Mengakses RKM serta Alur Data dengan RKM

1. Guru atau wali kelas memilih menu RKM pada sidebar; sistem mengarahkan ke alamat aplikasi RKM.
2. Pengguna login ke RKM sesuai mekanisme autentikasi RKM (akun SIAKAD tidak otomatis berlaku) dan mengerjakan penilaian serta rapor di RKM.
3. Alur data antar sistem: admin menambah atau mengubah data guru, siswa, kelas, mata pelajaran, dan penempatan melalui SIAKAD; SIAKAD menyimpan ke tabel master bersama; RKM membaca data tersebut untuk proses penilaian.
4. Keputusan kenaikan kelas ditetapkan di RKM, lalu dibaca SIAKAD untuk menyiapkan penempatan tahun ajaran baru (alur 7.13).
5. Rekap absensi rapor tetap diisi manual oleh guru di RKM; SIAKAD tidak mengubahnya.

# 8. Model Data (High-Level)

Entitas bertanda "RKM" berada pada tabel yang digunakan bersama RKM; nama kolom pada baris tersebut diverifikasi dari schema.sql. Rancangan awal 25 tabel `siakad_*` pada Panduan Eksekusi Query menjadi acuan implementasi tabel baru; nama entitas di bawah bersifat logis. Entitas bertanda "(SIAKAD, baru)" adalah tabel baru yang dibuat khusus untuk SIAKAD.

| **Entitas** | **Field Utama** | **Keterangan** |
| --- | --- | --- |
| **Guru (`m_guru`, RKM, bersama)** | id, nama, nip, jk, is_bk, stat_data (A, P, M) | Dikelola admin melalui SIAKAD dan dibaca RKM. Nonaktif melalui `stat_data`. Tidak ada kolom jabatan, email, maupun nomor telepon. Direktori publik hanya mengambil kolom yang diizinkan (DIR-8). |
| **Siswa (`m_siswa`, RKM, bersama)** | id (UNIQUE, tanpa primary key), nis, nisn, nama, jk, tmp_lahir, tgl_lahir, agama, status, anakke, alamat, notelp, sek_asal, sek_asal_alamat, diterima_kelas, diterima_tgl, diterima_smt, ijazah_no, ijazah_thn, skhun_no, skhun_thn, ortu_ayah, ortu_ibu, ortu_alamat, ortu_notelp, ortu_ayah_pkj, ortu_ibu_pkj, wali, wali_alamat, notelp_rumah, wali_pkj, inputID, tgl_input, tgl_update, stat_data (A, K, M, L), foto | Sekitar 30 kolom NOT NULL tanpa default; `inputID` diisi 0; `tgl_update` tidak dipakai RKM (bernilai nol). Alumni tetap berstatus A. NIS, NISN, dan data pribadi tidak ditampilkan pada direktori publik. |
| **Kelas (`m_kelas`, RKM, bersama)** | id, tingkat (7, 8, 9), nama (mis. VII-1 (Fullday)) | Sekitar 20 baris. Rombel tambahan dibuat dengan menambah baris. |
| **Penempatan Siswa (`t_kelas_siswa`, RKM, bersama)** | id (UNIQUE), id_kelas, id_siswa, ta (YEAR) | Primary key gabungan (id_kelas, id). Tidak ada unique (siswa, tahun) dan sebagian data historis ganda. Riwayat tahun sebelumnya dipertahankan. |
| **Mata Pelajaran (`m_mapel`, RKM, bersama)** | id, kelompok (A, B, C), tambahan_sub (NO, PAI, MULOK), kd_singkat, nama, is_sikap (0, 1) | Dikelola admin; dipakai penugasan, jadwal, dan materi. |
| **Penugasan Guru (`t_guru_mapel`, RKM, bersama)** | id, tasm, id_guru, id_kelas, id_mapel | Per periode (semester), sekitar 390 baris per periode. Dirujuk tabel nilai tanpa foreign key (PGS-7). Dasar pembatasan akses guru. |
| **Wali Kelas (`t_walikelas`, RKM, bersama)** | id, tasm, id_guru, id_kelas | Per periode (semester). Tidak ada unique key; satu kelas satu wali dan satu guru satu kelas dijaga oleh aplikasi. Riwayat dipertahankan. |
| **Tahun Ajaran (`tahun`, RKM, bersama)** | id, tahun (kode periode, mis. 20242), aktif (Y, N), nama_kepsek, nip_kepsek, tgl_raport, tgl_raport_kelas3 (kelas 9) | Menentukan periode aktif. Salinan database hanya memuat 4 periode (20231 sampai 20242). Terpisah dari halaman Kepala Sekolah publik yang statis. |
| **Kenaikan Kelas (`t_naikkelas`, RKM)** | id, id_siswa, ta (kode periode 5 karakter), naik (Y, N, NULL), catatan_wali | Hanya dibaca SIAKAD (kolom catatan_wali tidak dibaca). Data yang ada hanya bernilai Y atau NULL. |
| **Ekstrakurikuler (`m_ekstra`, RKM, bersama)** | id, nama | Hanya menyimpan nama; penilaian rapor tetap di RKM. |
| **Akun RKM (`m_admin`, RKM)** | id, username, password (SHA-1), level, konid, aktif | Tidak diubah oleh SIAKAD (42 akun: 3 admin dan 39 guru). Username guru boleh dipakai ulang pada akun SIAKAD. |
| **Akun Pengguna (`siakad_users`, SIAKAD, baru)** | id, username (unik), password (hash), role, id_guru, id_siswa, is_active, [wajib_ganti_password], [penanda_admin_utama] | Terpisah dari tabel akun RKM. Role: admin, guru, siswa (wali kelas diturunkan dari Wali Kelas). Dua kolom bertanda kurung siku adalah usulan tambahan pada rancangan awal. |
| **Jadwal Pelajaran (SIAKAD, baru)** | tahun_ajaran, kelas, hari, jam_mulai, jam_selesai, mapel, guru, status_aktif | Satu set jadwal per tahun ajaran; salinan tidak mengubah jadwal lama. |
| **Sesi Pertemuan (SIAKAD, baru)** | jadwal, tanggal, dibuat_oleh | Satu sesi per jadwal per tanggal. |
| **Absensi Siswa (SIAKAD, baru)** | sesi_pertemuan, siswa, status (hadir, sakit, izin, tanpa_keterangan) | Dicatat per pertemuan; tidak otomatis mengubah rekap absensi rapor RKM. |
| **Materi (SIAKAD, baru)** | judul, deskripsi, mapel, guru_pemilik, jenis (file atau tautan), lokasi_file, url_tautan, tanggal_dibuat, [status] | Satu materi dapat ditujukan ke beberapa kelas. |
| **Materi–Kelas (SIAKAD, baru)** | materi, kelas | Relasi materi dan kelas tujuan. |
| **Berita (SIAKAD, baru)** | judul, slug, ringkasan, isi, gambar_utama, tanggal, status (draft, published, archived) | Menggantikan data dummy berita. |
| **Pengumuman (SIAKAD, baru)** | judul, slug, ringkasan, isi, tanggal, status | Terpisah dari berita. |
| **Fasilitas (SIAKAD, baru)** | nama, deskripsi, gambar, status | Menggantikan data dummy fasilitas. |
| **Ekstrakurikuler Publik (SIAKAD, baru)** | ekstrakurikuler_id, deskripsi, gambar, informasi_pendukung, [jadwal], status | Terhubung ke tabel ekstrakurikuler melalui id; dipisahkan dari data akademik. |
| **Prestasi (SIAKAD, baru)** | nama_prestasi, jenis, tingkat, tanggal, deskripsi, dokumentasi, [nama_siswa_tim], status | Terpisah dari `t_prestasi` (prestasi untuk rapor). Publikasi nama atau foto siswa mengikuti kebijakan sekolah (TBD-9). |
| **Galeri (SIAKAD, baru)** | judul, lokasi_file, keterangan, [tanggal], [kategori], status | Menyimpan metadata dan lokasi file gambar. |
| **Catatan Aktivitas Konten (SIAKAD, baru)** | pengguna, jenis_konten, aksi, waktu | Sumber informasi aktivitas pada dashboard admin. |
| **Profil Sekolah (statis)** | nama, alamat, deskripsi, visi, misi, [sejarah], [kontak] | Data statis di kode/konfigurasi. |
| **Kepala Sekolah (statis)** | nama, jabatan, foto, sambutan | Satu sumber untuk beranda dan halaman kepala sekolah; terpisah dari data kepala sekolah pada Tahun Ajaran. |
| **Struktur Organisasi (statis)** | jabatan, nama_pejabat, [foto], [tugas] | Data statis. |
| **Organisasi Siswa (statis)** | nama, logo, deskripsi, kegiatan_utama | Tetap statis; tidak memiliki tabel database. |
| **Tautan Portal (konfigurasi)** | nama, ikon, url | Tiga entri: CBT, Rapor, PPDB (URL placeholder). |
| **Absensi Guru Fingerprint** | [guru, waktu_pindai, identitas_perangkat, status_ketepatan] | Fase Lanjutan (FL-14); struktur bergantung pada mesin yang dipakai (TBD-18). |
| **Pengaturan Jam Kerja Guru** | [guru, jam_kerja atau batas_kedatangan] | Fase Lanjutan (FL-17). |
| **Rekap Absensi dan Bonus Guru** | [guru, bulan, tahun, jumlah_tepat_waktu, jumlah_terlambat, total_bonus] | Fase Lanjutan (FL-15, FL-16); rumus bonus belum ditentukan (TBD-21). |

**Catatan:** field dalam [tanda kurung siku] merupakan bagian dari fitur usulan/Fase Lanjutan (Bab 11) atau masih perlu diverifikasi (TBD). Struktur tabel final belum ditetapkan.

# 9. Kebutuhan Non-Fungsional (Non-Functional Requirements)

- **Responsivitas :** halaman dapat digunakan di komputer dan perangkat seluler dengan pendekatan Mobile First; navigasi tidak menutupi konten di layar kecil.
- **Performa :** halaman ringan; query hanya mengambil kolom yang dibutuhkan, memakai pagination, menghindari query berulang per item, dan memakai eager loading bila perlu; indeks kolom pencarian dan filter diperiksa sebelum perubahan skema.
- **Media :** gambar dikompresi, berukuran tetap agar layout tidak bergeser, memakai thumbnail serta lazy loading, dan tanpa autoplay video di beranda.
- **Frontend :** komponen bersama dipakai ulang; JavaScript, CSS, dan pustaka tambahan hanya jika diperlukan; tanpa efek visual berat.
- **Tailwind CSS (build produksi) :** CSS dikompilasi dan diminimasi lewat proses build; Tailwind v4 mendeteksi sumber kelas secara otomatis dan direktif `@source` ditambahkan bila ada berkas di luar deteksi bawaan (mis. komponen pada folder baru); Tailwind tidak dimuat dari CDN pada produksi; nama kelas tidak disusun dinamis dengan penggabungan teks karena tidak terdeteksi saat build.
- **Konsistensi desain :** warna, ukuran huruf, jarak, dan breakpoint didefinisikan sekali pada konfigurasi tema Tailwind; pengulangan gaya diselesaikan dengan komponen Blade, bukan banyak aturan `@apply` atau CSS kustom.
- **Aset frontend :** satu berkas CSS dan satu berkas JavaScript kecil per halaman; JavaScript dimuat dengan `defer` dan hanya untuk kebutuhan nyata (mis. buka-tutup menu seluler); ikon berupa SVG sebaris atau sprite SVG (bukan pustaka ikon font); berkas aset diberi versi (hash) dan di-cache oleh peramban; kompresi gzip atau brotli diaktifkan di server jika memungkinkan.
- **Tipografi :** memakai font sistem atau maksimal satu keluarga font yang dihosting sendiri dengan jumlah bobot terbatas; tidak memuat font dari layanan eksternal.
- **Kinerja tampilan :** efek animasi dan bayangan berat dihindari; layout tidak bergeser saat memuat (ukuran gambar ditetapkan); tidak ada gambar latar besar yang tidak dioptimasi.
- **Keamanan dan hak akses :** halaman publik hanya membaca; operasi tambah, ubah, dan hapus hanya tersedia setelah login sesuai role. Pemeriksaan hak akses dilakukan di backend untuk setiap permintaan, bukan hanya dengan menyembunyikan menu atau tombol. Guru hanya mengakses kelas dan mata pelajaran sesuai penugasan; siswa hanya mengakses data miliknya; wali kelas hanya mengakses kelas perwaliannya. Hak akses database dibatasi sesuai kebutuhan, dan tabel nilai, rapor, serta tabel inti RKM tidak diubah tanpa audit dan persetujuan.
- **Keamanan akun :** password disimpan dalam bentuk hash yang kuat (bukan teks biasa dan bukan SHA-1); admin tidak dapat melihat password pengguna; pesan kesalahan login tidak membocorkan informasi sensitif; username unik; akun nonaktif tidak dapat login. Password minimal 8 karakter dan tidak boleh sama dengan username atau NIS; password awal acak dan wajib diganti saat login pertama; masa berlaku sesi dan perilaku logout ditentukan pada TBD-33.
- **Integritas data dan riwayat akademik :** penghapusan fisik data master yang memiliki relasi dibatasi (soft delete, nonaktifkan, atau arsip); riwayat penempatan siswa, penetapan wali kelas, dan jadwal tahun ajaran sebelumnya dipertahankan; operasi yang melibatkan beberapa tabel (impor siswa, kenaikan kelas, salin jadwal) memakai transaksi database; data duplikat tidak menimpa data lama secara otomatis.
- **Keamanan unggahan :** file dan gambar yang diunggah divalidasi jenis dan ukurannya sebelum disimpan; rekomendasi awal untuk materi adalah PDF, DOC/DOCX, PPT/PPTX, XLS/XLSX, JPG/JPEG, dan PNG maksimal 15 MB per file (TBD-24, TBD-32). Isi berita dan pengumuman berupa teks biasa yang di-escape saat ditampilkan agar aman dari XSS.
- **Privasi data :** direktori publik hanya menampilkan nama (dan jabatan/mata pelajaran guru, kelas siswa); publikasi nama, kelas, dan foto siswa mengikuti persetujuan dan kebijakan sekolah serta ketentuan perlindungan data yang berlaku.
- **Integritas konten :** data dummy tidak ditampilkan sebagai data resmi dan harus ditandai; data direktori tidak boleh mengarang jabatan atau mata pelajaran.
- **Kompatibilitas RKM :** perubahan data melalui SIAKAD tidak boleh merusak proses RKM; struktur tabel lama tidak diubah tanpa memeriksa ketergantungan kode RKM; ID master yang sama dipakai sebagai penghubung; query dan operasi tulis diuji dengan data nyata atau salinan sebelum dihubungkan ke produksi.
- **Objek basis data :** integritas dan keamanan data juga dibebankan pada DBMS (MySQL 8.0), bukan hanya aplikasi. *View* menyajikan data publik terbatas (direktori guru dan siswa tanpa kolom sensitif); *trigger* mencatat audit dan memvalidasi aturan (mis. bentrok jadwal, penempatan ganda); *stored procedure* berisi *transaksi* untuk operasi multi-tabel (impor siswa, pembuatan akun massal, salin penugasan dan jadwal, kenaikan kelas); *privilege* pengguna database dipisah per fungsi (publik hanya membaca view, aplikasi membaca dan menulis tabel master dan tabel SIAKAD, tanpa hak tulis ke tabel nilai RKM; ketersediaan hak akses per tabel di hosting masih perlu dicoba, TBD-5). Rinciannya dibahas pada ARCHITECTURE.md.
- **Kemudahan pemeliharaan :** data dummy dipisahkan dari logika tampilan dan diberi komentar penanda; struktur data konsisten agar peralihan ke database mudah.
- **Pengujian performa :** diperiksa waktu muat beranda, kecepatan daftar guru/siswa, performa pencarian dan filter, ukuran aset, tampilan pada koneksi lambat dan seluler, jumlah query beranda, serta kecepatan proses impor Excel. Pengujian juga mencakup ukuran berkas CSS dan JavaScript hasil build serta memastikan tidak ada kelas Tailwind yang hilang pada build produksi. Target numerik ditetapkan setelah uji awal (lihat Bab 12).

**Matriks Hak Akses** (berdasarkan rancangan alur; pemeriksaan dilakukan di backend):

| **Fitur** | **Admin** | **Guru** | **Wali Kelas** | **Siswa** |
| --- | --- | --- | --- | --- |
| Mengelola data master | Ya | Tidak | Tidak | Tidak |
| Mengelola akun | Ya | Tidak | Tidak | Tidak |
| Mengelola jadwal | Ya | Tidak | Tidak | Tidak |
| Melihat jadwal | Sesuai kebutuhan | Jadwal mengajar | Jadwal mengajar dan kelas | Jadwal kelas sendiri |
| Mengelola absensi siswa | Pemantauan saja | Kelas yang diajar | Kelas yang diajar sebagai guru; di kelas perwalian hanya memantau (TBD-36) | Tidak |
| Melihat absensi siswa | Pemantauan | Kelas yang diajar | Kelas sendiri | Milik sendiri |
| Mengelola materi | Tidak menjadi fitur utama | Materi miliknya | Materi miliknya | Tidak |
| Melihat materi | Jika diperlukan | Materi yang dikelola | Materi yang dikelola | Materi kelas sendiri |
| Mengelola konten publik | Ya | Tidak | Tidak | Tidak |
| Melihat absensi fingerprint (Fase 2) | Pemantauan seluruh guru | Milik sendiri | Milik sendiri | Tidak |
| Mengakses RKM | Sesuai kewenangan | Ya (tautan) | Ya (tautan) | Tidak ditetapkan dalam rancangan |

# 10. Integrasi Pihak Ketiga

| **Layanan** | **Fungsi** | **Catatan** |
| --- | --- | --- |
| **RKM (CodeIgniter 3, database bersama)** | Sumber dan tujuan data master (guru, siswa, kelas, mata pelajaran, penempatan, penugasan, wali kelas, tahun ajaran, ekstrakurikuler); sumber status kenaikan kelas (hanya dibaca). | Tidak memerlukan API karena database yang sama. SIAKAD kini menulis ke tabel master bersama; skema dan aturan tahun ajaran aktif harus diverifikasi. Bukan layanan pihak ketiga murni, tetapi sistem terpisah yang berbagi database. |
| **Aplikasi RKM (tautan sidebar)** | Tautan pada sidebar guru dan wali kelas menuju aplikasi RKM. | Hanya tautan; login RKM terpisah. URL menunggu konfirmasi (TBD-7). |
| **Portal CBT** | Tautan pintasan dari beranda. | URL placeholder; menunggu URL resmi. |
| **Portal Rapor** | Tautan pintasan dari beranda. | URL placeholder; menunggu URL resmi. |
| **Portal PPDB** | Tautan pintasan dari beranda. | URL placeholder; menunggu URL resmi. |
| **Halaman Login SIAKAD** | Tombol Login SIAKAD pada navbar menuju halaman login. | Dibangun sebagai bagian SIAKAD (AUT) pada Urutan 1; bukan layanan eksternal. Alamat host (domain utama atau subdomain `siakad`) belum ditetapkan (TBD-44). |
| **Penyimpanan file aplikasi** | Penyimpanan materi pembelajaran dan gambar konten publik. | Kapasitas dan batas ukuran file menunggu pemeriksaan (TBD-24). |
| **Mesin fingerprint** | Sumber data kehadiran guru. | Fase Lanjutan (FL-14). Merek, tipe, dan mekanisme integrasi belum ditentukan serta sedang dikembangkan dan diuji terpisah (TBD-18, TBD-19). |
| **Sinkronisasi data RKM lanjutan** | Pembaruan data guru dan siswa bila diperlukan. | Fase Lanjutan (FL-12). |

# 11. Fitur Usulan / Fase Lanjutan

> Tiap fitur memiliki ID **FL-n**, rencana tahap, dan rencana prefix ID. Saat fitur dikerjakan, pindahkan ke Bab 6 dengan ID baru, lalu tandai "Dipindahkan ke Bab 6 pada vX.Y" di sini. Fitur FL-1 sampai FL-10 pada v0.2 menggunakan sebutan Tahap A/B; sejak v0.3 urutan pengerjaan mengikuti Bab 4.3.

## 11.1 Sudah Dipindahkan ke Bab 6 atau Dibatalkan pada v0.3

- **FL-1 CRUD Berita.** *Dipindahkan ke Bab 6 pada v0.3:* ADM-2, ADM-8 sampai ADM-10, BRT-4.
- **FL-2 CRUD Pengumuman.** *Dipindahkan ke Bab 6 pada v0.3:* ADM-3, ADM-8 sampai ADM-10, PGM-4.
- **FL-3 CRUD Fasilitas.** *Dipindahkan ke Bab 6 pada v0.3:* ADM-4, ADM-8 sampai ADM-10, PRF-8.
- ~~**FL-4 CRUD Organisasi Siswa.**~~ *Dibatalkan pada v0.3:* organisasi siswa ditetapkan tetap statis (KSW-1). ID FL-4 tidak dipakai ulang.
- **FL-5 CRUD Ekstrakurikuler.** *Dipindahkan ke Bab 6 pada v0.3:* EKS-1 sampai EKS-4, ADM-5, KSW-6.
- **FL-6 CRUD Prestasi Siswa.** *Dipindahkan ke Bab 6 pada v0.3:* ADM-6, KSW-6.
- **FL-7 CRUD Galeri.** *Dipindahkan ke Bab 6 pada v0.3:* ADM-7, GAL-4.
- **FL-8 Dashboard Pengelolaan Konten.** *Dipindahkan ke Bab 6 pada v0.3:* ADM-1, ADM-11, DSH-1 sampai DSH-4, AUT-1 sampai AUT-8, AKN-1 sampai AKN-8.
- **FL-9 Status Konten.** *Dipindahkan ke Bab 6 pada v0.3:* ADM-8.
- **FL-10 Migrasi Data Dummy ke Database.** *Dipindahkan ke Bab 6 pada v0.3:* BRD-8, BRD-9 (serta BRT-4, PGM-4, PRF-8, KSW-6, GAL-4).

## 11.2 Masih Menjadi Fase Lanjutan

- **FL-11 Penyempurnaan Pencarian dan Optimasi (rencana prefix DIR).** Penyempurnaan pencarian, filter, pagination, dan optimasi query pada direktori dan daftar data.
- **FL-12 Sinkronisasi atau Pembaruan Data Lanjutan dari RKM (rencana prefix DAT).** Penyesuaian mekanisme pembaruan data bila diperlukan, di luar pembacaan dan penulisan tabel bersama yang sudah tercakup pada Bab 6.
- **FL-13 Fitur Tambahan (prefix ditentukan kemudian).** Fitur lain berdasarkan evaluasi sekolah setelah rilis awal.

## 11.3 Fitur Fase 2 Baru pada v0.3

Seluruh fitur fingerprint bergantung pada pemeriksaan teknis dan koordinasi (TBD-18 sampai TBD-22). Detail teknis fingerprint sedang dikembangkan dan diuji secara terpisah sebelum diintegrasikan ke SIAKAD.

- **FL-14 Absensi Guru melalui Fingerprint: Pencatatan dan Pemantauan Harian (rencana prefix ABG).** Mengambil data kehadiran guru dari mesin fingerprint dan menampilkannya di SIAKAD secara realtime sesuai kemampuan integrasi perangkat. Tabel fingerprint pada rancangan awal basis data (`siakad_fingerprint_*`, `siakad_presensi_harian`, dan lainnya) ditunda sampai fitur ini dikerjakan.
  - *Alur pencatatan:* guru memindai sidik jari; mesin mencatat identitas dan waktu pindai; data diterima sistem penghubung antara mesin dan aplikasi; SIAKAD mencocokkan identitas fingerprint dengan data guru, memeriksa waktu pencatatan, lalu menyimpan atau menyinkronkan data ke penyimpanan absensi guru.
  - *Alur pemantauan harian:* admin membuka menu Absensi Guru; sistem mengambil data pada tanggal berjalan, menampilkan daftar guru beserta waktu kedatangan, membandingkannya dengan jadwal atau ketentuan jam kerja, dan menampilkan keterangan "tepat waktu" atau "terlambat".
  - *Ketentuan:* jika guru belum memiliki catatan fingerprint, sistem menampilkan bahwa data kehadiran belum tercatat; hal ini tidak otomatis membuktikan guru tidak hadir karena dapat disebabkan masalah perangkat atau sinkronisasi.
- **FL-15 Rekap Absensi Guru Bulanan dan Pencetakan (prefix ABG).** Admin memilih guru serta bulan dan tahun; sistem mengambil seluruh data absensi periode tersebut, mengelompokkan per tanggal, membandingkan waktu datang dengan ketentuan jam kerja, menghitung jumlah kehadiran tepat waktu dan keterlambatan, serta menampilkan total bonus (FL-16). Admin dapat mencetak atau menyimpan dokumen berisi detail absensi, status ketepatan waktu, jumlah keterlambatan, dan total bonus.
- **FL-16 Perhitungan Bonus Berdasarkan Ketepatan Waktu (prefix ABG).** Sistem membaca waktu kedatangan, mengambil batas waktu kedatangan guru (mengikuti jadwal mengajar bagi guru yang mengajar, dengan aturan berbeda untuk staf atau guru yang tidak mengajar), menentukan tepat waktu atau terlambat, memberikan bonus sesuai aturan sekolah, menjumlahkannya per bulan, dan menampilkan kolom keterangan bonus serta total bonus pada bagian bawah rekap. Rumus bonus belum ditentukan (TBD-21).
- **FL-17 Pengaturan Jam Kerja Guru (prefix ABG).** Admin memilih guru; sistem menampilkan jadwal mengajarnya sebagai referensi; admin menentukan jam kerja atau batas kedatangan dan menyimpannya untuk dipakai pada perhitungan status tepat waktu dan keterlambatan. Jadwal mengajar tidak selalu sama dengan jam kerja guru (TBD-20, TBD-22).
- **FL-18 Menu Absensi Saya untuk Guru (prefix ABG).** Guru melihat catatan fingerprint miliknya: waktu kedatangan hari ini beserta status tepat waktu atau terlambat jika aturan tersedia, serta rekap bulanan (detail jam datang, ringkasan kehadiran dan keterlambatan). Guru hanya melihat miliknya sendiri dan tidak dapat mengubah data fingerprint melalui SIAKAD. Informasi absensi pribadi juga ditampilkan pada dashboard guru bila tersedia.
- **FL-19 Status Draft, Published, dan Archived untuk Materi (opsional, prefix MTR).** Menambahkan status pada materi pembelajaran; bukan syarat untuk alur utama pengelolaan materi (MTR-1 sampai MTR-8).
- **FL-20 Ringkasan Nilai dari RKM (prefix baru ditentukan kemudian).** Menampilkan ringkasan nilai dari RKM pada SIAKAD bila waktu memungkinkan; nilai tetap dikelola di RKM.
- **FL-21 Penyempurnaan Laporan Statistik (prefix ditentukan kemudian).** Laporan statistik yang lebih lengkap dari data SIAKAD.
- **FL-22 Notifikasi Informasi Akademik (prefix ditentukan kemudian).** Pemberitahuan informasi akademik kepada pengguna.
- **FL-23 Penanganan Siswa Tidak Naik Kelas (rencana prefix KNK).** Siswa yang tidak naik kelas tetap pada tingkat dan rombel asal untuk periode baru sesuai status kenaikan di RKM (menggantikan KNK-5 pada v0.3). Data RKM yang ada hanya memuat status naik (Y) atau belum ditetapkan (NULL), sehingga alur RKM untuk siswa tidak naik perlu dipahami lebih dahulu (TBD-41).
- **FL-24 Log Audit Perubahan Data Master dan Akun (opsional, rencana prefix LOG).** Mencatat siapa mengubah atau menonaktifkan data guru, siswa, akun, penempatan, dan penugasan beserta nilai sebelum dan sesudah (dapat memakai trigger basis data). Pada MVP hanya catatan aktivitas konten (ADM-11) yang wajib.

*Urutan tahap di atas adalah rencana awal dan dapat berubah berdasarkan prioritas, waktu pengerjaan, dan hasil evaluasi tahap pertama.*

# 12. Pertanyaan Terbuka / TBD

> Tutup butir dengan menulis "TERJAWAB (vX.Y): jawaban" lalu perbarui bab terkait. Butir baru diberi nomor berikutnya (TBD-n).

- **TBD-1** Nama pengembang/penyusun dokumen dan nama kontak klien yang berwenang menyetujui.
- **TBD-2** **TERJAWAB (v0.4):** tabel guru tidak memiliki kolom jabatan; jabatan tidak ditampilkan (DIR-1, DIR-6).
- **TBD-3** *(diperbarui v0.4)* Sebagian terjawab: periode aktif adalah baris `tahun` dengan aktif Y, dan penempatan per tahun (empat digit pertama). Masih perlu dipastikan hanya satu baris aktif dan cara RKM menentukan kelas siswa bila ada penempatan ganda (TBD-40).
- **TBD-4** *(diperbarui v0.4)* Kolom `stat_data` ada pada guru dan siswa. Pada data, siswa bernilai A (1205) dan K (1), guru bernilai A (39) dan M (1). Alumni masih berstatus A sehingga siswa aktif ditentukan dari penempatan pada tahun berjalan. Arti nilai P dan M pada guru serta M pada siswa belum pasti (TBD-38).
- **TBD-5** *(diperbarui v0.3)* Hak akses database yang akan digunakan Laravel: SIAKAD kini memerlukan hak tulis pada tabel master bersama; apakah akun database terpisah (baca saja) dapat disediakan untuk halaman publik dan pembatasan tabel RKM yang tidak boleh ditulis. Pada v0.4: ketersediaan hak akses per tabel di hosting masih perlu dicoba (`SHOW GRANTS` di phpMyAdmin); lingkungan lokal MySQL 8.0 memakai pengguna database terpisah.
- **TBD-6** Struktur direktori dan komponen Laravel yang sudah ada pada proyek.
- **TBD-7** *(diperbarui v0.3)* URL resmi portal CBT, Rapor, dan PPDB, serta alamat aplikasi RKM untuk tautan sidebar.
- **TBD-8** *(diperbarui v0.3)* Konten resmi sekolah (nama, slogan, visi misi, sejarah, alamat, kontak, sambutan, struktur organisasi, organisasi siswa, foto) dan kepastian kontak mana yang boleh dipublikasikan. Konten tersebut tetap statis. Tabel `m_sekolah` RKM berisi alamat, telepon, email, dan situs sekolah; apakah dipakai sebagai sumber kontak pada footer belum diputuskan.
- **TBD-9** *(diperbarui v0.3)* Kebijakan sekolah tentang publikasi nama, kelas, dan foto siswa di direktori, prestasi, dan galeri. Publikasi prestasi dan informasi siswa diperbolehkan berdasarkan arahan sekolah, tetapi tetap perlu mengikuti kebijakan publikasi dan perlindungan data yang berlaku; detail kebijakan belum ditetapkan.
- **TBD-10** *(diperbarui v0.3)* Jadwal ekstrakurikuler dan data prestasi yang sah untuk dipublikasikan.
- **TBD-11** Target performa numerik (waktu muat, ukuran halaman) dan spesifikasi server atau hosting sekolah.
- **TBD-12** *(diperbarui v0.4)* Timeline: demo tugas besar awal Desember 2026 (lokal, sebaiknya juga di hosting); target bersama mitra adalah seluruh sistem di hosting tanpa jadwal pasti. Estimasi biaya (hosting, domain, penyimpanan) belum ada.
- **TBD-13** *(diperbarui v0.3)* Siapa yang bertanggung jawab menyiapkan dan memperbarui konten sebelum fitur pengelolaan konten (ADM) tersedia.
- **TBD-14** **TERJAWAB (v0.3):** halaman login SIAKAD dibangun sebagai bagian SIAKAD (AUT) dan menjadi pekerjaan backend pertama (Urutan 1). Frontend login dan dashboard belum ada.
- **TBD-15** Panduan identitas visual sekolah (logo, palet warna, font) yang akan dituangkan ke konfigurasi tema Tailwind; apakah ada kebutuhan mode gelap. Termasuk desain frontend login dan dashboard yang belum ada.
- **TBD-16** Apakah diperlukan pustaka JavaScript ringan (mis. Alpine.js) atau cukup JavaScript biasa untuk menu seluler, filter, dan galeri/lightbox.
- **TBD-17** Lokasi proses build Tailwind/Vite (di mesin pengembang lalu hasil build diunggah, atau di server sekolah yang harus mendukung Node.js).
- **TBD-18** Merek, tipe, dan mekanisme integrasi mesin fingerprint (SDK, API, atau perangkat lunak perantara). Detail teknis sedang dikembangkan dan diuji terpisah.
- **TBD-19** Ketersediaan pengiriman data fingerprint secara realtime, atau apakah sinkronisasi berlangsung berkala.
- **TBD-20** Aturan jam kerja dan batas keterlambatan guru (status tepat waktu dan terlambat).
- **TBD-21** Rumus bonus guru: besaran bonus, toleransi keterlambatan, aturan izin, sakit, dinas luar, dan hari libur.
- **TBD-22** Aturan untuk guru yang memiliki jadwal mengajar berbeda, serta aturan batas kedatangan untuk staf atau guru yang tidak mengajar (dan apakah staf tercatat pada data master).
- **TBD-23** *(diperbarui v0.4)* Wali kelas masing-masing mengisi `t_naikkelas`. Data hanya bernilai Y atau NULL (tidak pernah N) dan jumlah barisnya jauh lebih kecil daripada jumlah siswa. Masih perlu dipahami alur RKM: untuk tingkat mana baris dibuat, bagaimana RKM memindahkan siswa ke kelas baru, dan arti Y pada kelas 9 (lulus).
- **TBD-24** Kapasitas penyimpanan server untuk materi pembelajaran; apakah rekomendasi 15 MB per file dan format file yang diizinkan sesuai.
- **TBD-25** Penanganan baris duplikat atau bermasalah pada impor Excel siswa: apakah admin harus memperbaiki seluruh file sebelum impor, atau boleh melanjutkan hanya dengan baris yang lolos validasi. Data duplikat tidak boleh menimpa data lama secara otomatis (sudah disepakati).
- **TBD-26** *(diperbarui v0.4)* Siswa memakai NIS sebagai username, guru memakai username lama RKM, dan password awal acak dengan kewajiban ganti saat login pertama (AKN-9, AKN-10). Format username akun admin belum ditentukan.
- **TBD-27** **TERJAWAB (v0.4):** reset password dilakukan admin (AKN-11); tidak ada reset mandiri karena data tidak memuat email. Prosedur darurat jika admin utama lupa password ditentukan pada rancangan arsitektur.
- **TBD-28** **TERJAWAB (v0.4):** soft delete memakai kolom `stat_data` yang sudah ada pada guru dan siswa, tanpa kolom baru. Nilai yang dipakai menunggu TBD-38.
- **TBD-29** **TERJAWAB (v0.4):** pada tahap awal dibuat satu akun admin (admin utama) dengan hak penuh pada tabel master dan tabel SIAKAD, tanpa hak tulis ke tabel nilai RKM. Cakupan admin tambahan ditentukan kemudian.
- **TBD-30** Aturan tanggal pengisian absensi siswa: apakah hanya pada hari sesuai jadwal atau boleh tanggal lampau, dan batas waktu koreksi oleh guru.
- **TBD-31** *(diperbarui v0.4)* Penugasan yang dipakai jadwal, absensi, materi, atau nilai RKM dibatasi (PGS-7). Mekanisme rinci mengubah penugasan (mis. mengganti guru) belum ditentukan.
- **TBD-32** Batas ukuran dan format gambar untuk unggahan konten publik (berita, fasilitas, prestasi, galeri).
- **TBD-33** *(diperbarui v0.4)* Password minimal 8 karakter dan tidak sama dengan username atau NIS (AUT-6). Masa berlaku sesi login dan perilaku logout belum ditentukan.
- **TBD-34** Daftar informasi profil yang boleh ditampilkan pada menu profil dan sebagai "informasi dasar" siswa bagi guru dan wali kelas.
- **TBD-35** *(diperbarui v0.4)* Skema dasar tabel bersama sudah diverifikasi dari schema.sql (Bab 8). Masih perlu diperiksa perilaku RKM pada penulisan data (kode RKM) dan nilai default pada data nyata.
- **TBD-36** Konfirmasi hak wali kelas yang juga guru pengampu: matriks alur menulis "Tidak" untuk wali kelas pada pengelolaan absensi, sedangkan wali kelas memiliki seluruh fitur guru. Asumsi pengembang: wali kelas tetap dapat mengisi absensi pada kelas yang diajarnya, dan di kelas perwalian hanya memantau catatan guru pengampu.
- **TBD-37** Cakupan fitur yang harus selesai pada demo tugas besar (awal Desember 2026): Opsi A (Urutan 1 dan 2 serta data master inti), Opsi B (Opsi A ditambah penugasan, tahun ajaran, wali kelas), atau Opsi C (Opsi B ditambah potongan jadwal dan absensi). Termasuk jadwal target hosting bersama mitra.
- **TBD-38** Makna nilai `stat_data`: guru A, P, M dan siswa A, K, M, L; nilai mana yang dipakai untuk nonaktif. Perlu hasil pencarian `stat_data` pada kode RKM, termasuk apakah RKM memfilter nilai A pada setiap daftar.
- **TBD-39** NIS ganda: NIS 4742, 4846, dan 5119 masing-masing muncul dua kali. Perlu dicek apakah entri ganda siswa yang sama atau dua siswa berbeda sebelum NIS dipakai sebagai username.
- **TBD-40** Penempatan ganda pada `t_kelas_siswa` (banyak pada 2021; tahun 2024 ke atas belum dicek) dan cara RKM memindahkan siswa (mengubah baris atau menambah baris), serta aturan memilih penempatan yang dianggap berlaku.
- **TBD-41** Alur kenaikan kelas RKM (lihat TBD-23) dan penanganan siswa tidak naik kelas yang ditunda (FL-23).
- **TBD-42** Daftar lengkap tabel `m_kelas` (sekitar 20 baris) untuk menyusun aturan pemetaan kenaikan kelas dengan pola nama seperti VII-1 (Fullday).
- **TBD-43** Data periode: periode berjalan adalah 20261, sedangkan tabel `tahun` pada salinan menandai 20242 aktif dan tidak memuat 20251 sampai 20261. Perlu dipastikan apakah salinan database tertinggal atau RKM belum diperbarui, serta kondisi penempatan tahun 2025 dan 2026.
- **TBD-44** Susunan host. Usulan: domain utama untuk website publik dan subdomain `siakad` untuk login dan seluruh panel, dengan `rkm` tetap seperti sekarang. Perlu dicek versi PHP subdomain RKM, arah document root, dan isi domain utama saat ini.
- **TBD-45** Kolom wajib data siswa: RKM tidak menetapkan kolom wajib spesifik. Usulan: hanya NIS dan nama wajib, kolom lain boleh kosong. Perlu diputuskan juga nilai untuk kolom tanggal kosong (RKM menyimpan `0000-00-00`; Laravel memerlukan mode SQL yang sesuai atau nilai pengganti).

# 13. Glosarium

- **SIAKAD :** Sistem Informasi Akademik; sistem baru yang dikembangkan dengan Laravel.
- **RKM :** sistem akademik yang sudah berjalan (CodeIgniter 3) untuk penilaian dan rapor; berbagi database dengan SIAKAD.
- **Fitur Publik :** bagian SIAKAD yang dapat diakses tanpa login.
- **CBT :** Computer Based Test; portal ujian berbasis komputer.
- **Rapor :** portal laporan hasil belajar siswa.
- **PPDB :** Penerimaan Peserta Didik Baru.
- **CRUD :** Create, Read, Update, Delete; operasi menambah, melihat, mengubah, dan menghapus data.
- **Data Dummy :** data contoh sementara yang bukan data resmi dan harus diberi komentar penanda.
- **Direktori :** daftar publik guru dan siswa dengan informasi terbatas.
- **Slug :** bagian URL yang mudah dibaca untuk berita atau pengumuman.
- **Pagination :** pembagian daftar data menjadi beberapa halaman.
- **Lazy Loading :** gambar baru dimuat saat mendekati area layar.
- **Thumbnail :** gambar kecil untuk daftar atau galeri.
- **Mobile First :** pendekatan desain yang memprioritaskan tampilan ponsel.
- **MVP :** Minimum Viable Product; cakupan minimal yang harus ada pada rilis awal.
- **Tailwind CSS :** kerangka CSS berbasis kelas utilitas; pada produksi hanya kelas yang dipakai yang disertakan ke berkas CSS akhir.
- **Vite :** alat build frontend bawaan Laravel yang menggabungkan dan meminimasi CSS dan JavaScript.
- **Minifikasi :** pemampatan berkas CSS atau JavaScript dengan menghapus karakter yang tidak diperlukan agar ukuran lebih kecil.
- **Eager Loading :** teknik memuat data relasi sekaligus agar tidak terjadi query berulang.
- **Role :** peran pengguna yang menentukan hak akses (Admin, Guru, Siswa; wali kelas diturunkan dari penetapan).
- **Admin Utama :** admin dengan izin tambahan untuk mengelola akun admin lain; bukan role kelima.
- **Wali Kelas :** guru yang ditetapkan memantau satu kelas pada satu tahun ajaran; memakai akun dan dashboard guru yang sama.
- **Rombel :** rombongan belajar; kelompok siswa dalam satu kelas (mis. VII-1 (Fullday), VII-4 (Reguler)).
- **Penempatan Siswa :** penetapan kelas yang ditempati siswa pada satu tahun ajaran.
- **Penugasan Guru :** penetapan guru untuk mengajar mata pelajaran tertentu pada kelas dan tahun ajaran tertentu.
- **Tahun Ajaran / Periode (`tasm`) :** kode lima karakter berisi tahun ajaran dan semester; 20242 berarti tahun ajaran 2024/2025 semester 2. Pada menu SIAKAD disebut Tahun Ajaran.
- **Kenaikan Kelas :** keputusan naik atau tidak naik kelas yang ditetapkan di RKM dan dibaca SIAKAD untuk penempatan tahun ajaran baru.
- **Pertemuan (Sesi Pertemuan) :** satu kali pertemuan mata pelajaran pada jadwal dan tanggal tertentu; dasar pencatatan absensi siswa.
- **Rekap Absensi Rapor :** rekap sakit, izin, dan tanpa keterangan untuk rapor yang diisi manual oleh guru di RKM.
- **Soft Delete :** penghapusan logis; data ditandai terhapus tetapi tetap tersimpan sehingga riwayat tidak rusak.
- **stat_data :** kolom status pada tabel guru (A, P, M) dan siswa (A, K, M, L) RKM yang dipakai sebagai penanda nonaktif.
- **View :** objek basis data berupa query tersimpan yang menyajikan kolom terpilih dari satu atau beberapa tabel, dipakai antara lain untuk data publik terbatas.
- **Trigger :** objek basis data yang berjalan otomatis saat data berubah, dipakai untuk audit dan validasi aturan.
- **Stored Procedure :** rangkaian perintah SQL tersimpan di basis data, dipakai untuk operasi multi-langkah dalam satu transaksi.
- **Privilege :** hak akses pengguna database (mis. SELECT, INSERT, UPDATE) pada tabel atau objek tertentu.
- **Impor Excel :** pengunggahan banyak data siswa melalui template Excel dengan validasi sebelum penyimpanan.
- **Transaksi Database :** mekanisme yang membatalkan seluruh proses penyimpanan bila salah satu langkahnya gagal.
- **Hash :** bentuk password yang telah diacak satu arah sehingga tidak dapat dibaca kembali.
- **Status Konten :** Draft (hanya terlihat admin), Published (tampil di website publik), Archived (tidak tampil sebagai konten aktif).
- **Fingerprint :** mesin pemindai sidik jari yang dipakai untuk mencatat kehadiran guru (Fase 2).

---

# Lampiran A. Panduan Memperbarui PRD

Dokumen ini dirancang agar dapat direvisi bertahap. Ikuti langkah berikut setiap kali ada perubahan.

**1. Aturan versi**

| **Jenis perubahan** | **Contoh** | **Penomoran** |
| --- | --- | --- |
| Penyempurnaan kecil (typo, penjelasan, jawaban TBD) | Jawaban URL portal | v0.1 → v0.2 |
| Fitur baru atau perubahan prioritas | Menambah modul baru | Naik minor (v0.2 → v0.3) |
| Persetujuan klien atas seluruh isi | Status FINAL | v1.0 |
| Perubahan besar setelah v1.0 | Fase baru | v1.1, v2.0 |

Status dokumen berubah dari **DRAFT SEMENTARA** menjadi **FINAL** hanya setelah seluruh TBD di Bab 12 yang bersifat wajib terjawab dan klien menyetujui.

**2. Menambah fitur baru**

1. Catat fitur di Bab 11 dengan ID **FL-n** berikutnya (setelah FL-24), tahap, dan rencana prefix.
2. Saat fitur dijadwalkan, pindahkan ke Bab 6: buat baris dengan ID berikutnya (mis. BRT-5) atau sub-bab dan prefix baru bila modulnya baru (mis. ABG-1). **Jangan pakai ulang ID lama.**
3. Perbarui bab terkait: Bab 4 (Ruang Lingkup dan Urutan Pengerjaan), Bab 7 (alur baru bila ada), Bab 8 (entitas atau field; hapus tanda kurung siku jika field resmi masuk MVP), Bab 9, dan Bab 10.
4. Tambahkan pertanyaan baru ke Bab 12 (TBD-n berikutnya, setelah TBD-45) dan tutup yang sudah terjawab.
5. Isi Riwayat Revisi (versi, tanggal, ringkasan, bab terdampak).

**3. Mengubah atau menghapus kebutuhan**

- Ubah teks langsung dan catat di Riwayat Revisi.
- Jika dibatalkan, coret teks (~~seperti ini~~) dan tulis alasannya di Riwayat Revisi. ID tetap tercatat.
- Jika ditunda, pindahkan ke Bab 11 dengan FL-n baru dan beri rujukan ke ID lama.

**4. Tabel penanda prefix ID**

| **Prefix** | **Modul** | **Status** |
| --- | --- | --- |
| NAV | Navbar dan Footer | Aktif (v0.1) |
| BRD | Beranda | Aktif (v0.1) |
| PRF | Profil Sekolah | Aktif (v0.1) |
| DIR | Direktori | Aktif (v0.1) |
| KSW | Kesiswaan | Aktif (v0.1) |
| GAL | Galeri | Aktif (v0.1) |
| BRT | Berita | Aktif (v0.1) |
| PGM | Pengumuman | Aktif (v0.1) |
| AUT | Autentikasi dan Profil | Aktif (v0.3) |
| AKN | Manajemen Akun | Aktif (v0.3) |
| DSH | Dashboard | Aktif (v0.3) |
| GRU | Data Guru | Aktif (v0.3) |
| SIS | Data Siswa | Aktif (v0.3) |
| KLS | Data Kelas | Aktif (v0.3) |
| PNS | Penempatan Siswa | Aktif (v0.3) |
| PWK | Penetapan Wali Kelas | Aktif (v0.3) |
| MPL | Mata Pelajaran | Aktif (v0.3) |
| PGS | Penugasan Guru | Aktif (v0.3) |
| TAJ | Tahun Ajaran | Aktif (v0.3) |
| KNK | Kenaikan Kelas dan Penempatan Tahun Ajaran Baru | Aktif (v0.3) |
| EKS | Ekstrakurikuler | Aktif (v0.3) |
| JDW | Jadwal Pelajaran | Aktif (v0.3) |
| ADM | Pengelolaan Konten Publik | Aktif (v0.3) |
| PAS | Pemantauan Absensi Siswa (Admin) | Aktif (v0.3) |
| GSW | Data Siswa untuk Guru | Aktif (v0.3) |
| ABS | Absensi Siswa per Pertemuan | Aktif (v0.3) |
| MTR | Materi Pembelajaran | Aktif (v0.3) |
| RKM | Akses RKM | Aktif (v0.3) |
| WKL | Fitur Wali Kelas | Aktif (v0.3) |
| SSW | Fitur Siswa (Jadwal, Materi, Absensi) | Aktif (v0.3) |
| ABG | Absensi Guru Fingerprint | Direncanakan (Fase 2) |
| DAT | Data dan Sinkronisasi RKM Lanjutan | Direncanakan (FL-12) |
| LOG | Log Audit Perubahan Data | Direncanakan (FL-24, opsional) |
| [baru] | Fitur Tambahan | Direncanakan (FL-13, FL-20 sampai FL-22) |

**5. Daftar periksa sebelum menerbitkan revisi**

- [ ] Semua ID unik dan tidak ada yang dipakai ulang.
- [ ] Prioritas hanya Wajib, Penting, atau Fase 2 (Fase 2 ditempatkan di Bab 11).
- [ ] Bab 4, 6, 8, 11, dan 12 saling konsisten.
- [ ] Tidak ada asumsi yang ditulis seolah-olah sudah disepakati.
- [ ] Riwayat Revisi, versi, dan tanggal pada header telah diperbarui.

---

*Dokumen ini merupakan draft sementara dan dapat berubah seiring pembahasan lebih lanjut dengan klien.*
