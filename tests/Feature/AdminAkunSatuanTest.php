<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAkunSatuanTest extends TestCase
{
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::create([
                'username' => 'admin_test_satuan',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
                'wajib_ganti_password' => false,
                'is_admin_utama' => true,
            ]);
    }

    public function test_admin_can_create_single_account_for_guru(): void
    {
        $guru = Guru::create([
            'nama' => 'Guru Satuan Test ' . rand(100, 999),
            'nip' => '99999' . rand(1000, 9999),
            'jk' => 'L',
            'is_bk' => '2',
            'stat_data' => 'A',
        ]);

        $customUsername = 'guru_' . rand(1000, 9999);
        $customPassword = 'PasswordTest123';

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('admin.master.guru.buat-akun', $guru->id), [
                'username' => $customUsername,
                'password' => $customPassword,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'username' => $customUsername,
            'password_baru' => $customPassword,
        ]);

        $this->assertDatabaseHas('siakad_users', [
            'username' => $customUsername,
            'role' => 'guru',
            'id_guru' => $guru->id,
            'wajib_ganti_password' => true,
            'is_active' => true,
        ]);

        $createdUser = User::where('username', $customUsername)->first();
        $this->assertTrue(Hash::check($customPassword, $createdUser->password));

        // Test login paksa ganti password
        $this->post(route('login.post'), [
            'username' => $customUsername,
            'password' => $customPassword,
        ]);
        $this->assertAuthenticatedAs($createdUser);

        // Clean up
        $createdUser->delete();
        $guru->delete();
    }

    public function test_admin_can_create_single_account_for_siswa_with_nis_as_username(): void
    {
        $nisUnik = '888' . rand(1000, 9999);
        $siswa = Siswa::create([
            'nis' => $nisUnik,
            'nama' => 'Siswa Satuan Test',
            'jk' => 'P',
            'stat_data' => 'A',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('admin.master.siswa.buat-akun', $siswa->id), [
                'password' => 'SiswaRahasia123',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'username' => $nisUnik,
            'password_baru' => 'SiswaRahasia123',
        ]);

        $this->assertDatabaseHas('siakad_users', [
            'username' => $nisUnik,
            'role' => 'siswa',
            'id_siswa' => $siswa->id,
            'wajib_ganti_password' => true,
            'is_active' => true,
        ]);

        $createdUser = User::where('username', $nisUnik)->first();
        $this->assertTrue(Hash::check('SiswaRahasia123', $createdUser->password));

        // Clean up
        $createdUser->delete();
        $siswa->delete();
    }

    public function test_cannot_create_duplicate_account_for_same_guru_or_siswa(): void
    {
        // 1. Guru
        $guru = Guru::create([
            'nama' => 'Guru Dup Test',
            'stat_data' => 'A',
        ]);

        $userGuru = User::create([
            'username' => 'guru_dup_' . rand(1000, 9999),
            'password' => bcrypt('secret123'),
            'role' => 'guru',
            'id_guru' => $guru->id,
            'is_active' => true,
        ]);

        $respGuru = $this->actingAs($this->adminUser)
            ->postJson(route('admin.master.guru.buat-akun', $guru->id));

        $respGuru->assertStatus(422);
        $respGuru->assertJson(['success' => false]);

        // 2. Siswa
        $siswa = Siswa::create([
            'nis' => '777' . rand(1000, 9999),
            'nama' => 'Siswa Dup Test',
            'stat_data' => 'A',
        ]);

        $userSiswa = User::create([
            'username' => $siswa->nis,
            'password' => bcrypt('secret123'),
            'role' => 'siswa',
            'id_siswa' => $siswa->id,
            'is_active' => true,
        ]);

        $respSiswa = $this->actingAs($this->adminUser)
            ->postJson(route('admin.master.siswa.buat-akun', $siswa->id));

        $respSiswa->assertStatus(422);
        $respSiswa->assertJson(['success' => false]);

        // Clean up
        $userGuru->delete();
        $guru->delete();
        $userSiswa->delete();
        $siswa->delete();
    }
}
