<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\MateriKelas;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiswaMateriController extends Controller
{
    /**
     * Tampilkan daftar materi yang ditujukan untuk rombel kelas siswa (SSW-3, SSW-4).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isSiswa()) {
            abort(403, 'Akses khusus siswa.');
        }

        $siswa = $user->siswa;
        $taAktif = Tahun::getTaAktif();
        $tasmAktif = Tahun::getTasmAktif();

        $kelasPenempatan = $siswa ? $siswa->kelasAktif($taAktif) : null;
        $kelasId = $kelasPenempatan ? $kelasPenempatan->id_kelas : null;
        $kelasNama = $kelasPenempatan ? ($kelasPenempatan->kelas->nama ?? '-') : '-';

        $materiList = collect();
        $mapelList = collect();

        if ($kelasId) {
            // Ambil ID penugasan guru_mapel yang cocok dengan kelas siswa pada semester aktif
            $query = Materi::with(['guru', 'mapel', 'materiKelas.guruMapel'])
                ->where('status', 'aktif')
                ->whereHas('materiKelas.guruMapel', function ($q) use ($kelasId, $tasmAktif) {
                    $q->where('id_kelas', $kelasId)
                      ->where('tasm', $tasmAktif);
                });

            if ($request->filled('mapel_id')) {
                $query->where('id_mapel', $request->mapel_id);
            }

            if ($request->filled('jenis')) {
                $query->where('jenis', $request->jenis);
            }

            if ($request->filled('q')) {
                $q = $request->q;
                $query->where(function ($sub) use ($q) {
                    $sub->where('judul', 'like', "%{$q}%")
                        ->orWhere('deskripsi', 'like', "%{$q}%");
                });
            }

            $materiList = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

            // List Mapel untuk opsi filter
            $mapelList = Materi::where('status', 'aktif')
                ->whereHas('materiKelas.guruMapel', function ($q) use ($kelasId, $tasmAktif) {
                    $q->where('id_kelas', $kelasId)
                      ->where('tasm', $tasmAktif);
                })
                ->with('mapel')
                ->get()
                ->pluck('mapel')
                ->unique('id');
        }

        return view('pages.siswa.materi', compact(
            'siswa',
            'kelasNama',
            'materiList',
            'mapelList',
            'tasmAktif'
        ));
    }

    /**
     * Unduh file materi secara terproteksi (Controller Unduh Berotorisasi).
     */
    public function download($id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        $materi = Materi::findOrFail($id);

        if ($materi->jenis !== 'file' || empty($materi->file_path)) {
            abort(404, 'Materi ini tidak memiliki berkas unduhan.');
        }

        // Cek Otorisasi Unduh:
        // 1. Admin memiliki hak akses penuh
        // 2. Guru pemilik materi diizinkan
        // 3. Siswa yang terdaftar di kelas tujuan pada semester aktif diizinkan
        $isAuthorized = false;

        if ($user->isAdmin()) {
            $isAuthorized = true;
        } elseif ($user->isGuru() && $user->id_guru == $materi->id_guru) {
            $isAuthorized = true;
        } elseif ($user->isSiswa() && $user->siswa) {
            $taAktif = Tahun::getTaAktif();
            $penempatan = $user->siswa->kelasAktif($taAktif);
            if ($penempatan) {
                $siswaKelasId = $penempatan->id_kelas;
                // Cek apakah materi ini ditujukan untuk kelas siswa
                $isTargetClass = MateriKelas::where('materi_id', $materi->id)
                    ->whereHas('guruMapel', function ($gm) use ($siswaKelasId) {
                        $gm->where('id_kelas', $siswaKelasId);
                    })
                    ->exists();

                if ($isTargetClass) {
                    $isAuthorized = true;
                }
            }
        }

        if (!$isAuthorized) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh materi ini.');
        }

        // Pastikan berkas fisik ada di disk lokal/privat
        if (!Storage::disk('local')->exists($materi->file_path)) {
            abort(404, 'Berkas materi tidak ditemukan pada penyimpanan server.');
        }

        $extension = pathinfo($materi->file_path, PATHINFO_EXTENSION);
        $downloadFilename = str()->slug($materi->judul) . ($extension ? '.' . $extension : '');

        return Storage::disk('local')->download($materi->file_path, $downloadFilename);
    }
}
