<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class GaleriFoto extends Model
{
    use HasFactory;

    protected $table = 'siakad_galeri_foto';

    public $timestamps = false; // Tabel hanya memiliki created_at

    protected $fillable = [
        'galeri_id',
        'gambar_path',
        'keterangan',
        'urutan',
        'status',
        'created_at',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = now();
            }
        });
    }

    public function galeri(): BelongsTo
    {
        return $this->belongsTo(Galeri::class, 'galeri_id');
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
}
