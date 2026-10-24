# PRODUCT REQUIREMENTS DOCUMENT (PRD)

## SIAKAD SMP Muhammadiyah 7 Medan — Fitur Publik Tahap Pertama

**STATUS: DRAFT SEMENTARA**

| | |
| --- | --- |
| **Nama Produk** | SIAKAD SMP Muhammadiyah 7 Medan (Modul Fitur Publik / Website Informasi Sekolah) |
| **Versi Dokumen** | v0.2 (Draft Sementara) |
| **Disusun oleh** | [TBD — nama pengembang, lihat Bab 12] (Pengembang) |
| **Untuk** | SMP Muhammadiyah 7 Medan (Klien) |
| **Tanggal** | 22 Oktober 2026 |
| **Dokumen Terkait** | Rancangan Fitur Publik SIAKAD SMP Muhammadiyah 7 Medan (RancanganWebSMPM7.md) — rancangan awal, belum permanen |

## Riwayat Revisi

> Setiap perubahan pada dokumen ini wajib dicatat di sini. Cara memperbarui dokumen dijelaskan pada **Lampiran A**.

| **Versi** | **Tanggal** | **Penyusun** | **Ringkasan Perubahan** | **Bab Terdampak** |
| --- | --- | --- | --- | --- |
| v0.1 | 22 Okt 2026 | [TBD] | Draf pertama, disusun dari Rancangan Fitur Publik SIAKAD SMP Muhammadiyah 7 Medan. | Semua |
| v0.2 | 22 Okt 2026 | [TBD] | Frontend ditetapkan menggunakan Tailwind CSS dengan syarat website tetap ringan dan optimal. Menambah NAV-7, aturan Tailwind dan batas aset pada Bab 9, TBD-15 sampai TBD-17, dan istilah glosarium. | 1, 4.1, 5, 6.1, 9, 12, 13 |

---

# 1. Ringkasan Produk (Overview)

SMP Muhammadiyah 7 Medan sedang mengembangkan SIAKAD (Sistem Informasi Akademik) baru, sementara sistem yang sudah berjalan, yaitu RKM (dibangun dengan CodeIgniter 3), tetap digunakan untuk data akademik seperti guru, siswa, kelas, mata pelajaran, dan rapor. Saat ini sekolah belum memiliki satu pintu informasi publik yang terpadu. Informasi profil sekolah, berita, pengumuman, dan kegiatan siswa belum terpusat, dan akses ke portal sekolah lain (CBT, Rapor, PPDB) belum tersedia dari satu halaman.

Solusi yang akan dibangun pada tahap pertama adalah **fitur publik SIAKAD**: website informasi sekolah berbasis Laravel dengan antarmuka Tailwind CSS yang dapat diakses masyarakat tanpa login. Cakupannya meliputi beranda, profil sekolah, direktori guru dan siswa (membaca database RKM yang sama), kesiswaan, galeri, berita, pengumuman, serta pintasan ke portal CBT, Rapor, dan PPDB dan ke halaman login SIAKAD. Konten yang belum memiliki sumber data final memakai data dummy yang diberi penanda. Tujuan besarnya adalah menjadi pusat informasi sekolah yang ringan, responsif, dan mudah dikembangkan bertahap ke pengelolaan konten berbasis database pada tahap susulan.

# 2. Tujuan & Sasaran (Goals)

- Memusatkan informasi publik SMP Muhammadiyah 7 Medan dalam satu website yang konsisten dan mudah dinavigasi.
- Menjadi pintu masuk tunggal menuju portal CBT, Rapor, PPDB, dan login SIAKAD.
- Menyediakan direktori guru dan siswa yang bersumber dari data RKM tanpa membuka data pribadi.
- Memberikan transparansi informasi kegiatan, organisasi, prestasi, berita, dan pengumuman sekolah kepada orang tua, calon siswa, dan masyarakat.
- Menghadirkan website yang ringan dan responsif di komputer maupun perangkat seluler, termasuk pada koneksi lambat.
- Menyiapkan fondasi teknis (data dummy terpisah dari tampilan, komponen bersama) agar penambahan fitur dan peralihan ke database pada tahap susulan tidak membutuhkan pembangunan ulang halaman.

# 3. Pengguna & Peran (Users & Roles)

