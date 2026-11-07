<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PrestasiPublik extends Model
{
    use HasFactory;

    protected $table = 'siakad_prestasi_publik';

    protected $fillable = [
        'id_siswa',
        'id_prestasi_rkm',
        'judul',
        'tingkat',
        'tanggal',
        'deskripsi',
        'gambar_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_siswa', 'id_siswa');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

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

    public function getFormattedDateAttribute(): string
    {
        return $this->tanggal ? $this->tanggal->translatedFormat('d F Y') : '-';
    }

    public function getTahunAttribute(): string
    {
        return $this->tanggal ? $this->tanggal->format('Y') : date('Y');
    }
}
