<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Bersihkan siakad_users sebelum pengetesan
        User::query()->delete();
        RateLimiter::clear('admin_utama|127.0.0.1');
        RateLimiter::clear('guru_test|127.0.0.1');
        RateLimiter::clear('siswa_test|127.0.0.1');
        RateLimiter::clear('user_nonaktif|127.0.0.1');
    }

    /**
     * [AUTH-001] Test render halaman login dan elemen form.
     */
    public function test_login_screen_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SIAKAD SMP Muhammadiyah 7');
        $response->assertSee('Username / Nomor Induk');
        $response->assertSee('name="password"', false);
        $response->assertSee('role="tablist"', false);
    }

    /**
     * [AUTH-001] Test login sukses untuk Admin mengarahkan ke /admin.
     */
    public function test_admin_can_login_and_redirects_to_admin_dashboard(): void
    {
        $admin = User::create([
            'username' => 'admin_utama',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'wajib_ganti_password' => false,
            'is_admin_utama' => true,
        ]);

        $response = $this->post('/login', [
            'username' => 'admin_utama',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * [AUTH-001] Test login sukses untuk Guru mengarahkan ke /guru-panel.
     */
    public function test_teacher_can_login_and_redirects_to_teacher_panel(): void
    {
        $guru = User::create([
            'username' => 'guru_test',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'id_guru' => 1,
            'is_active' => true,
            'wajib_ganti_password' => false,
        ]);

        $response = $this->post('/login', [
            'username' => 'guru_test',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/guru-panel');
        $this->assertAuthenticatedAs($guru);
    }

    /**
     * [AUTH-001] Test login sukses untuk Siswa mengarahkan ke /siswa-panel.
     */
    public function test_student_can_login_and_redirects_to_student_panel(): void
    {
        $siswa = User::create([
            'username' => '3901', // NIS
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'id_siswa' => 356,
            'is_active' => true,
            'wajib_ganti_password' => false,
        ]);

        $response = $this->post('/login', [
            'username' => '3901',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/siswa-panel');
        $this->assertAuthenticatedAs($siswa);
    }

    /**
     * [AUTH-001] Test login ditolak jika akun nonaktif (is_active = 0).
     */
    public function test_inactive_user_cannot_login(): void
    {
        User::create([
            'username' => 'user_nonaktif',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'is_active' => false,
            'wajib_ganti_password' => false,
        ]);

        $response = $this->from('/login')->post('/login', [
            'username' => 'user_nonaktif',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    /**
     * [AUTH-001] Test login gagal dengan pesan kesalahan generik.
     */
    public function test_login_fails_with_generic_error_message(): void
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'non_existent_user',
            'password' => 'wrong_password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest();
    }

    /**
     * [AUTH-001] Test rate limiting setelah 5x gagal.
     */
    public function test_login_rate_limiting_triggers_after_repeated_failures(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'username' => 'spam_user',
                'password' => 'wrong_pass',
            ]);
        }

        // Percobaan ke-6 harus ditolak rate limiter
        $response = $this->post('/login', [
            'username' => 'spam_user',
            'password' => 'wrong_pass',
        ]);

        $response->assertSessionHasErrors('username');
        $errors = session('errors')->get('username');
        $this->assertStringContainsString('Terlalu banyak percobaan gagal', $errors[0]);
    }

    /**
     * [AUTH-002] Test mode wajib ganti password mengalihkan user ke /ubah-password.
     */
    public function test_user_with_wajib_ganti_password_is_forced_to_change_password(): void
    {
        $user = User::create([
            'username' => 'siswa_baru',
            'password' => Hash::make('temp12345'),
            'role' => 'siswa',
            'is_active' => true,
            'wajib_ganti_password' => true,
        ]);

        // Login sukses, harus redirect ke /ubah-password
        $response = $this->post('/login', [
            'username' => 'siswa_baru',
            'password' => 'temp12345',
        ]);

        $response->assertRedirect(route('password.change'));

        // Coba akses dashboard siswa langsung, middleware harus mencegat dan mengalihkan kembali ke /ubah-password
        $dashboardResponse = $this->actingAs($user)->get('/siswa-panel');
        $dashboardResponse->assertRedirect(route('password.change'));
    }

    /**
     * [AUTH-002] Test validasi form ubah password:
     * - Password lama harus cocok
     * - Minimal 8 karakter
     * - Tidak boleh sama dengan username
     * - Konfirmasi harus cocok
     */
    public function test_change_password_validations(): void
    {
        $user = User::create([
            'username' => 'guru_matematika',
            'password' => Hash::make('passwordLama123'),
            'role' => 'guru',
            'is_active' => true,
            'wajib_ganti_password' => true,
        ]);

        // 1. Password lama salah
        $responseWrongOld = $this->actingAs($user)->post(route('password.change.update'), [
            'current_password' => 'passwordSalah',
            'password' => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);
        $responseWrongOld->assertSessionHasErrors('current_password');

        // 2. Kurang dari 8 karakter
        $responseShort = $this->actingAs($user)->post(route('password.change.update'), [
            'current_password' => 'passwordLama123',
            'password' => 'pendek',
            'password_confirmation' => 'pendek',
        ]);
        $responseShort->assertSessionHasErrors('password');

        // 3. Sama dengan username
        $responseSameUser = $this->actingAs($user)->post(route('password.change.update'), [
            'current_password' => 'passwordLama123',
            'password' => 'guru_matematika',
            'password_confirmation' => 'guru_matematika',
        ]);
        $responseSameUser->assertSessionHasErrors('password');

        // 4. Konfirmasi tidak cocok
        $responseMismatch = $this->actingAs($user)->post(route('password.change.update'), [
            'current_password' => 'passwordLama123',
            'password' => 'passwordBaru123',
            'password_confirmation' => 'passwordLain123',
        ]);
        $responseMismatch->assertSessionHasErrors('password');
    }

    /**
     * [AUTH-002] Test ubah password berhasil dan mematikan flag wajib_ganti_password.
     */
    public function test_change_password_success_clears_flag_and_redirects(): void
    {
        $user = User::create([
            'username' => 'guru_sukses',
            'password' => Hash::make('passwordLama123'),
            'role' => 'guru',
            'is_active' => true,
            'wajib_ganti_password' => true,
        ]);

        $response = $this->actingAs($user)->post(route('password.change.update'), [
            'current_password' => 'passwordLama123',
            'password' => 'passwordBaruKuat123',
            'password_confirmation' => 'passwordBaruKuat123',
        ]);

        $response->assertRedirect('/guru-panel');

        $user->refresh();
        $this->assertFalse($user->wajib_ganti_password);
        $this->assertTrue(Hash::check('passwordBaruKuat123', $user->password));

        // Sekarang user bisa akses dashboard tanpa dicegat
        $dashboardResponse = $this->actingAs($user)->get('/guru-panel');
        $dashboardResponse->assertStatus(200);
    }

    /**
     * [AUTH-001] Test logout membersihkan sesi autentikasi.
     */
    public function test_user_can_logout(): void
    {
        $user = User::create([
            'username' => 'admin_test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'wajib_ganti_password' => false,
        ]);

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}

