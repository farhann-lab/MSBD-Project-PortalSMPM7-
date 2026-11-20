<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MateriKelas extends Model
{
    /**
     * Nama tabel MySQL siakad_materi_kelas.
     */
    protected $table = 'siakad_materi_kelas';

    /**
     * Tabel ini hanya memiliki created_at dan tidak memiliki updated_at.
     */
    const UPDATED_AT = null;

    /**
     * Primary key komposit.
     */
    public $incrementing = false;
    protected $primaryKey = ['materi_id', 'id_guru_mapel'];

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'materi_id',
        'id_guru_mapel',
    ];

    /**
     * Relasi ke Materi (siakad_materi).
     */
    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'id');
    }

    /**
     * Relasi ke Penugasan Guru Mapel (t_guru_mapel).
     */
    public function guruMapel(): BelongsTo
    {
        return $this->belongsTo(GuruMapel::class, 'id_guru_mapel', 'id');
    }
}