- **Pengunjung Publik :** orang tua, calon siswa, alumni, dan masyarakat umum. Mengakses halaman publik tanpa login (hanya membaca), mencari direktori, dan menuju portal lain.
- **Admin Sekolah (Fase Lanjutan) :** mengelola konten publik (berita, pengumuman, fasilitas, organisasi, ekstrakurikuler, prestasi, galeri) melalui dashboard dengan status draf, dipublikasikan, dan diarsipkan. Belum tersedia pada tahap pertama.
- **Pengembang :** mengganti data dummy dengan data resmi, memverifikasi skema RKM, dan menjaga agar penggunaan database bersama tidak mengganggu RKM.

# 4. Ruang Lingkup (Scope)

## 4.1 Termasuk (MVP)

- Komponen bersama navbar dan footer untuk seluruh halaman publik.
- Beranda: hero, tiga pintasan portal (CBT, Rapor, PPDB), sambutan kepala sekolah, berita terbaru, pengumuman terbaru, dan statistik dummy.
- Profil sekolah: tentang sekolah, kepala sekolah, struktur organisasi, serta sarana dan prasarana (data statis/dummy).
- Direktori guru dan direktori siswa dari database RKM, dengan pencarian dan filter.
- Kesiswaan: organisasi siswa, ekstrakurikuler, dan prestasi siswa (data dummy).
- Galeri (data dummy).
- Halaman daftar dan detail berita serta pengumuman (data dummy).
- Antarmuka frontend berbasis Tailwind CSS dengan komponen UI bersama.
- Optimasi performa dan tampilan responsif (Bab 9).
- Seluruh data dummy diberi komentar penanda dan dipisahkan dari logika tampilan.

## 4.2 Di Luar Lingkup Awal / Fase Lanjutan

Belum termasuk tahap pertama: seluruh CRUD (berita, pengumuman, fasilitas, organisasi siswa, ekstrakurikuler, prestasi, galeri), dashboard pengelolaan konten, status konten, penyimpanan konten dalam database, sinkronisasi atau pembaruan data lanjutan dari RKM, dan fitur tambahan lain sesuai kebutuhan sekolah. Rincian dan rencana tahapnya ada pada Bab 11.

# 5. Asumsi & Batasan (Assumptions & Constraints)

- **Teknologi (sudah ditetapkan):** SIAKAD baru menggunakan Laravel; RKM menggunakan CodeIgniter 3.
- **Frontend (sudah ditetapkan):** antarmuka website publik dibangun dengan Tailwind CSS. Syarat utamanya: website tetap optimal dan tidak berat (aturan teknis pada Bab 9).
- **Database bersama (sudah ditetapkan):** SIAKAD dan RKM memakai satu database yang sama. API tidak diperlukan hanya untuk membaca tabel yang sama, tetapi tiap aplikasi tetap memiliki aturan akses dan logikanya sendiri.
- **Batasan keamanan data:** tabel nilai, rapor, dan tabel inti RKM tidak boleh diubah tanpa audit dan persetujuan. Laravel memakai hak akses database secukupnya (idealnya hanya baca untuk tahap pertama).
- **Tanpa perubahan skema RKM:** tahap pertama tidak menambah kolom atau tabel di RKM. Jika jabatan guru tidak tersedia, hanya informasi yang benar-benar ada yang ditampilkan.
- **Data dummy sementara:** konten selain direktori memakai data dummy. Data dummy tidak boleh tampil seolah-olah data resmi (khususnya statistik dan prestasi).
- **Tautan portal:** URL CBT, Rapor, dan PPDB memakai placeholder dan dibuka pada tab yang sama.
- **Asumsi pengembang:** nama tabel RKM yang diketahui adalah `m_guru`, `m_siswa`, `m_mapel`, `m_kelas`, `t_guru_mapel`, `t_kelas_siswa`, dan tabel terkait tahun ajaran. Nama kolom dan aturan tahun ajaran aktif harus diverifikasi sebelum query dibuat.
- **Asumsi pengembang:** pendekatan desain mengutamakan tampilan seluler (Mobile First) dan halaman dirender di sisi server melalui Laravel Blade.
- **Asumsi pengembang:** Tailwind CSS dipasang sebagai dependensi proyek dan dikompilasi melalui proses build (Vite bawaan Laravel), bukan dimuat dari CDN pada produksi, sehingga hanya kelas yang dipakai yang masuk ke berkas CSS akhir.
- **Asumsi pengembang:** tidak memakai pustaka UI atau JavaScript berat (mis. Bootstrap, jQuery, pustaka slider atau galeri besar); interaktivitas dibuat seminimal mungkin. Pilihan pustaka JavaScript ringan, bila diperlukan, dicatat pada Bab 12 (TBD-16).
- **Batasan:** proses build memerlukan Node.js pada lingkungan pengembangan atau penerapan; lokasi proses build ditentukan pada TBD-17.
- **Asumsi pengembang:** perubahan rencana atau penambahan fitur akan dicatat melalui revisi dokumen ini (Lampiran A).
- **Biaya:** belum dibahas; potensi biaya hosting, domain, dan penyimpanan gambar dicatat pada Bab 12.

