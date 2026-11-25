<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminKenaikanKelasTest extends TestCase
{
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::create([
                'username' => 'admin_test_knk',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
                'wajib_ganti_password' => false,
                'is_admin_utama' => true,
            ]);
    }

    /**
     * KNK-1, KNK-2, KNK-6: Admin dapat membuka halaman pratinjau kenaikan kelas.
     */
    public function test_admin_can_view_kenaikan_kelas_page(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.akademik.kenaikan'));

        $response->assertStatus(200);
        $response->assertSee('Kenaikan Kelas');
        $response->assertSee('t_naikkelas');
        $response->assertSee('Pilih Rombel Asal');
        $response->assertSee('Keputusan RKM');
        $response->assertSee('Konfirmasi Eksekusi Kenaikan');
    }

    /**
     * KNK-9: Siswa kelas 9 yang lulus ditandai "Lulus, tidak ditempatkan".
     */
    public function test_siswa_kelas_9_lulus_tidak_ditempatkan(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.akademik.kenaikan', [
                'tasm' => '20252',
                'kelas_asal_id' => 13, // IX-1
            ]));

        $response->assertStatus(200);
        $response->assertSee('Lulus, tidak ditempatkan');
        $response->assertSee('Tidak Ditempatkan (Alumni)');
    }

    /**
     * KNK-7 & Acceptance Criteria: Tidak ada penyimpanan otomatis sebelum konfirmasi admin.
     */
    public function test_eksekusi_requires_confirmation(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.akademik.kenaikan.eksekusi'), [
                'tasm_asal' => '20252',
                'ta_tujuan' => 2026,
                'siswa_ids' => [1],
                'kelas_tujuan' => [1 => 6],
                // Tanpa konfirmasi => 1
            ]);

        $response->assertSessionHasErrors('konfirmasi');
    }

    /**
     * KNK-7, ARCHITECTURE 8.8: Eksekusi penempatan tersimpan dengan benar di t_kelas_siswa.
     */
    public function test_eksekusi_penempatan_tersimpan_di_t_kelas_siswa(): void
    {
        DB::beginTransaction();

        try {
            // Buat mock data untuk pengujian transaksi
            $dummySiswa = Siswa::create([
                'nis' => '999998',
                'nama' => 'Siswa Uji Kenaikan Kelas',
                'stat_data' => 'A',
            ]);

            DB::table('t_kelas_siswa')->insert([
                'id_kelas' => 1, // VII-1
                'id_siswa' => $dummySiswa->id,
                'ta' => 2025,
            ]);

            DB::table('t_naikkelas')->insert([
                'id_siswa' => $dummySiswa->id,
                'ta' => '20252',
                'naik' => 'Y',
                'catatan_wali' => '',
            ]);

            $response = $this->actingAs($this->adminUser)
                ->post(route('admin.akademik.kenaikan.eksekusi'), [
                    'tasm_asal' => '20252',
                    'ta_tujuan' => 2026,
                    'konfirmasi' => 1,
                    'siswa_ids' => [$dummySiswa->id],
                    'kelas_tujuan' => [$dummySiswa->id => 6], // VIII-1
                ]);

            $response->assertRedirect();
            $response->assertSessionHas('success');

            // Verifikasi penempatan tersimpan di t_kelas_siswa
            $this->assertDatabaseHas('t_kelas_siswa', [
                'id_siswa' => $dummySiswa->id,
                'id_kelas' => 6,
                'ta' => 2026,
            ]);

            // Verifikasi log aktivitas tercatat
            $this->assertDatabaseHas('siakad_log_aktivitas', [
                'aksi' => 'KENAIKAN_KELAS',
                'entity_type' => 't_kelas_siswa',
            ]);

        } finally {
            DB::rollBack();
        }
    }
}
