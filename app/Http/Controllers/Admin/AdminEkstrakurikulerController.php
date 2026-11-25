<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\EkstrakurikulerPublik;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class AdminEkstrakurikulerController extends Controller
{
    private const STATUS_LABELS = [
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ];

    public function index(Request $request): View
    {
        $searchInput = $request->query('q');
        $search = is_string($searchInput) ? trim($searchInput) : '';
        $ekskulList = Ekstrakurikuler::with('publik')
            ->when($search !== '', fn ($query) => $query->where('nama', 'like', '%'.$search.'%'))
            ->orderBy('nama')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.master.ekskul', [
            'ekskulList' => $ekskulList,
            'search' => $search,
            'statusLabels' => self::STATUS_LABELS,
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.master.ekskul.nama', ['ekskul' => new Ekstrakurikuler]);
    }

    public function store(Request $request): RedirectResponse
    {
        Ekstrakurikuler::create($this->validateName($request));

        return redirect()->route('admin.master.ekskul')
            ->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function edit(Ekstrakurikuler $ekskul): View
    {
        return view('pages.admin.master.ekskul.nama', compact('ekskul'));
    }

    public function update(Request $request, Ekstrakurikuler $ekskul): RedirectResponse
    {
        $ekskul->update($this->validateName($request));

        return redirect()->route('admin.master.ekskul')
            ->with('success', 'Nama ekstrakurikuler berhasil diperbarui.');
    }

    public function editPublication(Ekstrakurikuler $ekskul): View
    {
        $publik = $ekskul->publik ?? new EkstrakurikulerPublik(['status' => 'draft']);

        return view('pages.admin.master.ekskul.publik', [
            'ekskul' => $ekskul,
            'publik' => $publik,
            'statusLabels' => self::STATUS_LABELS,
        ]);
    }

    public function updatePublication(Request $request, Ekstrakurikuler $ekskul): RedirectResponse
    {
        $validated = $request->validate([
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_keys(self::STATUS_LABELS))],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'hapus_gambar' => ['sometimes', 'boolean'],
        ]);

        $newPath = null;
        if ($request->hasFile('gambar')) {
            $newPath = $request->file('gambar')->store(EkstrakurikulerPublik::IMAGE_DIRECTORY, 'local');
            if ($newPath === false) {
                throw ValidationException::withMessages(['gambar' => 'Gambar gagal disimpan. Coba lagi.']);
            }
        }

        try {
            $previousPath = $ekskul->getConnection()->transaction(function () use ($ekskul, $request, $validated, $newPath) {
                // Lock the master row so concurrent saves also serialize the first publication.
                $master = $ekskul->newQuery()->lockForUpdate()->findOrFail($ekskul->getKey());
                $publik = $master->publik()->firstOrNew();
                $previousPath = null;

                if ($newPath !== null || $request->boolean('hapus_gambar')) {
                    $previousPath = $publik->hasManagedImage() ? $publik->gambar_path : null;
                    $publik->gambar_path = $newPath;
                }

                $publik->fill([
                    'deskripsi' => $validated['deskripsi'] ?? null,
                    'status' => $validated['status'],
                ]);
                $master->publik()->save($publik);

                return $previousPath;
            });
        } catch (Throwable $error) {
            if ($newPath !== null) {
                Storage::disk('local')->delete($newPath);
            }

            throw $error;
        }

        if ($previousPath !== null) {
            Storage::disk('local')->delete($previousPath);
        }

        return redirect()->route('admin.master.ekskul')
            ->with('success', 'Informasi publik ekstrakurikuler berhasil disimpan.');
    }

    public function image(Ekstrakurikuler $ekskul): StreamedResponse
    {
        $publik = $ekskul->publik;
        $disk = Storage::disk('local');
        abort_unless($publik?->hasManagedImage() && $disk->exists($publik->gambar_path), 404);

        return $disk->response($publik->gambar_path, headers: ['Cache-Control' => 'private, no-store']);
    }

    private function validateName(Request $request): array
    {
        return $request->validate([
            'nama' => ['bail', 'required', 'string', 'max:50', function (string $attribute, string $value, Closure $fail) {
                $latin1 = mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                if (mb_convert_encoding($latin1, 'UTF-8', 'Windows-1252') !== $value) {
                    $fail('Nama memuat karakter yang tidak didukung oleh database RKM.');
                }
            }],
        ], ['nama.required' => 'Nama ekstrakurikuler wajib diisi.', 'nama.max' => 'Nama ekstrakurikuler maksimal 50 karakter.']);
    }
}
