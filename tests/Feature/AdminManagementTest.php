<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    /**
     * Pastikan admin biasa dicekal 403 Forbidden saat membuka menu manajemen admin.
     */
    public function test_admin_biasa_gets_403_forbidden_on_admin_management(): void
    {
        $adminBiasa = new User([
            'id' => 999,
            'username' => 'admin_staff',
            'role' => 'admin',
            'is_active' => true,
            'wajib_ganti_password' => false,
            'is_admin_utama' => false,
        ]);

        $response = $this->actingAs($adminBiasa)->get('/admin/manajemen-admin');

        $response->assertStatus(403);
    }

    /**
     * Pastikan non-admin (misal: guru atau siswa) juga dicekal 403 Forbidden.
     */
    public function test_non_admin_gets_403_forbidden_on_admin_management(): void
    {
        $guru = new User([
            'id' => 998,
            'username' => 'guru_test',
            'role' => 'guru',
            'is_active' => true,
            'wajib_ganti_password' => false,
            'is_admin_utama' => false,
        ]);

        $response = $this->actingAs($guru)->get('/admin/manajemen-admin');

        $response->assertStatus(403);
    }

    /**
     * Pastikan Admin Utama berhasil membuka halaman manajemen admin dan sidebar menampilkan tautan menu.
     */
    public function test_admin_utama_can_view_management_page_and_sidebar_link(): void
    {
        $adminUtama = new User([
            'id' => 1,
            'username' => 'admin',
            'role' => 'admin',
            'is_active' => true,
            'wajib_ganti_password' => false,
            'is_admin_utama' => true,
        ]);

        $response = $this->actingAs($adminUtama)->get('/admin/manajemen-admin');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Akun Administrator');
        $response->assertSee('Hak Istimewa Admin Utama');
        $response->assertSee('Tambah Admin Baru');
        $response->assertSee('Manajemen Admin'); // Tautan di sidebar
    }

    /**
     * Pastikan sidebar admin biasa TIDAK menampilkan tautan 'Manajemen Admin'.
     */
    public function test_sidebar_does_not_show_management_link_for_regular_admin(): void
    {
        $adminBiasa = new User([
            'id' => 20,
            'username' => 'admin_biasa_test',
            'role' => 'admin',
            'is_active' => true,
            'wajib_ganti_password' => false,
            'is_admin_utama' => false,
        ]);

        $response = $this->actingAs($adminBiasa)->get('/admin');

        $response->assertStatus(200);
        $response->assertDontSee('sistem-manajemen-admin');
    }

    /**
     * Pastikan Admin Utama berhasil menambah akun admin baru dengan aturan hash bcrypt & wajib_ganti_password = 1.
     */
    public function test_admin_utama_can_create_new_admin_user(): void
    {
        // Cari atau gunakan akun admin utama di database
        $adminUtama = User::where('role', 'admin')->where('is_admin_utama', true)->first();

        // Pastikan username unik untuk pengujian
        $newUsername = 'test_admin_' . time();

        $response = $this->actingAs($adminUtama)->post('/admin/manajemen-admin', [
            'username' => $newUsername,
            'password' => 'passwordBaru123!',
        ]);

        $response->assertRedirect('/admin/manajemen-admin');
        $response->assertSessionHas('success');

        $created = User::where('username', $newUsername)->first();
        $this->assertNotNull($created);
        $this->assertEquals('admin', $created->role);
        $this->assertTrue((bool)$created->is_active);
        $this->assertTrue((bool)$created->wajib_ganti_password);
        $this->assertFalse((bool)$created->is_admin_utama);
        $this->assertTrue(Hash::check('passwordBaru123!', $created->password));

        // Bersihkan data uji
        $created->delete();
    }

    /**
     * Pastikan akun Admin Utama TIDAK BISA dinonaktifkan (Proteksi Aturan Bisnis).
     */
    public function test_admin_utama_cannot_be_deactivated(): void
    {
        $adminUtama = User::where('role', 'admin')->where('is_admin_utama', true)->first();

        $response = $this->actingAs($adminUtama)->patch("/admin/manajemen-admin/{$adminUtama->id}/toggle-status");

        $response->assertRedirect('/admin/manajemen-admin');
        $response->assertSessionHas('error');

        $adminUtama->refresh();
        $this->assertTrue((bool)$adminUtama->is_active);
    }

    /**
     * Pastikan Admin Utama dapat melakukan toggle status keaktifan pada admin biasa.
     */
    public function test_admin_utama_can_toggle_regular_admin_status(): void
    {
        $adminUtama = User::where('role', 'admin')->where('is_admin_utama', true)->first();

        $adminBiasa = User::create([
            'username' => 'toggle_test_' . time(),
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'wajib_ganti_password' => false,
            'is_admin_utama' => false,
        ]);

        // Toggle: aktif -> nonaktif
        $response = $this->actingAs($adminUtama)->patch("/admin/manajemen-admin/{$adminBiasa->id}/toggle-status");
        $response->assertRedirect('/admin/manajemen-admin');
        $adminBiasa->refresh();
        $this->assertFalse((bool)$adminBiasa->is_active);

        // Toggle: nonaktif -> aktif
        $response = $this->actingAs($adminUtama)->patch("/admin/manajemen-admin/{$adminBiasa->id}/toggle-status");
        $response->assertRedirect('/admin/manajemen-admin');
        $adminBiasa->refresh();
        $this->assertTrue((bool)$adminBiasa->is_active);

        $adminBiasa->delete();
    }

    /**
     * Pastikan reset password berfungsi dan mengatur wajib_ganti_password menjadi 1.
     */
    public function test_admin_utama_can_reset_password_for_admin(): void
    {
        $adminUtama = User::where('role', 'admin')->where('is_admin_utama', true)->first();

        $adminBiasa = User::create([
            'username' => 'reset_test_' . time(),
            'password' => Hash::make('oldPassword123'),
            'role' => 'admin',
            'is_active' => true,
            'wajib_ganti_password' => false,
            'is_admin_utama' => false,
        ]);

        $response = $this->actingAs($adminUtama)->post("/admin/manajemen-admin/{$adminBiasa->id}/reset-password", [
            'password_baru' => 'newPasswordSIAKAD2026',
        ]);

        $response->assertRedirect('/admin/manajemen-admin');
        $response->assertSessionHas('success');

        $adminBiasa->refresh();
        $this->assertTrue(Hash::check('newPasswordSIAKAD2026', $adminBiasa->password));
        $this->assertTrue((bool)$adminBiasa->wajib_ganti_password);

        $adminBiasa->delete();
    }
}
