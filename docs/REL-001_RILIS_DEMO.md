# REL-001 Audit dan rilis demo SIAKAD

Hasil 23 November 2026. **Rilis belum siap.** Demo lokal lulus 29 pemeriksaan dengan mode kompatibilitas. Pada simulasi RBAC, penyimpanan presensi, dashboard siswa, dan kenaikan kelas masih gagal. Staging Niagahoster belum dapat diverifikasi karena URL dan akses staging yang berwenang belum tersedia.

## Lingkungan dan hasil verifikasi

Pengujian memakai instans MySQL 8.0.30 lokal terisolasi dengan skema `siakad_rkm_dev` dan data sintetis yang dibuat dari skema repositori. Koneksi `.env` proyek tetap mengarah ke `siakad_db`. Dua simulasi HTTP menggunakan `APP_DEBUG=false` dan aset hasil build. Akun MySQL RBAC dibuat khusus untuk pengujian berdasarkan arsitektur dan SQL delta yang tersedia. Paket GRANT dasar yang dirujuk dokumentasi belum ada di repositori. Hasil hak akses berlaku untuk akun uji ini. [Panduan migrasi](SIAKAD_MIGRATIONS.md) · [Panduan RBAC](RBAC_LOGIN_IMPLEMENTASI.md)

| Pemeriksaan | Hasil |
| --- | --- |
| Suite `php artisan test` | 154 lulus, 17 kegagalan assertion, dan 12 error dari 183 tes. PHPUnit mencatat 1.067 assertion. |
| Simulasi HTTP kompatibilitas | 29 lulus, 0 gagal. Login tiap role, dashboard, master, presensi, kenaikan kelas, dan halaman publik teruji sampai ke hasil database. Rute jadwal guru pada mode ini masih menyajikan tampilan statis. |
| Simulasi HTTP dengan RBAC | 23 lulus, 6 gagal. Login, dashboard admin dan guru/wali kelas, tambah master ekskul, pembukaan sesi presensi, dan halaman publik lulus. Uji tambahan untuk akses dua siswa dan guru kedua lulus 10/10. |
| Objek database | 50 tabel dan 16 view ditemukan. Sebanyak 16 view dapat dikueri. Audit menemukan 15 trigger, 25 prosedur, 2 fungsi, dan 0 baris tanpa induk pada tujuh relasi inti yang diperiksa. Definisi opsional ditinjau dan dimuat dari berkas `.stub` di instans uji. |
| Trigger dan prosedur | Trigger menolak jadwal bentrok, jam terbalik, dan penghapusan admin utama terakhir. Prosedur penempatan lulus pada kasus sukses dan menolak duplikat. Simulasi galat pada salinan jadwal setelah insert pertama membatalkan insert dan melepas lock. |
| Privilege database | Lima koneksi runtime aktif dan `SHOW GRANTS` diperiksa. Akun publik membaca view yang diizinkan, dengan akses tabel master dan perubahan konten ditolak. Seluruh 80 percobaan `UPDATE`/`DELETE` tanpa perubahan baris pada delapan tabel nilai, keputusan kenaikan, dan prestasi RKM ditolak. |
| Build dan cache | `npm run build` lulus setelah backup. CSS sekitar 144,71 kB (22,07 kB gzip), JS 56,53 kB (20,84 kB gzip). Manifest dan aset tersedia. Cache konfigurasi, rute, dan Blade berhasil dibuat secara lokal. `migrate:status` dan `migrate --pretend` berjalan pada DB uji yang sudah termigrasi. |

Pada alur kompatibilitas, status sakit tersimpan dan muncul pada riwayat siswa. Satu penempatan baru tercatat di VIII-1 untuk tahun 2026 sesudah konfirmasi admin. Pada alur RBAC, sesi terbentuk tetapi baris presensi untuk sesi tersebut kosong, dan kandidat kenaikan kelas tidak mendapat penempatan baru. Uji regresi antarmuka RKM belum terverifikasi karena lingkungan ini hanya menyediakan skema RKM dan aplikasi Laravel.

