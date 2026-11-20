# Implementasi login RBAC multi-koneksi — feature/rbac-login

Basis sumber: upload `MSBD-Project-PortalSMPM7-(2).zip` (snapshot proyek, 14–15 Nov 2026), dan struktur `mysqldump siakad_rkm.txt` (server MySQL 8.0.30). **Bukan perubahan langsung pada laptop atau GitHub.**

## Perubahan yang dibuat

1. Middleware `EnsureRole`: `role:admin`, `role:guru`, `role:siswa` serta pengecekan `is_active`.
2. Route `/admin/*`, `/guru/*`, `/siswa/*` dan ketiga dashboard sekarang dilindungi. `AdminUtamaOnly` tetap aktif hanya pada manajemen admin.
3. Lima koneksi MySQL untuk runtime RBAC: `mysql_auth`, `mysql_admin`, `mysql_guru`, `mysql_siswa`, `mysql_public` (`config/database.php`); pemilihan berdasarkan Auth user yang diverifikasi, bukan input HTTP.
4. Model `User` memakai `mysql_auth` pada mode RBAC; model master/CMS operasional Admin memakai `mysql_admin`; model direktori publik memakai `mysql_public`.
5. Saat RBAC aktif, koneksi **default** secara sengaja `mysql_public`, bukan root; query yang belum dialihkan gagal karena tidak memperoleh hak tulis. Perubahan ini hanya aktif setelah `SIAKAD_RBAC_ENABLED=true`.
6. Form login, Hash::check, rate limiter, session, force password dipertahankan. Remember-me dinonaktifkan karena tabel `siakad_users` belum memiliki `remember_token`.
7. Admin membuat/mengubah akun melalui koneksi `mysql_admin`, bukan akun baca `mysql_auth`; transaksi dan validasi unique memakai koneksi Admin.
8. Homepage, profil fasilitas, berita, pengumuman, prestasi, galeri menggunakan VIEW `v_publik_*` ketika RBAC aktif. Direktori guru dan siswa juga memakai koneksi `mysql_public`.
9. Guru `/guru/jadwal` menggunakan `mysql_guru` dan `v_jadwal_lengkap` dengan `id_guru` dari sesi login. Siswa `/siswa/profil` menggunakan `mysql_siswa` dan `v_publik_siswa` dengan `id_siswa` sesi.
10. `sp_impor_siswa_commit` lama tidak cocok dengan SQL 8.0 terbaru. Mode RBAC memblokir upload/commit sampai prosedur impor staging benar-benar selesai dan diverifikasi (`SIAKAD_IMPORT_SP_READY=false`).
11. Tes baru untuk proteksi route dan resolusi koneksi; SQL delta GRANT untuk hak yang benar-benar dipakai model/controller.

## Konfigurasi (sebelum aktivasi)