# 6. Kebutuhan Fungsional (Functional Requirements)

> Catatan pembaruan: ID tidak boleh dipakai ulang. Kebutuhan baru diberi nomor berikutnya pada modul terkait, atau modul baru dengan prefix baru (lihat Lampiran A). Kebutuhan yang dihapus ditandai ~~dicoret~~ dan dicatat di Riwayat Revisi.

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
| **BRD-3** | Sistem menampilkan ringkasan sambutan kepala sekolah (nama, jabatan, ringkasan, tombol ke halaman kepala sekolah) dari sumber data yang sama dengan PRF-2. | **Wajib** |
| **BRD-4** | Sistem menampilkan beberapa berita terbaru (judul, gambar, ringkasan, tanggal, tautan detail). | **Wajib** |
| **BRD-5** | Sistem menampilkan beberapa pengumuman terbaru (judul, ringkasan, tanggal, tautan detail). | **Wajib** |
| **BRD-6** | Sistem menampilkan statistik jumlah siswa, guru, dan kelas menggunakan angka dummy yang ditandai belum bersumber dari database. | **Wajib** |
| **BRD-7** | Seluruh data dummy beranda diletakkan terpisah dari logika tampilan dengan komentar penanda lokasi penggantian. | **Wajib** |

## 6.3 Pengunjung — Profil Sekolah

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PRF-1** | Pengunjung melihat halaman Tentang Sekolah berisi nama, alamat, deskripsi, serta visi dan misi (data statis). | **Wajib** |
| **PRF-2** | Pengunjung melihat halaman Kepala Sekolah berisi nama, jabatan, foto, dan sambutan lengkap (data statis). | **Wajib** |
| **PRF-3** | Pengunjung melihat struktur organisasi sekolah (nama jabatan dan nama pejabat) dalam bentuk daftar atau bagan sederhana. | **Wajib** |
| **PRF-4** | Struktur organisasi dapat menampilkan foto dan keterangan tugas jika datanya tersedia. | **Penting** |
| **PRF-5** | Pengunjung melihat daftar sarana dan prasarana (nama fasilitas, foto, deskripsi singkat) menggunakan data dummy. | **Wajib** |
| **PRF-6** | Halaman Tentang Sekolah dapat memuat sejarah sekolah dan kontak resmi jika telah disetujui untuk dipublikasikan. | **Penting** |
| **PRF-7** | Data profil dapat diganti tanpa mengubah struktur layout halaman. | **Wajib** |

## 6.4 Pengunjung — Direktori Guru dan Siswa

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **DIR-1** | Pengunjung melihat direktori guru berisi nama guru, serta jabatan dan mata pelajaran yang diampu hanya jika tersedia di sumber data RKM. | **Wajib** |
| **DIR-2** | Pengunjung melihat direktori siswa berisi nama siswa dan kelas aktif, dari siswa yang terdaftar pada kelas atau tahun ajaran aktif di RKM. | **Wajib** |
| **DIR-3** | Pengunjung mencari guru atau siswa berdasarkan nama. | **Wajib** |
| **DIR-4** | Pengunjung memfilter direktori siswa berdasarkan kelas. | **Wajib** |
| **DIR-5** | Pengunjung memfilter direktori guru berdasarkan mata pelajaran jika relasi penugasan mendukung. | **Penting** |
| **DIR-6** | Pengunjung memfilter direktori guru berdasarkan jabatan hanya jika sumber data menyediakan jabatan. | **Penting** |
| **DIR-7** | Sistem mengurutkan direktori berdasarkan nama. | **Wajib** |
| **DIR-8** | Sistem tidak menampilkan data sensitif: NIP, NIK, NIS, NISN, tanggal lahir, alamat, nomor telepon pribadi, data orang tua, nilai, absensi, maupun foto guru dan siswa. | **Wajib** |
| **DIR-9** | Sistem tidak menampilkan guru nonaktif sebagai guru aktif jika status aktif tersedia dan telah diverifikasi. | **Penting** |
| **DIR-10** | Sistem membatasi jumlah data per halaman (pagination) dan hanya mengambil kolom yang diperlukan. | **Penting** |

