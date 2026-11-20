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
            'siakad_materi_kelas',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_materi_kelas` (
  `materi_id` bigint unsigned NOT NULL,
  `id_guru_mapel` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`materi_id`,`id_guru_mapel`),
  KEY `fk_siakad_materi_kelas_id_guru_mapel` (`id_guru_mapel`),
  CONSTRAINT `fk_siakad_materi_kelas_id_guru_mapel` FOREIGN KEY (`id_guru_mapel`) REFERENCES `t_guru_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_siakad_materi_kelas_materi_id` FOREIGN KEY (`materi_id`) REFERENCES `siakad_materi` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['materi_id', 'id_guru_mapel', 'created_at'],
            ['siakad_materi', 't_guru_mapel']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
