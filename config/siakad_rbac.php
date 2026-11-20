<?php

return [
    // Aktivasi HANYA setelah 5 akun DB dan GRANT benar-benar tersedia di MySQL 8.0 DEV.
    // Saat false, aplikasi lama menggunakan koneksi default (tahap transisi).
    'enabled' => (bool) env('SIAKAD_RBAC_ENABLED', false),
    // Import ditunda sampai procedure staging MySQL 8 benar-benar operasional.
    'import_sp_ready' => (bool) env('SIAKAD_IMPORT_SP_READY', false),
];