## 6.5 Pengunjung — Kesiswaan

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **KSW-1** | Pengunjung melihat daftar organisasi siswa (nama, logo/gambar, deskripsi, kegiatan utama) dari data dummy. | **Wajib** |
| **KSW-2** | Pengunjung melihat daftar ekstrakurikuler (nama, gambar, deskripsi) dari data dummy. | **Wajib** |
| **KSW-3** | Pengunjung melihat prestasi siswa (nama prestasi, jenis, tingkat, tahun, deskripsi) dari data dummy yang jelas ditandai sebagai contoh, bukan pencapaian resmi. | **Wajib** |
| **KSW-4** | Ekstrakurikuler menampilkan jadwal hanya jika sudah dikonfirmasi sekolah. | **Penting** |
| **KSW-5** | Prestasi menampilkan nama siswa atau tim dan foto dokumentasi hanya jika diizinkan untuk dipublikasikan. | **Penting** |

## 6.6 Pengunjung — Galeri

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **GAL-1** | Pengunjung melihat galeri berisi judul, foto, dan keterangan (gambar dummy atau dokumentasi yang telah disetujui). | **Wajib** |
| **GAL-2** | Sistem memakai thumbnail dan lazy loading serta tidak memuat seluruh gambar beresolusi tinggi sekaligus. | **Wajib** |
| **GAL-3** | Galeri dapat menampilkan tanggal kegiatan dan kategori jika tersedia. | **Penting** |

## 6.7 Pengunjung — Berita

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **BRT-1** | Pengunjung melihat halaman daftar berita (judul, gambar utama, ringkasan, tanggal, tautan detail). | **Wajib** |
| **BRT-2** | Pengunjung melihat halaman detail berita (judul, gambar utama, tanggal, isi) dengan navigasi kembali ke daftar. | **Wajib** |
| **BRT-3** | Data berita dummy memakai struktur konsisten (judul, slug, ringkasan, isi, gambar, tanggal) agar mudah dialihkan ke database. | **Wajib** |

## 6.8 Pengunjung — Pengumuman

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PGM-1** | Pengunjung melihat halaman daftar pengumuman (judul, ringkasan, tanggal, tautan detail). | **Wajib** |
| **PGM-2** | Pengunjung melihat halaman detail pengumuman (judul, tanggal, isi) dengan navigasi kembali ke daftar. | **Wajib** |
| **PGM-3** | Halaman pengumuman terpisah dari berita dan tidak menerapkan tanggal mulai atau tanggal berakhir pada tahap ini. | **Wajib** |

# 7. Alur Pengguna Utama (Key User Flows)

## 7.1 Pengunjung Menjelajahi Beranda dan Menuju Portal Lain

1. Pengunjung membuka website sekolah tanpa login.
2. Sistem menampilkan beranda: hero, tiga ikon portal, sambutan kepala sekolah, berita, pengumuman, dan statistik.
3. Pengunjung mengetuk ikon CBT, Rapor, atau PPDB.
4. Sistem membuka portal tujuan pada tab yang sama.
5. *Pengecualian:* jika URL resmi belum tersedia, ikon menuju tautan placeholder yang jelas, bukan halaman yang tidak berkaitan.

## 7.2 Pengunjung Membaca Berita atau Pengumuman

1. Pengunjung memilih berita atau pengumuman dari beranda atau menu Berita/Pengumuman.
2. Sistem menampilkan halaman daftar.
3. Pengunjung memilih satu item.
4. Sistem menampilkan halaman detail.
5. Pengunjung memilih navigasi kembali; sistem menampilkan daftar semula.

## 7.3 Pengunjung Mencari Guru atau Siswa di Direktori

1. Pengunjung membuka menu Direktori dan memilih Guru atau Siswa.
2. Sistem membaca data aktif dari RKM dan menampilkan daftar terurut berdasarkan nama.
3. Pengunjung mengetik nama dan/atau memilih filter (kelas untuk siswa; mata pelajaran untuk guru bila tersedia).
4. Sistem menampilkan hasil yang cocok.
5. *Pengecualian:* jika tidak ada hasil, sistem menampilkan pesan "data tidak ditemukan". Jika data jabatan atau mata pelajaran tidak tersedia, kolom tersebut tidak ditampilkan.

