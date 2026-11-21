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
            'siakad_fasilitas',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_fasilitas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gambar_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id', 'nama', 'deskripsi', 'gambar_path', 'status', 'created_at', 'updated_at'],
            []
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
