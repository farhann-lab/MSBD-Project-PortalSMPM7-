<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminMasterDataPagesTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = new User([
            'id_user' => 1,
            'username' => 'admin_utama',
            'role' => 'admin',
            'is_active' => true,
            'wajib_ganti_password' => false,
            'is_admin_utama' => true,
        ]);
    }

    /**
     * Test modul Master Guru & Tendik renders correctly.
     */
    public function test_admin_master_guru_page_renders(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master.guru.index'));
        $response->assertStatus(200);
        $response->assertSee('Data Guru &amp; Tendik', false);
        $response->assertSee('Tambah Guru Baru');
        $response->assertSee('Aktif Mengajar', false);
    }

    /**
     * Test modul Master Siswa & Impor Excel renders correctly.
     */
    public function test_admin_master_siswa_page_renders(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master.siswa'));
        $response->assertStatus(200);
        $response->assertSee('Data Siswa &amp; Impor Excel', false);
        $response->assertSee('Unduh Template Excel');
        $response->assertSee('SIAKAD Staging Guard');
        $response->assertSee('356');
    }

    /**
     * Test modul Master Kelas & Rombel renders correctly.
     */
    public function test_admin_master_kelas_page_renders(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master.kelas'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Kelas &amp; Rombel', false);
        $response->assertSee('Data Rombel Kelas');
        $response->assertSee('Penempatan Siswa');
        $response->assertSee('Penetapan Wali Kelas');
        $response->assertSee('7A');
    }

    /**
     * Test modul Master Mapel renders correctly.
     */
    public function test_admin_master_mapel_page_renders(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master.mapel'));
        $response->assertStatus(200);
        $response->assertSee('Data Mata Pelajaran');
        $response->assertSee('Pendidikan Agama Islam');
        $response->assertSee('Matematika');
        $response->assertSee('14 Mata Pelajaran');
    }

    /**
     * Test modul Master Penugasan Guru renders correctly.
     */
    public function test_admin_master_penugasan_page_renders(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master.penugasan'));
        $response->assertStatus(200);
        $response->assertSee('Penugasan Guru');
        $response->assertSee('Salin dari Semester Lalu');
        $response->assertSee('Tambah Penugasan');
    }

    /**
     * Test modul Master Tahun Ajaran renders correctly.
     */
    public function test_admin_master_tahun_ajaran_page_renders(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master.tahun-ajaran'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Tahun Ajaran &amp; Semester', false);
        $response->assertSee('Tepat Satu Periode Aktif');
        $response->assertSee('20261');
    }

    /**
     * Test modul Master Ekstrakurikuler renders correctly.
     */
    public function test_admin_master_ekskul_page_renders(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master.ekskul'));
        $response->assertStatus(200);
        $response->assertSee('Data Ekstrakurikuler');
        $response->assertSee('Futsal');
        $response->assertSee('Pramuka');
    }
}
