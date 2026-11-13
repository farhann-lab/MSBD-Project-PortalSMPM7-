<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrestasiPublik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminPrestasiPublikController extends Controller
{
    /**
     * Status yang boleh dipilih saat membuat prestasi baru.
     */
    private const STATUS_CREATE = ['draft', 'published'];

    /**
     * Status yang boleh dipilih saat mengedit prestasi.
     */
    private const STATUS_EDIT = ['published', 'archived'];

    public function index(Request $request): View
    {
        $query = PrestasiPublik::latest('tanggal');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $prestasiList = $query->paginate(10)->withQueryString();

        $stats = [
            'total'     => PrestasiPublik::count(),
            'published' => PrestasiPublik::where('status', 'published')->count(),
            'draft'     => PrestasiPublik::where('status', 'draft')->count(),
            'tingkat'   => PrestasiPublik::distinct('tingkat')->count('tingkat'),
        ];

        return view('pages.admin.cms.prestasi.index', compact('prestasiList', 'stats'));
    }

    public function create(): View
    {
        return view('pages.admin.cms.prestasi.form', [
            'prestasi'      => new PrestasiPublik([
                'status'  => 'published',
                'tanggal' => now()->toDateString(),
                'tingkat' => 'Kota',
            ]),
            'isEdit'        => false,
            'statusOptions' => self::STATUS_CREATE,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'     => ['required', 'string', 'max:200'],
            'tingkat'   => ['nullable', 'string', 'max:80'],
            'tanggal'   => ['nullable', 'date'],
            'deskripsi' => ['nullable', 'string'],
            'status'    => ['required', 'in:' . implode(',', self::STATUS_CREATE)],
            'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'judul.required'  => 'Judul prestasi wajib diisi.',
            'status.required' => 'Status publikasi wajib dipilih.',
            'status.in'       => 'Prestasi baru hanya dapat disimpan sebagai Draft atau Published.',
            'gambar.image'    => 'File harus berupa gambar.',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('prestasi', 'public');
        }

        PrestasiPublik::create([
            'judul'       => $validated['judul'],
            'tingkat'     => $validated['tingkat'] ?? null,
            'tanggal'     => $validated['tanggal'] ?? null,
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'gambar_path' => $gambarPath,
            'status'      => $validated['status'],
        ]);

        return redirect()->route('admin.cms.prestasi.index')
            ->with('success', 'Prestasi baru berhasil ditambahkan.');
    }

    public function edit(PrestasiPublik $prestasi): View
    {
        return view('pages.admin.cms.prestasi.form', [
            'prestasi'      => $prestasi,
            'isEdit'        => true,
            'statusOptions' => self::STATUS_EDIT,
        ]);
    }

    public function update(Request $request, PrestasiPublik $prestasi): RedirectResponse
    {
        $validated = $request->validate([
            'judul'        => ['required', 'string', 'max:200'],
            'tingkat'      => ['nullable', 'string', 'max:80'],
            'tanggal'      => ['nullable', 'date'],
            'deskripsi'    => ['nullable', 'string'],
            'status'       => ['required', 'in:' . implode(',', self::STATUS_EDIT)],
            'gambar'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'hapus_gambar' => ['nullable', 'boolean'],
        ], [
            'judul.required'  => 'Judul prestasi wajib diisi.',
            'status.required' => 'Status publikasi wajib dipilih.',
            'status.in'       => 'Saat edit, status hanya dapat diubah menjadi Published atau Archived.',
            'gambar.image'    => 'File harus berupa gambar.',
        ]);

        if ($request->boolean('hapus_gambar') && $prestasi->gambar_path) {
            Storage::disk('public')->delete($prestasi->gambar_path);
            $prestasi->gambar_path = null;
        }

        if ($request->hasFile('gambar')) {
            if ($prestasi->gambar_path) {
                Storage::disk('public')->delete($prestasi->gambar_path);
            }
            $prestasi->gambar_path = $request->file('gambar')->store('prestasi', 'public');
        }

        $prestasi->judul = $validated['judul'];
        $prestasi->tingkat = $validated['tingkat'] ?? null;
        $prestasi->tanggal = $validated['tanggal'] ?? null;
        $prestasi->deskripsi = $validated['deskripsi'] ?? null;
        $prestasi->status = $validated['status'];
        $prestasi->save();

        return redirect()->route('admin.cms.prestasi.index')
            ->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(PrestasiPublik $prestasi): RedirectResponse
    {
        if ($prestasi->gambar_path) {
            Storage::disk('public')->delete($prestasi->gambar_path);
        }

        $judul = $prestasi->judul;
        $prestasi->delete();

        return redirect()->route('admin.cms.prestasi.index')
            ->with('success', "Prestasi '{$judul}' berhasil dihapus.");
    }
}