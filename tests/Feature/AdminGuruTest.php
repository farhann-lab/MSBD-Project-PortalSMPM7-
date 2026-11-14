<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminGuruTest extends TestCase
{
    protected User $admin;
    protected Guru $testGuru;

    protected function setUp(): void
    {
        parent::setUp();

        // Cari atau buat admin untuk testing
        $this->admin = User::firstOrCreate(
            ['username' => 'test_admin_guru'],
            [
                'password'             => Hash::make('password123'),
                'role'                 => 'admin',
                'is_active'            => true,
                'wajib_ganti_password' => false,
                'is_admin_utama'       => true,
            ]
        );
    }

    protected function tearDown(): void
    {
        // Bersihkan data yang dibuat selama testing spesifik
        User::where('username', 'like', 'guru_unit_test%')->delete();
        Guru::where('nama', 'like', 'Guru Unit Test%')->delete();
        User::where('username', 'test_admin_guru')->delete();

        parent::tearDown();
    }

    /**
     * Test admin dapat melihat daftar guru (200 OK).
     */
    public function test_admin_can_view_guru_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master.guru.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Guru &amp; Tenaga Kependidikan', false);
        $response->assertSee('Tambah Guru Baru');
    }

    /**
     * Test admin dapat menambah guru baru tanpa akun login.
     */
    public function test_admin_can_store_guru_without_account(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.master.guru.store'), [
            'nama'      => 'Guru Unit Test Tanpa Akun',
            'nip'       => 'TEST99001',
            'jk'        => 'L',
            'is_bk'     => '2',
            'buat_akun' => '0',
        ]);

        $response->assertRedirect(route('admin.master.guru.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('m_guru', [
            'nama'      => 'Guru Unit Test Tanpa Akun',
            'nip'       => 'TEST99001',
            'stat_data' => 'A',
        ]);
    }

    /**
     * Test admin dapat menambah guru baru dengan akun login sekaligus.
     */
    public function test_admin_can_store_guru_with_account(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.master.guru.store'), [
            'nama'      => 'Guru Unit Test Dengan Akun',
            'nip'       => 'TEST99002',
            'jk'        => 'P',
            'is_bk'     => '1',
            'buat_akun' => '1',
            'username'  => 'guru_unit_test_auth',
            'password'  => 'rahasia123',
        ]);

        $response->assertRedirect(route('admin.master.guru.index'));
        $response->assertSessionHas('success');

        $guru = Guru::where('nip', 'TEST99002')->first();
        $this->assertNotNull($guru);

        $this->assertDatabaseHas('siakad_users', [
            'username'             => 'guru_unit_test_auth',
            'role'                 => 'guru',
            'id_guru'              => $guru->id,
            'is_active'            => 1,
            'wajib_ganti_password' => 1,
        ]);
    }

    /**
     * Test admin dapat mengedit data guru.
     */
    public function test_admin_can_update_guru(): void
    {
        $guru = Guru::create([
            'nama'      => 'Guru Unit Test Edit Asal',
            'nip'       => 'TEST99003',
            'jk'        => 'L',
            'is_bk'     => '2',
            'stat_data' => 'A',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.master.guru.update', $guru), [
            'nama'      => 'Guru Unit Test Edit Berhasil',
            'nip'       => 'TEST99003_REV',
            'jk'        => 'P',
            'is_bk'     => '1',
            'stat_data' => 'A',
        ]);

        $response->assertRedirect(route('admin.master.guru.index'));

        $this->assertDatabaseHas('m_guru', [
            'id'    => $guru->id,
            'nama'  => 'Guru Unit Test Edit Berhasil',
            'nip'   => 'TEST99003_REV',
            'jk'    => 'P',
            'is_bk' => '1',
        ]);
    }

    /**
     * Test toggle status mengubah stat_data di m_guru dan sinkronisasi is_active di siakad_users.
     */
    public function test_toggle_status_syncs_with_user_account(): void
    {
        $guru = Guru::create([
            'nama'      => 'Guru Unit Test Toggle',
            'nip'       => 'TEST99004',
            'jk'        => 'L',
            'is_bk'     => '2',
            'stat_data' => 'A',
        ]);

        $user = User::create([
            'username'             => 'guru_unit_test_toggle',
            'password'             => Hash::make('secret123'),
            'role'                 => 'guru',
            'id_guru'              => $guru->id,
            'is_active'            => true,
            'wajib_ganti_password' => false,
            'is_admin_utama'       => false,
        ]);

        // Toggle dari A (Aktif) -> P (Pensiun)
        $response = $this->actingAs($this->admin)->patchJson(route('admin.master.guru.toggle-status', $guru));
        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'status' => 'P', 'is_active' => false]);

        $guru->refresh();
        $user->refresh();

        $this->assertEquals('P', $guru->stat_data);
        $this->assertFalse((bool) $user->is_active);

        // Toggle kembali dari P -> A
        $response2 = $this->actingAs($this->admin)->patchJson(route('admin.master.guru.toggle-status', $guru));
        $response2->assertStatus(200);
        $response2->assertJson(['success' => true, 'status' => 'A', 'is_active' => true]);

        $guru->refresh();
        $user->refresh();

        $this->assertEquals('A', $guru->stat_data);
        $this->assertTrue((bool) $user->is_active);
    }

    /**
     * Test guru yang dinonaktifkan (is_active = 0) dicekal saat mencoba login.
     */
    public function test_inactive_guru_is_blocked_from_login(): void
    {
        $guru = Guru::create([
            'nama'      => 'Guru Unit Test Inactive',
            'nip'       => 'TEST99005',
            'jk'        => 'L',
            'is_bk'     => '2',
            'stat_data' => 'P', // Pensiun/Nonaktif
        ]);

        User::create([
            'username'             => 'guru_unit_test_inactive',
            'password'             => Hash::make('password123'),
            'role'                 => 'guru',
            'id_guru'              => $guru->id,
            'is_active'            => false, // Nonaktif
            'wajib_ganti_password' => false,
            'is_admin_utama'       => false,
        ]);

        $response = $this->post(route('login.post'), [
            'username' => 'guru_unit_test_inactive',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    /**
     * Test guru nonaktif tidak muncul di direktori publik /guru.
     */
    public function test_inactive_guru_does_not_appear_in_public_directory(): void
    {
        $guru = Guru::create([
            'nama'      => 'Guru Unit Test Nonaktif Direktori',
            'nip'       => 'TEST99006',
            'jk'        => 'L',
            'is_bk'     => '2',
            'stat_data' => 'P', // Nonaktif
        ]);

        $response = $this->get('/guru?q=Nonaktif+Direktori');
        $response->assertStatus(200);
        $response->assertDontSee('Guru Unit Test Nonaktif Direktori');
    }

    /**
     * Test soft-delete (destroy) mengubah stat_data = M dan is_active = 0.
     */
    public function test_admin_can_soft_delete_guru(): void
    {
        $guru = Guru::create([
            'nama'      => 'Guru Unit Test Soft Delete',
            'nip'       => 'TEST99007',
            'jk'        => 'P',
            'is_bk'     => '2',
            'stat_data' => 'A',
        ]);

        $user = User::create([
            'username'             => 'guru_unit_test_softdel',
            'password'             => Hash::make('password123'),
            'role'                 => 'guru',
            'id_guru'              => $guru->id,
            'is_active'            => true,
            'wajib_ganti_password' => false,
            'is_admin_utama'       => false,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.master.guru.destroy', $guru));
        $response->assertRedirect(route('admin.master.guru.index'));

        $guru->refresh();
        $user->refresh();

        $this->assertEquals('M', $guru->stat_data);
        $this->assertFalse((bool) $user->is_active);
    }
}
