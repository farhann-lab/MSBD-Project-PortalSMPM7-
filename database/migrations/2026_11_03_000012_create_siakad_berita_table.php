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
            'siakad_berita',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_berita` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `penulis_user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_siakad_berita_slug` (`slug`),
  KEY `fk_siakad_berita_penulis_user_id` (`penulis_user_id`),
  CONSTRAINT `fk_siakad_berita_penulis_user_id` FOREIGN KEY (`penulis_user_id`) REFERENCES `siakad_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id', 'judul', 'slug', 'isi', 'gambar_path', 'status', 'published_at', 'penulis_user_id', 'created_at', 'updated_at'],
            ['siakad_users']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
