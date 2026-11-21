# Migrasi Database SIAKAD–RKM — MySQL 8.0

Paket untuk **branch `feature/rbac-login`**, dibuat dengan acuan dump **`mysqldump siakad_rkm.txt`** (server MySQL **8.0.30**, schema-only) dan migration yang sudah ada pada ZIP proyek `MSBD-Project-PortalSMPM7-(2).zip`.

**File yang perlu disalin:** folder `app/Support/Migrations`, `database/migrations`, `database/schema_baseline_manifest.json` dan `docs`. Jangan salin folder `vendor`, `node_modules`, atau `.env` dari arsip mana pun. Paket ini hanya menambah file; tidak menimpa controller / route / GRANT patch RBAC.

## Cakupan

| Bagian | Jumlah | Cara menjalankan |
|---|---:|---|
| `siakad_*` | **26 tabel** | Otomatis: `php artisan migrate --database=mysql` |
| Tabel warisan RKM (`m_*`, `t_*`, `tahun`) | **21 tabel** | **Opsional, hanya jika membuat database DEV KOSONG** |
| Tabel Laravel | **9** termasuk tabel `migrations` | Sudah disediakan migration Laravel bawaan; `migrations` dikelola Laravel |
| VIEW | **16** | Opsional, setelah tabel lengkap |
| TRIGGER | **15** | Opsional, setelah FUNCTION dan tabel lengkap |
| FUNCTION | **2** | Opsional; sumber: paket SQL MySQL 8 sebelumnya |
| STORED PROCEDURE | **25** | Opsional; sumber: paket SQL MySQL 8 sebelumnya |

SQL disimpan sebagai DDL MySQL asli (`DB::unprepared`), bukan `Schema::create`. Ini disengaja agar tipe `YEAR`, `ENUM`, index composite, foreign key, collation `latin1` RKM, dan constraint dalam dump tidak berubah diam-diam. Bukan migration yang portabel ke SQLite/MariaDB/PostgreSQL.

**Perhatian:** 2 function dan 25 procedure *tidak tercantum dalam schema-only dump*. Mereka disalin dari paket SQL terdahulu dan beberapa memiliki logika placeholder/TODO. Jangan aktifkan fungsi produksi atau grant EXECUTE sebelum review keamanan dan signature SP. GRANT akun MySQL bukan bagian migration tabel: dikelola melalui skrip privilege RBAC terpisah.

## A. Database DEV SUDAH MEMILIKI 56 TABEL (seperti dump terbaru)

1. Backup database dan pastikan `git status` bersih, branch `feature/rbac-login`.
2. Salin file dari paket ke root proyek Laravel.
3. Buka `.env` dan pastikan `DB_DATABASE=siakad_rkm_dev` (salinan, bukan database operasional), `DB_CONNECTION=mysql`, dan **koneksi `mysql` menggunakan akun schema migrator berizin DDL**. Jangan mengubah `.env` production.
4. Periksa status:
   ```powershell
   php artisan optimize:clear
   php artisan migrate:status --database=mysql
   ```
5. **Periksa output `migrate:status` sebelum lanjut.** Jika migration bawaan `0001_01_01_*` masih **Pending** padahal tabel `users`, `cache`, atau `jobs` sudah ada, **STOP**. Migration bawaan Laravel tidak semuanya create-if-missing dan bisa gagal karena tabel telah ada. Rekonsiliasi migration history di salinan DEV terlebih dahulu, jangan menghapus tabel ataupun mengarang baris pada `migrations`.
6. Setelah status konsisten dan database DEV sudah lengkap, jalankan:
   ```powershell
   php artisan migrate --database=mysql
   ```
7. Periksa hasil migrasi, struktur tabel serta pengujian aplikasi. **Jangan jalankan folder `legacy_rkm_bootstrap` atau `optional_db_objects` pada database operasional.**

Migration tabel baru bersifat *create-if-missing*: jika tabel sudah ada, migration mengecek nama kolom utama terhadap dump, lalu melewati CREATE. Ini **BUKAN** sinkronisasi otomatis perbedaan tipe/index/FK. Untuk tabel yang berbeda dari schema rujukan, audit `SHOW CREATE TABLE` dan buat migration ALTER terpisah setelah review; jangan menimpa schema lama.

## B. Membuat database DEV BARU DARI NOL (TANPA data RKM)

HANYA untuk database kosong yang dibuat khusus eksperimen dan bukan database sekolah yang sedang dipakai:

```powershell
# Setelah membuat siakad_rkm_dev kosong di MySQL 8.0 dan mengatur .env
# Set SIAKAD_ALLOW_LEGACY_BOOTSTRAP=true pada .env DEV, lalu php artisan optimize:clear
php artisan migrate --database=mysql --path=database/migrations/legacy_rkm_bootstrap
php artisan migrate --database=mysql
```

