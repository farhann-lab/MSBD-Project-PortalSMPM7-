<?php

namespace Tests\Feature;

use App\Models\AbsensiSiswa;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GuruAbsensiSiswaTest extends TestCase
{
    protected User $guruUser;
    protected Guru $guru;
    protected User $guruLainUser;
    protected Guru $guruLain;
    protected User $siswaUser;
    protected Siswa $siswa;
    protected Kelas $kelas;
    protected Mapel $mapel;
    protected Jadwal $jadwal;
    protected string $tasmAktif;
    protected string $taAktif;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tasmAktif = Tahun::getTasmAktif() ?? '20242';
        $this->taAktif = Tahun::getTaAktif() ?? '2024';

        // 1. Buat Kelas dan Mapel
        $this->kelas = Kelas::firstOrCreate(['nama' => '7-ABS'], ['tingkat' => '7']);
        $this->mapel = Mapel::firstOrCreate(['kd_singkat' => 'ABS1'], ['nama' => 'Mapel Absensi 1', 'kelompok' => 'A', 'tambahan_sub' => 'NO']);

        // 2. Buat Guru Pengampu
        $this->guru = Guru::firstOrCreate(
            ['nip' => '199001012020011009'],
            ['nama' => 'Guru Pengampu Absensi', 'stat_data' => 'P', 'jk' => 'L', 'is_bk' => '0']
        );
        $this->guruUser = User::firstOrCreate(
            ['username' => 'guru_abs_utama'],
            [
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'id_guru' => $this->guru->id,
                'is_active' => true,
            ]
        );

        // 3. Buat Guru Lain (Bukan Pengampu Jadwal ini)
        $this->guruLain = Guru::firstOrCreate(
            ['nip' => '199002022020021008'],
            ['nama' => 'Guru Lain Asing', 'stat_data' => 'P', 'jk' => 'L', 'is_bk' => '0']
        );
        $this->guruLainUser = User::firstOrCreate(
            ['username' => 'guru_abs_asing'],
            [
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'id_guru' => $this->guruLain->id,
                'is_active' => true,
            ]
        );

        // 4. Buat Siswa dan Penempatan Kelas pada TA Aktif
        $this->siswa = Siswa::firstOrCreate(
            ['nis' => '889901'],
            ['nama' => 'Siswa Hadir Penguji', 'jk' => 'L', 'stat_data' => 'A']
        );
        $this->siswaUser = User::firstOrCreate(
            ['username' => '889901'],
            [
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'id_siswa' => $this->siswa->id,
                'is_active' => true,
            ]
        );

        KelasSiswa::firstOrCreate([
            'ta' => $this->taAktif,
            'id_kelas' => $this->kelas->id,
            'id_siswa' => $this->siswa->id,
        ]);

        // 5. Penugasan Guru Mapel dan Jadwal
        $gm = GuruMapel::firstOrCreate([
            'tasm' => $this->tasmAktif,
            'id_guru' => $this->guru->id,
            'id_kelas' => $this->kelas->id,
            'id_mapel' => $this->mapel->id,
        ]);

        $this->jadwal = Jadwal::firstOrCreate([
            'id_guru_mapel' => $gm->id,
            'tasm' => $this->tasmAktif,
            'hari' => 'Rabu',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:20:00',
            'aktif' => 1,
        ]);
    }

    public function test_guru_can_view_absensi_page_and_teaching_schedules(): void
    {
        $response = $this->actingAs($this->guruUser)
            ->get(route('guru.absensi'));

        $response->assertStatus(200);
        $response->assertSee('Input &amp; Kelola Absensi Peserta Didik', false);
        $response->assertSee('Kelas ' . $this->kelas->nama);
        $response->assertSee($this->mapel->nama);
    }

    public function test_guru_can_open_new_meeting_session(): void
    {
        $response = $this->actingAs($this->guruUser)
            ->post(route('guru.absensi.buka-sesi'), [
                'jadwal_id' => $this->jadwal->id,
                'tanggal' => date('Y-m-d'),
                'catatan' => 'Pertemuan Uji Coba Absensi 1',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('siakad_pertemuan', [
            'jadwal_id' => $this->jadwal->id,
            'tanggal' => date('Y-m-d'),
            'catatan' => 'Pertemuan Uji Coba Absensi 1',
            'dibuat_oleh' => $this->guruUser->id,
        ]);
    }

    public function test_unauthorized_teacher_cannot_open_session_for_other_teacher_schedule(): void
    {
        // Guru lain mencoba membuka sesi pada jadwal milik Guru Utama
        $response = $this->actingAs($this->guruLainUser)
            ->post(route('guru.absensi.buka-sesi'), [
                'jadwal_id' => $this->jadwal->id,
                'tanggal' => date('Y-m-d'),
            ]);

        $response->assertStatus(403);
    }

    public function test_guru_can_save_and_update_attendance_records(): void
    {
        // Hitung sesi_ke berikutnya agar unik
        $sesiKe = (Pertemuan::where('jadwal_id', $this->jadwal->id)->max('sesi_ke') ?? 0) + 1;

        // Buat sesi pertemuan
        $pertemuan = Pertemuan::create([
            'jadwal_id' => $this->jadwal->id,
            'tanggal' => date('Y-m-d'),
            'sesi_ke' => $sesiKe,
            'catatan' => 'Sesi Input Presensi',
            'dibuat_oleh' => $this->guruUser->id,
        ]);

        // 1. Simpan kehadiran siswa sebagai Hadir (H)
        $resStore = $this->actingAs($this->guruUser)
            ->post(route('guru.absensi.simpan', $pertemuan->id), [
                'absensi' => [
                    $this->siswa->id => [
                        'status' => 'H',
                        'keterangan' => 'Tepat waktu',
                    ],
                ],
            ]);

        $resStore->assertRedirect();
        $resStore->assertSessionHas('success');

        $this->assertDatabaseHas('siakad_absensi_siswa', [
            'pertemuan_id' => $pertemuan->id,
            'id_siswa' => $this->siswa->id,
            'status' => 'H',
            'keterangan' => 'Tepat waktu',
        ]);

        // 2. Guru memperbarui status (Koreksi) menjadi Sakit (S) (ABS-6)
        $resUpdate = $this->actingAs($this->guruUser)
            ->post(route('guru.absensi.simpan', $pertemuan->id), [
                'absensi' => [
                    $this->siswa->id => [
                        'status' => 'S',
                        'keterangan' => 'Surat dokter terlampir',
                    ],
                ],
            ]);

        $resUpdate->assertRedirect();
        $resUpdate->assertSessionHas('success');

        $this->assertDatabaseHas('siakad_absensi_siswa', [
            'pertemuan_id' => $pertemuan->id,
            'id_siswa' => $this->siswa->id,
            'status' => 'S',
            'keterangan' => 'Surat dokter terlampir',
        ]);

        // 3. Siswa dapat melihat riwayat absensi pribadinya (SSW-5, SSW-6)
        $resSiswa = $this->actingAs($this->siswaUser)
            ->get(route('siswa.absensi'));

        $resSiswa->assertStatus(200);
        $resSiswa->assertSee('Sakit');
        $resSiswa->assertSee('Surat dokter terlampir');
        $resSiswa->assertSee($this->mapel->nama);

        // Bersihkan data uji
        AbsensiSiswa::where('pertemuan_id', $pertemuan->id)->delete();
        $pertemuan->delete();
    }
}
