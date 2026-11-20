<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Tahun;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminJadwalPelajaranTest extends TestCase
{
    protected User $adminUser;
    protected string $tasmAktif;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::create([
                'username' => 'admin_test_jadwal',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
                'wajib_ganti_password' => false,
                'is_admin_utama' => true,
            ]);

        $this->tasmAktif = Tahun::getTasmAktif() ?? '20242';
    }

    public function test_admin_can_view_jadwal_page(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.akademik.jadwal'));

        $response->assertStatus(200);
        $response->assertSee('Plotting Jadwal Pelajaran');
        $response->assertSee('Pencegahan Bentrok Otomatis');
    }

    public function test_admin_can_create_jadwal_and_prevent_clash(): void
    {
        // Siapkan Kelas
        $kelasA = Kelas::firstOrCreate(
            ['nama' => '7A Test Jadwal'],
            ['tingkat' => '7']
        );

        $kelasB = Kelas::firstOrCreate(
            ['nama' => '7B Test Jadwal'],
            ['tingkat' => '7']
        );

        // Siapkan Guru
        $guru1 = Guru::firstOrCreate(
            ['nip' => '9999999991'],
            ['nama' => 'Guru Penguji 1', 'stat_data' => 'P']
        );

        $guru2 = Guru::firstOrCreate(
            ['nip' => '9999999992'],
            ['nama' => 'Guru Penguji 2', 'stat_data' => 'P']
        );

        // Siapkan Mapel
        $mapel = Mapel::firstOrCreate(
            ['kd_singkat' => 'TJ1'],
            ['nama' => 'Mapel Test Jadwal', 'kelompok' => 'A', 'tambahan_sub' => 'NO']
        );

        // Buat Penugasan Guru Mapel pada tasm aktif
        $gmA = GuruMapel::firstOrCreate([
            'tasm' => $this->tasmAktif,
            'id_guru' => $guru1->id,
            'id_kelas' => $kelasA->id,
            'id_mapel' => $mapel->id,
        ]);

        $gmB_guruSama = GuruMapel::firstOrCreate([
            'tasm' => $this->tasmAktif,
            'id_guru' => $guru1->id, // Guru 1 sama
            'id_kelas' => $kelasB->id,
            'id_mapel' => $mapel->id,
        ]);

        $gmA_guruBeda = GuruMapel::firstOrCreate([
            'tasm' => $this->tasmAktif,
            'id_guru' => $guru2->id, // Guru 2 beda
            'id_kelas' => $kelasA->id, // Kelas A sama
            'id_mapel' => $mapel->id,
        ]);

        // Bersihkan jadwal uji sebelumnya jika ada
        Jadwal::whereIn('id_guru_mapel', [$gmA->id, $gmB_guruSama->id, $gmA_guruBeda->id])->delete();

        // 1. Simpan jadwal pertama (Kelas A, Guru 1, Senin 08:00 - 09:20) -> Harus Berhasil
        $res1 = $this->actingAs($this->adminUser)
            ->post(route('admin.akademik.jadwal.store'), [
                'tasm' => $this->tasmAktif,
                'id_guru_mapel' => $gmA->id,
                'hari' => 'Senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '09:20',
            ]);

        $res1->assertSessionHas('success');
        $this->assertDatabaseHas('siakad_jadwal', [
            'tasm' => $this->tasmAktif,
            'id_guru_mapel' => $gmA->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:20:00',
            'aktif' => 1,
        ]);

        // 2. Coba simpan jadwal bentrok Guru (Guru 1 di Kelas B, Senin 08:30 - 09:50) -> Harus Ditolak Bentrok Guru
        $resBentrokGuru = $this->actingAs($this->adminUser)
            ->post(route('admin.akademik.jadwal.store'), [
                'tasm' => $this->tasmAktif,
                'id_guru_mapel' => $gmB_guruSama->id,
                'hari' => 'Senin',
                'jam_mulai' => '08:30',
                'jam_selesai' => '09:50',
            ]);

        $resBentrokGuru->assertSessionHas('error');
        $this->assertDatabaseMissing('siakad_jadwal', [
            'tasm' => $this->tasmAktif,
            'id_guru_mapel' => $gmB_guruSama->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:30:00',
        ]);

        // 3. Coba simpan jadwal bentrok Kelas (Kelas A diisi Guru 2, Senin 07:45 - 08:15) -> Harus Ditolak Bentrok Kelas
        $resBentrokKelas = $this->actingAs($this->adminUser)
            ->post(route('admin.akademik.jadwal.store'), [
                'tasm' => $this->tasmAktif,
                'id_guru_mapel' => $gmA_guruBeda->id,
                'hari' => 'Senin',
                'jam_mulai' => '07:45',
                'jam_selesai' => '08:15',
            ]);

        $resBentrokKelas->assertSessionHas('error');
        $this->assertDatabaseMissing('siakad_jadwal', [
            'tasm' => $this->tasmAktif,
            'id_guru_mapel' => $gmA_guruBeda->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:45:00',
        ]);

        // 4. Update jadwal pertama ke jam lain (Senin 10:00 - 11:20) -> Harus Berhasil
        $jadwalUtama = Jadwal::where('id_guru_mapel', $gmA->id)->first();
        $resUpdate = $this->actingAs($this->adminUser)
            ->put(route('admin.akademik.jadwal.update', $jadwalUtama->id), [
                'tasm' => $this->tasmAktif,
                'id_guru_mapel' => $gmA->id,
                'hari' => 'Senin',
                'jam_mulai' => '10:00',
                'jam_selesai' => '11:20',
            ]);

        $resUpdate->assertSessionHas('success');
        $this->assertDatabaseHas('siakad_jadwal', [
            'id' => $jadwalUtama->id,
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '11:20:00',
        ]);

        // 5. Hapus jadwal (Destroy) -> Harus terhapus permanen karena belum ada riwayat pertemuan
        $resDelete = $this->actingAs($this->adminUser)
            ->delete(route('admin.akademik.jadwal.destroy', $jadwalUtama->id));

        $resDelete->assertSessionHas('success');
        $this->assertDatabaseMissing('siakad_jadwal', [
            'id' => $jadwalUtama->id,
        ]);
    }

    public function test_admin_cannot_save_invalid_time_range(): void
    {
        $gm = GuruMapel::first();
        if (!$gm) {
            $this->markTestSkipped('Tidak ada penugasan guru mapel.');
        }

        // Jam mulai lebih besar dari jam selesai
        $res = $this->actingAs($this->adminUser)
            ->post(route('admin.akademik.jadwal.store'), [
                'tasm' => $gm->tasm,
                'id_guru_mapel' => $gm->id,
                'hari' => 'Senin',
                'jam_mulai' => '10:00',
                'jam_selesai' => '09:00',
            ]);

        $res->assertSessionHasErrors('jam_selesai');
    }
}
