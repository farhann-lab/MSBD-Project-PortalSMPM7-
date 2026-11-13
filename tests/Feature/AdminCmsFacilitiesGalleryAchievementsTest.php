<?php

namespace Tests\Feature;

use App\Models\Fasilitas;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use App\Models\PrestasiPublik;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCmsFacilitiesGalleryAchievementsTest extends TestCase
{
    protected function getAdminUser(): User
    {
        return User::where('role', 'admin')->first() ?? User::create([
            'username'             => 'admin_cms_extra_test',
            'password'             => bcrypt('password123'),
            'role'                 => 'admin',
            'is_active'            => true,
            'wajib_ganti_password' => false,
            'is_admin_utama'       => true,
        ]);
    }

    /**
     * Test admin dapat mengelola modul fasilitas sekolah.
     */
    public function test_admin_can_manage_fasilitas(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        // 1. Index
        $responseIndex = $this->actingAs($admin)->get('/admin/cms/fasilitas');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Sarana &amp; Prasarana', false);

        // 2. Store
        $namaFasilitas = 'Laboratorium Bahasa Modern ' . time();
        $image = UploadedFile::fake()->image('lab.jpg', 640, 480);

        $responseStore = $this->actingAs($admin)->post('/admin/cms/fasilitas', [
            'nama'      => $namaFasilitas,
            'deskripsi' => 'Ruang laboratorium bahasa ber-AC dan multimedia.',
            'status'    => 'published',
            'gambar'    => $image,
        ]);
        $responseStore->assertRedirect('/admin/cms/fasilitas');
        $responseStore->assertSessionHas('success');

        $fasilitas = Fasilitas::where('nama', $namaFasilitas)->first();
        $this->assertNotNull($fasilitas);
        $this->assertEquals('published', $fasilitas->status);
        $this->assertNotNull($fasilitas->gambar_path);
        Storage::disk('public')->assertExists($fasilitas->gambar_path);

        // 3. Update
        $responseUpdate = $this->actingAs($admin)->put("/admin/cms/fasilitas/{$fasilitas->id}", [
            'nama'      => $namaFasilitas . ' (Revisi)',
            'deskripsi' => 'Deskripsi diperbarui.',
            'status'    => 'draft',
        ]);
        $responseUpdate->assertRedirect('/admin/cms/fasilitas');
        $fasilitas->refresh();
        $this->assertEquals('draft', $fasilitas->status);
        $this->assertStringContainsString('(Revisi)', $fasilitas->nama);

        // 4. Delete
        $responseDelete = $this->actingAs($admin)->delete("/admin/cms/fasilitas/{$fasilitas->id}");
        $responseDelete->assertRedirect('/admin/cms/fasilitas');
        $this->assertNull(Fasilitas::find($fasilitas->id));
    }

    /**
     * Test admin dapat mengelola modul galeri foto.
     */
    public function test_admin_can_manage_galeri(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        // 1. Index
        $responseIndex = $this->actingAs($admin)->get('/admin/cms/galeri');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Kelola Galeri Foto');

        // 2. Store album dengan multiple foto
        $judulAlbum = 'Peringatan Hari Guru Nasional ' . time();
        $foto1 = UploadedFile::fake()->image('foto1.jpg', 600, 400);
        $foto2 = UploadedFile::fake()->image('foto2.jpg', 600, 400);

        $responseStore = $this->actingAs($admin)->post('/admin/cms/galeri', [
            'judul'     => $judulAlbum,
            'deskripsi' => 'Upacara dan penyerahan penghargaan guru.',
            'status'    => 'published',
            'fotos'     => [$foto1, $foto2],
        ]);
        $responseStore->assertRedirect('/admin/cms/galeri');

        $galeri = Galeri::where('judul', $judulAlbum)->first();
        $this->assertNotNull($galeri);
        $this->assertEquals(2, $galeri->foto()->count());

        // 3. Update
        $responseUpdate = $this->actingAs($admin)->put("/admin/cms/galeri/{$galeri->id}", [
            'judul'     => $judulAlbum . ' (Update)',
            'deskripsi' => 'Deskripsi album galeri diperbarui.',
            'status'    => 'published',
        ]);
        $responseUpdate->assertRedirect('/admin/cms/galeri');
        $galeri->refresh();
        $this->assertStringContainsString('(Update)', $galeri->judul);

        // 4. Delete foto satuan
        $firstFoto = $galeri->foto()->first();
        $responseDelFoto = $this->actingAs($admin)->delete("/admin/cms/galeri/foto/{$firstFoto->id}");
        $responseDelFoto->assertStatus(302);
        $this->assertEquals(1, $galeri->foto()->count());

        // 5. Delete album
        $responseDelete = $this->actingAs($admin)->delete("/admin/cms/galeri/{$galeri->id}");
        $responseDelete->assertRedirect('/admin/cms/galeri');
        $this->assertNull(Galeri::find($galeri->id));
    }

    /**
     * Test admin dapat mengelola modul prestasi publik.
     */
    public function test_admin_can_manage_prestasi_publik(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        // 1. Index
        $responseIndex = $this->actingAs($admin)->get('/admin/cms/prestasi');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Manajemen Prestasi Publik');

        // 2. Store
        $judulPrestasi = 'Juara 1 Debat Bahasa Inggris Kota Medan ' . time();
        $responseStore = $this->actingAs($admin)->post('/admin/cms/prestasi', [
            'judul'     => $judulPrestasi,
            'tingkat'   => 'Kota',
            'tanggal'   => '2026-11-01',
            'deskripsi' => 'Meraih juara 1 tingkat kota Medan.',
            'status'    => 'published',
        ]);
        $responseStore->assertRedirect('/admin/cms/prestasi');

        $prestasi = PrestasiPublik::where('judul', $judulPrestasi)->first();
        $this->assertNotNull($prestasi);
        $this->assertEquals('published', $prestasi->status);
        $this->assertEquals('Kota', $prestasi->tingkat);

        // 3. Update
        $responseUpdate = $this->actingAs($admin)->put("/admin/cms/prestasi/{$prestasi->id}", [
            'judul'     => $judulPrestasi . ' (Update)',
            'tingkat'   => 'Provinsi',
            'tanggal'   => '2026-11-01',
            'status'    => 'published',
        ]);
        $responseUpdate->assertRedirect('/admin/cms/prestasi');
        $prestasi->refresh();
        $this->assertEquals('Provinsi', $prestasi->tingkat);

        // 4. Delete
        $responseDelete = $this->actingAs($admin)->delete("/admin/cms/prestasi/{$prestasi->id}");
        $responseDelete->assertRedirect('/admin/cms/prestasi');
        $this->assertNull(PrestasiPublik::find($prestasi->id));
    }

    /**
     * Test data yang dipublish muncul pada katalog publik (/profil/fasilitas, /kesiswaan/prestasi, /galeri).
     */
    public function test_published_data_appears_on_public_pages(): void
    {
        // 1. Buat data published
        $fasilitas = Fasilitas::create([
            'nama'        => 'Gedung Serbaguna Uji Coba ' . time(),
            'deskripsi'   => 'Deskripsi fasilitas publik gedung serbaguna.',
            'status'      => 'published',
        ]);

        $prestasi = PrestasiPublik::create([
            'judul'       => 'Juara 1 Sains Terapan ' . time(),
            'tingkat'     => 'Nasional',
            'tanggal'     => '2026-10-10',
            'deskripsi'   => 'Pencapaian medali emas tingkat nasional.',
            'status'      => 'published',
        ]);

        $galeri = Galeri::create([
            'judul'       => 'Dokumentasi Pentas Seni Akbar ' . time(),
            'deskripsi'   => 'Deskripsi dokumentasi seni.',
            'status'      => 'published',
        ]);

        // 2. Akses halaman publik fasilitas
        $resFasilitas = $this->get('/profil/fasilitas');
        $resFasilitas->assertStatus(200);
        $resFasilitas->assertSee($fasilitas->nama);

        // 3. Akses halaman publik prestasi
        $resPrestasi = $this->get('/kesiswaan/prestasi');
        $resPrestasi->assertStatus(200);
        $resPrestasi->assertSee($prestasi->judul);

        // 4. Akses halaman publik galeri
        $resGaleri = $this->get('/galeri');
        $resGaleri->assertStatus(200);
        $resGaleri->assertSee($galeri->judul);

        // Bersihkan
        $fasilitas->delete();
        $prestasi->delete();
        $galeri->delete();
    }
}
