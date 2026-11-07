<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminBeritaController extends Controller
{
    /**
     * Status yang boleh dipilih saat membuat berita baru.
     */
    private const STATUS_CREATE = ['draft', 'published'];

    /**
     * Status yang boleh dipilih saat mengedit berita.
     */
    private const STATUS_EDIT = ['published', 'archived'];

    /**
     * Tampilkan daftar berita dengan filter status dan pencarian.
     */
    public function index(Request $request): View
    {
        $query = Berita::with('penulis')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('isi', 'like', "%{$search}%");
            });
        }

        $beritaList = $query->paginate(10)->withQueryString();

        $stats = [
            'total'     => Berita::count(),
            'published' => Berita::where('status', 'published')->count(),
            'draft'     => Berita::where('status', 'draft')->count(),
            'archived'  => Berita::where('status', 'archived')->count(),
        ];

        return view('pages.admin.cms.berita.index', compact('beritaList', 'stats'));
    }

    /**
     * Form tambah berita baru.
     */
    public function create(): View
    {
        return view('pages.admin.cms.berita.form', [
            'berita'        => new Berita(['status' => 'draft']),
            'isEdit'        => false,
            'statusOptions' => self::STATUS_CREATE,
        ]);
    }

    /**
     * Simpan berita baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'        => ['required', 'string', 'max:200'],
            'isi'          => ['required', 'string'],
            'status'       => ['required', 'in:' . implode(',', self::STATUS_CREATE)],
            'gambar'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'published_at' => ['nullable', 'date'],
        ], [
            'judul.required'  => 'Judul berita wajib diisi.',
            'judul.max'       => 'Judul berita maksimal 200 karakter.',
            'isi.required'    => 'Konten isi berita wajib diisi.',
            'status.required' => 'Status publikasi wajib dipilih.',
            'status.in'       => 'Berita baru hanya dapat disimpan sebagai Draft atau Published.',
            'gambar.image'    => 'File harus berupa gambar valid.',
            'gambar.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Buat slug unik
        $baseSlug = Str::slug($validated['judul']);
        $slug = $baseSlug;
        $counter = 1;
        while (Berita::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('berita', 'public');
        }

        $publishedAt = null;
        if ($validated['status'] === 'published') {
            $publishedAt = !empty($validated['published_at']) ? $validated['published_at'] : now();
        }

        Berita::create([
            'judul'           => $validated['judul'],
            'slug'            => $slug,
            'isi'             => $validated['isi'],
            'gambar_path'     => $gambarPath,
            'status'          => $validated['status'],
            'published_at'    => $publishedAt,
            'penulis_user_id' => $request->user()->id,
        ]);

        return redirect()->route('admin.cms.berita.index')
            ->with('success', 'Berita baru berhasil ditambahkan.');
    }

    /**
     * Form edit berita.
     */
    public function edit(Berita $berita): View
    {
        return view('pages.admin.cms.berita.form', [
            'berita'        => $berita,
            'isEdit'        => true,
            'statusOptions' => self::STATUS_EDIT,
        ]);
    }

    /**
     * Update berita di database.
     */
    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $validated = $request->validate([
            'judul'        => ['required', 'string', 'max:200'],
            'isi'          => ['required', 'string'],
            'status'       => ['required', 'in:' . implode(',', self::STATUS_EDIT)],
            'gambar'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'published_at' => ['nullable', 'date'],
            'hapus_gambar' => ['nullable', 'boolean'],
        ], [
            'judul.required'  => 'Judul berita wajib diisi.',
            'isi.required'    => 'Konten isi berita wajib diisi.',
            'status.required' => 'Status publikasi wajib dipilih.',
            'status.in'       => 'Saat edit, status hanya dapat diubah menjadi Published atau Archived.',
            'gambar.image'    => 'File harus berupa gambar valid.',
            'gambar.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Perbarui slug jika judul berubah
        if ($berita->judul !== $validated['judul']) {
            $baseSlug = Str::slug($validated['judul']);
            $slug = $baseSlug;
            $counter = 1;
            while (Berita::where('slug', $slug)->where('id', '!=', $berita->id)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $berita->slug = $slug;
        }

        // Kelola gambar
        if ($request->boolean('hapus_gambar') && $berita->gambar_path) {
            Storage::disk('public')->delete($berita->gambar_path);
            $berita->gambar_path = null;
        }

        if ($request->hasFile('gambar')) {
            if ($berita->gambar_path) {
                Storage::disk('public')->delete($berita->gambar_path);
            }
            $berita->gambar_path = $request->file('gambar')->store('berita', 'public');
        }

        // Kelola tanggal publish (status archived mempertahankan tanggal lama)
        if ($validated['status'] === 'published') {
            $berita->published_at = !empty($validated['published_at'])
                ? $validated['published_at']
                : ($berita->published_at ?? now());
        }

        $berita->judul = $validated['judul'];
        $berita->isi = $validated['isi'];
        $berita->status = $validated['status'];
        $berita->save();

        return redirect()->route('admin.cms.berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Hapus berita dari database.
     */
    public function destroy(Berita $berita): RedirectResponse
    {
        if ($berita->gambar_path) {
            Storage::disk('public')->delete($berita->gambar_path);
        }

        $judul = $berita->judul;
        $berita->delete();

        return redirect()->route('admin.cms.berita.index')
            ->with('success', "Berita '{$judul}' berhasil dihapus.");
    }
}