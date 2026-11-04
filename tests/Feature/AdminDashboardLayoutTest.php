<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardLayoutTest extends TestCase
{
    /**
     * Test layout admin merender komponen sidebar dan topbar sesuai DESIGN.md.
     */
    public function test_admin_dashboard_renders_header_and_sidebar_correctly(): void
    {
        $admin = new User([
            'id_user' => 1,
            'username' => 'admin_test',
            'role' => 'admin',
            'is_active' => true,
            'wajib_ganti_password' => false,
            'is_admin_utama' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);

        // Sidebar Verifications (PRD & DESIGN.md Section 6.2 & 10.1)
        $response->assertSee('Sidebar Navigasi Admin');
        $response->assertSee('SIAKAD Panel');
        $response->assertSee('SMP Muhammadiyah 7');
        $response->assertSee('Menu Utama');
        $response->assertSee('Website Sekolah');
        $response->assertSee('Data Master');
        $response->assertSee('Akademik');
        $response->assertSee('Akun &amp; Sistem', false);
        $response->assertSee('Guru &amp; Tendik', false);
        $response->assertSee('Data Siswa &amp; Impor', false);
        $response->assertSee('Manajemen Kelas');
        $response->assertSee('Mata Pelajaran');
        $response->assertSee('Lihat Web Sekolah');

        // Topbar (Header) Verifications (DESIGN.md Section 6.2)
        $response->assertSee('Dashboard Admin');
        $response->assertSee('2026/2027 Semester 1');
        $response->assertSee('SIAKAD Aman');
        $response->assertSee('admin_test');
        $response->assertSee('Pemberitahuan Sistem');
        $response->assertSee('Keluar dari SIAKAD');

        // Main Workspace Verifications (DESIGN.md Section 10.1: Guru, Siswa, Kelas, Mapel, Tahun Ajaran)
        $response->assertSee('Statistik Utama Sekolah');
        $response->assertSee('356');
        $response->assertSee('17');
        $response->assertSee('10');
        $response->assertSee('14');
    }
}
