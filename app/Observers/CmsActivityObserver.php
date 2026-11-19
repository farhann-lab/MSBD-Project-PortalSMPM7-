<?php

namespace App\Observers;

use App\Models\Berita;
use App\Models\Fasilitas;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use App\Models\LogAktivitas;
use App\Models\Pengumuman;
use App\Models\PrestasiPublik;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CmsActivityObserver
{
    /**
     * Handle event "created" pada model CMS.
     */
    public function created(Model $model): void
    {
        $userId = Auth::id() ?? 1; // Default ke ID 1 jika dieksekusi tanpa sesi auth aktif
        $entityType = $this->resolveEntityType($model);
        $title = $this->resolveEntityTitle($model);

        LogAktivitas::catat(
            $userId,
            'tambah',
            $entityType,
            $model->getKey(),
            "Menambahkan konten {$entityType}: '{$title}'"
        );
    }

    /**
     * Handle event "updated" pada model CMS.
     */
    public function updated(Model $model): void
    {
        $userId = Auth::id() ?? 1;
        $entityType = $this->resolveEntityType($model);
        $title = $this->resolveEntityTitle($model);

        $aksi = 'ubah';
        $keterangan = "Memperbarui konten {$entityType}: '{$title}'";

        // Deteksi perubahan status publikasi khusus
        if ($model->isDirty('status')) {
            $statusLama = $model->getOriginal('status');
            $statusBaru = $model->status;
            if ($statusBaru === 'published') {
                $aksi = 'terbit';
                $keterangan = "Menerbitkan konten {$entityType}: '{$title}'";
            } elseif ($statusBaru === 'draft') {
                $aksi = 'draft';
                $keterangan = "Mengubah status konten {$entityType}: '{$title}' menjadi draft";
            } else {
                $keterangan .= " (status: {$statusLama} -> {$statusBaru})";
            }
        }

        LogAktivitas::catat(
            $userId,
            $aksi,
            $entityType,
            $model->getKey(),
            $keterangan
        );
    }

    /**
     * Handle event "deleted" pada model CMS.
     */
    public function deleted(Model $model): void
    {
        $userId = Auth::id() ?? 1;
        $entityType = $this->resolveEntityType($model);
        $title = $this->resolveEntityTitle($model);

        LogAktivitas::catat(
            $userId,
            'hapus',
            $entityType,
            $model->getKey(),
            "Menghapus konten {$entityType}: '{$title}'"
        );
    }

    /**
     * Menentukan nama entitas yang bersih untuk pencatatan log.
     */
    protected function resolveEntityType(Model $model): string
    {
        return match (get_class($model)) {
            Berita::class => 'berita',
            Pengumuman::class => 'pengumuman',
            Fasilitas::class => 'fasilitas',
            Galeri::class => 'galeri',
            GaleriFoto::class => 'galeri_foto',
            PrestasiPublik::class => 'prestasi',
            default => strtolower(class_basename($model)),
        };
    }

    /**
     * Menentukan label/judul dari objek model.
     */
    protected function resolveEntityTitle(Model $model): string
    {
        return $model->judul 
            ?? $model->nama 
            ?? $model->keterangan 
            ?? ('ID #' . $model->getKey());
    }
}
