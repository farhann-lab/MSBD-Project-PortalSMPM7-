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
            'siakad_materi',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_materi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_guru` int NOT NULL,
  `id_mapel` int NOT NULL,
  `judul` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jenis` enum('file','tautan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tautan_url` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ukuran_byte` bigint unsigned DEFAULT NULL,
  `status` enum('aktif','arsip') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_siakad_materi_id_guru` (`id_guru`),
  KEY `fk_siakad_materi_id_mapel` (`id_mapel`),
  CONSTRAINT `fk_siakad_materi_id_guru` FOREIGN KEY (`id_guru`) REFERENCES `m_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_siakad_materi_id_mapel` FOREIGN KEY (`id_mapel`) REFERENCES `m_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id', 'id_guru', 'id_mapel', 'judul', 'deskripsi', 'jenis', 'file_path', 'tautan_url', 'ukuran_byte', 'status', 'created_at', 'updated_at'],
            ['m_guru', 'm_mapel']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
