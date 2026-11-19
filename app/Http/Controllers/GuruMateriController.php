<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use App\Models\Materi;
use App\Models\MateriKelas;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GuruMateriController extends Controller
{
    /**
     * Tampilkan daftar materi yang dikelola oleh guru yang sedang login (MTR-1).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            abort(403, 'Akses khusus guru.');
        }

        $guru = $user->guru;
        $tasmAktif = Tahun::getTasmAktif();

        // Ambil penugasan guru yang aktif untuk filter dan form modal
        $penugasanAktif = GuruMapel::with(['kelas', 'mapel'])
            ->where('tasm', $tasmAktif)
            ->where('id_guru', $guru->id)
            ->get();

        $mapelList = $penugasanAktif->pluck('mapel')->unique('id');

        $query = Materi::with(['mapel', 'materiKelas.guruMapel.kelas'])
            ->where('id_guru', $guru->id);

        if ($request->filled('mapel_id')) {
            $query->where('id_mapel', $request->mapel_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        $materiList = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        // Info Wali Kelas untuk header layout guru
        $isWaliKelas = $user->isWaliKelas($tasmAktif);
        $waliKelasRecord = $isWaliKelas ? $guru->waliKelas()->where('tasm', $tasmAktif)->with('kelas')->first() : null;
        $kelasPerwalian = $waliKelasRecord ? ($waliKelasRecord->kelas->nama ?? '-') : '-';

        return view('pages.guru.materi', compact(
            'guru',
            'tasmAktif',
            'materiList',
            'penugasanAktif',
            'mapelList',
            'isWaliKelas',
            'kelasPerwalian'
        ));
    }

    /**
     * Simpan materi baru beserta relasi kelas tujuan (MTR-2 s.d. MTR-5).
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            abort(403);
        }

        $guru = $user->guru;
        $tasmAktif = Tahun::getTasmAktif();

        $validated = $request->validate([
            'id_mapel' => ['required', 'integer', 'exists:m_mapel,id'],
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
            'jenis' => ['required', 'in:file,tautan'],
            'file_materi' => [
                'required_if:jenis,file',
                'nullable',
                'file',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png',
                'max:15360', // Maksimal 15 MB sesuai PRD MTR-5
            ],
            'tautan_url' => [
                'required_if:jenis,tautan',
                'nullable',
                'url',
                'max:1000',
            ],
            'id_guru_mapel' => ['required', 'array', 'min:1'],
            'id_guru_mapel.*' => ['integer', 'exists:t_guru_mapel,id'],
        ], [
            'file_materi.required_if' => 'Berkas materi wajib diunggah jika jenis materi adalah Dokumen / Berkas.',
            'file_materi.mimes' => 'Format berkas hanya diperbolehkan: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, JPG, JPEG, PNG.',
            'file_materi.max' => 'Ukuran berkas materi maksimal 15 MB.',
            'tautan_url.required_if' => 'Tautan URL eksternal wajib diisi jika jenis materi adalah Tautan Web.',
            'id_guru_mapel.required' => 'Pilih minimal satu kelas tujuan untuk materi ini.',
        ]);

        // Verifikasi bahwa seluruh id_guru_mapel yang dipilih benar-benar milik guru tersebut dan sesuai id_mapel
        $validPenugasanCount = GuruMapel::whereIn('id', $validated['id_guru_mapel'])
            ->where('id_guru', $guru->id)
            ->where('id_mapel', $validated['id_mapel'])
            ->count();

        if ($validPenugasanCount !== count($validated['id_guru_mapel'])) {
            return back()->withInput()->with('error', 'Pilihan kelas penugasan tidak sesuai dengan mata pelajaran dan kewenangan mengajar Anda.');
        }

        try {
            DB::beginTransaction();

            $filePath = null;
            $tautanUrl = null;
            $ukuranByte = null;

            if ($validated['jenis'] === 'file' && $request->hasFile('file_materi')) {
                $file = $request->file('file_materi');
                $filePath = $file->store('materi', 'local'); // Disimpan di storage/app/private/materi (atau storage/app/materi)
                $ukuranByte = $file->getSize();
            } elseif ($validated['jenis'] === 'tautan') {
                $tautanUrl = $validated['tautan_url'];
            }

            $materi = Materi::create([
                'id_guru' => $guru->id,
                'id_mapel' => $validated['id_mapel'],
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'jenis' => $validated['jenis'],
                'file_path' => $filePath,
                'tautan_url' => $tautanUrl,
                'ukuran_byte' => $ukuranByte,
                'status' => 'aktif',
            ]);

            // Hubungkan ke kelas target pada siakad_materi_kelas
            foreach ($validated['id_guru_mapel'] as $gmId) {
                MateriKelas::create([
                    'materi_id' => $materi->id,
                    'id_guru_mapel' => $gmId,
                ]);
            }

            DB::commit();

            return redirect()->route('guru.materi')->with('success', 'Materi pembelajaran berhasil ditambahkan.');
        } catch (Throwable $e) {
            DB::rollBack();
            if (isset($filePath)) {
                Storage::disk('local')->delete($filePath);
            }
            return back()->withInput()->with('error', 'Gagal menambahkan materi: ' . $e->getMessage());
        }
    }

    /**
     * Update materi pembelajaran miliknya (MTR-6).
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            abort(403);
        }

        $guru = $user->guru;
        $materi = Materi::findOrFail($id);
        if ($materi->id_guru !== $guru->id) {
            abort(403, 'Anda tidak memiliki hak untuk mengubah materi guru lain.');
        }

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,arsip'],
            'file_materi' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png',
                'max:15360',
            ],
            'tautan_url' => [
                'nullable',
                'url',
                'max:1000',
            ],
        ]);

        try {
            DB::beginTransaction();

            $materi->judul = $validated['judul'];
            $materi->deskripsi = $validated['deskripsi'] ?? null;
            $materi->status = $validated['status'];

            if ($materi->jenis === 'file' && $request->hasFile('file_materi')) {
                // Hapus berkas lama jika ada
                if ($materi->file_path) {
                    Storage::disk('local')->delete($materi->file_path);
                }
                $file = $request->file('file_materi');
                $materi->file_path = $file->store('materi', 'local');
                $materi->ukuran_byte = $file->getSize();
            } elseif ($materi->jenis === 'tautan' && !empty($validated['tautan_url'])) {
                $materi->tautan_url = $validated['tautan_url'];
            }

            $materi->save();

            DB::commit();

            return redirect()->route('guru.materi')->with('success', 'Materi pembelajaran berhasil diperbarui.');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui materi: ' . $e->getMessage());
        }
    }

    /**
     * Hapus materi pembelajaran (MTR-7).
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            abort(403);
        }

        $guru = $user->guru;
        $materi = Materi::findOrFail($id);
        if ($materi->id_guru !== $guru->id) {
            abort(403, 'Anda tidak memiliki hak untuk mengubah materi guru lain.');
        }

        try {
            DB::beginTransaction();

            // Hapus pivot materi kelas
            MateriKelas::where('materi_id', $materi->id)->delete();

            // Hapus berkas fisik jika tipe file
            if ($materi->file_path) {
                Storage::disk('local')->delete($materi->file_path);
            }

            $materi->delete();

            DB::commit();

            return redirect()->route('guru.materi')->with('success', 'Materi pembelajaran berhasil dihapus.');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus materi: ' . $e->getMessage());
        }
    }
}
