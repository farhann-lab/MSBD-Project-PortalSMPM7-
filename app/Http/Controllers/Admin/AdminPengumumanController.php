<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPengumumanController extends Controller
{
    /**
     * Status yang boleh dipilih saat membuat pengumuman baru.
     */
    private const STATUS_CREATE = ['draft', 'published'];

    /**
     * Status yang boleh dipilih saat mengedit pengumuman.
     */
    private const STATUS_EDIT = ['published', 'archived'];

    /**
     * Tampilkan daftar pengumuman dengan filter status dan pencarian.
     */
    public function index(Request $request): View
    {
        $query = Pengumuman::with('pembuat')->latest('tanggal');

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

        $pengumumanList = $query->paginate(10)->withQueryString();

        $stats = [
            'total'     => Pengumuman::count(),
            'published' => Pengumuman::where('status', 'published')->count(),
            'draft'     => Pengumuman::where('status', 'draft')->count(),
            'archived'  => Pengumuman::where('status', 'archived')->count(),
        ];

        return view('pages.admin.cms.pengumuman.index', compact('pengumumanList', 'stats'));
    }

    /**
     * Form tambah pengumuman baru.
     */
    public function create(): View
    {
        return view('pages.admin.cms.pengumuman.form', [
            'pengumuman'    => new Pengumuman([
                'status'  => 'published',
                'tanggal' => now()->toDateString(),
            ]),
            'isEdit'        => false,
            'statusOptions' => self::STATUS_CREATE,
        ]);
    }

    /**
     * Simpan pengumuman baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'   => ['required', 'string', 'max:200'],
            'isi'     => ['required', 'string'],
            'tanggal' => ['required', 'date'],
            'status'  => ['required', 'in:' . implode(',', self::STATUS_CREATE)],
        ], [
            'judul.required'   => 'Judul pengumuman wajib diisi.',
            'judul.max'        => 'Judul pengumuman maksimal 200 karakter.',
            'isi.required'     => 'Konten isi pengumuman wajib diisi.',
            'tanggal.required' => 'Tanggal pengumuman wajib diisi.',
            'status.required'  => 'Status pengumuman wajib dipilih.',
            'status.in'        => 'Pengumuman baru hanya dapat disimpan sebagai Draft atau Published.',
        ]);

        Pengumuman::create([
            'judul'           => $validated['judul'],
            'isi'             => $validated['isi'],
            'tanggal'         => $validated['tanggal'],
            'status'          => $validated['status'],
            'pembuat_user_id' => $request->user()->id,
        ]);

        return redirect()->route('admin.cms.pengumuman.index')
            ->with('success', 'Pengumuman baru berhasil ditambahkan.');
    }

    /**
     * Form edit pengumuman.
     */
    public function edit(Pengumuman $pengumuman): View
    {
        return view('pages.admin.cms.pengumuman.form', [
            'pengumuman'    => $pengumuman,
            'isEdit'        => true,
            'statusOptions' => self::STATUS_EDIT,
        ]);
    }

    /**
     * Update pengumuman di database.
     */
    public function update(Request $request, Pengumuman $pengumuman): RedirectResponse
    {
        $validated = $request->validate([
            'judul'   => ['required', 'string', 'max:200'],
            'isi'     => ['required', 'string'],
            'tanggal' => ['required', 'date'],
            'status'  => ['required', 'in:' . implode(',', self::STATUS_EDIT)],
        ], [
            'judul.required'   => 'Judul pengumuman wajib diisi.',
            'isi.required'     => 'Konten isi pengumuman wajib diisi.',
            'tanggal.required' => 'Tanggal pengumuman wajib diisi.',
            'status.required'  => 'Status pengumuman wajib dipilih.',
            'status.in'        => 'Saat edit, status hanya dapat diubah menjadi Published atau Archived.',
        ]);

        $pengumuman->update([
            'judul'   => $validated['judul'],
            'isi'     => $validated['isi'],
            'tanggal' => $validated['tanggal'],
            'status'  => $validated['status'],
        ]);

        return redirect()->route('admin.cms.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Hapus pengumuman dari database.
     */
    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        $judul = $pengumuman->judul;
        $pengumuman->delete();

        return redirect()->route('admin.cms.pengumuman.index')
            ->with('success', "Pengumuman '{$judul}' berhasil dihapus.");
    }
}