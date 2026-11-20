<?php

namespace Tests\Feature;

use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pertemuan;
use App\Models\Siswa;
use App\Models\Tahun;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminPemantauanAbsensiTest extends TestCase
{
    protected User $adminUser;
    protected User $guruUser;
    protected string $tasmAktif;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::create([
                'username' => 'admin_test_pas',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
                'wajib_ganti_password' => false,
                'is_admin_utama' => true,
            ]);

        $this->tasmAktif = Tahun::getTasmAktif() ?? '20242';
    }

    public function test_admin_can_view_pemantauan_absensi_index(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.akademik.absensi'));

        $response->assertStatus(200);
        $response->assertSee('Pemantauan Presensi Siswa');
        $response->assertSee('Mode Pemantauan (Read-Only)');
        $response->assertSee('Ketentuan Akses Admin (PRD PAS-5)');
        $response->assertSee('Tingkat Kehadiran');
    }

    public function test_admin_can_monitor_pertemuan_tanpa_absensi_and_filter(): void
    {
        // 1. Siapkan Kelas, Guru, Mapel, Siswa
        $kelas = Kelas::firstOrCreate(['nama' => '7-PAS Test'], ['tingkat' => '7']);
        $guru = Guru::firstOrCreate(['nip' => '8888888881'], ['nama' => 'Guru PAS 1', 'stat_data' => 'P']);
        $mapel = Mapel::firstOrCreate(['kd_singkat' => 'PAS1'], ['nama' => 'Mapel PAS 1', 'kelompok' => 'A', 'tambahan_sub' => 'NO']);

        // Akun Guru untuk pembuat
        $guruUser = User::firstOrCreate(
            ['username' => 'guru_pas_test'],
            [
                'password' => bcrypt('password123'),
                'role' => 'guru',
                'id_guru' => $guru->id,
                'is_active' => true,
            ]
        );

        $gm = GuruMapel::firstOrCreate([
            'tasm' => $this->tasmAktif,
            'id_guru' => $guru->id,
            'id_kelas' => $kelas->id,
            'id_mapel' => $mapel->id,
        ]);

        $jadwal = Jadwal::firstOrCreate([
            'id_guru_mapel' => $gm->id,
            'tasm' => $this->tasmAktif,
            'hari' => 'Selasa',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:20:00',
            'aktif' => 1,
        ]);

        // 2. Buat sesi pertemuan yang BELUM diisi absensi
        $pertemuanBelumAbst = Pertemuan::create([
            'jadwal_id' => $jadwal->id,
            'tanggal' => date('Y-m-d'),
            'sesi_ke' => 1,
            'catatan' => 'Sesi tanpa absensi untuk pengujian',
            'dibuat_oleh' => $guruUser->id,
        ]);

        // Cek halaman pemantauan absensi dengan filter kelas
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.akademik.absensi', [
                'tasm' => $this->tasmAktif,
                'id_kelas' => $kelas->id,
                'id_guru' => $guru->id,
            ]));

        $response->assertStatus(200);
        $response->assertSee('Pertemuan Belum Terabsen');
        $response->assertSee('Belum Diabsen');
        $response->assertSee($guru->nama);

        // Bersihkan data uji pertemuan
        $pertemuanBelumAbst->delete();
    }

    public function test_admin_cannot_mutate_attendance_records(): void
    {
        // Pastikan tidak ada route POST/PUT/DELETE untuk mutasi absensi pada route admin
        // Sesuai PAS-5: admin hanya melihat dan memantau, tidak mengubah catatan absensi guru pengampu
        $resPost = $this->actingAs($this->adminUser)
            ->post('/admin/akademik/absensi', [
                'status' => 'H',
            ]);

        // Harus 405 Method Not Allowed karena tidak disediakan route POST mutasi absensi oleh admin
        $this->assertEquals(405, $resPost->getStatusCode());
    }
}
