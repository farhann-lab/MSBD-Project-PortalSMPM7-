<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTahunAjaranController extends Controller
{
    /**
     * Tampilkan daftar periode tahun ajaran (TAJ-1).
     */
    public function index()
    {
        $periodeList = Tahun::orderBy('tahun', 'desc')->get();
        $periodeAktif = Tahun::getAktif();

        $stats = [
            'total' => $periodeList->count(),
            'ganjil' => $periodeList->filter(fn($t) => substr($t->tahun, -1) === '1')->count(),
            'genap' => $periodeList->filter(fn($t) => substr($t->tahun, -1) === '2')->count(),
            'aktif' => $periodeAktif,
        ];

        return view('pages.admin.master.tahun-ajaran.index', compact('periodeList', 'periodeAktif', 'stats'));
    }

    /**
     * Tambah tahun ajaran baru (TAJ-2 & TAJ-3).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => ['required', 'string', 'regex:/^[0-9]{4}[1-2]$/', 'unique:tahun,tahun'],
            'nama_kepsek' => ['required', 'string', 'max:50'],
            'nip_kepsek' => ['nullable', 'string', 'max:30'],
            'tgl_raport' => ['required', 'date'],
            'tgl_raport_kelas3' => ['required', 'date'],
        ], [
            'tahun.required' => 'Kode periode TASM wajib diisi.',
            'tahun.regex' => 'Format kode periode harus 5 digit (4 digit tahun + 1 digit semester: 1=Ganjil, 2=Genap, misal: 20251).',
            'tahun.unique' => 'Kode periode tahun ajaran sudah ada di database.',
            'nama_kepsek.required' => 'Nama kepala sekolah wajib diisi.',
            'tgl_raport.required' => 'Tanggal cetak rapor kelas 7 & 8 wajib diisi.',
            'tgl_raport_kelas3.required' => 'Tanggal cetak rapor kelas 9 wajib diisi.',
        ]);

        $validated['aktif'] = 'N'; // Periode baru default non-aktif

        $tahun = Tahun::create($validated);

        return redirect()->route('admin.master.tahun-ajaran.index')
            ->with('success', "Periode Tahun Ajaran {$tahun->nama_lengkap} ({$tahun->tahun}) berhasil ditambahkan.");
    }

    /**
     * Update informasi kepala sekolah dan tanggal rapor periode.
     */
    public function update(Request $request, Tahun $tahun)
    {
        $validated = $request->validate([
            'nama_kepsek' => ['required', 'string', 'max:50'],
            'nip_kepsek' => ['nullable', 'string', 'max:30'],
            'tgl_raport' => ['required', 'date'],
            'tgl_raport_kelas3' => ['required', 'date'],
        ], [
            'nama_kepsek.required' => 'Nama kepala sekolah wajib diisi.',
            'tgl_raport.required' => 'Tanggal cetak rapor kelas 7 & 8 wajib diisi.',
            'tgl_raport_kelas3.required' => 'Tanggal cetak rapor kelas 9 wajib diisi.',
        ]);

        $tahun->update($validated);

        return redirect()->route('admin.master.tahun-ajaran.index')
            ->with('success', "Informasi Periode {$tahun->nama_lengkap} ({$tahun->tahun}) berhasil diperbarui.");
    }

    /**
     * Aktivasi periode tahun ajaran (TAJ-4 & TAJ-5).
     * Memanggil Stored Procedure sp_set_periode_aktif atau fallback transaksi Eloquent.
     */
    public function setAktif(Tahun $tahun)
    {
        $tasm = $tahun->tahun;

        try {
            DB::statement('CALL sp_set_periode_aktif(?)', [$tasm]);
        } catch (\Throwable $e) {
            // Fallback transaksi atomik jika SP belum dibuat / gagal
            DB::transaction(function () use ($tahun) {
                Tahun::query()->update(['aktif' => 'N']);
                $tahun->update(['aktif' => 'Y']);
            });
        }

        return redirect()->route('admin.master.tahun-ajaran.index')
            ->with('success', "Periode {$tahun->nama_lengkap} ({$tahun->tahun}) sekarang aktif sebagai acuan akademik sistem.");
    }
}
