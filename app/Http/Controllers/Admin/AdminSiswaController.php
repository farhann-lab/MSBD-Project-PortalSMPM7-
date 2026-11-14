<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminSiswaController extends Controller
{
    /**
     * Tampilkan daftar siswa dengan pagination, pencarian, dan filter status/kelas.
     */
    public function index(Request $request): View
    {
        $query = Siswa::query()->with('user');

        // Filter status: A (Aktif), K (Keluar), M (Mutasi), L (Lulus)
        if ($request->filled('status')) {
            $query->status($request->status);
        } else {
            $query->status('A');
        }

        // Filter pencarian nama, NIS, atau NISN
        if ($request->filled('q')) {
            $query->search(trim($request->q));
        }

        $students = $query->orderBy('nama')->paginate(20)->withQueryString();

        $stats = [
            'total'   => Siswa::count(),
            'aktif'   => Siswa::where('stat_data', 'A')->count(),
            'keluar'  => Siswa::where('stat_data', 'K')->count(),
            'mutasi'  => Siswa::where('stat_data', 'M')->count(),
            'lulus'   => Siswa::where('stat_data', 'L')->count(),
        ];

        return view('pages.admin.master.siswa.index', compact('students', 'stats'));
    }

    /**
     * Tampilkan formulir penambahan data siswa baru (3 Tab).
     */
    public function create(): View
    {
        return view('pages.admin.master.siswa.form');
    }

    /**
     * Simpan data siswa baru ke tabel m_siswa.
     */
    public function store(Request $request): RedirectResponse
    {
        // PRD & DESIGN.md: Hanya NIS dan Nama yang berstatus wajib
        $validated = $request->validate([
            'nis'             => ['required', 'string', 'max:10', 'unique:m_siswa,nis'],
            'nama'            => ['required', 'string', 'max:100'],
            'nisn'            => ['nullable', 'string', 'max:10'],
            'jk'              => ['nullable', Rule::in(['L', 'P'])],
            'tmp_lahir'       => ['nullable', 'string', 'max:50'],
            'tgl_lahir'       => ['nullable', 'date'],
            'agama'           => ['nullable', 'string', 'max:10'],
            'status'          => ['nullable', 'string', 'max:2'],
            'anakke'          => ['nullable', 'integer', 'min:1', 'max:99'],
            'alamat'          => ['nullable', 'string', 'max:50'],
            'notelp'          => ['nullable', 'string', 'max:13'],
            // Sekolah Asal
            'sek_asal'        => ['nullable', 'string', 'max:30'],
            'sek_asal_alamat' => ['nullable', 'string', 'max:50'],
            'diterima_kelas'  => ['nullable', 'string', 'max:5'],
            'diterima_tgl'    => ['nullable', 'date'],
            'diterima_smt'    => ['nullable', 'string', 'max:2'],
            'ijazah_no'       => ['nullable', 'string', 'max:50'],
            'ijazah_thn'      => ['nullable', 'string', 'max:4'],
            'skhun_no'        => ['nullable', 'string', 'max:50'],
            'skhun_thn'       => ['nullable', 'string', 'max:4'],
            // Orang Tua / Wali
            'ortu_ayah'       => ['nullable', 'string', 'max:50'],
            'ortu_ibu'        => ['nullable', 'string', 'max:50'],
            'ortu_alamat'     => ['nullable', 'string', 'max:50'],
            'ortu_notelp'     => ['nullable', 'string', 'max:13'],
            'ortu_ayah_pkj'   => ['nullable', 'string', 'max:30'],
            'ortu_ibu_pkj'    => ['nullable', 'string', 'max:30'],
            'wali'            => ['nullable', 'string', 'max:20'],
            'wali_alamat'     => ['nullable', 'string', 'max:50'],
            'notelp_rumah'    => ['nullable', 'string', 'max:13'],
            'wali_pkj'        => ['nullable', 'string', 'max:13'],
            // Akun login opsional
            'buat_akun'       => ['nullable', 'boolean'],
        ]);

        DB::beginTransaction();
        try {
            // Persiapkan data kompatibel RKM (NOT NULL string kosong / default)
            $data = [
                'nis'             => $validated['nis'],
                'nama'            => $validated['nama'],
                'nisn'            => $validated['nisn'] ?? '0',
                'jk'              => $validated['jk'] ?? 'L',
                'tmp_lahir'       => $validated['tmp_lahir'] ?? '',
                'tgl_lahir'       => !empty($validated['tgl_lahir']) ? $validated['tgl_lahir'] : '0000-00-00',
                'agama'           => $validated['agama'] ?? '',
                'status'          => $validated['status'] ?? '',
                'anakke'          => $validated['anakke'] ?? 1,
                'alamat'          => $validated['alamat'] ?? '',
                'notelp'          => $validated['notelp'] ?? '',
                'sek_asal'        => $validated['sek_asal'] ?? '',
                'sek_asal_alamat' => $validated['sek_asal_alamat'] ?? '',
                'diterima_kelas'  => $validated['diterima_kelas'] ?? '',
                'diterima_tgl'    => !empty($validated['diterima_tgl']) ? $validated['diterima_tgl'] : '0000-00-00',
                'diterima_smt'    => $validated['diterima_smt'] ?? '',
                'ijazah_no'       => $validated['ijazah_no'] ?? '',
                'ijazah_thn'      => $validated['ijazah_thn'] ?? '',
                'skhun_no'        => $validated['skhun_no'] ?? '',
                'skhun_thn'       => $validated['skhun_thn'] ?? '',
                'ortu_ayah'       => $validated['ortu_ayah'] ?? '',
                'ortu_ibu'        => $validated['ortu_ibu'] ?? '',
                'ortu_alamat'     => $validated['ortu_alamat'] ?? '',
                'ortu_notelp'     => $validated['ortu_notelp'] ?? '',
                'ortu_ayah_pkj'   => $validated['ortu_ayah_pkj'] ?? '',
                'ortu_ibu_pkj'    => $validated['ortu_ibu_pkj'] ?? '',
                'wali'            => $validated['wali'] ?? '',
                'wali_alamat'     => $validated['wali_alamat'] ?? '',
                'notelp_rumah'    => $validated['notelp_rumah'] ?? '',
                'wali_pkj'        => $validated['wali_pkj'] ?? '',
                'inputID'         => 0, // Sesuai PRD SIS-3 & ARCHITECTURE: inputID selalu 0
                'stat_data'       => 'A', // Default Aktif
                'foto'            => '',
            ];

            $siswa = Siswa::create($data);

            // Jika opsi buat akun dipilih (username = NIS per PRD AKN-2)
            if ($request->boolean('buat_akun')) {
                User::create([
                    'username'             => $siswa->nis,
                    'password'             => Hash::make('Siswa@' . $siswa->nis),
                    'role'                 => 'siswa',
                    'id_siswa'             => $siswa->id,
                    'is_active'            => true,
                    'wajib_ganti_password' => true,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.master.siswa.index')
                ->with('success', "Data siswa {$siswa->nama} (NIS: {$siswa->nis}) berhasil ditambahkan.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan formulir edit data siswa.
     */
    public function edit(Siswa $siswa): View
    {
        return view('pages.admin.master.siswa.form', compact('siswa'));
    }

    /**
     * Perbarui data siswa di m_siswa.
     */
    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'nis'             => ['required', 'string', 'max:10', Rule::unique('m_siswa', 'nis')->ignore($siswa->id)],
            'nama'            => ['required', 'string', 'max:100'],
            'nisn'            => ['nullable', 'string', 'max:10'],
            'jk'              => ['nullable', Rule::in(['L', 'P'])],
            'tmp_lahir'       => ['nullable', 'string', 'max:50'],
            'tgl_lahir'       => ['nullable', 'date'],
            'agama'           => ['nullable', 'string', 'max:10'],
            'status'          => ['nullable', 'string', 'max:2'],
            'anakke'          => ['nullable', 'integer', 'min:1', 'max:99'],
            'alamat'          => ['nullable', 'string', 'max:50'],
            'notelp'          => ['nullable', 'string', 'max:13'],
            // Sekolah Asal
            'sek_asal'        => ['nullable', 'string', 'max:30'],
            'sek_asal_alamat' => ['nullable', 'string', 'max:50'],
            'diterima_kelas'  => ['nullable', 'string', 'max:5'],
            'diterima_tgl'    => ['nullable', 'date'],
            'diterima_smt'    => ['nullable', 'string', 'max:2'],
            'ijazah_no'       => ['nullable', 'string', 'max:50'],
            'ijazah_thn'      => ['nullable', 'string', 'max:4'],
            'skhun_no'        => ['nullable', 'string', 'max:50'],
            'skhun_thn'       => ['nullable', 'string', 'max:4'],
            // Orang Tua / Wali
            'ortu_ayah'       => ['nullable', 'string', 'max:50'],
            'ortu_ibu'        => ['nullable', 'string', 'max:50'],
            'ortu_alamat'     => ['nullable', 'string', 'max:50'],
            'ortu_notelp'     => ['nullable', 'string', 'max:13'],
            'ortu_ayah_pkj'   => ['nullable', 'string', 'max:30'],
            'ortu_ibu_pkj'    => ['nullable', 'string', 'max:30'],
            'wali'            => ['nullable', 'string', 'max:20'],
            'wali_alamat'     => ['nullable', 'string', 'max:50'],
            'notelp_rumah'    => ['nullable', 'string', 'max:13'],
            'wali_pkj'        => ['nullable', 'string', 'max:13'],
            // Status Data
            'stat_data'       => ['nullable', Rule::in(['A', 'K', 'M', 'L'])],
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'nis'             => $validated['nis'],
                'nama'            => $validated['nama'],
                'nisn'            => $validated['nisn'] ?? '0',
                'jk'              => $validated['jk'] ?? $siswa->jk,
                'tmp_lahir'       => $validated['tmp_lahir'] ?? '',
                'tgl_lahir'       => !empty($validated['tgl_lahir']) ? $validated['tgl_lahir'] : '0000-00-00',
                'agama'           => $validated['agama'] ?? '',
                'status'          => $validated['status'] ?? '',
                'anakke'          => $validated['anakke'] ?? $siswa->anakke,
                'alamat'          => $validated['alamat'] ?? '',
                'notelp'          => $validated['notelp'] ?? '',
                'sek_asal'        => $validated['sek_asal'] ?? '',
                'sek_asal_alamat' => $validated['sek_asal_alamat'] ?? '',
                'diterima_kelas'  => $validated['diterima_kelas'] ?? '',
                'diterima_tgl'    => !empty($validated['diterima_tgl']) ? $validated['diterima_tgl'] : '0000-00-00',
                'diterima_smt'    => $validated['diterima_smt'] ?? '',
                'ijazah_no'       => $validated['ijazah_no'] ?? '',
                'ijazah_thn'      => $validated['ijazah_thn'] ?? '',
                'skhun_no'        => $validated['skhun_no'] ?? '',
                'skhun_thn'       => $validated['skhun_thn'] ?? '',
                'ortu_ayah'       => $validated['ortu_ayah'] ?? '',
                'ortu_ibu'        => $validated['ortu_ibu'] ?? '',
                'ortu_alamat'     => $validated['ortu_alamat'] ?? '',
                'ortu_notelp'     => $validated['ortu_notelp'] ?? '',
                'ortu_ayah_pkj'   => $validated['ortu_ayah_pkj'] ?? '',
                'ortu_ibu_pkj'    => $validated['ortu_ibu_pkj'] ?? '',
                'wali'            => $validated['wali'] ?? '',
                'wali_alamat'     => $validated['wali_alamat'] ?? '',
                'notelp_rumah'    => $validated['notelp_rumah'] ?? '',
                'wali_pkj'        => $validated['wali_pkj'] ?? '',
                'stat_data'       => $validated['stat_data'] ?? $siswa->stat_data,
            ];

            $siswa->update($data);

            // Sinkronkan username jika NIS berubah dan akun sudah ada
            if ($siswa->user && $siswa->user->username !== $siswa->nis) {
                $siswa->user->update(['username' => $siswa->nis]);
            }

            DB::commit();

            return redirect()
                ->route('admin.master.siswa.index')
                ->with('success', "Data siswa {$siswa->nama} berhasil diperbarui.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Nonaktifkan siswa / ubah status (Soft Delete via stat_data).
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        try {
            $siswa->update(['stat_data' => 'K']);

            if ($siswa->user) {
                $siswa->user->update(['is_active' => false]);
            }

            return redirect()
                ->route('admin.master.siswa.index')
                ->with('success', "Status siswa {$siswa->nama} berhasil diubah menjadi Keluar (Nonaktif).");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengubah status siswa: ' . $e->getMessage());
        }
    }
}
