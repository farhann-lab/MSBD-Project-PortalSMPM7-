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
            'siakad_galeri_foto',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_galeri_foto` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `galeri_id` bigint unsigned NOT NULL,
  `gambar_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urutan` int unsigned NOT NULL DEFAULT '0',
  `status` enum('draft','published','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_siakad_galeri_foto_galeri_id` (`galeri_id`),
  CONSTRAINT `fk_siakad_galeri_foto_galeri_id` FOREIGN KEY (`galeri_id`) REFERENCES `siakad_galeri` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id', 'galeri_id', 'gambar_path', 'keterangan', 'urutan', 'status', 'created_at'],
            ['siakad_galeri']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
