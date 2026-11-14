<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('siakad_users')) {
            Schema::table('siakad_users', function (Blueprint $table) {
                if (!Schema::hasColumn('siakad_users', 'wajib_ganti_password')) {
                    $table->boolean('wajib_ganti_password')->default(false)->after('is_active');
                }
                if (!Schema::hasColumn('siakad_users', 'is_admin_utama')) {
                    $table->boolean('is_admin_utama')->default(false)->after('wajib_ganti_password');
                }
                $table->rememberToken()->nullable()->after('is_admin_utama');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('siakad_users')) {
            Schema::table('siakad_users', function (Blueprint $table) {
                $cols = array_filter(['wajib_ganti_password', 'is_admin_utama'], fn($c) => Schema::hasColumn('siakad_users', $c));
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
                $table->dropRememberToken();
            });
        }
    }
};

