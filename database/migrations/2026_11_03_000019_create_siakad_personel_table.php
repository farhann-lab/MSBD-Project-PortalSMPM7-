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
            'siakad_personel',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_personel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_guru` int DEFAULT NULL,
  `id_staf` bigint unsigned DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_siakad_personel_id_guru` (`id_guru`),
  UNIQUE KEY `uq_siakad_personel_id_staf` (`id_staf`),
  CONSTRAINT `fk_siakad_personel_id_guru` FOREIGN KEY (`id_guru`) REFERENCES `m_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_siakad_personel_id_staf` FOREIGN KEY (`id_staf`) REFERENCES `siakad_staf` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SIAKAD_SQL_TEXT,
            ['id', 'id_guru', 'id_staf', 'aktif'],
            ['m_guru', 'siakad_staf']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
