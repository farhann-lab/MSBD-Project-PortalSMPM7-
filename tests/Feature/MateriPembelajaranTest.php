<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\MateriKelas;
use App\Models\Siswa;
use App\Models\Tahun;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MateriPembelajaranTest extends TestCase
{
    protected User $guruUser;
    protected Guru $guru;
    protected User $guruLainUser;
    protected Guru $guruLain;
    protected User $siswaUser;
    protected Siswa $siswa;
    protected User $siswaLainUser;
    protected Siswa $siswaLain;
    protected Kelas $kelasA;
    protected Kelas $kelasB;
    protected Mapel $mapel;
    protected GuruMapel $guruMapelA;
    protected string $tasmAktif;
    protected string $taAktif;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tasmAktif = Tahun::getTasmAktif() ?? '20242';
        $this->taAktif = Tahun::getTaAktif() ?? '2024';

        // Fake local storage for testing uploads & downloads
        Storage::fake('local');

        // 1. Setup Kelas dan Mapel
        $this->kelasA = Kelas::firstOrCreate(['nama' => '7-MTR-A'], ['tingkat' => '7']);
        $this->kelasB = Kelas::firstOrCreate(['nama' => '7-MTR-B'], ['tingkat' => '7']);
        $this->mapel = Mapel::firstOrCreate(['kd_singkat' => 'MTR1'], ['nama' => 'Mata Pelajaran Materi 1', 'kelompok' => 'A', 'tambahan_sub' => 'NO']);

        // 2. Setup Guru Utama
        $this->guru = Guru::firstOrCreate(
            ['nip' => '198801012020011001'],
            ['nama' => 'Guru Materi Utama', 'stat_data' => 'P', 'jk' => 'L', 'is_bk' => '0']
        );
        $this->guruUser = User::firstOrCreate(
            ['username' => 'guru_mtr_utama'],
            [
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'id_guru' => $this->guru->id,
                'is_active' => true,
            ]
        );

        // 3. Setup Guru Lain
        $this->guruLain = Guru::firstOrCreate(
            ['nip' => '198902022020012002'],
            ['nama' => 'Guru Materi Lain', 'stat_data' => 'P', 'jk' => 'P', 'is_bk' => '0']
        );
        $this->guruLainUser = User::firstOrCreate(
            ['username' => 'guru_mtr_lain'],
            [
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'id_guru' => $this->guruLain->id,
                'is_active' => true,
            ]
        );

        // 4. Hubungkan Guru Utama mengajar Mapel di Kelas A
        $this->guruMapelA = GuruMapel::firstOrCreate(
            [
                'id_guru' => $this->guru->id,
                'id_kelas' => $this->kelasA->id,
                'id_mapel' => $this->mapel->id,
                'tasm' => $this->tasmAktif,
            ]
        );

        // 5. Setup Siswa Terdaftar di Kelas A
        $this->siswa = Siswa::firstOrCreate(
            ['nisn' => '0099887711'],
            ['nama' => 'Siswa Kelas A Materi', 'stat_data' => 'A', 'jk' => 'L']
        );
        $this->siswaUser = User::firstOrCreate(
            ['username' => 'siswa_mtr_a'],
            [
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'id_siswa' => $this->siswa->id,
                'is_active' => true,
            ]
        );
        KelasSiswa::firstOrCreate(
            [
                'id_siswa' => $this->siswa->id,
                'id_kelas' => $this->kelasA->id,
                'ta' => $this->taAktif,
            ],
            ['status' => 'A']
        );

        // 6. Setup Siswa Lain di Kelas B
        $this->siswaLain = Siswa::firstOrCreate(
            ['nisn' => '0099887722'],
            ['nama' => 'Siswa Kelas B Materi', 'stat_data' => 'A', 'jk' => 'P']
        );
        $this->siswaLainUser = User::firstOrCreate(
            ['username' => 'siswa_mtr_b'],
            [
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'id_siswa' => $this->siswaLain->id,
                'is_active' => true,
            ]
        );
        KelasSiswa::firstOrCreate(
            [
                'id_siswa' => $this->siswaLain->id,
                'id_kelas' => $this->kelasB->id,
                'ta' => $this->taAktif,
            ],
            ['status' => 'A']
        );
    }

    public function test_guru_can_access_materi_management_page()
    {
        $response = $this->actingAs($this->guruUser)->get(route('guru.materi'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Materi Pembelajaran');
    }

    public function test_guru_can_create_document_materi_with_file_upload()
    {
        $file = UploadedFile::fake()->create('modul_ajar.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->guruUser)->post(route('guru.materi.store'), [
            'id_mapel' => $this->mapel->id,
            'judul' => 'Modul Bab 1 Sistem Operasi',
            'deskripsi' => 'Pengenalan konsep dasar sistem operasi komputer.',
            'jenis' => 'file',
            'file_materi' => $file,
            'id_guru_mapel' => [$this->guruMapelA->id],
        ]);

        $response->assertRedirect(route('guru.materi'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siakad_materi', [
            'id_guru' => $this->guru->id,
            'id_mapel' => $this->mapel->id,
            'judul' => 'Modul Bab 1 Sistem Operasi',
            'jenis' => 'file',
        ]);

        $materi = Materi::where('judul', 'Modul Bab 1 Sistem Operasi')->latest('id')->first();
        $this->assertNotNull($materi);
        $this->assertTrue(Storage::disk('local')->exists($materi->file_path));

        // Verifikasi siakad_materi_kelas
        $this->assertDatabaseHas('siakad_materi_kelas', [
            'materi_id' => $materi->id,
            'id_guru_mapel' => $this->guruMapelA->id,
        ]);
    }

    public function test_guru_can_create_external_link_materi()
    {
        $response = $this->actingAs($this->guruUser)->post(route('guru.materi.store'), [
            'id_mapel' => $this->mapel->id,
            'judul' => 'Video Pembelajaran YouTube Bab 1',
            'deskripsi' => 'Simak video tutorial berikut.',
            'jenis' => 'tautan',
            'tautan_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'id_guru_mapel' => [$this->guruMapelA->id],
        ]);

        $response->assertRedirect(route('guru.materi'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siakad_materi', [
            'id_guru' => $this->guru->id,
            'id_mapel' => $this->mapel->id,
            'judul' => 'Video Pembelajaran YouTube Bab 1',
            'jenis' => 'tautan',
            'tautan_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);
    }

    public function test_guru_cannot_edit_or_delete_other_teacher_materi()
    {
        // Materi milik Guru Utama
        $materi = Materi::create([
            'id_guru' => $this->guru->id,
            'id_mapel' => $this->mapel->id,
            'judul' => 'Materi Milik Guru Utama',
            'jenis' => 'tautan',
            'tautan_url' => 'https://example.com/modul',
            'status' => 'aktif',
        ]);
        MateriKelas::create([
            'materi_id' => $materi->id,
            'id_guru_mapel' => $this->guruMapelA->id,
        ]);

        // Guru Lain mencoba update
        $responseUpdate = $this->actingAs($this->guruLainUser)->put(route('guru.materi.update', $materi->id), [
            'judul' => 'Materi Diubah Ilegal',
            'status' => 'aktif',
        ]);
        $responseUpdate->assertStatus(403);

        // Guru Lain mencoba hapus
        $responseDelete = $this->actingAs($this->guruLainUser)->delete(route('guru.materi.destroy', $materi->id));
        $responseDelete->assertStatus(403);

        $this->assertDatabaseHas('siakad_materi', [
            'id' => $materi->id,
            'judul' => 'Materi Milik Guru Utama',
        ]);
    }

    public function test_student_in_enrolled_class_can_view_and_download_materi()
    {
        // Simpan file ke fake local storage
        $dummyPath = 'materi/test_modul_rahasia.pdf';
        Storage::disk('local')->put($dummyPath, 'Konten modul ajar rahasia.');

        $materi = Materi::create([
            'id_guru' => $this->guru->id,
            'id_mapel' => $this->mapel->id,
            'judul' => 'Modul Belajar Siswa Kelas A',
            'deskripsi' => 'Modul khusus kelas A.',
            'jenis' => 'file',
            'file_path' => $dummyPath,
            'ukuran_byte' => strlen('Konten modul ajar rahasia.'),
            'status' => 'aktif',
        ]);
        MateriKelas::create([
            'materi_id' => $materi->id,
            'id_guru_mapel' => $this->guruMapelA->id,
        ]);

        // Siswa Kelas A mengakses halaman materi siswa
        $responseIndex = $this->actingAs($this->siswaUser)->get(route('siswa.materi'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Modul Belajar Siswa Kelas A');

        // Siswa Kelas A mengunduh file materi terproteksi
        $responseDownload = $this->actingAs($this->siswaUser)->get(route('materi.download', $materi->id));
        $responseDownload->assertStatus(200);
        $this->assertEquals('application/pdf', $responseDownload->headers->get('content-type'));
    }

    public function test_unauthorized_student_or_guest_cannot_download_materi()
    {
        $dummyPath = 'materi/test_modul_khusus.pdf';
        Storage::disk('local')->put($dummyPath, 'Hanya untuk kelas A.');

        $materi = Materi::create([
            'id_guru' => $this->guru->id,
            'id_mapel' => $this->mapel->id,
            'judul' => 'Modul Khusus Kelas A',
            'jenis' => 'file',
            'file_path' => $dummyPath,
            'ukuran_byte' => strlen('Hanya untuk kelas A.'),
            'status' => 'aktif',
        ]);
        MateriKelas::create([
            'materi_id' => $materi->id,
            'id_guru_mapel' => $this->guruMapelA->id,
        ]);

        // 1. Guest (unauthenticated) -> redirect to login or 401
        $responseGuest = $this->get(route('materi.download', $materi->id));
        $this->assertTrue(in_array($responseGuest->status(), [302, 401]));

        // 2. Siswa Kelas B (tidak ditargetkan) -> 403 Forbidden
        $responseSiswaB = $this->actingAs($this->siswaLainUser)->get(route('materi.download', $materi->id));
        $responseSiswaB->assertStatus(403);
    }
}