Uji cakupan RBAC memakai presensi yang telah tersimpan. Siswa pemilik melihat catatannya sendiri, siswa dari kelas lain tidak melihat catatan tersebut, dan guru kedua melihat jadwalnya sendiri. Login juga memerlukan hak baca seluruh kolom yang diambil `SELECT *`, termasuk `remember_token` pada skema uji. Hak baca kolom ini ditambahkan pada akun autentikasi uji untuk melanjutkan simulasi. [Uji cakupan antar-role](../storage/app/private/rel-001/e2e-rbac-scope.txt) · [Grant efektif akun uji](../storage/app/private/rel-001/rbac-rapor.txt)

## Penghambat rilis

1. **Penyimpanan presensi gagal dengan RBAC aktif.** Jalur guru memerlukan `EXECUTE` untuk `sp_simpan_absensi_siswa`. Dokumentasi RBAC menahan grant tersebut sampai pemeriksaan identitas aktor pada prosedur siap. Flash error juga menampilkan `SQLSTATE` meski `APP_DEBUG=false`. Kesiapan rilis memerlukan jalur penyimpanan yang sesuai dengan hak akses dan pesan gagal yang aman bagi pengguna. [Controller presensi](../app/Http/Controllers/GuruAbsensiController.php) · [Panduan RBAC](RBAC_LOGIN_IMPLEMENTASI.md)
2. **Dashboard siswa mengembalikan HTTP 500 pada matriks grant uji.** Dashboard membaca `t_walikelas`, sementara akun siswa uji belum memiliki hak baca pada tabel itu. Tetapkan akses minimum untuk informasi wali kelas, lalu verifikasi dashboard dan batas data antarkelas. [Controller dashboard siswa](../app/Http/Controllers/SiswaDashboardController.php)
3. **Pratinjau dan eksekusi kenaikan kelas gagal dengan RBAC aktif.** Query membutuhkan `t_naikkelas.id` untuk memilih keputusan terbaru. Kontrak arsitektur membatasi akses admin pada `id_siswa`, `ta`, dan `naik`. Selaraskan query atau antarmuka database dengan kontrak sebelum menguji kembali konfirmasi, penempatan, dan perlindungan catatan wali. [Controller kenaikan kelas](../app/Http/Controllers/Admin/AdminKenaikanKelasController.php) · [Arsitektur](../ARCHITECTURE.md)
4. **Suite penuh masih merah.** Tes lama masih memakai nama rute yang telah berubah atau mengharapkan nama dan jumlah data tertentu. Sebagian fixture bentrok dengan constraint jadwal, akun unik, dan foreign key. Ada pula kegagalan HTTP 500 dan assertion ubah password. Perbaikan sebaiknya mempertahankan constraint dan memastikan fixture mandiri sebelum suite penuh dijalankan ulang.

Impor Excel perlu diverifikasi jika masuk cakupan demo. `SIAKAD_IMPORT_SP_READY` tetap `false` pada audit RBAC. Definisi prosedur opsional masih memblokir commit, sedangkan SQL lama yang disertakan memakai signature berbeda. Lima tugas fondasi database tetap berstatus *Not Started* di `TASKS.md` meski objek uji berhasil dimuat. Provisioning staging dan paket GRANT dasar perlu direkonsiliasi sebelum rilis. [Daftar task](../TASKS.md) · [SQL impor](../database/sql/sp_impor_siswa.sql)

## Catatan performa

`EXPLAIN` menunjukkan full scan dan filesort pada pencarian master dengan pola `%kata%`. Direktori siswa memakai temporary table untuk memilih penempatan terbaru lalu memakai indeks pada join. Direktori guru memakai indeks penugasan dan filesort pada hasil. Rekap presensi memakai indeks utama pada join tetapi masih memindai sumber absensi dan mengagregasi melalui temporary table. Volume data sintetis kecil. Ukur ulang jumlah query, waktu respons, dan tampilan seluler pada dataset anonim yang representatif sebelum memutuskan perubahan indeks. [Hasil EXPLAIN lokal](../storage/app/private/rel-001/explain.txt)

## Backup dan bukti lokal

Backup SQL sintetis dibuat sebelum build, berukuran 164.226 byte, dan berhasil dipulihkan ke `siakad_rkm_restore_dev`. Pemulihan memuat empat siswa sintetis, satu ekskul terbit, 27 rutin, dan 15 trigger. SHA-256 `4A10E0278E8D73CF37B65C9C30DB06CCC62539026A8098FF47368DC5F294A963`. Simpan backup lokal di luar document root dan gunakan akun uji tersendiri untuk staging. [Backup sintetis](../storage/app/private/rel-001/siakad_rkm_dev-sintetis.sql) · [Hasil pemulihan](../storage/app/private/rel-001/backup-restore.txt)

