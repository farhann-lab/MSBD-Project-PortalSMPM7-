<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mapel extends Model
{
    /**
     * Nama tabel MySQL bersama bawaan sistem RKM.
     */
    protected $table = 'm_mapel';

    /**
     * Tabel m_mapel pada RKM tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'kelompok',
        'tambahan_sub',
        'kd_singkat',
        'nama',
        'is_sikap',
    ];

    /**
     * Relasi ke penugasan guru di t_guru_mapel.
     */
    public function penugasan(): HasMany
    {
        return $this->hasMany(GuruMapel::class, 'id_mapel', 'id');
    }

    /**
     * Scope filter kelompok mata pelajaran ('A', 'B', 'C').
     */
    public function scopeKelompok($query, ?string $kelompok)
    {
        if (!empty($kelompok) && in_array($kelompok, ['A', 'B', 'C'])) {
            $query->where('kelompok', $kelompok);
        }
        return $query;
    }

    /**
     * Scope pencarian nama mata pelajaran atau kode singkat.
     */
    public function scopeSearch($query, ?string $q)
    {
        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('kd_singkat', 'like', "%{$q}%");
            });
        }
        return $query;
    }
}
