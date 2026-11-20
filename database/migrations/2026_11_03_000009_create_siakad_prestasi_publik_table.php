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
            'siakad_prestasi_publik',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_prestasi_publik` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_siswa` int DEFAULT NULL,
  `id_prestasi_rkm` int DEFAULT NULL,
  `judul` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gambar_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_siakad_prestasi_publik_id_siswa` (`id_siswa`),
  KEY `fk_siakad_prestasi_publik_id_prestasi_rkm` (`id_prestasi_rkm`),
  CONSTRAINT `fk_siakad_prestasi_publik_id_prestasi_rkm` FOREIGN KEY (`id_prestasi_rkm`) REFERENCES `t_prestasi` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_siakad_prestasi_publik_id_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `m_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id', 'id_siswa', 'id_prestasi_rkm', 'judul', 'tingkat', 'tanggal', 'deskripsi', 'gambar_path', 'status', 'created_at', 'updated_at'],
            ['m_siswa', 't_prestasi']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
