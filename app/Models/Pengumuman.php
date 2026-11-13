<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'siakad_pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'tanggal',
        'status',
        'pembuat_user_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    /**
     * Relasi ke pembuat (User).
     */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembuat_user_id');
    }

    /**
     * Scope pengumuman berstatus published.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Slug otomatis untuk routing publik berbasis id dan judul.
     */
    public function getSlugAttribute(): string
    {
        return Str::slug($this->judul) . '-' . $this->id;
    }

    /**
     * Ringkasan teks pengumuman dari isi.
     */
    public function getSummaryAttribute(): string
    {
        return Str::limit(strip_tags($this->isi), 160);
    }

    /**
     * Format tanggal pengumuman ramah baca.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->tanggal ? $this->tanggal->translatedFormat('d F Y') : '-';
    }
}
