<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'siakad_berita';

    protected $fillable = [
        'judul',
        'slug',
        'isi',
        'gambar_path',
        'status',
        'published_at',
        'penulis_user_id',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke penulis (User).
     */
    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penulis_user_id');
    }

    /**
     * Scope berita yang sudah dipublikasikan.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Ringkasan teks berita dari kolom isi.
     */
    public function getSummaryAttribute(): string
    {
        return Str::limit(strip_tags($this->isi), 160);
    }

    /**
     * URL gambar publik atau fallback.
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

    /**
     * Format tanggal publikasi ramah baca.
     */
    public function getFormattedDateAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;
        return $date ? $date->translatedFormat('d F Y') : '-';
    }
}