## 7.4 Pengunjung Menuju Login SIAKAD

1. Pengunjung memilih tombol Login SIAKAD pada navbar.
2. Sistem mengarahkan ke halaman login SIAKAD.
3. Proses autentikasi selanjutnya berada di luar cakupan PRD ini.

## 7.5 Pengembang Mengganti Data Dummy dengan Data Resmi

1. Pengembang menerima konten atau URL resmi dari sekolah.
2. Pengembang mencari komentar penanda pada berkas data dummy yang terkait.
3. Pengembang mengganti isi data tanpa mengubah layout halaman.
4. Pengembang mencatat penggantian pada Riwayat Revisi dan menutup butir terkait di Bab 12.
5. Pada tahap susulan, sumber data dialihkan dari berkas statis ke database melalui struktur data yang sama.

# 8. Model Data (High-Level)

| **Entitas** | **Field Utama** | **Keterangan** |
| --- | --- | --- |
| **Guru (`m_guru`, RKM)** | nama, [kolom jabatan: TBD], [kolom status_aktif: TBD] | Dibaca saja dari RKM. Kolom aktual harus diverifikasi. Data sensitif tidak diambil. |
| **Penugasan Guru (`t_guru_mapel`, RKM)** | guru, mapel | Relasi guru dan mata pelajaran untuk tampilan dan filter. |
| **Mata Pelajaran (`m_mapel`, RKM)** | nama_mapel | Dibaca saja dari RKM. |
| **Siswa (`m_siswa`, RKM)** | nama, [kolom status_aktif: TBD] | Dibaca saja dari RKM. NIS, NISN, dan data pribadi tidak diambil. |
| **Kelas (`m_kelas`, RKM)** | nama_kelas | Dibaca saja dari RKM. |
| **Kelas Siswa (`t_kelas_siswa`, RKM)** | siswa, kelas, tahun_ajaran | Menentukan kelas aktif siswa. Aturan tahun ajaran aktif mengikuti RKM setelah diverifikasi. |
| **Tahun Ajaran (RKM)** | nama tabel dan kolom: TBD | Menentukan tahun ajaran aktif. |
| **Profil Sekolah (statis)** | nama, alamat, deskripsi, visi, misi, [sejarah], [kontak] | Data statis di kode/konfigurasi. |
| **Kepala Sekolah (statis)** | nama, jabatan, foto, sambutan | Satu sumber untuk beranda dan halaman kepala sekolah. |
| **Struktur Organisasi (statis)** | jabatan, nama_pejabat, [foto], [tugas] | Data statis. |
| **Fasilitas (dummy)** | nama, foto, deskripsi | Data dummy; [tabel database di Fase Lanjutan]. |
| **Organisasi Siswa (dummy)** | nama, logo, deskripsi, kegiatan_utama | Data dummy; [tabel database di Fase Lanjutan]. |
| **Ekstrakurikuler (dummy)** | nama, gambar, deskripsi, [jadwal] | Data dummy; [tabel database di Fase Lanjutan]. |
| **Prestasi (dummy)** | nama_prestasi, jenis, tingkat, tahun, deskripsi, [nama_siswa_tim], [foto] | Data dummy yang ditandai contoh. |
| **Galeri (dummy)** | judul, foto, keterangan, [tanggal], [kategori] | Data dummy atau dokumentasi yang telah disetujui. |
| **Berita (dummy)** | judul, slug, ringkasan, isi, gambar, tanggal, [status] | Struktur dijaga konsisten agar mudah dialihkan ke database. |
| **Pengumuman (dummy)** | judul, slug, ringkasan, isi, tanggal, [status] | Terpisah dari berita. |
| **Tautan Portal (konfigurasi)** | nama, ikon, url | Tiga entri: CBT, Rapor, PPDB (URL placeholder). |
| **Pengguna Admin** | [id, nama, role] | Hanya Fase Lanjutan (pengelolaan konten dan pembatasan akses). |

**Catatan:** field dalam [tanda kurung siku] merupakan bagian dari fitur usulan/Fase Lanjutan (Bab 11) atau masih perlu diverifikasi (TBD). Entitas bertanda "dummy" belum memiliki tabel di database pada tahap pertama.

