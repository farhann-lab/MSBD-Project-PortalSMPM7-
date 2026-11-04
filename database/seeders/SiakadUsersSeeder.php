<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiakadUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::whereIn('username', ['admin', 'guru', '3901', 'guru_baru'])->delete();

        $accounts = [
            [
                'username'            => 'admin',
                'password'            => Hash::make('Admin@12345'),
                'role'                => 'admin',
                'is_active'           => true,
                'wajib_ganti_password'=> false,
                'is_admin_utama'      => true,
            ],
            [
                'username'            => 'guru',
                'password'            => Hash::make('Guru@12345'),
                'role'                => 'guru',
                'id_guru'             => 1,
                'is_active'           => true,
                'wajib_ganti_password'=> false,
            ],
            [
                'username'            => '3901',
                'password'            => Hash::make('Siswa@12345'),
                'role'                => 'siswa',
                'id_siswa'            => 356,
                'is_active'           => true,
                'wajib_ganti_password'=> false,
            ],
            [
                'username'            => 'guru_baru',
                'password'            => Hash::make('temp123456'),
                'role'                => 'guru',
                'id_guru'             => 2,
                'is_active'           => true,
                'wajib_ganti_password'=> true,
            ],
        ];

        foreach ($accounts as $data) {
            User::create($data);
        }

        $this->command->info('Seeder selesai: 4 akun dibuat.');
    }
}

