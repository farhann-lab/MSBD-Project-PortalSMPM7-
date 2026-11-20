<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Fasilitas;
use App\Models\Galeri;
use App\Models\LogAktivitas;
use App\Models\Pengumuman;
use App\Models\PrestasiPublik;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminCmsLogAktivitasTest extends TestCase
{
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::create([
                'username' => 'admin_test_log',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
                'wajib_ganti_password' => false,
                'is_admin_utama' => true,
            ]);
    }

    public function test_creating_berita_triggers_log_aktivitas(): void
    {
        $judul = 'Uji Coba Berita Log ' . rand(1000, 9999);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.cms.berita.store'), [
                'judul' => $judul,
                'isi' => '<p>Konten berita uji log aktivitas</p>',
                'status' => 'draft',
            ]);

        $response->assertRedirect();

        $berita = Berita::where('judul', $judul)->first();
        $this->assertNotNull($berita);

        $this->assertDatabaseHas('siakad_log_aktivitas', [
            'aksi' => 'tambah',
            'entity_type' => 'berita',
            'entity_id' => $berita->id,
            'user_id' => $this->adminUser->id,
        ]);
    }

    public function test_updating_and_publishing_berita_triggers_log_aktivitas(): void
    {
        $berita = Berita::create([
            'judul' => 'Berita Awal ' . rand(1000, 9999),
            'slug' => 'berita-awal-' . rand(1000, 9999),
            'isi' => '<p>Konten awal</p>',
            'status' => 'draft',
            'penulis_user_id' => $this->adminUser->id,
        ]);

        // Update status menjadi published
        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.cms.berita.update', $berita->id), [
                'judul' => $berita->judul . ' (Updated)',
                'isi' => '<p>Konten diubah</p>',
                'status' => 'published',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('siakad_log_aktivitas', [
            'aksi' => 'terbit',
            'entity_type' => 'berita',
            'entity_id' => $berita->id,
            'user_id' => $this->adminUser->id,
        ]);
    }

    public function test_deleting_pengumuman_triggers_log_aktivitas(): void
    {
        $pengumuman = Pengumuman::create([
            'judul' => 'Pengumuman Uji Hapus ' . rand(1000, 9999),
            'isi' => '<p>Isi pengumuman</p>',
            'status' => 'draft',
            'penulis_user_id' => $this->adminUser->id,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.cms.pengumuman.destroy', $pengumuman->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('siakad_log_aktivitas', [
            'aksi' => 'hapus',
            'entity_type' => 'pengumuman',
            'entity_id' => $pengumuman->id,
            'user_id' => $this->adminUser->id,
        ]);
    }

    public function test_dashboard_displays_recent_activities(): void
    {
        // Masukkan log aktivitas dummy
        LogAktivitas::catat(
            $this->adminUser->id,
            'tambah',
            'fasilitas',
            101,
            'Menambahkan fasilitas Lab Komputer Modern'
        );

        $response = $this->actingAs($this->adminUser)
            ->get(route('dashboard.admin'));

        $response->assertStatus(200);
        $response->assertSee('Log Aktivitas');
        $response->assertSee('Menambahkan fasilitas Lab Komputer Modern');
    }
}