# 9. Kebutuhan Non-Fungsional (Non-Functional Requirements)

- **Responsivitas :** halaman dapat digunakan di komputer dan perangkat seluler dengan pendekatan Mobile First; navigasi tidak menutupi konten di layar kecil.
- **Performa :** halaman ringan; query hanya mengambil kolom yang dibutuhkan, memakai pagination, menghindari query berulang per item, dan memakai eager loading bila perlu; indeks kolom pencarian dan filter diperiksa sebelum perubahan skema.
- **Media :** gambar dikompresi, berukuran tetap agar layout tidak bergeser, memakai thumbnail serta lazy loading, dan tanpa autoplay video di beranda.
- **Frontend :** komponen bersama dipakai ulang; JavaScript, CSS, dan pustaka tambahan hanya jika diperlukan; tanpa efek visual berat.
- **Tailwind CSS (build produksi) :** CSS dikompilasi dan diminimasi lewat proses build; jalur berkas yang dipindai Tailwind dikonfigurasi lengkap (semua templat Blade dan komponen) agar kelas yang tidak dipakai tidak ikut terbawa; Tailwind tidak dimuat dari CDN pada produksi; nama kelas tidak disusun dinamis dengan penggabungan teks karena tidak terdeteksi saat build.
- **Konsistensi desain :** warna, ukuran huruf, jarak, dan breakpoint didefinisikan sekali pada konfigurasi tema Tailwind; pengulangan gaya diselesaikan dengan komponen Blade, bukan banyak aturan `@apply` atau CSS kustom.
- **Aset frontend :** satu berkas CSS dan satu berkas JavaScript kecil per halaman; JavaScript dimuat dengan `defer` dan hanya untuk kebutuhan nyata (mis. buka-tutup menu seluler); ikon berupa SVG sebaris atau sprite SVG (bukan pustaka ikon font); berkas aset diberi versi (hash) dan di-cache oleh peramban; kompresi gzip atau brotli diaktifkan di server jika memungkinkan.
- **Tipografi :** memakai font sistem atau maksimal satu keluarga font yang dihosting sendiri dengan jumlah bobot terbatas; tidak memuat font dari layanan eksternal.
- **Kinerja tampilan :** efek animasi dan bayangan berat dihindari; layout tidak bergeser saat memuat (ukuran gambar ditetapkan); tidak ada gambar latar besar yang tidak dioptimasi.
- **Keamanan dan hak akses :** halaman publik tidak memiliki CRUD; hak akses database Laravel dibatasi secukupnya; tabel inti RKM tidak diubah tanpa audit dan persetujuan.
- **Privasi data :** hanya nama (dan jabatan/mata pelajaran guru, kelas siswa) yang ditampilkan; publikasi nama, kelas, dan foto siswa mengikuti persetujuan dan kebijakan sekolah.
- **Integritas data :** data dummy tidak ditampilkan sebagai data resmi dan harus ditandai; data direktori tidak boleh mengarang jabatan atau mata pelajaran.
- **Kompatibilitas RKM :** penggunaan database bersama tidak boleh merusak fungsi RKM; query diuji dengan data nyata sebelum dihubungkan ke halaman publik.
- **Kemudahan pemeliharaan :** data dummy dipisahkan dari logika tampilan dan diberi komentar penanda; struktur data konsisten agar peralihan ke database mudah.
- **Pengujian performa :** diperiksa waktu muat beranda, kecepatan daftar guru/siswa, performa pencarian dan filter, ukuran aset, tampilan pada koneksi lambat dan seluler, serta jumlah query beranda. Pengujian juga mencakup ukuran berkas CSS dan JavaScript hasil build serta memastikan tidak ada kelas Tailwind yang hilang pada build produksi. Target numerik ditetapkan setelah uji awal (lihat Bab 12).

# 10. Integrasi Pihak Ketiga

