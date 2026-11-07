<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'siakad_fasilitas';

    protected $fillable = [
        'nama',
        'deskripsi',
        'gambar_path',
        'status',
    ];

    /**
     * Scope fasilitas published.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * URL Gambar publik.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->gambar_path) {
            return null;
        }

        if (Str::startsWith($this->gambar_path, ['http://', 'https://', '/'])) {
            return $this->gambar_path;
        }

        return asset('storage/' . $this->gambar_path);
    }
}