Perintah pertama membuat struktur **21 tabel RKM kosong**. Perintah kedua membuat tabel Laravel dan 26 tabel SIAKAD. Ini **tidak memindahkan data siswa/guru/nilai**. Untuk aplikasi yang butuh data riil harus memakai backup/restorasi yang sah dari RKM, bukan membuat tabel kosong di database operasional.

### Optional: Objek database untuk DEV baru

Setelah semua tabel ada, dan hanya setelah meninjau SQL serta memastikan akun pembuat objek punya privilege yang tepat:

```powershell
# Set SIAKAD_ALLOW_OPTIONAL_OBJECT_BOOTSTRAP=true pada .env DEV, lalu php artisan optimize:clear
php artisan migrate --database=mysql --path=database/migrations/optional_db_objects
```

Urutan: 2 FUNCTION, 25 STORED PROCEDURE, 15 TRIGGER, 16 VIEW. Objek yang sudah ada **tidak ditimpa**, termasuk trigger atau VIEW dengan definer lama. Definer `root@localhost` dari dump **dihapus** saat membuat VIEW/TRIGGER baru; objek baru menggunakan akun pembuat schema. **Jangan gunakan akun root web-app sebagai definer di produksi.**

## Integrasi RBAC

Selalu gunakan `--database=mysql` untuk migration. Saat `SIAKAD_RBAC_ENABLED=true`, koneksi default aplikasi dialihkan menjadi `mysql_public` yang **tidak boleh punya DDL**. Akun `mysql_admin`, `mysql_guru`, `mysql_siswa`, `mysql_auth`, dan `mysql_public` adalah koneksi runtime; migration memakai koneksi `mysql` sebagai **schema/migration connection sementara**. Jangan menambahkan hak `CREATE/DROP/ALTER` kepada akun role tersebut.

## Larangan keamanan

- **JANGAN** `php artisan migrate:fresh`, `migrate:refresh`, `db:wipe`, atau SQL `DROP TABLE` pada DB RKM/SIAKAD bersama.
- Folder opsional memiliki pengaman ganda: `*_dev` pada nama database, `APP_ENV` bukan production, dan variabel opt-in pada `.env` DEV. Jangan membuka gate ini di production.
- **JANGAN** menjalankan migration pada production sebelum backup, pengujian di DEV, dan review setiap query.
- `down()` sengaja NO-OP supaya `rollback` tidak menghapus tabel sekolah lama. `rollback` hanya mengubah catatan migration, bukan mengembalikan schema. Untuk reset DEV sekali pakai, buat ulang DB DEV saja.
- Mode DDL memakai SQL mode mysqldump untuk mengizinkan *legacy zero-date defaults* saat pembuatan tabel. Nilai tanggal nol adalah masalah kompatibilitas tersendiri dan tidak disarankan untuk data baru.
- `migrations` dikelola Laravel sendiri. Jangan buat migration manual untuk tabel metadata itu.
- Repo kalian sudah punya migration tanggal `2026_11_04` (auth flags) dan `2026_11_14` (staging impor); migration baseline SIAKAD sengaja diurutkan **2026_11_03** agar dependensi `siakad_users`/`siakad_impor_siswa` tersedia sebelum dua migration tersebut jika membangun database baru.

## Git branch / integrasi

Buat commit khusus agar terpisah dari patch login:

```powershell
git branch --show-current
git status
# Periksa file yang disalin, jangan menambahkan .env
php artisan migrate:status --database=mysql
git add app/Support/Migrations database/migrations database/schema_baseline_manifest.json docs/SIAKAD_MIGRATIONS.md
git commit -m "feat(db): baseline migrations MySQL8 SIAKAD and optional RKM schema"
git push origin feature/rbac-login
```

Jalankan `git status` dan review sebelum commit; path yang disebut hanya untuk file yang ada. `git add database/migrations` juga akan melakukan staging pada migration lama apabila sudah dimodifikasi, jadi **cek `git diff --cached`**.

## Validasi yang dilakukan di paket

- Parse: 56 CREATE TABLE, 16 VIEW, 15 TRIGGER dari dump MySQL 8.0.30.
- Parse dari paket SQL terdahulu: 2 FUNCTION dan 25 STORED PROCEDURE.
- PHP syntax `php -l` pada seluruh migration + helper.
- Kecocokan nama tabel/kolom dan urutan foreign-key pada manifest.

**Belum diuji:** koneksi nyata MySQL 8.0, pelaksanaan migration di komputer pengguna, behavior existing indexes/foreign keys, dan tes seluruh fitur Laravel setelah perubahan.