| **Layanan** | **Fungsi** | **Catatan** |
| --- | --- | --- |
| **RKM (CodeIgniter 3, database bersama)** | Sumber data guru, siswa, kelas, mata pelajaran, dan tahun ajaran untuk direktori. | Hanya baca pada tahap pertama; skema dan aturan tahun ajaran aktif harus diverifikasi. Bukan layanan pihak ketiga murni, tetapi sistem terpisah yang berbagi database. |
| **Portal CBT** | Tautan pintasan dari beranda. | URL placeholder; menunggu URL resmi. |
| **Portal Rapor** | Tautan pintasan dari beranda. | URL placeholder; menunggu URL resmi. |
| **Portal PPDB** | Tautan pintasan dari beranda. | URL placeholder; menunggu URL resmi. |
| **Halaman Login SIAKAD** | Tombol Login SIAKAD pada navbar. | Autentikasi di luar cakupan PRD ini. |
| **Sinkronisasi data RKM lanjutan** | Pembaruan data guru dan siswa bila diperlukan. | Fase Lanjutan. |

# 11. Fitur Usulan / Fase Lanjutan

> Tiap fitur memiliki ID **FL-n**, rencana tahap (A/B/C sesuai rancangan awal), dan rencana prefix ID. Saat fitur dikerjakan, pindahkan ke Bab 6 dengan ID baru, lalu tandai "Dipindahkan ke Bab 6 pada vX.Y" di sini.

- **FL-1 CRUD Berita (Tahap A, rencana prefix BRT).** Admin menambah, melihat, mengubah, mengarsipkan, dan memublikasikan berita; menggantikan data dummy pada BRT-1 sampai BRT-3.
- **FL-2 CRUD Pengumuman (Tahap A, prefix PGM).** Admin mengelola pengumuman melalui dashboard; terkait PGM-1 sampai PGM-3.
- **FL-3 CRUD Fasilitas (Tahap A, prefix PRF).** Data fasilitas disimpan dan dikelola melalui database; terkait PRF-5.
- **FL-4 CRUD Organisasi Siswa (Tahap A, prefix KSW).** Data organisasi disimpan dan dikelola melalui database; terkait KSW-1.
- **FL-5 CRUD Ekstrakurikuler (Tahap A, prefix KSW).** Data ekstrakurikuler dikelola melalui database; terkait KSW-2 dan KSW-4.
- **FL-6 CRUD Prestasi Siswa (Tahap A, prefix KSW).** Admin mengelola data prestasi dan dokumentasinya; terkait KSW-3 dan KSW-5.
- **FL-7 CRUD Galeri (Tahap A, prefix GAL).** Admin mengunggah, mengubah, dan mengarsipkan foto; terkait GAL-1 sampai GAL-3.
- **FL-8 Dashboard Pengelolaan Konten (Tahap A, prefix ADM).** Antarmuka internal untuk mengelola semua konten publik, dengan pembatasan akses berdasarkan role admin serta validasi data dan unggahan gambar.
- **FL-9 Status Konten (Tahap A, prefix ADM).** Status draf, dipublikasikan, dan diarsipkan. Penghapusan memakai mekanisme arsip bila data perlu tetap disimpan.
- **FL-10 Migrasi Data Dummy ke Database (Tahap B, prefix DAT).** Mengganti data dummy dengan data database, menetapkan struktur tabel konten, dan mengganti statistik beranda dengan data valid (terkait BRD-6).
- **FL-11 Penyempurnaan Pencarian dan Optimasi (Tahap B, prefix DIR).** Penyempurnaan pencarian, filter, pagination, dan optimasi query.
- **FL-12 Sinkronisasi atau Pembaruan Data Lanjutan dari RKM (Tahap B, prefix DAT).** Penyesuaian mekanisme pembaruan data guru dan siswa bila diperlukan.
- **FL-13 Fitur Tambahan (Tahap C, prefix ditentukan kemudian).** Fitur lain setelah kebutuhan SIAKAD yang lebih luas dikonfirmasi, termasuk kemungkinan modul akademik internal atau integrasi lanjutan dengan sistem sekolah.

*Urutan tahap di atas adalah rencana awal dan dapat berubah berdasarkan prioritas, waktu pengerjaan, dan hasil evaluasi tahap pertama.*

# 12. Pertanyaan Terbuka / TBD

> Tutup butir dengan menulis "TERJAWAB (vX.Y): jawaban" lalu perbarui bab terkait. Butir baru diberi nomor berikutnya (TBD-n).

