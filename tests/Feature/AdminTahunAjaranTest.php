<?php

namespace Tests\Feature;

use App\Models\Tahun;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminTahunAjaranTest extends TestCase
{
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::create([
                'username' => 'admin_test_taj',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
                'wajib_ganti_password' => false,
                'is_admin_utama' => true,
            ]);
    }

    public function test_admin_can_view_tahun_ajaran_index(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.master.tahun-ajaran.index'));

        $response->assertStatus(200);
        $response->assertSee('Periode Aktif Saat Ini');
        $response->assertSee('Daftar Periode Tahun Ajaran');
        $response->assertSee('Tambah Periode Baru');
    }

    public function test_admin_can_create_new_tahun_ajaran(): void
    {
        $kodeTasm = '20991';

        // Pastikan bersih sebelum test
        Tahun::where('tahun', $kodeTasm)->delete();

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.master.tahun-ajaran.store'), [
                'tahun' => $kodeTasm,
                'nama_kepsek' => 'Dr. H. Ahmad Dahlan, M.Pd.',
                'nip_kepsek' => '198001012005011001',
                'tgl_raport' => '2099-12-20',
                'tgl_raport_kelas3' => '2099-12-18',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tahun', [
            'tahun' => $kodeTasm,
            'aktif' => 'N',
            'nama_kepsek' => 'Dr. H. Ahmad Dahlan, M.Pd.',
        ]);

        // Clean up
        Tahun::where('tahun', $kodeTasm)->delete();
    }

    public function test_validation_prevents_duplicate_and_invalid_tasm_format(): void
    {
        $existing = Tahun::first();

        // Duplikat
        $respDup = $this->actingAs($this->adminUser)
            ->post(route('admin.master.tahun-ajaran.store'), [
                'tahun' => $existing->tahun,
                'nama_kepsek' => 'Kepsek Test',
                'tgl_raport' => '2025-06-20',
                'tgl_raport_kelas3' => '2025-06-15',
            ]);
        $respDup->assertSessionHasErrors(['tahun']);

        // Format invalid (misal digit semester = 3)
        $respInv = $this->actingAs($this->adminUser)
            ->post(route('admin.master.tahun-ajaran.store'), [
                'tahun' => '20253',
                'nama_kepsek' => 'Kepsek Test',
                'tgl_raport' => '2025-06-20',
                'tgl_raport_kelas3' => '2025-06-15',
            ]);
        $respInv->assertSessionHasErrors(['tahun']);
    }

    public function test_admin_can_update_tahun_ajaran_information(): void
    {
        $tahun = Tahun::create([
            'tahun' => '20992',
            'aktif' => 'N',
            'nama_kepsek' => 'Kepsek Awal',
            'nip_kepsek' => '12345678',
            'tgl_raport' => '2099-06-20',
            'tgl_raport_kelas3' => '2099-06-15',
        ]);

        $resp = $this->actingAs($this->adminUser)
            ->put(route('admin.master.tahun-ajaran.update', $tahun->id), [
                'nama_kepsek' => 'Kepsek Terupdate, M.Pd.',
                'nip_kepsek' => '87654321',
                'tgl_raport' => '2099-06-25',
                'tgl_raport_kelas3' => '2099-06-22',
            ]);

        $resp->assertRedirect();
        $this->assertDatabaseHas('tahun', [
            'id' => $tahun->id,
            'nama_kepsek' => 'Kepsek Terupdate, M.Pd.',
            'nip_kepsek' => '87654321',
        ]);

        $tahun->delete();
    }

    public function test_admin_can_activate_periode_and_switches_active_flag(): void
    {
        // Simpan periode aktif saat ini agar bisa dikembalikan di akhir test
        $currentActive = Tahun::getAktif();

        $tahunBaru = Tahun::create([
            'tahun' => '20991',
            'aktif' => 'N',
            'nama_kepsek' => 'Kepsek Aktif Test',
            'nip_kepsek' => '111222333',
            'tgl_raport' => '2099-12-20',
            'tgl_raport_kelas3' => '2099-12-18',
        ]);

        $resp = $this->actingAs($this->adminUser)
            ->post(route('admin.master.tahun-ajaran.aktifkan', $tahunBaru->id));

        $resp->assertRedirect();

        // Verifikasi tahunBaru sekarang aktif ('Y')
        $this->assertEquals('Y', $tahunBaru->fresh()->aktif);

        // Verifikasi hanya ada 1 baris aktif di tabel tahun
        $countAktif = Tahun::where('aktif', 'Y')->count();
        $this->assertEquals(1, $countAktif);

        // Verifikasi helper method merefleksikan perubahan
        $this->assertEquals('20991', Tahun::getTasmAktif());
        $this->assertEquals('2099', Tahun::getTaAktif());

        // Kembalikan ke periode semula
        if ($currentActive) {
            DB::statement("UPDATE tahun SET aktif = 'N'");
            DB::statement("UPDATE tahun SET aktif = 'Y' WHERE id = ?", [$currentActive->id]);
        }
        $tahunBaru->delete();
    }
}
