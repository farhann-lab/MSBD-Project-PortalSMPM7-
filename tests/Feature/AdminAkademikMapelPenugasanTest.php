<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminAkademikMapelPenugasanTest extends TestCase
{
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::create([
                'username' => 'admin_test_mapel',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
                'wajib_ganti_password' => false,
                'is_admin_utama' => true,
            ]);
    }

    public function test_admin_can_view_mapel_index(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.master.mapel.index'));

        $response->assertStatus(200);
        $response->assertSee('Mata Pelajaran');
        $response->assertSee('Tambah Mata Pelajaran');
    }

    public function test_admin_can_create_and_update_mapel(): void
    {
        $namaUnik = 'Mapel Test ' . rand(1000, 9999);
        $kdSingkat = 'T' . rand(10, 99);

        // 1. Create Mapel (MPL-2)
        $resp = $this->actingAs($this->adminUser)
            ->post(route('admin.master.mapel.store'), [
                'nama' => $namaUnik,
                'kd_singkat' => $kdSingkat,
                'kelompok' => 'A',
                'tambahan_sub' => 'NO',
                'is_sikap' => '0',
            ]);

        $resp->assertRedirect();
        $this->assertDatabaseHas('m_mapel', [
            'nama' => $namaUnik,
            'kd_singkat' => $kdSingkat,
            'kelompok' => 'A',
        ]);

        $mapel = Mapel::where('nama', $namaUnik)->first();

        // 2. Update Mapel (MPL-3)
        $namaUpdate = $namaUnik . ' Renamed';
        $updateResp = $this->actingAs($this->adminUser)
            ->put(route('admin.master.mapel.update', $mapel->id), [
                'nama' => $namaUpdate,
                'kd_singkat' => $kdSingkat,
                'kelompok' => 'B',
                'tambahan_sub' => 'MULOK',
                'is_sikap' => '1',
            ]);

        $updateResp->assertRedirect();
        $this->assertDatabaseHas('m_mapel', [
            'id' => $mapel->id,
            'nama' => $namaUpdate,
            'kelompok' => 'B',
            'tambahan_sub' => 'MULOK',
            'is_sikap' => '1',
        ]);

        // Clean up
        $mapel->delete();
    }

    public function test_cannot_delete_mapel_with_penugasan_dependencies(): void
    {
        $mapel = Mapel::create([
            'nama' => 'Mapel Relasi ' . rand(100, 999),
            'kd_singkat' => 'MR' . rand(10, 99),
            'kelompok' => 'A',
            'tambahan_sub' => 'NO',
            'is_sikap' => '0',
        ]);

        $guru = Guru::aktif()->first();
        $kelas = Kelas::first();

        // Buat penugasan
        $gm = GuruMapel::create([
            'tasm' => '20991',
            'id_guru' => $guru->id,
            'id_kelas' => $kelas->id,
            'id_mapel' => $mapel->id,
        ]);

        // Coba hapus mapel (MPL-4)
        $resp = $this->actingAs($this->adminUser)
            ->delete(route('admin.master.mapel.destroy', $mapel->id));

        $resp->assertRedirect();
        $resp->assertSessionHas('error');
        $this->assertDatabaseHas('m_mapel', ['id' => $mapel->id]);

        // Clean up
        $gm->delete();
        $mapel->delete();
    }

    public function test_admin_can_view_penugasan_index(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.master.penugasan.index'));

        $response->assertStatus(200);
        $response->assertSee('Penugasan Guru per Kelas');
        $response->assertSee('Tetapkan Penugasan Guru');
    }

    public function test_admin_can_assign_guru_to_classes_and_prevent_duplicate(): void
    {
        $guru = Guru::aktif()->first();
        $mapel = Mapel::first();
        $kelas1 = Kelas::first();
        $kelas2 = Kelas::skip(1)->first();
        $tasm = '20991';

        // 1. Assign ke dua kelas sekaligus (PGS-1, PGS-2)
        $resp = $this->actingAs($this->adminUser)
            ->post(route('admin.master.penugasan.store'), [
                'tasm' => $tasm,
                'id_guru' => $guru->id,
                'id_mapel' => $mapel->id,
                'kelas_ids' => [$kelas1->id, $kelas2->id],
            ]);

        $resp->assertRedirect();
        $this->assertDatabaseHas('t_guru_mapel', [
            'tasm' => $tasm,
            'id_guru' => $guru->id,
            'id_mapel' => $mapel->id,
            'id_kelas' => $kelas1->id,
        ]);
        $this->assertDatabaseHas('t_guru_mapel', [
            'tasm' => $tasm,
            'id_guru' => $guru->id,
            'id_mapel' => $mapel->id,
            'id_kelas' => $kelas2->id,
        ]);

        // 2. Cegah duplikasi penugasan yang sama (PGS-3)
        $respDup = $this->actingAs($this->adminUser)
            ->post(route('admin.master.penugasan.store'), [
                'tasm' => $tasm,
                'id_guru' => $guru->id,
                'id_mapel' => $mapel->id,
                'kelas_ids' => [$kelas1->id],
            ]);

        $respDup->assertRedirect();
        $count = GuruMapel::where('tasm', $tasm)
            ->where('id_guru', $guru->id)
            ->where('id_mapel', $mapel->id)
            ->where('id_kelas', $kelas1->id)
            ->count();
        $this->assertEquals(1, $count);

        // Clean up
        GuruMapel::where('tasm', $tasm)->where('id_guru', $guru->id)->delete();
    }

    public function test_salin_penugasan_guru_antar_semester(): void
    {
        $guru = Guru::aktif()->first();
        $mapel = Mapel::first();
        $kelas = Kelas::first();
        $tasmAsal = '20981';
        $tasmTujuan = '20982';

        GuruMapel::create([
            'tasm' => $tasmAsal,
            'id_guru' => $guru->id,
            'id_mapel' => $mapel->id,
            'id_kelas' => $kelas->id,
        ]);

        $resp = $this->actingAs($this->adminUser)
            ->post(route('admin.master.penugasan.salin'), [
                'tasm_asal' => $tasmAsal,
                'tasm_tujuan' => $tasmTujuan,
            ]);

        $resp->assertRedirect();
        $this->assertDatabaseHas('t_guru_mapel', [
            'tasm' => $tasmTujuan,
            'id_guru' => $guru->id,
            'id_mapel' => $mapel->id,
            'id_kelas' => $kelas->id,
        ]);

        // Clean up
        GuruMapel::whereIn('tasm', [$tasmAsal, $tasmTujuan])->delete();
    }

    public function test_cannot_delete_penugasan_with_nilai_dependencies(): void
    {
        $guru = Guru::aktif()->first();
        $mapel = Mapel::first();
        $kelas = Kelas::first();
        $tasm = '20991';

        $gm = GuruMapel::create([
            'tasm' => $tasm,
            'id_guru' => $guru->id,
            'id_mapel' => $mapel->id,
            'id_kelas' => $kelas->id,
        ]);

        // Tambah dummy nilai di t_nilai untuk menguji proteksi PGS-7
        $nilaiId = DB::table('t_nilai')->insertGetId([
            'tasm' => $tasm,
            'jenis' => 'harian',
            'id_guru_mapel' => $gm->id,
            'id_mapel_kd' => 1,
            'id_siswa' => 1,
            'nilai' => 85,
        ]);

        // Coba hapus (harus ditolak PGS-7)
        $resp = $this->actingAs($this->adminUser)
            ->delete(route('admin.master.penugasan.destroy', $gm->id));

        $resp->assertRedirect();
        $resp->assertSessionHas('error');
        $this->assertDatabaseHas('t_guru_mapel', ['id' => $gm->id]);

        // Hapus nilai dummy, lalu coba hapus lagi (harus berhasil)
        DB::table('t_nilai')->where('id', $nilaiId)->delete();

        $respSuccess = $this->actingAs($this->adminUser)
            ->delete(route('admin.master.penugasan.destroy', $gm->id));

        $respSuccess->assertRedirect();
        $respSuccess->assertSessionHas('success');
        $this->assertDatabaseMissing('t_guru_mapel', ['id' => $gm->id]);
    }
}