- **TBD-1** Nama pengembang/penyusun dokumen dan nama kontak klien yang berwenang menyetujui.
- **TBD-2** Kolom pada tabel guru untuk jabatan, dan apakah jabatan akan ditampilkan.
- **TBD-3** Relasi dan aturan RKM untuk menentukan kelas dan tahun ajaran aktif.
- **TBD-4** Kolom dan aturan status aktif guru dan siswa.
- **TBD-5** Hak akses database yang akan digunakan Laravel (apakah akun baca saja dapat disediakan).
- **TBD-6** Struktur direktori dan komponen Laravel yang sudah ada pada proyek.
- **TBD-7** URL resmi portal CBT, Rapor, dan PPDB.
- **TBD-8** Konten resmi sekolah (nama, slogan, visi misi, sejarah, alamat, kontak, sambutan, struktur organisasi, foto) dan kepastian kontak mana yang boleh dipublikasikan.
- **TBD-9** Kebijakan sekolah tentang publikasi nama, kelas, dan foto siswa di direktori, prestasi, dan galeri.
- **TBD-10** Jadwal ekstrakurikuler dan data prestasi yang sah untuk dipublikasikan.
- **TBD-11** Target performa numerik (waktu muat, ukuran halaman) dan spesifikasi server atau hosting sekolah.
- **TBD-12** Timeline pengerjaan tahap pertama dan tahap susulan, serta estimasi biaya (termasuk hosting, domain, dan penyimpanan).
- **TBD-13** Siapa yang bertanggung jawab menyiapkan dan memperbarui konten sebelum dashboard admin tersedia.
- **TBD-14** Apakah halaman login SIAKAD sudah tersedia atau dibangun pada tahap ini.
- **TBD-15** Panduan identitas visual sekolah (logo, palet warna, font) yang akan dituangkan ke konfigurasi tema Tailwind; apakah ada kebutuhan mode gelap.
- **TBD-16** Apakah diperlukan pustaka JavaScript ringan (mis. Alpine.js) atau cukup JavaScript biasa untuk menu seluler, filter, dan galeri/lightbox.
- **TBD-17** Lokasi proses build Tailwind/Vite (di mesin pengembang lalu hasil build diunggah, atau di server sekolah yang harus mendukung Node.js).

# 13. Glosarium

- **SIAKAD :** Sistem Informasi Akademik; sistem baru yang dikembangkan dengan Laravel.
- **RKM :** sistem akademik yang sudah berjalan (CodeIgniter 3) dan sumber data guru, siswa, serta kelas.
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

1. Catat fitur di Bab 11 dengan ID **FL-n** berikutnya, tahap, dan rencana prefix.
2. Saat fitur dijadwalkan, pindahkan ke Bab 6: buat baris dengan ID berikutnya (mis. BRT-4) atau sub-bab dan prefix baru bila modulnya baru (mis. ADM-1). **Jangan pakai ulang ID lama.**
3. Perbarui bab terkait: Bab 4 (Ruang Lingkup), Bab 7 (alur baru bila ada), Bab 8 (entitas atau field; hapus tanda kurung siku jika field resmi masuk MVP), Bab 9, dan Bab 10.
4. Tambahkan pertanyaan baru ke Bab 12 (TBD-n) dan tutup yang sudah terjawab.
5. Isi Riwayat Revisi (versi, tanggal, ringkasan, bab terdampak).

**3. Mengubah atau menghapus kebutuhan**

- Ubah teks langsung dan catat di Riwayat Revisi.
- Jika dibatalkan, coret teks (~~seperti ini~~) dan tulis alasannya di Riwayat Revisi. ID tetap tercatat.
- Jika ditunda, pindahkan ke Bab 11 dengan FL-n baru dan beri rujukan ke ID lama.

**4. Tabel penanda prefix ID (cadangan untuk penambahan)**

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
| ADM | Dashboard dan Pengelolaan Konten | Direncanakan (Tahap A) |
| DAT | Data dan Sinkronisasi RKM | Direncanakan (Tahap B) |
| [baru] | Fitur Tambahan | Direncanakan (Tahap C) |

**5. Daftar periksa sebelum menerbitkan revisi**

- [ ] Semua ID unik dan tidak ada yang dipakai ulang.
- [ ] Prioritas hanya Wajib, Penting, atau Fase 2 (Fase 2 ditempatkan di Bab 11).
- [ ] Bab 4, 6, 8, 11, dan 12 saling konsisten.
- [ ] Tidak ada asumsi yang ditulis seolah-olah sudah disepakati.
- [ ] Riwayat Revisi, versi, dan tanggal pada header telah diperbarui.

---

*Dokumen ini merupakan draft sementara dan dapat berubah seiring pembahasan lebih lanjut dengan klien.*
