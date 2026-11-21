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
            'siakad_ekstra_publik',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_ekstra_publik` (
  `id_ekstra` int NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gambar_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_ekstra`),
  CONSTRAINT `fk_siakad_ekstra_publik_id_ekstra` FOREIGN KEY (`id_ekstra`) REFERENCES `m_ekstra` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id_ekstra', 'deskripsi', 'gambar_path', 'status', 'updated_at'],
            ['m_ekstra']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
