<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminFasilitasController extends Controller
{
    /**
     * Status yang boleh dipilih saat membuat fasilitas baru.
     */
    private const STATUS_CREATE = ['draft', 'published'];

    /**
     * Status yang boleh dipilih saat mengedit fasilitas.
     */
    private const STATUS_EDIT = ['published', 'archived'];

    /**
     * Tampilkan daftar fasilitas dengan filter status dan pencarian.
     */
    public function index(Request $request): View
    {
        $query = Fasilitas::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $fasilitasList = $query->paginate(10)->withQueryString();

        $stats = [
            'total'     => Fasilitas::count(),
            'published' => Fasilitas::where('status', 'published')->count(),
            'draft'     => Fasilitas::where('status', 'draft')->count(),
            'archived'  => Fasilitas::where('status', 'archived')->count(),
        ];

        return view('pages.admin.cms.fasilitas.index', compact('fasilitasList', 'stats'));
    }

    /**
     * Form tambah fasilitas baru.
     */
    public function create(): View
    {
        return view('pages.admin.cms.fasilitas.form', [
            'fasilitas'     => new Fasilitas(['status' => 'draft']),
            'isEdit'        => false,
            'statusOptions' => self::STATUS_CREATE,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'      => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'status'    => ['required', 'in:' . implode(',', self::STATUS_CREATE)],
            'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'nama.required'   => 'Nama fasilitas wajib diisi.',
            'status.required' => 'Status publikasi wajib dipilih.',
            'status.in'       => 'Fasilitas baru hanya dapat disimpan sebagai Draft atau Published.',
            'gambar.image'    => 'File harus berupa gambar.',
            'gambar.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('fasilitas', 'public');
        }

        Fasilitas::create([
            'nama'        => $validated['nama'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'gambar_path' => $gambarPath,
            'status'      => $validated['status'],
        ]);

        return redirect()->route('admin.cms.fasilitas.index')
            ->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

    public function edit(Fasilitas $fasilitas): View
    {
        return view('pages.admin.cms.fasilitas.form', [
            'fasilitas'     => $fasilitas,
            'isEdit'        => true,
            'statusOptions' => self::STATUS_EDIT,
        ]);
    }

    public function update(Request $request, Fasilitas $fasilitas): RedirectResponse
    {
        $validated = $request->validate([
            'nama'         => ['required', 'string', 'max:150'],
            'deskripsi'    => ['nullable', 'string'],
            'status'       => ['required', 'in:' . implode(',', self::STATUS_EDIT)],
            'gambar'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'hapus_gambar' => ['nullable', 'boolean'],
        ], [
            'nama.required'   => 'Nama fasilitas wajib diisi.',
            'status.required' => 'Status publikasi wajib dipilih.',
            'status.in'       => 'Saat edit, status hanya dapat diubah menjadi Published atau Archived.',
            'gambar.image'    => 'File harus berupa gambar.',
            'gambar.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->boolean('hapus_gambar') && $fasilitas->gambar_path) {
            Storage::disk('public')->delete($fasilitas->gambar_path);
            $fasilitas->gambar_path = null;
        }

        if ($request->hasFile('gambar')) {
            if ($fasilitas->gambar_path) {
                Storage::disk('public')->delete($fasilitas->gambar_path);
            }
            $fasilitas->gambar_path = $request->file('gambar')->store('fasilitas', 'public');
        }

        $fasilitas->nama = $validated['nama'];
        $fasilitas->deskripsi = $validated['deskripsi'] ?? null;
        $fasilitas->status = $validated['status'];
        $fasilitas->save();

        return redirect()->route('admin.cms.fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilitas): RedirectResponse
    {
        if ($fasilitas->gambar_path) {
            Storage::disk('public')->delete($fasilitas->gambar_path);
        }

        $nama = $fasilitas->nama;
        $fasilitas->delete();

        return redirect()->route('admin.cms.fasilitas.index')
            ->with('success', "Fasilitas '{$nama}' berhasil dihapus.");
    }
}