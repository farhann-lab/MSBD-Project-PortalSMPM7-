<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\GuruMapel;
use App\Models\Jadwal;
use App\Models\Pertemuan;
use App\Models\Siswa;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class GuruAbsensiController extends Controller
{
    /**
     * Tampilkan antarmuka pemilihan jadwal dan pembukaan sesi presensi (ABS-1 s.d. ABS-3).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            abort(403, 'Akses khusus guru.');
        }

        $guru = $user->guru;
        $tasmAktif = Tahun::getTasmAktif();
        $taAktif = Tahun::getTaAktif();

        // 1. Ambil daftar jadwal mengajar guru pada periode semester aktif
        $jadwalList = Jadwal::with(['guruMapel.kelas', 'guruMapel.mapel'])
            ->where('tasm', $tasmAktif)
            ->where('aktif', 1)
            ->whereHas('guruMapel', function ($q) use ($guru) {
                $q->where('id_guru', $guru->id);
            })
            ->get();

        $selectedJadwalId = $request->get('jadwal_id');
        if (!$selectedJadwalId && $jadwalList->isNotEmpty()) {
            $selectedJadwalId = $jadwalList->first()->id;
        }

        $selectedJadwal = null;
        $daftarPertemuan = collect();
        $selectedPertemuan = null;
        $siswaRombel = collect();
        $absensiMap = [];

        if ($selectedJadwalId) {
            $selectedJadwal = $jadwalList->firstWhere('id', $selectedJadwalId);

            if ($selectedJadwal) {
                // Riwayat pertemuan pada jadwal ini
                $daftarPertemuan = Pertemuan::with('absensiSiswa')
                    ->where('jadwal_id', $selectedJadwal->id)
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('sesi_ke', 'desc')
                    ->get();

                // Pilih pertemuan tertentu atau default ke pertemuan terbaru jika ada
                $selectedPertemuanId = $request->get('pertemuan_id');
                if ($selectedPertemuanId) {
                    $selectedPertemuan = $daftarPertemuan->firstWhere('id', $selectedPertemuanId);
                }

                // Ambil daftar siswa aktif yang terdaftar di rombel kelas jadwal ini pada tahun ajaran aktif
                $idKelas = $selectedJadwal->guruMapel->id_kelas;
                $siswaRombel = Siswa::whereHas('penempatanKelas', function ($q) use ($idKelas, $taAktif) {
                    $q->where('id_kelas', $idKelas)
                      ->where('ta', $taAktif);
                })
                ->where('stat_data', 'A')
                ->orderBy('nama')
                ->get();

                // Peta absensi yang sudah tersimpan untuk pertemuan terpilih
                if ($selectedPertemuan) {
                    $absensiMap = AbsensiSiswa::where('pertemuan_id', $selectedPertemuan->id)
                        ->get()
                        ->keyBy('id_siswa');
                }
            }
        }

        // Tentukan status wali kelas untuk header layout
        $isWaliKelas = $user->isWaliKelas($tasmAktif);
        $waliKelasRecord = $isWaliKelas ? $guru->waliKelas()->where('tasm', $tasmAktif)->with('kelas')->first() : null;
        $kelasPerwalian = $waliKelasRecord ? ($waliKelasRecord->kelas->nama ?? '-') : '-';

        return view('pages.guru.absensi', compact(
            'guru',
            'tasmAktif',
            'jadwalList',
            'selectedJadwalId',
            'selectedJadwal',
            'daftarPertemuan',
            'selectedPertemuan',
            'siswaRombel',
            'absensiMap',
            'isWaliKelas',
            'kelasPerwalian'
        ));
    }

    /**
     * Buka sesi pertemuan baru untuk jadwal tertentu (ABS-2, ABS-3).
     */
    public function bukaSesi(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            abort(403);
        }

        $validated = $request->validate([
            'jadwal_id' => ['required', 'integer', 'exists:siakad_jadwal,id'],
            'tanggal'   => ['required', 'date'],
            'catatan'   => ['nullable', 'string', 'max:500'],
        ]);

        $jadwal = Jadwal::with('guruMapel')->findOrFail($validated['jadwal_id']);

        // Verifikasi kepemilikan jadwal (guru yang login harus pengampu jadwal)
        if ($jadwal->guruMapel->id_guru != $user->id_guru) {
            abort(403, 'Anda tidak berwenang membuka absensi untuk jadwal ini.');
        }

        // Hitung sesi_ke berikutnya untuk jadwal ini
        $sesiKeTerakhir = Pertemuan::where('jadwal_id', $jadwal->id)->max('sesi_ke') ?? 0;
        $sesiKeBaru = $sesiKeTerakhir + 1;

        try {
            $pertemuan = Pertemuan::create([
                'jadwal_id'   => $jadwal->id,
                'tanggal'     => $validated['tanggal'],
                'sesi_ke'     => $sesiKeBaru,
                'catatan'     => $validated['catatan'] ?? null,
                'dibuat_oleh' => $user->id,
            ]);

            return redirect()->route('guru.absensi', [
                'jadwal_id' => $jadwal->id,
                'pertemuan_id' => $pertemuan->id,
            ])->with('success', "Sesi Pertemuan Ke-{$sesiKeBaru} berhasil dibuka. Silakan input status kehadiran siswa.");
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal membuka sesi pertemuan: ' . $e->getMessage());
        }
    }

    /**
     * Simpan status absensi rombel siswa pada sesi pertemuan (ABS-4 s.d. ABS-6, ABS-8).
     */
    public function simpanAbsensi(Request $request, $pertemuanId)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            abort(403);
        }

        $pertemuan = Pertemuan::with('jadwal.guruMapel')->findOrFail($pertemuanId);

        // Verifikasi kepemilikan sesi pertemuan
        if ($pertemuan->jadwal->guruMapel->id_guru != $user->id_guru) {
            abort(403, 'Anda tidak memiliki hak akses untuk menginput absensi pada pertemuan ini.');
        }

        $validated = $request->validate([
            'absensi' => ['required', 'array'],
            'absensi.*.status' => ['required', 'in:H,S,I,A'],
            'absensi.*.keterangan' => ['nullable', 'string', 'max:255'],
        ], [
            'absensi.*.status.required' => 'Status kehadiran siswa wajib dipilih.',
            'absensi.*.status.in' => 'Status absensi harus bernilai Hadir (H), Sakit (S), Izin (I), atau Alpa (A).',
        ]);

        $absensiData = $validated['absensi'];
        $actorUserId = $user->id;

        try {
            foreach ($absensiData as $idSiswa => $item) {
                $status = $item['status'];
                $keterangan = !empty($item['keterangan']) ? trim($item['keterangan']) : null;

                // Eksekusi Stored Procedure sp_simpan_absensi_siswa
                DB::statement('CALL sp_simpan_absensi_siswa(?, ?, ?, ?, ?)', [
                    $pertemuan->id,
                    $idSiswa,
                    $actorUserId,
                    $status,
                    $keterangan,
                ]);
            }

            return redirect()->route('guru.absensi', [
                'jadwal_id' => $pertemuan->jadwal_id,
                'pertemuan_id' => $pertemuan->id,
            ])->with('success', 'Presensi siswa pertemuan ke-' . $pertemuan->sesi_ke . ' berhasil disimpan.');
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan presensi: ' . $e->getMessage());
        }
    }
}
