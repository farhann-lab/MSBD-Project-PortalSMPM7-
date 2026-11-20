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
            'siakad_jadwal',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_jadwal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_guru_mapel` int NOT NULL,
  `tasm` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_siakad_jadwal_id_guru_mapel` (`id_guru_mapel`),
  KEY `idx_siakad_jadwal_tasm` (`tasm`),
  CONSTRAINT `fk_siakad_jadwal_id_guru_mapel` FOREIGN KEY (`id_guru_mapel`) REFERENCES `t_guru_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id', 'id_guru_mapel', 'tasm', 'hari', 'jam_mulai', 'jam_selesai', 'aktif', 'created_at', 'updated_at'],
            ['t_guru_mapel']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