- **Backup** database lalu gunakan salinan `siakad_rkm_dev` MySQL **8.0**.
- Pastikan VIEW/PROCEDURE/trigger/definer ada dan akun pembuat objek mempunyai hak yang sah. Jangan memasang file mysqldump yang berisi `DROP TABLE` di produksi.
- Administrator MySQL membuat lima user koneksi dengan password berbeda dan hanya hak yang tepat. Terapkan paket GRANT RBAC sebelumnya, lalu `database/sql/rbac/01_grant_delta_DEV_mysql8.sql` (tambahan privilege, tidak mencabut privilege lama). Periksa `SHOW GRANTS` untuk kelima akun, bukan hanya role.
- Atur `.env` secara lokal (JANGAN commit):

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siakad_rkm_dev
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
SIAKAD_RBAC_ENABLED=true
SIAKAD_IMPORT_SP_READY=false
DB_AUTH_USERNAME=nama_auth
DB_AUTH_PASSWORD=(password dari administrator DB)
DB_ADMIN_USERNAME=nama_admin
DB_ADMIN_PASSWORD=(password berbeda)
DB_GURU_USERNAME=nama_guru
DB_GURU_PASSWORD=(password berbeda)
DB_SISWA_USERNAME=nama_siswa
DB_SISWA_PASSWORD=(password berbeda)
DB_PUBLIC_USERNAME=nama_public
DB_PUBLIC_PASSWORD=(password berbeda)
```

`SIAKAD_RBAC_ENABLED` secara default **false** agar kode lama tidak tiba-tiba terputus saat dipasang. Pada rilis yang menggunakan RBAC, ubah menjadi `true` **hanya** setelah privilege terpasang dan smoke test DEV lulus. Akun database default / `DB_USERNAME` **jangan root** pada runtime produksi.

**Catatan:** file `.env` contoh di atas bukan password yang bisa dipakai; jangan pakai teks contoh sebagai kredensial.

## Pemasangan di branch lokal yang sudah ada

Source ZIP yang digunakan berasal dari snapshot lama yang berisi branch `main`. Patch ini dikerjakan pada salinan lokal dengan nama branch `feature/rbac-login`, **bukan** pada repo Windows/GitHub. Jika branch Windows sudah mendapat perubahan baru, tinjau konflik sebelum menerapkan patch.

PowerShell dari folder proyek asli:

```powershell
git branch --show-current
git status
# Harus feature/rbac-login; amankan semua perubahan lokal yang belum disimpan.
git apply --check "C:\\path\\ke\\feature-rbac-login.patch"
git apply "C:\\path\\ke\\feature-rbac-login.patch"
php artisan optimize:clear
php artisan route:list -v
php artisan test
```

Jika `git apply --check` gagal, **jangan gunakan `git reset --hard` atau menimpa file**. Bandingkan dengan ZIP overlay patch per file, lalu selesaikan konflik secara manual.

Sebelum `git push`, cek `git diff`, status, `.env` tidak berubah di Git, dan tes MySQL DEV:

```powershell
git diff --check
git status
# Sesudah peninjauan dan pengujian:
git add .env.example app bootstrap config database/sql/rbac docs resources/views/pages/login resources/views/pages/guru/jadwal.blade.php resources/views/pages/siswa/profil.blade.php routes tests
git commit -m "feat: enforce role routes and mysql rbac connections"
git push -u origin feature/rbac-login
```

Lakukan push setelah semua tes lokal berhasil. Jangan commit kredensial, data siswa, dump database, atau `.env`.

## Uji yang wajib dikerjakan pada MySQL 8.0 DEV

- Login Admin, Guru, Siswa menggunakan akun contoh DEV dan password bcrypt.
- Akses URL role lain harus 403. Guru yang tidak punya `id_guru` dan siswa yang tidak punya `id_siswa` harus 403 pada endpoint data pribadinya.
- Uji `CURRENT_USER()` pada setiap koneksi dan `SHOW GRANTS` menggunakan sesi akun masing-masing.
- Guru membuka jadwal miliknya melalui `v_jadwal_lengkap`; siswa melihat nama/kelasnya di profil melalui `v_publik_siswa`. Tidak menampilkan siswa lain.
- Admin tetap dapat mengelola master dan CMS sesuai privilege; lihat apakah `stat_data` benar secara semantik terhadap RKM.
- Uji privilege negatif: siswa tidak bisa UPDATE data guru; guru tidak bisa EXECUTE SP Admin; admin tidak bisa UPDATE tabel nilai RKM.
- Input karakter khusus untuk pencarian/validasi harus diperlakukan sebagai data (Laravel binding), dan error query tidak boleh menampilkan password/SQL mentah.
- Uji impor terpisah **setelah** SQL `sp_impor_siswa_validasi` dan `sp_impor_siswa_commit` benar-benar diimplementasikan dan cocok dengan tabel staging. Jangan mengaktifkan `SIAKAD_IMPORT_SP_READY` sebelum itu.

## Batas verifikasi saat membuat patch

- Lolos `php -l` pada file PHP, `git diff --check`, inspeksi `route:list -v`, dan smoke test middleware/resolver role secara lokal.
- **Tidak** ada koneksi ke database MySQL 8.0 asli, jadi pengujian SQL GRANT, login nyata, dan CRUD database belum dapat dinyatakan lulus.
- PHPUnit tidak dapat dijalankan di lingkungan pemeriksaan karena extension `mbstring`, `dom`, dan `xmlwriter` tidak tersedia; `pdo_mysql` juga tidak tersedia. Jalankan `php artisan test` di Windows atau CI dengan PHP extensions lengkap.
- Fitur materi/absensi Guru dan jadwal/absensi pribadi Siswa yang masih placeholder **tidak diaktifkan secara palsu**. Hak akses per orang perlu dijaga pada controller/policy dan SP; `GRANT SELECT` tidak membatasi baris data.
- `sp_simpan_absensi_siswa` dan `sp_atur_akun` lama menerima ID aktor sebagai parameter: jangan memberikan `EXECUTE` kepada role rendah sebelum otorisasi pemanggil diperkuat.
- Status `stat_data` guru/siswa dan definisi `DEFINER=root@localhost` pada dump memerlukan audit sebelum produksi.
