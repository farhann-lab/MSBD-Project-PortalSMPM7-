<?php

// Koneksi layanan berbagi satu database. Tidak ada password atau host rahasia di source code.
$siakadRoleConnection = static function (string $role): array {
    return [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'siakad_rkm'),
        'username' => env('DB_'.$role.'_USERNAME'),
        'password' => env('DB_'.$role.'_PASSWORD'),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
    ];
};

return [
    'mysql_auth' => $siakadRoleConnection('AUTH'),
    'mysql_admin' => $siakadRoleConnection('ADMIN'),
    'mysql_guru' => $siakadRoleConnection('GURU'),
    'mysql_siswa' => $siakadRoleConnection('SISWA'),
    'mysql_public' => $siakadRoleConnection('PUBLIC'),
];
