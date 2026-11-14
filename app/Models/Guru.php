<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guru extends Model
{
    /**
     * Nama tabel MySQL bawaan sistem RKM.
     */
    protected $table = 'm_guru';

    /**
     * Tabel m_guru pada RKM tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'nama',
        'nip',
        'jk',
        'is_bk',
        'stat_data',
    ];

    /**
     * Relasi ke akun login pengguna di siakad_users (role = 'guru').
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id_guru', 'id');
    }

    /**
     * Scope untuk memfilter guru yang aktif mengajar ('A').
     */
    public function scopeAktif($query)
    {
        return $query->where('stat_data', 'A');
    }

    /**
     * Scope pencarian nama atau NIP guru.
     */
    public function scopeSearch($query, ?string $q)
    {
        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('nip', 'like', "%{$q}%");
            });
        }

        return $query;
    }

    /**
     * Scope filter status keaktifan data.
     */
    public function scopeStatus($query, ?string $status)
    {
        if (!empty($status) && in_array($status, ['A', 'P', 'M'])) {
            $query->where('stat_data', $status);
        }

        return $query;
    }

    /**
     * Status boolean keaktifan guru.
     */
    public function getIsAktifAttribute(): bool
    {
        return $this->stat_data === 'A';
    }

    /**
     * Label ramah manusia untuk status keaktifan.
     */
    public function getLabelStatusAttribute(): string
    {
        return match ($this->stat_data) {
            'A' => 'Aktif',
            'P' => 'Pensiun / Cuti',
            'M' => 'Mutasi / Keluar',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Kelas warna badge status untuk styling UI Tailwind Forest Green.
     */
    public function getBadgeClassAttribute(): string
    {
        return match ($this->stat_data) {
            'A' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'P' => 'bg-amber-100 text-amber-800 border-amber-200',
            'M' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    /**
     * Label jenis kelamin.
     */
    public function getLabelJkAttribute(): string
    {
        return match ($this->jk) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => '-',
        };
    }

    /**
     * Label peranan bimbingan konseling.
     */
    public function getLabelBkAttribute(): string
    {
        return $this->is_bk === '1' ? 'Guru BK' : 'Guru Mata Pelajaran';
    }
}
