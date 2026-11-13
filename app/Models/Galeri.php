<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'siakad_galeri';

    protected $fillable = [
        'judul',
        'deskripsi',
        'status',
    ];

    /**
     * Relasi ke seluruh item foto dalam galeri.
     */
    public function foto(): HasMany
    {
        return $this->hasMany(GaleriFoto::class, 'galeri_id')->orderBy('urutan');
    }

    /**
     * Foto utama / cover galeri (foto urutan pertama).
     */
    public function fotoUtama(): HasOne
    {
        return $this->hasOne(GaleriFoto::class, 'galeri_id')->oldestOfMany('urutan');
    }

    /**
     * Scope galeri published.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Ambil URL gambar cover.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->fotoUtama?->image_url;
    }
}
