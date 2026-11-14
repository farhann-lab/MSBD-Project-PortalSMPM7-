<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSiswaCrudTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Bersihkan data uji jika tersisa
        Siswa::where('nis', '99991')->delete();
        Siswa::where('nis', '99992')->delete();
        User::whereIn('username', ['99991', '99992'])->delete();
    }

    protected function tearDown(): void
    {
        Siswa::where('nis', '99991')->delete();
        Siswa::where('nis', '99992')->delete();
        User::whereIn('username', ['99991', '99992'])->delete();
        parent::tearDown();
    }

    private function getAdminUser(): User
    {
        return User::where('role', 'admin')->first() ?? User::create([
            'username'             => 'admin_test',
            'password'             => bcrypt('Admin@12345'),
            'role'                 => 'admin',
            'is_admin_utama'      => true,
            'is_active'            => true,
            'wajib_ganti_password' => false,
        ]);
    }

    public function test_admin_can_view_siswa_index_with_database_data(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.master.siswa.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.master.siswa.index');
        $response->assertSee('Manajemen Data Siswa (~30 Kolom Berkelompok)');
    }

    public function test_admin_can_create_siswa_with_only_mandatory_fields(): void
    {
        $admin = $this->getAdminUser();

        $data = [
            'nis'  => '99991',
            'nama' => 'Siswa Uji Coba Minimal',
        ];

        $response = $this->actingAs($admin)->post(route('admin.master.siswa.store'), $data);

        $response->assertRedirect(route('admin.master.siswa.index'));
        $response->assertSessionHas('success');

        // Pastikan tersimpan di m_siswa
        $this->assertDatabaseHas('m_siswa', [
            'nis'       => '99991',
            'nama'      => 'Siswa Uji Coba Minimal',
            'inputID'   => 0,
            'stat_data' => 'A',
        ]);
    }

    public function test_admin_can_create_siswa_with_all_30_fields_and_account(): void
    {
        $admin = $this->getAdminUser();

        $data = [
            'nis'             => '99992',
            'nama'            => 'Siswa Uji Coba Lengkap',
            'nisn'            => '0098765432',
            'jk'              => 'L',
            'tmp_lahir'       => 'Medan',
            'tgl_lahir'       => '2012-05-10',
            'agama'           => 'Islam',
            'status'          => 'AK',
            'anakke'          => 2,
            'alamat'          => 'Jl. Muhammadiyah No. 7',
            'notelp'          => '081299992222',
            'sek_asal'        => 'SD Muhammadiyah 01',
            'sek_asal_alamat' => 'Medan',
            'diterima_kelas'  => '7',
            'diterima_tgl'    => '2024-07-15',
            'diterima_smt'    => '1',
            'ijazah_no'       => 'DN-07/D-SD/2024',
            'ijazah_thn'      => '2024',
            'skhun_no'        => 'SKHUN-999',
            'skhun_thn'       => '2024',
            'ortu_ayah'       => 'Ahmad Sujono',
            'ortu_ibu'        => 'Siti Rahmah',
            'ortu_alamat'     => 'Jl. Muhammadiyah No. 7',
            'ortu_notelp'     => '081388884444',
            'ortu_ayah_pkj'   => 'Wiraswasta',
            'ortu_ibu_pkj'    => 'Guru',
            'wali'            => 'H. Usman',
            'wali_alamat'     => 'Medan',
            'notelp_rumah'    => '061777888',
            'wali_pkj'        => 'PNS',
            'buat_akun'       => '1',
        ];

        $response = $this->actingAs($admin)->post(route('admin.master.siswa.store'), $data);

        $response->assertRedirect(route('admin.master.siswa.index'));

        // Cek data di m_siswa
        $this->assertDatabaseHas('m_siswa', [
            'nis'       => '99992',
            'nama'      => 'Siswa Uji Coba Lengkap',
            'tmp_lahir' => 'Medan',
            'ortu_ayah' => 'Ahmad Sujono',
        ]);

        // Cek akun login di siakad_users
        $this->assertDatabaseHas('siakad_users', [
            'username'             => '99992',
            'role'                 => 'siswa',
            'is_active'            => 1,
            'wajib_ganti_password' => 1,
        ]);
    }

    public function test_admin_can_update_siswa_data(): void
    {
        $admin = $this->getAdminUser();

        $siswa = Siswa::create([
            'nis'       => '99991',
            'nama'      => 'Nama Awal',
            'stat_data' => 'A',
            'inputID'   => 0,
        ]);

        $updateData = [
            'nis'       => '99991',
            'nama'      => 'Nama Baru Terupdate',
            'tmp_lahir' => 'Jakarta',
            'stat_data' => 'A',
        ];

        $response = $this->actingAs($admin)->put(route('admin.master.siswa.update', $siswa), $updateData);

        $response->assertRedirect(route('admin.master.siswa.index'));
        $this->assertDatabaseHas('m_siswa', [
            'id'        => $siswa->id,
            'nama'      => 'Nama Baru Terupdate',
            'tmp_lahir' => 'Jakarta',
        ]);
    }

    public function test_admin_can_soft_delete_siswa_by_setting_stat_data_k(): void
    {
        $admin = $this->getAdminUser();

        $siswa = Siswa::create([
            'nis'       => '99991',
            'nama'      => 'Siswa Akan Dikeluarkan',
            'stat_data' => 'A',
            'inputID'   => 0,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.master.siswa.destroy', $siswa));

        $response->assertRedirect(route('admin.master.siswa.index'));
        $this->assertDatabaseHas('m_siswa', [
            'id'        => $siswa->id,
            'stat_data' => 'K',
        ]);
    }
}
