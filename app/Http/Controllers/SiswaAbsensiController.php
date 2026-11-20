<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaAbsensiController extends Controller
{
    /**
     * Tampilkan riwayat kehadiran pribadi siswa (SSW-5, SSW-6).
     * Siswa hanya dapat melihat rekaman miliknya sendiri (DIR-8).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isSiswa()) {
            abort(403, 'Akses khusus siswa.');
        }

        $siswa = $user->siswa;
        $tasmAktif = Tahun::getTasmAktif();
        $taAktif = Tahun::getTaAktif();

        $selectedTasm = $request->get('tasm', $tasmAktif);

        // Kelas penempatan siswa
        $kelasPenempatan = $siswa ? $siswa->kelasAktif($taAktif) : null;
        $kelasNama = $kelasPenempatan ? ($kelasPenempatan->kelas->nama ?? '-') : '-';

        // Ambil riwayat absensi siswa pada periode tasm terpilih
        $riwayatAbsensi = collect();
        if ($siswa) {
            $riwayatAbsensi = AbsensiSiswa::with([
                'pertemuan.jadwal.guruMapel.mapel',
                'pertemuan.jadwal.guruMapel.guru',
            ])
            ->where('id_siswa', $siswa->id)
            ->whereHas('pertemuan.jadwal', function ($q) use ($selectedTasm) {
                $q->where('tasm', $selectedTasm);
            })
            ->join('siakad_pertemuan', 'siakad_pertemuan.id', '=', 'siakad_absensi_siswa.pertemuan_id')
            ->orderBy('siakad_pertemuan.tanggal', 'desc')
            ->orderBy('siakad_pertemuan.sesi_ke', 'desc')
            ->select('siakad_absensi_siswa.*')
            ->get();
        }

        // Hitung agregat statistik kehadiran siswa pribadi
        $totalPertemuan = $riwayatAbsensi->count();
        $totalHadir = $riwayatAbsensi->where('status', 'H')->count();
        $totalSakit = $riwayatAbsensi->where('status', 'S')->count();
        $totalIzin = $riwayatAbsensi->where('status', 'I')->count();
        $totalAlpa = $riwayatAbsensi->where('status', 'A')->count();

        $persentaseKehadiran = $totalPertemuan > 0 
            ? round(($totalHadir / $totalPertemuan) * 100, 1) 
            : 0;

        return view('pages.siswa.absensi', compact(
            'siswa',
            'selectedTasm',
            'kelasNama',
            'riwayatAbsensi',
            'totalPertemuan',
            'totalHadir',
            'totalSakit',
            'totalIzin',
            'totalAlpa',
            'persentaseKehadiran'
        ));
    }
}
