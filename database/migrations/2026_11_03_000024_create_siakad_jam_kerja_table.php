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
            'siakad_jam_kerja',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_jam_kerja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `personel_id` bigint unsigned NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `berlaku_mulai` date NOT NULL,
  `berlaku_sampai` date DEFAULT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `toleransi_menit` smallint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_siakad_jam_kerja_personel_id` (`personel_id`),
  CONSTRAINT `fk_siakad_jam_kerja_personel_id` FOREIGN KEY (`personel_id`) REFERENCES `siakad_personel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id', 'personel_id', 'hari', 'berlaku_mulai', 'berlaku_sampai', 'jam_masuk', 'jam_pulang', 'toleransi_menit'],
            ['siakad_personel']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
