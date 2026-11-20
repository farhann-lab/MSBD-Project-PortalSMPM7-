<?php

namespace Tests\Feature;

use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\MateriKelas;
use App\Models\Pertemuan;
use App\Models\Siswa;
use App\Models\Tahun;
use App\Models\User;
use App\Models\Walikelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SiswaDashboardPanelTest extends TestCase
{
    protected User $siswaUserA;
    protected Siswa $siswaA;
    protected User $siswaUserB;
    protected Siswa $siswaB;
    protected User $guruUser;
    protected Guru $guru;
    protected Kelas $kelasA;
    protected Kelas $kelasB;
    protected Mapel $mapelA;
    protected Mapel $mapelB;
    protected string $tasmAktif;
    protected string $taAktif;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tasmAktif = Tahun::getTasmAktif() ?? '20242';
        $this->taAktif = Tahun::getTaAktif() ?? '2024';

        // 1. Buat Kelas dan Mapel
        $this->kelasA = Kelas::firstOrCreate(['nama' => '7-SSW-A'], ['tingkat' => '7']);
        $this->kelasB = Kelas::firstOrCreate(['nama' => '7-SSW-B'], ['tingkat' => '7']);
        $this->mapelA = Mapel::firstOrCreate(['kd_singkat' => 'SDSH1'], ['nama' => 'IPA Khusus Kelas A', 'kelompok' => 'A', 'tambahan_sub' => 'NO']);
        $this->mapelB = Mapel::firstOrCreate(['kd_singkat' => 'SDSH2'], ['nama' => 'IPS Khusus Kelas B', 'kelompok' => 'A', 'tambahan_sub' => 'NO']);

        // 2. Buat Guru dan Wali Kelas untuk Kelas A
        $this->guru = Guru::firstOrCreate(
            ['nip' => '198701012020011007'],
            ['nama' => 'Budi Wali Kelas A', 'stat_data' => 'A', 'jk' => 'L', 'is_bk' => '0']
        );
        $this->guruUser = User::firstOrCreate(
            ['username' => 'guru_wali_a'],
            [
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'id_guru' => $this->guru->id,
                'is_active' => true,
            ]
        );
        Walikelas::firstOrCreate([
            'id_guru' => $this->guru->id,
            'id_kelas' => $this->kelasA->id,
            'tasm' => $this->tasmAktif,
        ]);

        // Penugasan Guru di Kelas A
        $gmA = GuruMapel::firstOrCreate([
            'id_guru' => $this->guru->id,
            'id_kelas' => $this->kelasA->id,
            'id_mapel' => $this->mapelA->id,
            'tasm' => $this->tasmAktif,
        ]);

        // Jadwal untuk Kelas A
        $mapHariIndo = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
        $namaHariIni = $mapHariIndo[Carbon::now()->dayOfWeekIso] ?? 'Senin';

        $jadwalA = Jadwal::firstOrCreate(
            [
                'id_guru_mapel' => $gmA->id,
                'tasm' => $this->tasmAktif,
                'hari' => $namaHariIni,
                'jam_mulai' => '07:30:00',
            ],
            [
                'jam_selesai' => '09:00:00',
                'aktif' => true,
            ]
        );

        // 3. Siswa A (Terdaftar di Kelas A)
        $this->siswaA = Siswa::firstOrCreate(
            ['nisn' => '0077889911'],
            ['nama' => 'Ahmad Siswa Kelas A', 'stat_data' => 'A', 'jk' => 'L']
        );
        $this->siswaUserA = User::firstOrCreate(
            ['username' => 'siswa_user_a'],
            [
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'id_siswa' => $this->siswaA->id,
                'is_active' => true,
            ]
        );
        KelasSiswa::firstOrCreate(
            [
                'id_siswa' => $this->siswaA->id,
                'id_kelas' => $this->kelasA->id,
                'ta' => $this->taAktif,
            ],
            ['status' => 'A']
        );

        // Riwayat Absensi Siswa A
        $pertemuanA = Pertemuan::firstOrCreate(
            [
                'jadwal_id' => $jadwalA->id,
                'tanggal' => Carbon::today()->toDateString(),
                'sesi_ke' => 1,
            ],
            ['materi' => 'Pertemuan Pembuka']
        );
        AbsensiSiswa::firstOrCreate(
            [
                'pertemuan_id' => $pertemuanA->id,
                'id_siswa' => $this->siswaA->id,
            ],
            ['status' => 'H']
        );

        // Materi untuk Kelas A
        $materiA = Materi::firstOrCreate(
            [
                'judul' => 'Modul IPA Khusus 7-SSW-A',
            ],
            [
                'id_guru' => $this->guru->id,
                'id_mapel' => $this->mapelA->id,
                'jenis' => 'tautan',
                'tautan_url' => 'https://example.com/modul-a',
                'status' => 'aktif',
            ]
        );
        MateriKelas::firstOrCreate([
            'materi_id' => $materiA->id,
            'id_guru_mapel' => $gmA->id,
        ]);

        // 4. Siswa B (Terdaftar di Kelas B)
        $this->siswaB = Siswa::firstOrCreate(
            ['nisn' => '0077889922'],
            ['nama' => 'Bintang Siswa Kelas B', 'stat_data' => 'A', 'jk' => 'P']
        );
        $this->siswaUserB = User::firstOrCreate(
            ['username' => 'siswa_user_b'],
            [
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'id_siswa' => $this->siswaB->id,
                'is_active' => true,
            ]
        );
        KelasSiswa::firstOrCreate(
            [
                'id_siswa' => $this->siswaB->id,
                'id_kelas' => $this->kelasB->id,
                'ta' => $this->taAktif,
            ],
            ['status' => 'A']
        );
    }

    public function test_student_can_view_dashboard_with_own_class_info_schedule_and_materials()
    {
        $response = $this->actingAs($this->siswaUserA)->get(route('dashboard.siswa'));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Siswa Kelas A');
        $response->assertSee('Kelas 7-SSW-A');
        $response->assertSee('Budi Wali Kelas A');
        $response->assertSee('IPA Khusus Kelas A');
        $response->assertSee('Modul IPA Khusus 7-SSW-A');
        $response->assertSee('100%'); // 1 Hadir dari 1 Pertemuan
    }

    public function test_student_cannot_see_materials_or_schedule_of_other_classes()
    {
        // Siswa B (di Kelas B) tidak boleh melihat materi atau jadwal milik Kelas A
        $response = $this->actingAs($this->siswaUserB)->get(route('dashboard.siswa'));

        $response->assertStatus(200);
        $response->assertSee('Bintang Siswa Kelas B');
        $response->assertSee('Kelas 7-SSW-B');
        $response->assertDontSee('IPA Khusus Kelas A');
        $response->assertDontSee('Modul IPA Khusus 7-SSW-A');
    }

    public function test_unauthenticated_or_non_student_users_cannot_access_student_dashboard()
    {
        // 1. Guest -> redirect ke login
        $guestResponse = $this->get(route('dashboard.siswa'));
        $guestResponse->assertRedirect(route('login'));

        // 2. Guru -> 403 Forbidden
        $guruResponse = $this->actingAs($this->guruUser)->get(route('dashboard.siswa'));
        $guruResponse->assertStatus(403);
    }
}
