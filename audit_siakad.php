<?php
/**
 * Audit baca-saja struktur SIAKAD/RKM melalui koneksi Laravel "mysql".
 * Simpan sejajar dengan artisan, lalu jalankan: php audit_siakad.php
 * Tidak menjalankan INSERT, UPDATE, DELETE, ALTER, DROP, CREATE, atau GRANT.
 */

declare(strict_types=1);

if (!is_file(__DIR__ . '/vendor/autoload.php') || !is_file(__DIR__ . '/bootstrap/app.php')) {
    fwrite(STDERR, "ERROR: Simpan audit_siakad.php di root proyek Laravel, sejajar dengan artisan.\n");
    exit(1);
}

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

function heading(string $title): void
{
    echo "\n=== {$title} ===\n";
}

function warnAudit(string $message): void
{
    echo "[PERIKSA] {$message}\n";
}

try {
    $db = \Illuminate\Support\Facades\DB::connection('mysql');
    $info = $db->selectOne('SELECT DATABASE() AS db_name, VERSION() AS db_version, @@version_comment AS db_vendor, CURRENT_USER() AS db_account');
    $schema = (string) ($info->db_name ?? '');
    if ($schema === '') {
        throw new RuntimeException('Koneksi mysql belum memilih database. Periksa DB_DATABASE di .env.');
    }
} catch (\Throwable $e) {
    fwrite(STDERR, "GAGAL KONEKSI MYSQL: {$e->getMessage()}\n");
    exit(1);
}

heading('KONEKSI YANG DIPERIKSA');
echo "Database  : {$schema}\n";
echo "Server    : {$info->db_version} ({$info->db_vendor})\n";
echo "Akun DB   : {$info->db_account}\n";
if (!str_ends_with(strtolower($schema), '_dev')) {
    warnAudit('Database tidak berakhiran _dev. Ini audit baca-saja, JANGAN lakukan perbaikan langsung di database ini.');
}
if (str_contains(strtolower((string) $info->db_vendor), 'mariadb')) {
    warnAudit('Server tampaknya MariaDB. Proyek SIAKAD menargetkan MySQL 8.');
}

$objects = $db->select('SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? ORDER BY TABLE_NAME', [$schema]);
$objectMap = [];
$counts = ['BASE TABLE' => 0, 'VIEW' => 0];
foreach ($objects as $object) {
    $name = (string) $object->TABLE_NAME;
    $type = (string) $object->TABLE_TYPE;
    $objectMap[$name] = $type;
    $counts[$type] = ($counts[$type] ?? 0) + 1;
}

heading('RINGKASAN OBJEK');
echo 'Tabel fisik: ' . ($counts['BASE TABLE'] ?? 0) . "\n";
echo 'VIEW asli : ' . ($counts['VIEW'] ?? 0) . "\n";

$expectedViews = [
    'v_guru_tanpa_akun', 'v_jadwal_lengkap', 'v_penugasan_aktif', 'v_periode_aktif',
    'v_pertemuan_tanpa_absensi', 'v_publik_berita', 'v_publik_ekstra',
    'v_publik_fasilitas', 'v_publik_galeri', 'v_publik_guru',
    'v_publik_pengumuman', 'v_publik_prestasi', 'v_publik_siswa',
    'v_publik_statistik', 'v_rekap_absensi_kelas', 'v_siswa_tanpa_akun',
];

heading('STATUS 16 VIEW YANG DIBUTUHKAN');
$badViewCount = 0;
foreach ($expectedViews as $name) {
    $type = $objectMap[$name] ?? 'TIDAK ADA';
    if ($type !== 'VIEW') {
        ++$badViewCount;
    }
    printf("%-31s : %s%s\n", $name, $type, $type === 'VIEW' ? '' : ' <-- PERIKSA');
}
echo "Jumlah VIEW belum benar: {$badViewCount}\n";

heading('FOREIGN KEY');
try {
    $fks = $db->select('SELECT CONSTRAINT_NAME, TABLE_NAME, REFERENCED_TABLE_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? ORDER BY TABLE_NAME, CONSTRAINT_NAME', [$schema]);
    echo 'Jumlah constraint FK: ' . count($fks) . "\n";
    foreach (array_slice($fks, 0, 12) as $fk) {
        echo " - {$fk->TABLE_NAME} -> {$fk->REFERENCED_TABLE_NAME} ({$fk->CONSTRAINT_NAME})\n";
    }
    if (count($fks) > 12) {
        echo ' ... (' . (count($fks) - 12) . " lainnya)\n";
    }
    if (count($fks) === 0) {
        warnAudit('Tidak ada FK fisik yang terlihat. KEY bernama fk_* saja bukan FOREIGN KEY.');
    }
} catch (\Throwable $e) {
    warnAudit('Gagal membaca FK: ' . $e->getMessage());
}

