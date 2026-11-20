<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPenugasanGuruController extends Controller
{
    /**
     * Tampilkan daftar penugasan guru per periode semester (PGS-1).
     */
    public function index(Request $request)
    {
        $tahunList = Tahun::orderBy('tahun', 'desc')->get();
        $tasmAktif = Tahun::getTasmAktif();

        $selectedTasm = $request->get('tasm', $tasmAktif);
        $selectedKelasId = $request->get('id_kelas', '');
        $selectedGuruId = $request->get('id_guru', '');
        $selectedMapelId = $request->get('id_mapel', '');

        $query = GuruMapel::with(['guru', 'kelas', 'mapel'])
            ->where('tasm', $selectedTasm);

        if (!empty($selectedKelasId)) {
            $query->where('id_kelas', $selectedKelasId);
        }
        if (!empty($selectedGuruId)) {
            $query->where('id_guru', $selectedGuruId);
        }
        if (!empty($selectedMapelId)) {
            $query->where('id_mapel', $selectedMapelId);
        }

        $penugasanList = $query->join('m_guru', 't_guru_mapel.id_guru', '=', 'm_guru.id')
            ->orderBy('m_guru.nama')
            ->select('t_guru_mapel.*')
            ->paginate(25)
            ->withQueryString();

        $semuaGuru = Guru::aktif()->orderBy('nama')->get();
        $semuaKelas = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $semuaMapel = Mapel::orderBy('kelompok')->orderBy('nama')->get();

        $totalPenugasan = GuruMapel::where('tasm', $selectedTasm)->count();
        $totalGuruBertugas = GuruMapel::where('tasm', $selectedTasm)->distinct('id_guru')->count('id_guru');

        return view('pages.admin.master.penugasan.index', compact(
            'penugasanList',
            'tahunList',
            'selectedTasm',
            'selectedKelasId',
            'selectedGuruId',
            'selectedMapelId',
            'semuaGuru',
            'semuaKelas',
            'semuaMapel',
            'totalPenugasan',
            'totalGuruBertugas'
        ));
    }

    /**
     * Simpan penugasan guru baru (PGS-1..3).
     * Mendukung multi-select kelas.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tasm' => ['required', 'string', 'size:5'],
            'id_guru' => ['required', 'exists:m_guru,id'],
            'id_mapel' => ['required', 'exists:m_mapel,id'],
            'kelas_ids' => ['required', 'array', 'min:1'],
            'kelas_ids.*' => ['exists:m_kelas,id'],
        ], [
            'kelas_ids.required' => 'Pilih setidaknya satu kelas yang diajar.',
        ]);

        $tasm = $validated['tasm'];
        $idGuru = $validated['id_guru'];
        $idMapel = $validated['id_mapel'];
        $kelasIds = array_unique($validated['kelas_ids']);

        $count = 0;
        $duplikat = 0;

        DB::transaction(function () use ($tasm, $idGuru, $idMapel, $kelasIds, &$count, &$duplikat) {
            foreach ($kelasIds as $idKelas) {
                // PGS-3: Cek duplikasi penugasan yang sama pada periode tasm
                $exists = GuruMapel::where('tasm', $tasm)
                    ->where('id_guru', $idGuru)
                    ->where('id_mapel', $idMapel)
                    ->where('id_kelas', $idKelas)
                    ->exists();

                if (!$exists) {
                    GuruMapel::create([
                        'tasm' => $tasm,
                        'id_guru' => $idGuru,
                        'id_mapel' => $idMapel,
                        'id_kelas' => $idKelas,
                    ]);
                    $count++;
                } else {
                    $duplikat++;
                }
            }
        });

        $msg = "Berhasil menetapkan {$count} penugasan mengajar baru.";
        if ($duplikat > 0) {
            $msg .= " ({$duplikat} penugasan dilewati karena sudah ada).";
        }

        return redirect()->route('admin.master.penugasan.index', [
            'tasm' => $tasm,
            'id_guru' => $idGuru,
        ])->with('success', $msg);
    }

    /**
     * Hapus penugasan guru dengan proteksi rekaman nilai RKM (PGS-7).
     */
    public function destroy(GuruMapel $penugasan)
    {
        $tasm = $penugasan->tasm;

        // PGS-7: Periksa apakah penugasan sudah memiliki rekaman nilai di RKM
        if ($penugasan->hasNilaiDependencies()) {
            return redirect()->route('admin.master.penugasan.index', ['tasm' => $tasm])
                ->with('error', "Penugasan tidak dapat dihapus karena sudah memiliki riwayat rekaman nilai di sistem RKM (PGS-7).");
        }

        $penugasan->delete();

        return redirect()->route('admin.master.penugasan.index', ['tasm' => $tasm])
            ->with('success', 'Penugasan mengajar berhasil dihapus.');
    }

    /**
     * Salin penugasan guru antar-semester (PGS-6).
     */
    public function salinPenugasan(Request $request)
    {
        $validated = $request->validate([
            'tasm_asal' => ['required', 'string', 'size:5'],
            'tasm_tujuan' => ['required', 'string', 'size:5', 'different:tasm_asal'],
        ], [
            'tasm_tujuan.different' => 'Periode semester tujuan harus berbeda dari periode semester asal.',
        ]);

        $tasmAsal = $validated['tasm_asal'];
        $tasmTujuan = $validated['tasm_tujuan'];

        try {
            DB::statement('CALL sp_salin_penugasan_guru(?, ?)', [$tasmAsal, $tasmTujuan]);
        } catch (\Throwable $e) {
            // Fallback manual transaksi Eloquent jika SP error / butuh idempotensi
            $asalList = GuruMapel::where('tasm', $tasmAsal)->get();
            if ($asalList->isEmpty()) {
                return back()->with('error', "Tidak ada data penugasan pada periode asal ({$tasmAsal}) untuk disalin.");
            }

            DB::transaction(function () use ($asalList, $tasmTujuan) {
                foreach ($asalList as $item) {
                    $exists = GuruMapel::where('tasm', $tasmTujuan)
                        ->where('id_guru', $item->id_guru)
                        ->where('id_kelas', $item->id_kelas)
                        ->where('id_mapel', $item->id_mapel)
                        ->exists();

                    if (!$exists) {
                        GuruMapel::create([
                            'tasm' => $tasmTujuan,
                            'id_guru' => $item->id_guru,
                            'id_kelas' => $item->id_kelas,
                            'id_mapel' => $item->id_mapel,
                        ]);
                    }
                }
            });
        }

        $totalTujuan = GuruMapel::where('tasm', $tasmTujuan)->count();

        return redirect()->route('admin.master.penugasan.index', ['tasm' => $tasmTujuan])
            ->with('success', "Penugasan guru berhasil disalin dari periode {$tasmAsal} ke {$tasmTujuan}. Total penugasan saat ini: {$totalTujuan}.");
    }
}
