<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Mapel;
use App\Models\Pertemuan;
use App\Models\Siswa;
use App\Models\Tahun;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AdminDashboardAttendanceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_dashboard_counts_each_class_once_and_links_to_today_in_the_active_semester(): void
    {
        $this->travelTo(Carbon::parse('2026-10-04 19:00:00', 'UTC'));
        $tanggal = '2026-10-05';
        $tasm = '20261';
        Tahun::create([
            'tahun' => $tasm,
            'aktif' => 'Y',
            'nama_kepsek' => 'Kepala Sekolah Uji',
            'nip_kepsek' => '1020304050',
            'tgl_raport' => '2026-12-18',
            'tgl_raport_kelas3' => '2026-12-18',
        ]);
        $admin = User::create([
            'username' => 'admin_dash002_test',
            'password' => 'password-uji',
            'role' => 'admin',
            'is_active' => true,
        ]);
        $this->actingAs($admin)->get('/admin')->assertOk()
            ->assertSee('Belum ada absensi tercatat hari ini.');

        $guru = Guru::create(['nama' => 'Guru Uji DASH-002', 'nip' => '9990020001', 'stat_data' => 'A']);
        $akunGuru = User::create([
            'username' => 'guru_dash002_test',
            'password' => 'password-uji',
            'role' => 'guru',
            'id_guru' => $guru->id,
            'is_active' => true,
        ]);
        $mapel = Mapel::create(['nama' => 'Mapel Uji DASH-002', 'kd_singkat' => 'DSH2', 'kelompok' => 'A', 'tambahan_sub' => 'NO']);
        $kelasA = Kelas::create(['nama' => '7-DASH-A', 'tingkat' => '7']);
        $kelasB = Kelas::create(['nama' => '7-DASH-B', 'tingkat' => '7']);
        $siswaA = Siswa::create(['nama' => 'Siswa Uji A', 'nisn' => '9900200001', 'jk' => 'L', 'stat_data' => 'A']);
        $siswaB = Siswa::create(['nama' => 'Siswa Uji B', 'nisn' => '9900200002', 'jk' => 'P', 'stat_data' => 'A']);
        KelasSiswa::create(['id_kelas' => $kelasA->id, 'id_siswa' => $siswaA->id, 'ta' => '2026']);
        KelasSiswa::create(['id_kelas' => $kelasB->id, 'id_siswa' => $siswaB->id, 'ta' => '2026']);

        $jadwalA = $this->createJadwal($kelasA, $guru, $mapel, $tasm, '07:30:00', '08:30:00');
        $jadwalB = $this->createJadwal($kelasB, $guru, $mapel, $tasm, '09:00:00', '10:00:00');
        $jadwalSemesterLain = $this->createJadwal($kelasB, $guru, $mapel, '20262', '09:00:00', '10:00:00');

        foreach ([
            [$jadwalA, $tanggal, 1, $siswaA],
            [$jadwalA, $tanggal, 2, $siswaA],
            [$jadwalB, '2026-10-04', 1, $siswaB],
            [$jadwalSemesterLain, $tanggal, 1, $siswaB],
            [$jadwalB, $tanggal, 1, null],
        ] as [$jadwal, $tanggalPertemuan, $sesi, $siswa]) {
            $pertemuan = Pertemuan::create([
                'jadwal_id' => $jadwal->id,
                'tanggal' => $tanggalPertemuan,
                'sesi_ke' => $sesi,
                'dibuat_oleh' => $akunGuru->id,
            ]);
            if ($siswa) {
                $pertemuan->absensiSiswa()->create([
                    'id_siswa' => $siswa->id,
                    'status' => 'H',
                    'diubah_oleh' => $akunGuru->id,
                ]);
            }
        }

        $urlPemantauan = route('admin.akademik.absensi', [
            'tasm' => $tasm,
            'tanggal_mulai' => $tanggal,
            'tanggal_akhir' => $tanggal,
        ]);

        $this->get('/admin')->assertOk()
            ->assertSee('Absensi Hari Ini')
            ->assertViewHas('rombelTerabsen', 1)
            ->assertSee(e($urlPemantauan), false);

        $this->get($urlPemantauan)->assertOk()
            ->assertViewHas('selectedTasm', $tasm)
            ->assertViewHas('tanggalMulai', $tanggal)
            ->assertViewHas('tanggalAkhir', $tanggal)
            ->assertViewHas('totalHadir', 2);
    }

    private function createJadwal(Kelas $kelas, Guru $guru, Mapel $mapel, string $tasm, string $mulai, string $selesai): Jadwal
    {
        $penugasan = GuruMapel::create([
            'id_kelas' => $kelas->id,
            'id_guru' => $guru->id,
            'id_mapel' => $mapel->id,
            'tasm' => $tasm,
        ]);

        return Jadwal::create([
            'id_guru_mapel' => $penugasan->id,
            'tasm' => $tasm,
            'hari' => 'Senin',
            'jam_mulai' => $mulai,
            'jam_selesai' => $selesai,
        ]);
    }
}
