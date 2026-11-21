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
            'siakad_users',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','guru','siswa') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_guru` int DEFAULT NULL,
  `id_siswa` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `wajib_ganti_password` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin_utama` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_siakad_users_username` (`username`),
  UNIQUE KEY `uq_siakad_users_id_guru` (`id_guru`),
  UNIQUE KEY `uq_siakad_users_id_siswa` (`id_siswa`),
  CONSTRAINT `fk_siakad_users_id_guru` FOREIGN KEY (`id_guru`) REFERENCES `m_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_siakad_users_id_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `m_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id', 'username', 'password', 'role', 'id_guru', 'id_siswa', 'is_active', 'wajib_ganti_password', 'is_admin_utama', 'created_at', 'updated_at'],
            ['m_guru', 'm_siswa']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
