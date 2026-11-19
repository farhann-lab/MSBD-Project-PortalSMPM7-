<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\Tahun;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    public function test_guru_can_view_own_profile_and_teaching_assignments(): void
    {
        $guru = Guru::firstOrCreate(
            ['nip' => '198801012015011001'],
            ['nama' => 'Drs. Guru Profiler, M.Pd.', 'jk' => 'L', 'stat_data' => 'P', 'is_bk' => '0']
        );

        $guruUser = User::firstOrCreate(
            ['username' => 'guru_profil_test'],
            [
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'id_guru' => $guru->id,
                'is_active' => true,
            ]
        );

        $tasmAktif = Tahun::getTasmAktif() ?? '20242';
        $kelas = Kelas::firstOrCreate(['nama' => '7-PROFIL'], ['tingkat' => '7']);
        $mapel = Mapel::firstOrCreate(['kd_singkat' => 'PRF1'], ['nama' => 'Mapel Profil 1', 'kelompok' => 'A', 'tambahan_sub' => 'NO']);

        GuruMapel::firstOrCreate([
            'tasm' => $tasmAktif,
            'id_guru' => $guru->id,
            'id_kelas' => $kelas->id,
            'id_mapel' => $mapel->id,
        ]);

        $response = $this->actingAs($guruUser)
            ->get(route('guru.profil'));

        $response->assertStatus(200);
        $response->assertSee($guru->nama);
        $response->assertSee($guru->nip);
        $response->assertSee('Data Master Guru');
        $response->assertSee('Mapel Profil 1');
    }

    public function test_siswa_can_view_own_profile_without_sensitive_data(): void
    {
        $siswa = Siswa::firstOrCreate(
            ['nis' => '998877'],
            [
                'nama' => 'Siswa Penguji Profil',
                'nisn' => '0011223344',
                'jk' => 'P',
                'stat_data' => 'A',
                'tmp_lahir' => 'Medan',
                'tgl_lahir' => '2010-05-15',
                'alamat' => 'Jalan Rahasia Sangat Pribadi No. 99',
                'ortu_ayah' => 'Ayah Rahasia',
                'ortu_ibu' => 'Ibu Rahasia',
            ]
        );

        $siswaUser = User::firstOrCreate(
            ['username' => '998877'],
            [
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'id_siswa' => $siswa->id,
                'is_active' => true,
            ]
        );

        $taAktif = Tahun::getTaAktif() ?? '2024';
        $kelas = Kelas::firstOrCreate(['nama' => '8-PROFIL'], ['tingkat' => '8']);

        KelasSiswa::firstOrCreate([
            'ta' => $taAktif,
            'id_kelas' => $kelas->id,
            'id_siswa' => $siswa->id,
        ]);

        $response = $this->actingAs($siswaUser)
            ->get(route('siswa.profil'));

        $response->assertStatus(200);
        $response->assertSee($siswa->nama);
        $response->assertSee($siswa->nis);
        $response->assertSee($siswa->nisn);
        $response->assertSee('Kelas 8-PROFIL');

        // Pastikan TIDAK ADA kebocoran data sensitif (DIR-8)
        $response->assertDontSee('Jalan Rahasia Sangat Pribadi No. 99');
        $response->assertDontSee('Ayah Rahasia');
        $response->assertDontSee('Ibu Rahasia');
        $response->assertDontSee('2010-05-15');
    }

    public function test_user_can_change_password_with_validations(): void
    {
        $siswa = Siswa::firstOrCreate(
            ['nis' => '998811'],
            ['nama' => 'Siswa Ganti Sandi', 'stat_data' => 'A']
        );

        $user = User::firstOrCreate(
            ['username' => '998811'],
            [
                'password' => Hash::make('old_password123'),
                'role' => 'siswa',
                'id_siswa' => $siswa->id,
                'is_active' => true,
            ]
        );
        $user->update(['password' => Hash::make('old_password123')]);

        // 1. Password baru sama dengan username / NIS -> Harus ditolak
        $respSame = $this->actingAs($user)
            ->post(route('siswa.profil.password'), [
                'current_password' => 'old_password123',
                'password' => '998811',
                'password_confirmation' => '998811',
            ]);

        $respSame->assertSessionHasErrors('password');

        // 2. Password baru valid -> Harus berhasil
        $respSuccess = $this->actingAs($user)
            ->post(route('siswa.profil.password'), [
                'current_password' => 'old_password123',
                'password' => 'new_password123',
                'password_confirmation' => 'new_password123',
            ]);

        $respSuccess->assertSessionHas('success');
        $this->assertTrue(Hash::check('new_password123', $user->fresh()->password));
    }
}
