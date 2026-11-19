<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\Pertemuan;
use App\Models\Siswa;
use App\Models\Tahun;
use App\Models\User;
use App\Models\Walikelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GuruDashboardPanelTest extends TestCase
{
    protected User $guruBiasaUser;
    protected Guru $guruBiasa;
    protected User $guruWaliUser;
    protected Guru $guruWali;
    protected User $siswaUser;
    protected Siswa $siswa;
    protected Kelas $kelasA;
    protected Kelas $kelasB;
    protected Mapel $mapel;
    protected string $tasmAktif;
    protected string $taAktif;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tasmAktif = Tahun::getTasmAktif() ?? '20242';
        $this->taAktif = Tahun::getTaAktif() ?? '2024';

        // 1. Buat Kelas dan Mapel
        $this->kelasA = Kelas::firstOrCreate(['nama' => '7-GUR-A'], ['tingkat' => '7']);
        $this->kelasB = Kelas::firstOrCreate(['nama' => '7-GUR-B'], ['tingkat' => '7']);
        $this->mapel = Mapel::firstOrCreate(['kd_singkat' => 'GDSH1'], ['nama' => 'Mapel Guru Dashboard', 'kelompok' => 'A', 'tambahan_sub' => 'NO']);

        // 2. Guru Biasa (Bukan Wali Kelas)
        $this->guruBiasa = Guru::firstOrCreate(
            ['nip' => '198501012020011005'],
            ['nama' => 'Guru Pengampu Biasa', 'stat_data' => 'A', 'jk' => 'L', 'is_bk' => '0']
        );
        $this->guruBiasaUser = User::firstOrCreate(
            ['username' => 'guru_biasa_test'],
            [
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'id_guru' => $this->guruBiasa->id,
                'is_active' => true,
            ]
        );

        // Penugasan Mengajar untuk Guru Biasa
        $gmBiasa = GuruMapel::firstOrCreate([
            'id_guru' => $this->guruBiasa->id,
            'id_kelas' => $this->kelasA->id,
            'id_mapel' => $this->mapel->id,
            'tasm' => $this->tasmAktif,
        ]);

        // Jadwal Mengajar Hari Ini untuk Guru Biasa
        $mapHariIndo = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
        $namaHariIni = $mapHariIndo[Carbon::now()->dayOfWeekIso] ?? 'Senin';

        Jadwal::firstOrCreate(
            [
                'id_guru_mapel' => $gmBiasa->id,
                'tasm' => $this->tasmAktif,
                'hari' => $namaHariIni,
                'jam_mulai' => '08:00:00',
            ],
            [
                'jam_selesai' => '09:30:00',
                'aktif' => true,
            ]
        );

        // 3. Guru Merangkap Wali Kelas
        $this->guruWali = Guru::firstOrCreate(
            ['nip' => '198602022020011006'],
            ['nama' => 'Guru Wali Kelas Utama', 'stat_data' => 'A', 'jk' => 'P', 'is_bk' => '0']
        );
        $this->guruWaliUser = User::firstOrCreate(
            ['username' => 'guru_wali_test'],
            [
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'id_guru' => $this->guruWali->id,
                'is_active' => true,
            ]
        );

        // Hubungkan Guru Wali ke Kelas B sebagai Wali Kelas Aktif
        Walikelas::firstOrCreate([
            'id_guru' => $this->guruWali->id,
            'id_kelas' => $this->kelasB->id,
            'tasm' => $this->tasmAktif,
        ]);

        // Siswa di Kelas B
        $this->siswa = Siswa::firstOrCreate(
            ['nisn' => '0088776655'],
            ['nama' => 'Siswa Binaan Kelas B', 'stat_data' => 'A', 'jk' => 'L']
        );
        KelasSiswa::firstOrCreate(
            [
                'id_siswa' => $this->siswa->id,
                'id_kelas' => $this->kelasB->id,
                'ta' => $this->taAktif,
            ],
            ['status' => 'A']
        );

        // Siswa User
        $this->siswaUser = User::firstOrCreate(
            ['username' => 'siswa_penguji_dsh'],
            [
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'id_siswa' => $this->siswa->id,
                'is_active' => true,
            ]
        );
    }

    public function test_regular_teacher_can_view_teaching_dashboard_without_homeroom_panel()
    {
        $response = $this->actingAs($this->guruBiasaUser)->get(route('dashboard.guru'));

        $response->assertStatus(200);
        $response->assertSee('Guru Pengampu Biasa');
        $response->assertSee('Jadwal Mengajar Hari Ini');
        $response->assertSee('Mapel Guru Dashboard');
        $response->assertSee('Aplikasi Nilai RKM');

        // Pastikan tidak ada panel khusus perwalian untuk guru biasa
        $response->assertDontSee('Perwalian Kelas ' . $this->kelasB->nama);
        $response->assertDontSee('Siswa Perlu Perhatian (Alpa Tertinggi)');
    }

    public function test_homeroom_teacher_automatically_sees_homeroom_panel_and_rkm_shortcuts()
    {
        $response = $this->actingAs($this->guruWaliUser)->get(route('dashboard.guru'));

        $response->assertStatus(200);
        $response->assertSee('Guru Wali Kelas Utama');
        $response->assertSee('Wali Kelas ' . $this->kelasB->nama);
        $response->assertSee('Perwalian Kelas ' . $this->kelasB->nama);
        $response->assertSee('Total Peserta Didik');
        $response->assertSee('Tingkat Kehadiran');
        $response->assertSee('Aplikasi Nilai RKM');
        $response->assertSee('http://localhost:8080/rkm');
    }

    public function test_unauthenticated_or_non_teacher_users_are_forbidden()
    {
        // 1. Guest -> redirect ke login
        $guestResponse = $this->get(route('dashboard.guru'));
        $guestResponse->assertRedirect(route('login'));

        // 2. Siswa -> 403 Forbidden
        $siswaResponse = $this->actingAs($this->siswaUser)->get(route('dashboard.guru'));
        $siswaResponse->assertStatus(403);
    }
}
