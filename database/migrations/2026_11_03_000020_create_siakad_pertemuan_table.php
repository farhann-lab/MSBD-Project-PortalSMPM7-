<?php

use App\Support\Migrations\SharedSchemaBaseline;
use Illuminate\Database\Migrations\Migration;

// Exact table DDL from the provided MySQL 8.0.30 schema-only dump.
// Existing tables are preserved; no destructive rollback.
return new class extends Migration
{
    public function up(): void
    {
        SharedSchemaBaseline::table(
            'siakad_pertemuan',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_pertemuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jadwal_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `sesi_ke` smallint unsigned NOT NULL DEFAULT '1',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `dibuat_oleh` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pertemuan_jadwal_tanggal_sesi` (`jadwal_id`,`tanggal`,`sesi_ke`),
  KEY `fk_siakad_pertemuan_dibuat_oleh` (`dibuat_oleh`),
  CONSTRAINT `fk_siakad_pertemuan_dibuat_oleh` FOREIGN KEY (`dibuat_oleh`) REFERENCES `siakad_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_siakad_pertemuan_jadwal_id` FOREIGN KEY (`jadwal_id`) REFERENCES `siakad_jadwal` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id', 'jadwal_id', 'tanggal', 'sesi_ke', 'catatan', 'dibuat_oleh', 'created_at', 'updated_at'],
            ['siakad_jadwal', 'siakad_users']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
