<?php

// Jalankan dari root proyek: php scripts/check_role_connections.php
require dirname(__DIR__).'/vendor/autoload.php';
$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$expectedRoles = [
    'mysql_auth' => 'role_siakad_auth',
    'mysql_admin' => 'role_siakad_admin',
    'mysql_guru' => 'role_siakad_guru',
    'mysql_siswa' => 'role_siakad_siswa',
    'mysql_public' => 'role_siakad_publik',
];

$failures = 0;
foreach ($expectedRoles as $connection => $roleName) {
    try {
        $row = \Illuminate\Support\Facades\DB::connection($connection)
            ->selectOne('SELECT CURRENT_USER() AS account_name, CURRENT_ROLE() AS role_name, DATABASE() AS db_name');
        $actualRoles = (string) $row->role_name;
        $account = (string) $row->account_name;
        $database = (string) $row->db_name;
        $correctRole = str_contains($actualRoles, $roleName.'`') || str_contains($actualRoles, $roleName.'@');
        $correctDb = $database === (string) config('database.connections.'.$connection.'.database');
        $correctAccount = str_contains($account, $connection === 'mysql_public' ? 'siakad_public_user' :
            ($connection === 'mysql_auth' ? 'siakad_auth_user' :
            ($connection === 'mysql_admin' ? 'siakad_admin_user' :
            ($connection === 'mysql_guru' ? 'siakad_guru_user' : 'siakad_siswa_user'))));
        $ok = $correctRole && $correctDb && $correctAccount;
        if (! $ok) { $failures++; }
        echo sprintf("%s: %s | user=%s | role=%s | db=%s\n",
            $connection, $ok ? 'OK' : 'PERIKSA', $account, $actualRoles, $database);
    } catch (\Throwable $e) {
        $failures++;
        echo $connection.": GAGAL tersambung atau SELECT CURRENT_USER() ditolak. Periksa user, password, host dan default role.\n";
    }
}
exit($failures === 0 ? 0 : 1);
