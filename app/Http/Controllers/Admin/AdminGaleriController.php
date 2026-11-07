<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminGaleriController extends Controller
{
    /**
     * Status yang boleh dipilih saat membuat album baru.
     */
    private const STATUS_CREATE = ['draft', 'published'];

    /**
     * Status yang boleh dipilih saat mengedit album.
     */
    private const STATUS_EDIT = ['published', 'archived'];

    public function index(Request $request): View
    {
        $query = Galeri::with('fotoUtama', 'foto')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $galeriList = $query->paginate(10)->withQueryString();

        $stats = [
            'total'     => Galeri::count(),
            'published' => Galeri::where('status', 'published')->count(),
            'draft'     => Galeri::where('status', 'draft')->count(),
            'total_foto'=> GaleriFoto::count(),
        ];

        return view('pages.admin.cms.galeri.index', compact('galeriList', 'stats'));
    }

    public function create(): View
    {
        return view('pages.admin.cms.galeri.form', [
            'galeri'        => new Galeri(['status' => 'published']),
            'isEdit'        => false,
            'statusOptions' => self::STATUS_CREATE,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'     => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
            'status'    => ['required', 'in:' . implode(',', self::STATUS_CREATE)],
            'fotos'     => ['nullable', 'array'],
            'fotos.*'   => ['image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'judul.required'  => 'Judul album galeri wajib diisi.',
            'status.required' => 'Status galeri wajib dipilih.',
            'status.in'       => 'Album baru hanya dapat disimpan sebagai Draft atau Published.',
            'fotos.*.image'   => 'File harus berupa foto yang valid.',
        ]);

        $galeri = Galeri::create([
            'judul'     => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status'    => $validated['status'],
        ]);

        if ($request->hasFile('fotos')) {
            $urutan = 0;
            foreach ($request->file('fotos') as $file) {
                $path = $file->store('galeri', 'public');
                GaleriFoto::create([
                    'galeri_id'   => $galeri->id,
                    'gambar_path' => $path,
                    'keterangan'  => $galeri->judul,
                    'urutan'      => $urutan++,
                    'status'      => $galeri->status,
                ]);
            }
        }

        return redirect()->route('admin.cms.galeri.index')
            ->with('success', 'Album galeri berhasil dibuat.');
    }

    public function edit(Galeri $galeri): View
    {
        $galeri->load('foto');
        return view('pages.admin.cms.galeri.form', [
            'galeri'        => $galeri,
            'isEdit'        => true,
            'statusOptions' => self::STATUS_EDIT,
        ]);
    }

    public function update(Request $request, Galeri $galeri): RedirectResponse
    {
        $validated = $request->validate([
            'judul'     => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
            'status'    => ['required', 'in:' . implode(',', self::STATUS_EDIT)],
            'fotos'     => ['nullable', 'array'],
            'fotos.*'   => ['image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'judul.required'  => 'Judul album galeri wajib diisi.',
            'status.required' => 'Status galeri wajib dipilih.',
            'status.in'       => 'Saat edit, status hanya dapat diubah menjadi Published atau Archived.',
            'fotos.*.image'   => 'File harus berupa foto yang valid.',
        ]);

        $galeri->update([
            'judul'     => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status'    => $validated['status'],
        ]);

        // Upload foto tambahan jika ada
        if ($request->hasFile('fotos')) {
            $maxUrutan = (int) $galeri->foto()->max('urutan') + 1;
            foreach ($request->file('fotos') as $file) {
                $path = $file->store('galeri', 'public');
                GaleriFoto::create([
                    'galeri_id'   => $galeri->id,
                    'gambar_path' => $path,
                    'keterangan'  => $galeri->judul,
                    'urutan'      => $maxUrutan++,
                    'status'      => $galeri->status,
                ]);
            }
        }

        return redirect()->route('admin.cms.galeri.index')
            ->with('success', 'Album galeri berhasil diperbarui.');
    }

    public function destroy(Galeri $galeri): RedirectResponse
    {
        foreach ($galeri->foto as $foto) {
            Storage::disk('public')->delete($foto->gambar_path);
            $foto->delete();
        }

        $judul = $galeri->judul;
        $galeri->delete();

        return redirect()->route('admin.cms.galeri.index')
            ->with('success', "Album galeri '{$judul}' dan fotonya berhasil dihapus.");
    }

    public function destroyFoto(GaleriFoto $foto): RedirectResponse
    {
        Storage::disk('public')->delete($foto->gambar_path);
        $foto->delete();

        return back()->with('success', 'Foto berhasil dihapus dari galeri.');
    }
}