- [Suite penuh](../storage/app/private/rel-001/phpunit.txt) dan [hasil JUnit](../storage/app/private/rel-001/phpunit.xml).
- [Audit struktur](../storage/app/private/rel-001/audit-struktur.txt), [uji objek database](../storage/app/private/rel-001/dbms-functional.txt), dan [pemeriksaan privilege RKM](../storage/app/private/rel-001/rbac-rapor.txt).
- [Simulasi kompatibilitas](../storage/app/private/rel-001/e2e-compat.txt), [simulasi RBAC](../storage/app/private/rel-001/e2e-rbac.txt), dan [verifikasi data](../storage/app/private/rel-001/e2e-data.txt).
- [Manifest build](../storage/app/private/rel-001/build/manifest.json) dan [uji cache Laravel](../storage/app/private/rel-001/laravel-caches.txt).

## Deployment staging Niagahoster

1. Setelah penghambat rilis terselesaikan, konfirmasi akses hPanel/SSH untuk subdomain staging yang terpisah. Periksa PHP dan ekstensi dari CLI serta web, Composer, MySQL 8.0, batas unggahan, serta izin database untuk view, trigger, routine, dan definer. Arahkan document root subdomain ke `public/` dan simpan `.env`, `vendor/`, dan direktori privat di luar root web. [Akses SSH hPanel](https://www.hostinger.com/support/1583645-how-to-enable-ssh-access-in-hostinger/) · [Arsitektur pengujian dan rilis](../ARCHITECTURE.md#14-pengujian-dan-rilis)
2. Buat backup database staging dan simpan kode, aset, konfigurasi, serta unggahan dari rilis aktif. Uji pemulihan database ke skema terpisah. Siapkan database staging dengan salinan anonim. Pastikan database aplikasi RKM yang aktif terpisah dari target migrasi.
3. Unggah rilis dan `public/build` yang sudah lulus pengujian. Jalankan `composer install --no-dev --optimize-autoloader --no-interaction` dan `composer check-platform-reqs --no-dev`. Buat `.env` staging dengan `APP_ENV=staging`, `APP_DEBUG=false`, `APP_URL` sesuai HTTPS, `APP_KEY` staging, dan koneksi runtime terpisah. Pastikan `public/hot` tidak ikut terunggah. Berikan izin tulis yang diperlukan pada `storage/` dan `bootstrap/cache/`.
4. Periksa `php artisan migrate:status --database=mysql` dan `php artisan migrate --database=mysql --pretend`. Sesudah target dan perubahan diverifikasi, jalankan migrasi yang dibutuhkan pada staging dengan akun migrator terpisah. Migrasi reguler melewati berkas `.stub`. Inventarisasi objek yang diperlukan, lalu terapkan definisi yang disetujui sesuai urutan dependensinya. Pengelola database menerapkan GRANT dan memeriksa role efektif sebelum aplikasi dijalankan dengan akun runtime. [Panduan migrasi](SIAKAD_MIGRATIONS.md) · [Panduan RBAC](RBAC_LOGIN_IMPLEMENTASI.md)
5. Segarkan cache konfigurasi, rute, dan Blade. Verifikasi HTTPS, aset, autentikasi, pembatasan akses antar-role, alur admin dan guru/siswa, kompatibilitas RKM pada salinan, serta log Laravel. Ulangi simulasi inti dengan `APP_DEBUG=false`. Tahan rilis jika ada HTTP 500, kegagalan semantik, atau perbedaan hak akses.

## Rollback staging

1. Hentikan akses tulis staging, lalu simpan log dan snapshot kegagalan untuk diagnosis.
2. Aktifkan ulang kode beserta aset dari rilis sebelumnya. Jika rilis mengubah data atau objek database, pulihkan backup yang sudah diuji ke skema staging terpisah dan arahkan konfigurasi staging ke hasil pemulihan. Pulihkan konfigurasi dan unggahan dari rilis yang sama jika berubah.
3. Bangun ulang cache Laravel. Verifikasi versi aplikasi, login, halaman publik, hak akses, dan data inti sebelum membuka akses lagi. Untuk migrasi baseline dengan `down()` no-op, pemulihan database berasal dari backup. [Panduan migrasi](SIAKAD_MIGRATIONS.md)
