<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuruMapel;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminMapelController extends Controller
{
    /**
     * Tampilkan daftar mata pelajaran (MPL-1).
     */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $kelompok = $request->get('kelompok', '');

        $query = Mapel::withCount('penugasan');

        if (!empty($q)) {
            $query->search($q);
        }
        if (!empty($kelompok)) {
            $query->kelompok($kelompok);
        }

        $mapels = $query->orderBy('kelompok')
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Mapel::count(),
            'kelompok_a' => Mapel::where('kelompok', 'A')->count(),
            'kelompok_b' => Mapel::where('kelompok', 'B')->count(),
            'kelompok_c' => Mapel::where('kelompok', 'C')->count(),
        ];

        return view('pages.admin.master.mapel.index', compact('mapels', 'q', 'kelompok', 'stats'));
    }

    /**
     * Simpan data mata pelajaran baru (MPL-2).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150', 'unique:m_mapel,nama'],
            'kd_singkat' => ['required', 'string', 'max:5'],
            'kelompok' => ['required', 'in:A,B,C'],
            'tambahan_sub' => ['required', 'in:NO,PAI,MULOK'],
            'is_sikap' => ['required', 'in:0,1'],
        ], [
            'nama.unique' => 'Nama mata pelajaran sudah terdaftar.',
            'kd_singkat.max' => 'Kode singkat maksimal 5 karakter.',
            'kelompok.in' => 'Kelompok mapel harus A, B, atau C.',
            'tambahan_sub.in' => 'Jenis tambahan harus NO, PAI, atau MULOK.',
            'is_sikap.in' => 'Penanda sikap harus 0 atau 1.',
        ]);

        Mapel::create($validated);

        return redirect()->route('admin.master.mapel.index')
            ->with('success', "Mata pelajaran '{$validated['nama']}' berhasil ditambahkan.");
    }

    /**
     * Update data mata pelajaran (MPL-3).
     */
    public function update(Request $request, Mapel $mapel)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150', Rule::unique('m_mapel', 'nama')->ignore($mapel->id)],
            'kd_singkat' => ['required', 'string', 'max:5'],
            'kelompok' => ['required', 'in:A,B,C'],
            'tambahan_sub' => ['required', 'in:NO,PAI,MULOK'],
            'is_sikap' => ['required', 'in:0,1'],
        ], [
            'nama.unique' => 'Nama mata pelajaran sudah terdaftar.',
            'kd_singkat.max' => 'Kode singkat maksimal 5 karakter.',
            'kelompok.in' => 'Kelompok mapel harus A, B, atau C.',
            'tambahan_sub.in' => 'Jenis tambahan harus NO, PAI, atau MULOK.',
            'is_sikap.in' => 'Penanda sikap harus 0 atau 1.',
        ]);

        $mapel->update($validated);

        return redirect()->route('admin.master.mapel.index')
            ->with('success', "Mata pelajaran '{$mapel->nama}' berhasil diperbarui.");
    }

    /**
     * Hapus mata pelajaran dengan proteksi dependensi (MPL-4).
     */
    public function destroy(Mapel $mapel)
    {
        $hasPenugasan = GuruMapel::where('id_mapel', $mapel->id)->exists();

        if ($hasPenugasan) {
            return redirect()->route('admin.master.mapel.index')
                ->with('error', "Mata pelajaran '{$mapel->nama}' tidak dapat dihapus karena masih digunakan dalam data penugasan guru.");
        }

        $nama = $mapel->nama;
        $mapel->delete();

        return redirect()->route('admin.master.mapel.index')
            ->with('success', "Mata pelajaran '{$nama}' berhasil dihapus.");
    }
}
