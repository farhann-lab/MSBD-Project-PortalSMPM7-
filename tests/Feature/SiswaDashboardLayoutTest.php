<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class SiswaDashboardLayoutTest extends TestCase
{
    private User $siswa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->siswa = new User([
            'id_user' => 77,
            'username' => '3901',
            'role' => 'siswa',
            'is_active' => true,
            'wajib_ganti_password' => false,
        ]);
    }

    /**
     * Test layout siswa merender komponen sidebar, topbar, dan dashboard sesuai DESIGN.md 10.4.
     */
    public function test_siswa_dashboard_renders_header_and_sidebar_correctly(): void
    {
        $response = $this->actingAs($this->siswa)->get(route('dashboard.siswa'));

        $response->assertStatus(200);

        // Sidebar Siswa Verifications (DESIGN.md Section 10.4)
        $response->assertSee('Sidebar Navigasi Siswa');
        $response->assertSee('SIAKAD SISWA');
        $response->assertSee('SMP Muhammadiyah 7');
        $response->assertSee('Ahmad Rizky Pratama');
        $response->assertSee('Kelas VIII-B');
        $response->assertSee('Dashboard');
        $response->assertSee('Jadwal Pelajaran');
        $response->assertSee('Materi Pembelajaran');
        $response->assertSee('Absensi Saya');
        $response->assertSee('Profil &amp; Akun', false);
        $response->assertSee('Lihat Web Sekolah');
        $response->assertSee('Keluar dari SIAKAD');

        // Topbar Siswa Verifications
        $response->assertSee('Dashboard Siswa');
        $response->assertSee('2026/2027 Semester 1');
        $response->assertSee('Pemberitahuan Kelas');

        // Main Workspace Verifications (DESIGN.md Section 10.4)
        $response->assertSee('Assalamu\'alaikum, Ahmad Rizky Pratama!', false);
        $response->assertSee('Bpk. Budi Santoso, S.Pd.');
        $response->assertSee('Kehadiran Saya');
        $response->assertSee('100%');
        $response->assertSee('Jadwal Pelajaran Hari Ini (Senin)');
        $response->assertSee('Materi Pembelajaran Baru');
        $response->assertSee('Absensi Semester Ini');
    }

    /**
     * Test submenu Jadwal Pelajaran Siswa.
     */
    public function test_siswa_jadwal_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->siswa)->get(route('siswa.jadwal'));

        $response->assertStatus(200);
        $response->assertSee('Jadwal Pelajaran Mingguan');
        $response->assertSee('SENIN');
        $response->assertSee('SELASA');
        $response->assertSee('RABU');
        $response->assertSee('KAMIS');
        $response->assertSee('JUMAT');
        $response->assertSee('SABTU');
    }

    /**
     * Test submenu Materi Pembelajaran Siswa.
     */
    public function test_siswa_materi_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->siswa)->get(route('siswa.materi'));

        $response->assertStatus(200);
        $response->assertSee('Materi &amp; Bahan Belajar Mandiri', false);
        $response->assertSee('Sistem Gerak pada Manusia &amp; Tumbuhan', false);
        $response->assertSee('Unduh');
        $response->assertSee('Buka Video');
    }

    /**
     * Test submenu Absensi Saya.
     */
    public function test_siswa_absensi_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->siswa)->get(route('siswa.absensi'));

        $response->assertStatus(200);
        $response->assertSee('Catatan Kehadiran &amp; Presensi Saya', false);
        $response->assertSee('Persentase Kehadiran');
        $response->assertSee('100%');
        $response->assertSee('Riwayat Presensi Tatap Muka Terakhir');
    }

    /**
     * Test submenu Profil Siswa.
     */
    public function test_siswa_profil_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->siswa)->get(route('siswa.profil'));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Rizky Pratama');
        $response->assertSee('Biodata &amp; Informasi Akademik', false);
        $response->assertSee('Sekolah Asal &amp; Penerimaan', false);
        $response->assertSee('Ubah Password Akun');
    }
}
