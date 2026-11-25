<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EkstrakurikulerTest extends TestCase
{
    private const ADMIN_URL = '/admin/master/ekskul';

    private const PUBLIC_URL = '/kesiswaan/ekstrakurikuler';

    protected function setUp(): void
    {
        parent::setUp();

        $connection = ['driver' => 'sqlite', 'database' => ':memory:', 'foreign_key_constraints' => true];
        config([
            'database.default' => 'mysql_public',
            'database.connections.mysql_public' => $connection,
            'database.connections.mysql_admin' => $connection,
            'siakad_rbac.enabled' => true,
        ]);

        $schema = Schema::connection('mysql_admin');
        $schema->create('m_ekstra', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 50);
        });
        $schema->create('siakad_ekstra_publik', function (Blueprint $table) {
            $table->integer('id_ekstra')->primary();
            $table->foreign('id_ekstra')->references('id')->on('m_ekstra')->restrictOnDelete();
            $table->text('deskripsi')->nullable();
            $table->string('gambar_path', 500)->nullable();
            $table->enum('status', ['draft', 'published', 'archived']);
            $table->timestamp('updated_at')->nullable();
        });
        $admin = DB::connection('mysql_admin');
        $admin->statement(<<<'SQL'
            CREATE VIEW v_publik_ekstra AS
            SELECT e.id AS id_ekstra, e.nama, p.deskripsi, p.gambar_path
            FROM m_ekstra e JOIN siakad_ekstra_publik p ON p.id_ekstra = e.id
            WHERE p.status = 'published'
            SQL);

        DB::connection('mysql_public')->setPdo($admin->getPdo());
        Storage::fake('local');
    }

    public function test_admin_can_manage_master_name_and_publish_or_archive_promotion(): void
    {
        $this->actingAs($this->user('admin'));
        $admin = DB::connection('mysql_admin');
        $public = DB::connection('mysql_public');
        $public->enableQueryLog();

        $this->get(self::ADMIN_URL.'/tambah')->assertOk()->assertSee('Simpan Nama');
        $this->post(self::ADMIN_URL, ['nama' => 'Klub Sains Café'])
            ->assertRedirect(self::ADMIN_URL);
        $id = $admin->table('m_ekstra')->where('nama', 'Klub Sains Café')->value('id');
        $this->assertNotNull($id);
        $this->assertFalse($admin->table('siakad_ekstra_publik')->exists());
        $this->get(self::ADMIN_URL.'?q=Sains')->assertOk()->assertSee('Klub Sains Café');
        $this->get(self::ADMIN_URL."/$id/edit")->assertOk()->assertSee('Klub Sains Café');

        $publicationUrl = self::ADMIN_URL."/$id/publik";
        $publicImageUrl = self::PUBLIC_URL."/$id/gambar";
        $adminImageUrl = self::ADMIN_URL."/$id/gambar";
        $this->get($publicationUrl)->assertOk()->assertSee('Deskripsi publik');
        $this->put($publicationUrl, [
            'deskripsi' => 'Percobaan dan pameran sains',
            'status' => 'draft',
            'gambar' => UploadedFile::fake()->image('sains.png'),
        ])->assertRedirect(self::ADMIN_URL);

        $firstImage = $admin->table('siakad_ekstra_publik')->where('id_ekstra', $id)->value('gambar_path');
        Storage::disk('local')->assertExists($firstImage);
        $this->get($adminImageUrl)->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->app['auth']->forgetGuards();
        $this->assertGuest();
        $this->get(self::PUBLIC_URL)->assertOk()
            ->assertSee('Belum ada ekstrakurikuler yang dipublikasikan')
            ->assertDontSee('Klub Sains Café');
        $this->get($publicImageUrl)->assertNotFound();

        $this->actingAs($this->user('admin'))->put($publicationUrl, [
            'deskripsi' => 'Percobaan dan pameran sains',
            'status' => 'published',
        ])->assertRedirect(self::ADMIN_URL);
        $this->app['auth']->forgetGuards();
        $this->get(self::PUBLIC_URL)->assertOk()
            ->assertSee('Klub Sains Café')->assertSee('Percobaan dan pameran sains')->assertSee($publicImageUrl);
        $this->get($publicImageUrl)->assertOk();

        $this->actingAs($this->user('admin'))->put(self::ADMIN_URL."/$id", ['nama' => 'Klub Astronomi'])
            ->assertRedirect(self::ADMIN_URL);
        $this->put($publicationUrl, [
            'deskripsi' => 'Pengamatan bintang',
            'status' => 'published',
            'gambar' => UploadedFile::fake()->image('astronomi.png'),
        ])->assertRedirect(self::ADMIN_URL);
        Storage::disk('local')->assertMissing($firstImage);
        $this->app['auth']->forgetGuards();
        $this->get(self::PUBLIC_URL)->assertOk()->assertSee('Klub Astronomi')
            ->assertSee('Pengamatan bintang')->assertDontSee('Klub Sains Café');

        $this->actingAs($this->user('admin'))->put($publicationUrl, [
            'deskripsi' => 'Pengamatan bintang',
            'status' => 'archived',
        ])->assertRedirect(self::ADMIN_URL);
        $this->app['auth']->forgetGuards();
        $this->get(self::PUBLIC_URL)->assertOk()->assertDontSee('Klub Astronomi');
        $this->get($publicImageUrl)->assertNotFound();
        $this->assertSame('Klub Astronomi', $admin->table('m_ekstra')->where('id', $id)->value('nama'));
        $this->assertSame('archived', $admin->table('siakad_ekstra_publik')->where('id_ekstra', $id)->value('status'));
        $this->assertTrue(collect($public->getQueryLog())->contains(fn ($query) => str_contains($query['query'], 'v_publik_ekstra')));
    }

    public function test_master_name_and_publication_fields_are_validated(): void
    {
        $this->actingAs($this->user('admin'));

        $this->postJson(self::ADMIN_URL, ['nama' => str_repeat('a', 51)])->assertUnprocessable()
            ->assertJsonValidationErrors('nama');
        $this->postJson(self::ADMIN_URL, ['nama' => 'Klub 🚲'])->assertUnprocessable()
            ->assertJsonValidationErrors('nama');
        $this->assertDatabaseCount('m_ekstra', 0, 'mysql_admin');

        $id = DB::connection('mysql_admin')->table('m_ekstra')->insertGetId(['nama' => 'Klub Robotik']);
        $this->putJson(self::ADMIN_URL."/$id/publik", ['status' => 'private'])->assertUnprocessable()
            ->assertJsonValidationErrors('status');
        $this->assertDatabaseCount('siakad_ekstra_publik', 0, 'mysql_admin');
    }

    public function test_management_requires_admin_and_excludes_physical_deletion(): void
    {
        $id = DB::connection('mysql_admin')->table('m_ekstra')->insertGetId(['nama' => 'Klub Film']);

        $this->post(self::ADMIN_URL, ['nama' => 'Klub Musik'])->assertRedirect(route('login'));
        $this->actingAs($this->user('siswa'))->put(self::ADMIN_URL."/$id/publik", ['status' => 'published'])
            ->assertForbidden();
        $this->actingAs($this->user('admin'))->delete(self::ADMIN_URL."/$id")->assertMethodNotAllowed();
        $this->assertDatabaseHas('m_ekstra', ['id' => $id], 'mysql_admin');
    }

    private function user(string $role): User
    {
        return new User([
            'id_user' => 1,
            'username' => 'ekskul_test_'.$role,
            'role' => $role,
            'is_active' => true,
            'wajib_ganti_password' => false,
        ]);
    }
}