heading('TRIGGER, PROCEDURE, FUNCTION');
try {
    $nTriggers = $db->selectOne('SELECT COUNT(*) AS n FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA = ?', [$schema]);
    echo 'Trigger             : ' . $nTriggers->n . "\n";
} catch (\Throwable $e) {
    warnAudit('Gagal membaca trigger: ' . $e->getMessage());
}
try {
    $routines = $db->select('SELECT ROUTINE_TYPE, ROUTINE_NAME FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ? ORDER BY ROUTINE_TYPE, ROUTINE_NAME', [$schema]);
    $nFunctions = count(array_filter($routines, fn ($row) => $row->ROUTINE_TYPE === 'FUNCTION'));
    $nProcedures = count(array_filter($routines, fn ($row) => $row->ROUTINE_TYPE === 'PROCEDURE'));
    echo "Procedure           : {$nProcedures}\n";
    echo "Function            : {$nFunctions}\n";
    foreach ($routines as $routine) {
        echo " - {$routine->ROUTINE_TYPE}: {$routine->ROUTINE_NAME}\n";
    }
    if (!$routines) {
        warnAudit('Routine tidak terlihat. Bisa memang kosong atau akun tidak memiliki hak melihat definisinya.');
    }
} catch (\Throwable $e) {
    warnAudit('Gagal membaca routines: ' . $e->getMessage());
}

heading('TABEL INTI SIAKAD');
$critical = [
    'm_guru', 'm_siswa', 'm_kelas', 'm_mapel', 't_guru_mapel', 't_kelas_siswa',
    'siakad_users', 'siakad_jadwal', 'siakad_pertemuan', 'siakad_absensi_siswa',
    'siakad_materi', 'siakad_materi_kelas', 'migrations',
];
foreach ($critical as $name) {
    echo sprintf("%-31s : %s\n", $name, $objectMap[$name] ?? 'TIDAK ADA');
}

heading('KOLOM AUTENTIKASI');
if (isset($objectMap['siakad_users'])) {
    $cols = $db->select('SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION', [$schema, 'siakad_users']);
    echo 'siakad_users: ' . implode(', ', array_map(fn ($row) => $row->COLUMN_NAME, $cols)) . "\n";
    foreach (['id', 'username', 'password', 'role', 'id_guru', 'id_siswa', 'is_active', 'remember_token'] as $column) {
        if (!in_array($column, array_map(fn ($row) => $row->COLUMN_NAME, $cols), true)) {
            warnAudit("Kolom siakad_users.{$column} tidak ditemukan. Periksa kesesuaian dengan model Laravel.");
        }
    }
}

heading('CEK DATA RELASI INTI (JUMLAH ORPHAN, TANPA MENAMPILKAN DATA PRIBADI)');
$relations = [
    ['siakad_users', 'id_guru', 'm_guru', 'id'],
    ['siakad_users', 'id_siswa', 'm_siswa', 'id'],
    ['siakad_jadwal', 'id_guru_mapel', 't_guru_mapel', 'id'],
    ['siakad_pertemuan', 'jadwal_id', 'siakad_jadwal', 'id'],
    ['siakad_absensi_siswa', 'pertemuan_id', 'siakad_pertemuan', 'id'],
    ['siakad_absensi_siswa', 'id_siswa', 'm_siswa', 'id'],
    ['siakad_materi_kelas', 'materi_id', 'siakad_materi', 'id'],
];
foreach ($relations as [$child, $childCol, $parent, $parentCol]) {
    $label = "{$child}.{$childCol} -> {$parent}.{$parentCol}";
    if (($objectMap[$child] ?? '') !== 'BASE TABLE' || ($objectMap[$parent] ?? '') !== 'BASE TABLE') {
        echo "$label : dilewati (tabel belum ada)\n";
        continue;
    }
    try {
        // Nama tabel dan kolom statis dalam script, bukan input pengguna.
        $sql = "SELECT COUNT(*) AS n FROM `{$child}` AS c LEFT JOIN `{$parent}` AS p ON c.`{$childCol}` = p.`{$parentCol}` WHERE c.`{$childCol}` IS NOT NULL AND p.`{$parentCol}` IS NULL";
        $result = $db->selectOne($sql);
        echo "$label : {$result->n} orphan\n";
    } catch (\Throwable $e) {
        warnAudit("Tidak bisa cek {$label}: {$e->getMessage()}");
    }
}

heading('AKHIR AUDIT');
echo "Tidak ada perubahan database dilakukan oleh script ini.\n";
echo "Kirim output ini untuk menentukan urutan migration perbaikan di database DEV.\n";
