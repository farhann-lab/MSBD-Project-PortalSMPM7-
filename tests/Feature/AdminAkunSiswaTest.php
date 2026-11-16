<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAkunSiswaTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Cari atau buat admin untuk pengujian
        $this->admin = User::firstOrCreate(
            ['username' => 'test_admin_akun_siswa'],
            [
                'password'             => Hash::make('password123'),
                'role'                 => 'admin',
                'is_active'            => true,
                'wajib_ganti_password' => false,
                'is_admin_utama'       => false, // Admin biasa (non-utama)
            ]
        );
    }

    protected function tearDown(): void
    {
        // Bersihkan data dummy khusus pengujian
        User::where('username', 'like', '9999%')->delete();
        Siswa::where('nis', 'like', '9999%')->delete();
        User::where('username', 'test_admin_akun_siswa')->delete();

        parent::tearDown();
    }

    /**
     * Test admin dapat melihat jumlah statistik siswa belum berakun di halaman master siswa.
     */
    public function test_admin_can_view_siswa_unregistered_account_stats(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master.siswa.index'));

        $response->assertStatus(200);
        $response->assertSee('Generate Akun Massal');
        $response->assertSee('Belum Berakun');
    }

    /**
     * Test generate akun massal membuat user di siakad_users dengan role='siswa', username=NIS,
     * wajib_ganti_password=1, dan is_active=1.
     */
    public function test_generate_akun_massal_creates_valid_student_accounts(): void
    {
        // Siapkan 2 siswa aktif tanpa akun
        $siswa1 = Siswa::create([
            'nis'            => '99991',
            'nama'           => 'Siswa Test Massal 1',
            'stat_data'      => 'A',
            'diterima_kelas' => 'VII-1',
        ]);

        $siswa2 = Siswa::create([
            'nis'            => '99992',
            'nama'           => 'Siswa Test Massal 2',
            'stat_data'      => 'A',
            'diterima_kelas' => 'VII-1',
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('admin.master.siswa.generate-akun-massal'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        // Verifikasi akun siswa 1
        $this->assertDatabaseHas('siakad_users', [
            'username'             => '99991',
            'role'                 => 'siswa',
            'id_siswa'             => $siswa1->id,
            'is_active'            => 1,
            'wajib_ganti_password' => 1,
        ]);

        // Verifikasi akun siswa 2
        $this->assertDatabaseHas('siakad_users', [
            'username'             => '99992',
            'role'                 => 'siswa',
            'id_siswa'             => $siswa2->id,
            'is_active'            => 1,
            'wajib_ganti_password' => 1,
        ]);
    }

    /**
     * Test generate akun massal bersifat idempotent: siswa yang sudah berakun dilewati secara aman,
     * tidak menimbulkan error duplikasi, dan diberikan keterangan status yang jelas.
     */
    public function test_generate_akun_massal_is_idempotent(): void
    {
        $siswa1 = Siswa::create([
            'nis'            => '99993',
            'nama'           => 'Siswa Test Idempotent 1',
            'stat_data'      => 'A',
            'diterima_kelas' => 'VII-2',
        ]);

        // Jalankan batch pertama
        $responseFirst = $this->actingAs($this->admin)->postJson(route('admin.master.siswa.generate-akun-massal'));

        $responseFirst->assertStatus(200);
        $userCountBefore = User::where('username', '99993')->count();
        $this->assertGreaterThanOrEqual(1, $userCountBefore);

        // Tambah siswa kedua yang belum punya akun
        $siswa2 = Siswa::create([
            'nis'            => '99997',
            'nama'           => 'Siswa Test Idempotent 2',
            'stat_data'      => 'A',
            'diterima_kelas' => 'VII-2',
        ]);

        // Jalankan batch kedua: siswa1 harus dilewati dengan keterangan, siswa2 berhasil dibuat
        $responseSecond = $this->actingAs($this->admin)->postJson(route('admin.master.siswa.generate-akun-massal'));

        $responseSecond->assertStatus(200);
        $data = $responseSecond->json();
        $this->assertTrue($data['success']);

        // Cari siswa2 di data dibuat
        $foundSiswa2 = collect($data['data'])->firstWhere('nis', '99997');
        $this->assertNotNull($foundSiswa2);

        // Cari siswa1 di data dilewati
        $foundSiswa1 = collect($data['dilewati'])->firstWhere('nis', '99993');
        $this->assertNotNull($foundSiswa1);
        $this->assertStringContainsString('Akun sudah ada', $foundSiswa1['keterangan']);

        // Jalankan batch ketiga: semua siswa aktif sudah berakun
        $responseThird = $this->actingAs($this->admin)->postJson(route('admin.master.siswa.generate-akun-massal'));

        $responseThird->assertStatus(200);
        $this->assertEquals(0, $responseThird->json('total_dibuat'));
        $this->assertStringContainsString('sudah memiliki akun', $responseThird->json('message'));
    }

    /**
     * Test admin dapat mereset password siswa satuan dan nilai password tersimpan ter-hash.
     */
    public function test_admin_can_reset_single_student_password(): void
    {
        $siswa = Siswa::create([
            'nis'            => '99994',
            'nama'           => 'Siswa Test Reset',
            'stat_data'      => 'A',
            'diterima_kelas' => 'VIII-1',
        ]);

        $user = User::create([
            'username'             => '99994',
            'password'             => Hash::make('passwordLama123'),
            'role'                 => 'siswa',
            'id_siswa'             => $siswa->id,
            'is_active'            => true,
            'wajib_ganti_password' => false,
            'is_admin_utama'       => false,
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('admin.master.siswa.reset-password', $siswa), [
            'password_baru' => 'PasswordBaru999!',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success'  => true,
            'username' => '99994',
        ]);

        $user->refresh();
        $this->assertTrue(Hash::check('PasswordBaru999!', $user->password));
        $this->assertTrue((bool) $user->wajib_ganti_password);
    }

    /**
     * Test siswa baru / yang direset berhasil login dan diarahkan ke halaman ubah password.
     */
    public function test_student_with_new_account_is_forced_to_change_password_on_login(): void
    {
        $siswa = Siswa::create([
            'nis'            => '99995',
            'nama'           => 'Siswa Test Login Force',
            'stat_data'      => 'A',
            'diterima_kelas' => 'IX-1',
        ]);

        User::create([
            'username'             => '99995',
            'password'             => Hash::make('TempPass123'),
            'role'                 => 'siswa',
            'id_siswa'             => $siswa->id,
            'is_active'            => true,
            'wajib_ganti_password' => true,
            'is_admin_utama'       => false,
        ]);

        $response = $this->post(route('login.post'), [
            'username' => '99995',
            'password' => 'TempPass123',
        ]);

        $this->assertAuthenticated();
        // Sesuai AuthController, jika wajib_ganti_password=1 diarahkan ke route password.change
        $response->assertRedirect(route('password.change'));
    }

    /**
     * Test admin biasa (non-admin_utama) tetap dapat mengakses fitur generate massal dan reset password.
     */
    public function test_regular_admin_can_access_student_account_features(): void
    {
        $siswa = Siswa::create([
            'nis'            => '99996',
            'nama'           => 'Siswa Regular Admin Test',
            'stat_data'      => 'A',
            'diterima_kelas' => 'VII-3',
        ]);

        // Cek bahwa admin biasa (is_admin_utama = false) tidak terkena 403 Forbidden
        $response = $this->actingAs($this->admin)->postJson(route('admin.master.siswa.reset-password', $siswa), [
            'password_baru' => 'RegularAdminPass123',
        ]);

        $response->assertStatus(200);
    }
}
