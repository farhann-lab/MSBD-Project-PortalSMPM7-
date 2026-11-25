<?php

namespace App\Http\Controllers;

use App\Support\RoleDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/** Identitas siswa hanya diambil berdasarkan ID sesi dan view publik yang minim data. */
class SiswaProfilController extends Controller
{
    public function index(Request $request, UserProfileController $profiles): View
    {
        if (! RoleDatabase::enabled()) {
            return $profiles->profileSiswa();
        }

        $idSiswa = $request->user()->id_siswa;
        abort_unless($idSiswa, 403, 'Akun belum dihubungkan ke identitas siswa.');

        $profil = DB::connection(RoleDatabase::forAuthenticatedRole())
            ->table('v_publik_siswa')
            ->select('id', 'nama', 'nama_kelas', 'tingkat')
            ->where('id', (int) $idSiswa)
            ->first();

        return view('pages.siswa.profil', [
            'identitasSiswa' => $profil,
            'kelasPenempatan' => null,
        ]);
    }
}
