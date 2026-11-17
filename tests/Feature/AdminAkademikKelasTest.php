<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\Tahun;
use App\Models\User;
use App\Models\Walikelas;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminAkademikKelasTest extends TestCase
{
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::create([
                'username' => 'admin_test_kelas',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
                'wajib_ganti_password' => false,
                'is_admin_utama' => true,
            ]);
    }

    public function test_admin_can_view_kelas_index(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.master.kelas.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Kelas');
        $response->assertSee('Data Rombel Kelas');
        $response->assertSee('Penempatan Siswa');
        $response->assertSee('Penetapan Wali Kelas');
    }

    public function test_admin_can_create_and_update_kelas(): void
    {
        $namaUnik = 'TEST-KLS-' . rand(100, 999);

        // Create
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.master.kelas.store'), [
                'nama' => $namaUnik,
                'tingkat' => 7,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('m_kelas', [
            'nama' => $namaUnik,
            'tingkat' => 7,
        ]);

        $kelas = Kelas::where('nama', $namaUnik)->first();

        // Update
        $namaUpdated = $namaUnik . '-UPD';
        $updateResp = $this->actingAs($this->adminUser)
            ->put(route('admin.master.kelas.update', $kelas->id), [
                'nama' => $namaUpdated,
                'tingkat' => 8,
            ]);

        $updateResp->assertRedirect();
        $this->assertDatabaseHas('m_kelas', [
            'id' => $kelas->id,
            'nama' => $namaUpdated,
            'tingkat' => 8,
        ]);

        // Clean up
        $kelas->delete();
    }

    public function test_cannot_delete_kelas_with_active_relations(): void
    {
        $kelas = Kelas::create([
            'nama' => 'TEST-REL-' . rand(100, 999),
            'tingkat' => 9,
        ]);

        $siswa = Siswa::first();
        if (!$siswa) {
            $siswa = Siswa::create([
                'nama' => 'Test Siswa Rel',
                'stat_data' => 'A',
            ]);
        }

        // Buat relasi ke t_kelas_siswa
        $ks = KelasSiswa::create([
            'id_kelas' => $kelas->id,
            'id_siswa' => $siswa->id,
            'ta' => '2024',
        ]);

        // Coba hapus
        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.master.kelas.destroy', $kelas->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('m_kelas', ['id' => $kelas->id]);

        // Cleanup
        $ks->delete();
        $kelas->delete();
    }

    public function test_penempatan_siswa_and_prevent_duplicate_in_same_ta(): void
    {
        $kelas = Kelas::create([
            'nama' => 'TEST-PNS-' . rand(100, 999),
            'tingkat' => 7,
        ]);

        $siswa = Siswa::first();
        $ta = '2099'; // Gunakan TA dummy agar tidak bentrok

        // Penempatan pertama
        $resp1 = $this->actingAs($this->adminUser)
            ->post(route('admin.master.kelas.penempatan.store'), [
                'id_kelas' => $kelas->id,
                'ta' => $ta,
                'siswa_ids' => [$siswa->id],
            ]);

        $resp1->assertRedirect();
        $this->assertDatabaseHas('t_kelas_siswa', [
            'id_kelas' => $kelas->id,
            'id_siswa' => $siswa->id,
            'ta' => $ta,
        ]);

        // Penempatan kedua (harus dicegah PNS-5)
        $resp2 = $this->actingAs($this->adminUser)
            ->post(route('admin.master.kelas.penempatan.store'), [
                'id_kelas' => $kelas->id,
                'ta' => $ta,
                'siswa_ids' => [$siswa->id],
            ]);

        $resp2->assertRedirect();
        // Pastikan hanya ada 1 baris
        $count = KelasSiswa::where('id_siswa', $siswa->id)->where('ta', $ta)->count();
        $this->assertEquals(1, $count);

        // Cleanup
        KelasSiswa::where('id_siswa', $siswa->id)->where('ta', $ta)->delete();
        $kelas->delete();
    }

    public function test_pindah_rombel_siswa(): void
    {
        $kelasAsal = Kelas::create(['nama' => 'TEST-ORIG-' . rand(100, 999), 'tingkat' => 7]);
        $kelasTujuan = Kelas::create(['nama' => 'TEST-DEST-' . rand(100, 999), 'tingkat' => 7]);
        $siswa = Siswa::first();
        $ta = '2099';

        $ks = KelasSiswa::create([
            'id_kelas' => $kelasAsal->id,
            'id_siswa' => $siswa->id,
            'ta' => $ta,
        ]);

        $resp = $this->actingAs($this->adminUser)
            ->post(route('admin.master.kelas.penempatan.pindah'), [
                'id_kelas_siswa' => $ks->id,
                'id_kelas_tujuan' => $kelasTujuan->id,
            ]);

        $resp->assertRedirect();
        $this->assertDatabaseHas('t_kelas_siswa', [
            'id' => $ks->id,
            'id_kelas' => $kelasTujuan->id,
        ]);

        // Cleanup
        $ks->delete();
        $kelasAsal->delete();
        $kelasTujuan->delete();
    }

    public function test_penetapan_wali_kelas_business_rules(): void
    {
        $kelas1 = Kelas::create(['nama' => 'TEST-WK1-' . rand(100, 999), 'tingkat' => 7]);
        $kelas2 = Kelas::create(['nama' => 'TEST-WK2-' . rand(100, 999), 'tingkat' => 7]);
        
        $guru1 = Guru::aktif()->first();
        $guru2 = Guru::aktif()->skip(1)->first();
        $tasm = '20991';

        // 1. Sukses penetapan pertama
        $resp1 = $this->actingAs($this->adminUser)
            ->post(route('admin.master.kelas.walikelas.store'), [
                'tasm' => $tasm,
                'id_kelas' => $kelas1->id,
                'id_guru' => $guru1->id,
            ]);
        $resp1->assertRedirect();
        $this->assertDatabaseHas('t_walikelas', [
            'tasm' => $tasm,
            'id_kelas' => $kelas1->id,
            'id_guru' => $guru1->id,
        ]);

        // 2. Gagal: Kelas sudah punya walikelas (PWK-2)
        $resp2 = $this->actingAs($this->adminUser)
            ->post(route('admin.master.kelas.walikelas.store'), [
                'tasm' => $tasm,
                'id_kelas' => $kelas1->id,
                'id_guru' => $guru2->id,
            ]);
        $resp2->assertSessionHas('error');

        // 3. Gagal: Guru sudah jadi walikelas di kelas lain (PWK-3)
        $resp3 = $this->actingAs($this->adminUser)
            ->post(route('admin.master.kelas.walikelas.store'), [
                'tasm' => $tasm,
                'id_kelas' => $kelas2->id,
                'id_guru' => $guru1->id,
            ]);
        $resp3->assertSessionHas('error');

        // Cleanup
        Walikelas::where('tasm', $tasm)->delete();
        $kelas1->delete();
        $kelas2->delete();
    }

    public function test_guru_walikelas_detection(): void
    {
        $guru = Guru::aktif()->first();
        $kelas = Kelas::first();
        $tasm = '20991';

        $wk = Walikelas::create([
            'id_guru' => $guru->id,
            'id_kelas' => $kelas->id,
            'tasm' => $tasm,
        ]);

        $this->assertTrue($guru->isWaliKelas($tasm));

        $user = User::where('id_guru', $guru->id)->first();
        if ($user) {
            $this->assertTrue($user->isWaliKelas($tasm));
        }

        $wk->delete();
    }

    public function test_salin_wali_kelas_antar_periode(): void
    {
        $kelas = Kelas::create(['nama' => 'TEST-CPY-' . rand(100, 999), 'tingkat' => 8]);
        $guru = Guru::aktif()->first();
        $tasmAsal = '20981';
        $tasmTujuan = '20982';

        Walikelas::create([
            'id_guru' => $guru->id,
            'id_kelas' => $kelas->id,
            'tasm' => $tasmAsal,
        ]);

        $resp = $this->actingAs($this->adminUser)
            ->post(route('admin.master.kelas.walikelas.salin'), [
                'tasm_asal' => $tasmAsal,
                'tasm_tujuan' => $tasmTujuan,
            ]);

        $resp->assertRedirect();
        $this->assertDatabaseHas('t_walikelas', [
            'id_guru' => $guru->id,
            'id_kelas' => $kelas->id,
            'tasm' => $tasmTujuan,
        ]);

        // Cleanup
        Walikelas::whereIn('tasm', [$tasmAsal, $tasmTujuan])->delete();
        $kelas->delete();
    }
}
