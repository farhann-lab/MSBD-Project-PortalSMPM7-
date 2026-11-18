<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    /**
     * Nama tabel MySQL siakad_jadwal.
     */
    protected $table = 'siakad_jadwal';

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'id_guru_mapel',
        'tasm',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'aktif',
    ];

    /**
     * Casting tipe data kolom.
     */
    protected $casts = [
        'aktif' => 'boolean',
    ];

    /**
     * Relasi ke penugasan guru mapel (t_guru_mapel).
     */
    public function guruMapel(): BelongsTo
    {
        return $this->belongsTo(GuruMapel::class, 'id_guru_mapel', 'id');
    }

    /**
     * Relasi ke Periode Tahun Ajaran Semester (tasm).
     */
    public function tahun(): BelongsTo
    {
        return $this->belongsTo(Tahun::class, 'tasm', 'tahun');
    }

    /**
     * Scope untuk jadwal aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', 1);
    }

    /**
     * Scope untuk filter periode tasm.
     */
    public function scopeTasm($query, $tasm)
    {
        return $query->where('tasm', $tasm);
    }
}

