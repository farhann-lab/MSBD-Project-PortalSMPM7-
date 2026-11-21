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
            'siakad_absensi_siswa',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_absensi_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pertemuan_id` bigint unsigned NOT NULL,
  `id_siswa` int NOT NULL,
  `status` enum('H','S','I','A') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diubah_oleh` bigint unsigned NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_absensi_pertemuan_siswa` (`pertemuan_id`,`id_siswa`),
  KEY `fk_siakad_absensi_siswa_id_siswa` (`id_siswa`),
  KEY `fk_siakad_absensi_siswa_diubah_oleh` (`diubah_oleh`),
  CONSTRAINT `fk_siakad_absensi_siswa_diubah_oleh` FOREIGN KEY (`diubah_oleh`) REFERENCES `siakad_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_siakad_absensi_siswa_id_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `m_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_siakad_absensi_siswa_pertemuan_id` FOREIGN KEY (`pertemuan_id`) REFERENCES `siakad_pertemuan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id', 'pertemuan_id', 'id_siswa', 'status', 'keterangan', 'diubah_oleh', 'updated_at'],
            ['m_siswa', 'siakad_pertemuan', 'siakad_users']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
