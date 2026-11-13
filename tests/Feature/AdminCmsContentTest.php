<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCmsContentTest extends TestCase
{
    protected function getAdminUser(): User
    {
        return User::where('role', 'admin')->first() ?? User::create([
            'username'             => 'admin_cms_test',
            'password'             => bcrypt('password123'),
            'role'                 => 'admin',
            'is_active'            => true,
            'wajib_ganti_password' => false,
            'is_admin_utama'       => true,
        ]);
    }

    /**
     * Test admin dapat melihat halaman daftar berita.
     */
    public function test_admin_can_view_berita_index(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/cms/berita');

        $response->assertStatus(200);
        $response->assertSee('Kelola Berita');
        $response->assertSee('Tulis Berita Baru');
    }

    /**
     * Test admin dapat membuat berita baru.
     */
    public function test_admin_can_create_new_berita(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        $image = UploadedFile::fake()->image('kegiatan.jpg', 600, 400);

        $judul = 'Uji Coba Berita Baru ' . time();
        $response = $this->actingAs($admin)->post('/admin/cms/berita', [
            'judul'        => $judul,
            'isi'          => '<p>Ini adalah isi berita uji coba untuk CMS SIAKAD.</p>',
            'status'       => 'published',
            'gambar'       => $image,
            'published_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect('/admin/cms/berita');
        $response->assertSessionHas('success');

        $berita = Berita::where('judul', $judul)->first();
        $this->assertNotNull($berita);
        $this->assertEquals('published', $berita->status);
        $this->assertNotNull($berita->gambar_path);
        Storage::disk('public')->assertExists($berita->gambar_path);

        // Bersihkan
        $berita->delete();
    }

    /**
     * Test admin dapat memperbarui berita.
     */
    public function test_admin_can_update_berita(): void
    {
        $admin = $this->getAdminUser();

        $berita = Berita::create([
            'judul'           => 'Berita Sebelum Update ' . time(),
            'slug'            => 'berita-sebelum-update-' . time(),
            'isi'             => '<p>Konten awal.</p>',
            'status'          => 'draft',
            'penulis_user_id' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->put("/admin/cms/berita/{$berita->id}", [
            'judul'  => 'Berita Sesudah Update ' . time(),
            'isi'    => '<p>Konten yang telah direvisi.</p>',
            'status' => 'published',
        ]);

        $response->assertRedirect('/admin/cms/berita');
        $response->assertSessionHas('success');

        $berita->refresh();
        $this->assertStringContainsString('Berita Sesudah Update', $berita->judul);
        $this->assertEquals('published', $berita->status);

        $berita->delete();
    }

    /**
     * Test admin dapat menghapus berita.
     */
    public function test_admin_can_delete_berita(): void
    {
        $admin = $this->getAdminUser();

        $berita = Berita::create([
            'judul'           => 'Berita Siap Dihapus ' . time(),
            'slug'            => 'berita-siap-dihapus-' . time(),
            'isi'             => '<p>Konten yang akan dihapus.</p>',
            'status'          => 'draft',
            'penulis_user_id' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->delete("/admin/cms/berita/{$berita->id}");

        $response->assertRedirect('/admin/cms/berita');
        $response->assertSessionHas('success');

        $this->assertNull(Berita::find($berita->id));
    }

    /**
     * Test admin dapat melihat halaman pengumuman dan membuat pengumuman baru.
     */
    public function test_admin_can_manage_pengumuman(): void
    {
        $admin = $this->getAdminUser();

        // 1. Index
        $responseIndex = $this->actingAs($admin)->get('/admin/cms/pengumuman');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Manajemen Pengumuman');

        // 2. Create
        $judul = 'Edaran Penting Sekolah ' . time();
        $responseStore = $this->actingAs($admin)->post('/admin/cms/pengumuman', [
            'judul'   => $judul,
            'isi'     => '<p>Isi edaran resmi sekolah.</p>',
            'tanggal' => now()->toDateString(),
            'status'  => 'published',
        ]);

        $responseStore->assertRedirect('/admin/cms/pengumuman');
        $pengumuman = Pengumuman::where('judul', $judul)->first();
        $this->assertNotNull($pengumuman);
        $this->assertEquals('published', $pengumuman->status);

        // 3. Edit / Update
        $responseUpdate = $this->actingAs($admin)->put("/admin/cms/pengumuman/{$pengumuman->id}", [
            'judul'   => $judul . ' (Updated)',
            'isi'     => '<p>Isi edaran diperbarui.</p>',
            'tanggal' => now()->toDateString(),
            'status'  => 'archived',
        ]);
        $responseUpdate->assertRedirect('/admin/cms/pengumuman');
        $pengumuman->refresh();
        $this->assertEquals('archived', $pengumuman->status);

        // 4. Delete
        $responseDelete = $this->actingAs($admin)->delete("/admin/cms/pengumuman/{$pengumuman->id}");
        $responseDelete->assertRedirect('/admin/cms/pengumuman');
        $this->assertNull(Pengumuman::find($pengumuman->id));
    }

    /**
     * Test berita published tampil pada katalog publik /berita dan /berita/{slug}.
     */
    public function test_published_news_appears_on_public_portal(): void
    {
        $admin = $this->getAdminUser();

        $slug = 'berita-publik-test-' . time();
        $berita = Berita::create([
            'judul'           => 'Berita Publikasi Terbuka ' . time(),
            'slug'            => $slug,
            'isi'             => '<p>Konten berita khusus tampil di portal publik.</p>',
            'status'          => 'published',
            'published_at'    => now(),
            'penulis_user_id' => $admin->id,
        ]);

        // Verifikasi di /berita
        $responseList = $this->get('/berita');
        $responseList->assertStatus(200);
        $responseList->assertSee($berita->judul);

        // Verifikasi di detail /berita/{slug}
        $responseDetail = $this->get('/berita/' . $slug);
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee($berita->judul);
        $responseDetail->assertSee('Konten berita khusus tampil di portal publik.');

        $berita->delete();
    }
}
