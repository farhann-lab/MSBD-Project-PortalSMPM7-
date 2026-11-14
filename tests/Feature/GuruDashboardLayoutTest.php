<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class GuruDashboardLayoutTest extends TestCase
{
    /**
     * Test layout guru merender komponen sidebar dan topbar sesuai DESIGN.md Section 10.2 & 10.3.
     */
    public function test_guru_dashboard_renders_header_and_sidebar_correctly(): void
    {
        $guru = new User([
            'id_user' => 2,
            'username' => 'guru_budi',
            'role' => 'guru',
            'is_active' => true,
            'wajib_ganti_password' => false,
        ]);

        $response = $this->actingAs($guru)->get('/guru-panel');

        $response->assertStatus(200);

        // Sidebar Verifications (DESIGN.md Section 10.2 & 10.3)
        $response->assertSee('Sidebar Navigasi Guru');
        $response->assertSee('SIAKAD GURU');
        $response->assertSee('SMP Muhammadiyah 7');
        $response->assertSee('Menu Utama');
        $response->assertSee('Akademik &amp; Pembelajaran', false);
        $response->assertSee('Jadwal Mengajar');
        $response->assertSee('Kelas &amp; Mata Pelajaran', false);
        $response->assertSee('Input Absensi Siswa');
        $response->assertSee('Materi Pembelajaran');
        $response->assertSee('Aplikasi RKM Nilai');
        $response->assertSee('Lihat Web Publik');

        // Wali Kelas Sidebar Verifications (DESIGN.md Section 10.3)
        $response->assertSee('Perwalian (Kelas VIII-B)');
        $response->assertSee('Daftar Siswa Perwalian');
        $response->assertSee('Pemantauan Absensi Kelas');

        // Topbar (Header) Verifications (DESIGN.md Section 6.2 & 10.2)
        $response->assertSee('Dashboard Guru');
        $response->assertSee('2026/2027 Semester 1');
        $response->assertSee('Wali Kelas VIII-B');
        $response->assertSee('guru_budi');
        $response->assertSee('Pemberitahuan Guru');
        $response->assertSee('Keluar dari SIAKAD');

        // Workspace Statistics & Components (DESIGN.md Section 10.2)
        $response->assertSee('Selamat Datang');
        $response->assertSee('Rombel');
        $response->assertSee('Jadwal Hari Ini');
        $response->assertSee('Absensi Siswa');
        $response->assertSee('Materi Dikelola');
    }
}
