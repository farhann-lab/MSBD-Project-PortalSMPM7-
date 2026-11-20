<?php
/**
 * Diagnosa aman untuk lima koneksi role, tanpa menampilkan password.
 * Jalankan dari root Laravel: php scripts/diagnosa_role_mysql.php
 */

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';
$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$connections = [
    'mysql_auth' => ['siakad_auth_user', 'role_siakad_auth'],
    'mysql_admin' => ['siakad_admin_user', 'role_siakad_admin'],
    'mysql_guru' => ['siakad_guru_user', 'role_siakad_guru'],
    'mysql_siswa' => ['siakad_siswa_user', 'role_siakad_siswa'],
    'mysql_public' => ['siakad_public_user', 'role_siakad_publik'],
];

$failed = 0;
printf("Database default: %s | RBAC: %s\n", config('database.default'), config('siakad_rbac.enabled') ? 'aktif' : 'nonaktif');
foreach ($connections as $name => [$expectedUser, $expectedRole]) {
    $config = config('database.connections.'.$name);
    if (!is_array($config)) {
        echo "$name: ERROR KONFIGURASI -- koneksi belum didefinisikan\n";
        $failed++;
        continue;
    }
    $user = (string) ($config['username'] ?? '');
    $db = (string) ($config['database'] ?? '');
    $host = (string) ($config['host'] ?? '');
    if ($user === '' || !is_string($config['password'] ?? null) || $config['password'] === '') {
        echo "$name: ERROR ENV -- username/password kosong; user={$user}, host={$host}, database={$db}\n";
        $failed++;
        continue;
    }
    try {
        $row = \Illuminate\Support\Facades\DB::connection($name)->selectOne(
            'SELECT CURRENT_USER() AS db_user, CURRENT_ROLE() AS db_role, DATABASE() AS db_name'
        );
        $actualUser = (string) $row->db_user;
        $actualRole = (string) $row->db_role;
        $actualDb = (string) $row->db_name;
        $roleOk = preg_match('/(^|[, ])`?'.preg_quote($expectedRole, '/').'`?@/i', $actualRole) === 1;
        $userOk = explode('@', $actualUser, 2)[0] === $expectedUser;
        $dbOk = $actualDb === 'siakad_rkm';
        $ok = $roleOk && $userOk && $dbOk;
        if (!$ok) { $failed++; }
        printf("%s: %s | mysql_user=%s | active_role=%s | database=%s\n",
            $name, $ok ? 'OK' : 'PERIKSA ROLE/DB', $actualUser, $actualRole, $actualDb);
    } catch (\Throwable $e) {
        $failed++;
        $code = (string) $e->getCode();
        $mysqlCode = property_exists($e, 'errorInfo') ? ($e->errorInfo[1] ?? '-') : '-';
        printf("%s: GAGAL | jenis=%s | kode=%s | mysql=%s\n", $name, get_class($e), $code, (string) $mysqlCode);
        if ((string) $mysqlCode === '1045') {
            echo "  Petunjuk: akun MySQL belum ada, password salah, atau host user MySQL tidak cocok.\n";
        } elseif ((string) $mysqlCode === '1044') {
            echo "  Petunjuk: hak akses database belum diberikan atau default role belum aktif.\n";
        }
    }
}
exit($failed === 0 ? 0 : 1);
