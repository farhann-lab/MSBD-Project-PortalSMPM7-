<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class GuruSubmenuPagesTest extends TestCase
{
    private User $guru;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guru = new User([
            'id_user' => 2,
            'username' => 'guru_budi',
            'role' => 'guru',
            'is_active' => true,
            'wajib_ganti_password' => false,
        ]);
    }

    /**
     * Test halaman Jadwal Mengajar Guru dapat diakses dan merender grid mingguan.
     */
    public function test_jadwal_mengajar_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->guru)->get(route('guru.jadwal'));

        $response->assertStatus(200);
        $response->assertSee('Jadwal Mengajar Guru');
        $response->assertSee('SENIN');
        $response->assertSee('SELASA');
        $response->assertSee('RABU');
        $response->assertSee('KAMIS');
        $response->assertSee('IPA Terpadu');
        $response->assertSee('Kelas VII-B');
    }

    /**
     * Test halaman Kelas & Mata Pelajaran dapat diakses dan merender 4 rombel.
     */
    public function test_kelas_mapel_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->guru)->get(route('guru.kelas-mapel'));

        $response->assertStatus(200);
        $response->assertSee('Kelas &amp; Mata Pelajaran Diampu', false);
        $response->assertSee('Kelas VII-A');
        $response->assertSee('Kelas VII-B');
        $response->assertSee('Kelas VIII-A');
        $response->assertSee('Kelas VIII-B');
        $response->assertSee('Kelas Perwalian Anda');
    }

    /**
     * Test halaman Input Absensi Siswa dapat diakses dan merender form presensi.
     */
    public function test_absensi_siswa_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->guru)->get(route('guru.absensi'));

        $response->assertStatus(200);
        $response->assertSee('Input Absensi Peserta Didik');
        $response->assertSee('Kelas Rombel');
        $response->assertSee('Tandai Semua Hadir');
        $response->assertSee('Ahmad Rizky Pratama');
        $response->assertSee('Simpan Presensi Pertemuan');
    }

    /**
     * Test halaman Daftar Siswa Perwalian dapat diakses dan merender data kelas VIII-B.
     */
    public function test_perwalian_siswa_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->guru)->get(route('guru.perwalian.siswa'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Siswa Perwalian');
        $response->assertSee('Perwalian Kelas VIII-B');
        $response->assertSee('Ahmad Rizky Pratama');
        $response->assertSee('Ketua Kelas');
        $response->assertSee('32');
    }

    /**
     * Test halaman Pemantauan Absensi Kelas perwalian dapat diakses dan merender rekapitulasi.
     */
    public function test_perwalian_absensi_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->guru)->get(route('guru.perwalian.absensi'));

        $response->assertStatus(200);
        $response->assertSee('Pemantauan Absensi Kelas Perwalian');
        $response->assertSee('Perhatian Wali Kelas');
        $response->assertSee('Rekapitulasi Presensi Kumulatif Kelas VIII-B');
        $response->assertSee('Bagas Wicaksono');
        $response->assertSee('Perhatian BK');
    }

    /**
     * Test halaman Catatan Siswa / BK dapat diakses dan merender feed pembinaan.
     */
    public function test_perwalian_catatan_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->guru)->get(route('guru.perwalian.catatan'));

        $response->assertStatus(200);
        $response->assertSee('Catatan Pembinaan Siswa &amp; Rekomendasi BK', false);
        $response->assertSee('Tambah Catatan Pembinaan');
        $response->assertSee('Koordinasi Ketidakhadiran 3 Hari Berturut-turut');
        $response->assertSee('Juara 1 Olimpiade Matematika Tingkat Kota Medan');
    }

    /**
     * Test halaman Materi Pembelajaran Guru dapat diakses dan merender materi yang diampu.
     */
    public function test_guru_materi_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->guru)->get(route('guru.materi'));

        $response->assertStatus(200);
        $response->assertSee('Materi &amp; Bahan Ajar Pembelajaran', false);
        $response->assertSee('Akademik &amp; Pembelajaran', false);
        $response->assertSee('Sistem Gerak pada Manusia &amp; Tumbuhan', false);
        $response->assertSee('Video Eksperimen: Pembuktian Fotosintesis');
        $response->assertSee('Besaran, Satuan, dan Pengukuran Sains');
        $response->assertSee('Unggah Materi Baru');
    }
}
