<?php

namespace App\Http\Controllers;

use App\Support\RoleDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/** Jadwal guru: data dibatasi memakai id_guru dari sesi login, bukan parameter URL. */
class GuruJadwalController extends Controller
{
    public function index(Request $request): View
    {
        if (! RoleDatabase::enabled()) {
            return view('pages.guru.jadwal', ['jadwalMengajar' => null]);
        }

        $idGuru = $request->user()->id_guru;
        abort_unless($idGuru, 403, 'Akun guru belum dihubungkan ke identitas guru.');

        $jadwal = DB::connection(RoleDatabase::forAuthenticatedRole())
            ->table('v_jadwal_lengkap')
            ->select('jadwal_id', 'hari', 'jam_mulai', 'jam_selesai', 'nama_mapel', 'nama_kelas', 'tasm')
            ->where('id_guru', (int) $idGuru)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('pages.guru.jadwal', ['jadwalMengajar' => $jadwal]);
    }
}
