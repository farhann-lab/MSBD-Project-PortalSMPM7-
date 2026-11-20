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
            'siakad_impor_siswa',
            <<<'SIAKAD_SQL_TEXT'
CREATE TABLE `siakad_impor_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admin_user_id` bigint unsigned NOT NULL,
  `nama_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_baris` int unsigned NOT NULL,
  `jumlah_berhasil` int unsigned NOT NULL,
  `jumlah_gagal` int unsigned NOT NULL,
  `status` enum('validasi','selesai','gagal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_siakad_impor_siswa_admin_user_id` (`admin_user_id`),
  CONSTRAINT `fk_siakad_impor_siswa_admin_user_id` FOREIGN KEY (`admin_user_id`) REFERENCES `siakad_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SIAKAD_SQL_TEXT,
            ['id', 'admin_user_id', 'nama_file', 'jumlah_baris', 'jumlah_berhasil', 'jumlah_gagal', 'status', 'created_at'],
            ['siakad_users']
        );
    }

    public function down(): void
    {
        // NO-OP: do not drop a school table that may contain pre-existing data.
    }
};
