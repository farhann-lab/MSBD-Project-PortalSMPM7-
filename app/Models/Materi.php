<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materi extends Model
{
    /**
     * Nama tabel MySQL siakad_materi.
     */
    protected $table = 'siakad_materi';

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'id_guru',
        'id_mapel',
        'judul',
        'deskripsi',
        'jenis',
        'file_path',
        'tautan_url',
        'ukuran_byte',
        'status',
    ];

    /**
     * Casting tipe data kolom.
     */
    protected $casts = [
        'ukuran_byte' => 'integer',
    ];

    /**
     * Relasi ke Guru pemilik materi (m_guru).
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id');
    }

    /**
     * Relasi ke Mata Pelajaran (m_mapel).
     */
    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id');
    }

    /**
     * Relasi ke pivot siakad_materi_kelas.
     */
    public function materiKelas(): HasMany
    {
        return $this->hasMany(MateriKelas::class, 'materi_id', 'id');
    }

    /**
     * Scope untuk materi yang berstatus aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Helper untuk format ukuran berkas ramah baca.
     */
    public function getUkuranFormattedAttribute(): string
    {
        if (!$this->ukuran_byte) {
            return '-';
        }

        $bytes = $this->ukuran_byte;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' B';
    }
}
