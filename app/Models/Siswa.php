<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model
{
    /**
     * Nama tabel MySQL bersama bawaan sistem RKM.
     */
    protected $table = 'm_siswa';

    /**
     * Primary key pada m_siswa.
     */
    protected $primaryKey = 'id';

    /**
     * Tabel m_siswa pada RKM tidak memakai sistem timestamp otomatis Laravel.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi secara mass-assignment (~30 kolom).
     */
    protected $fillable = [
        'nis',
        'nisn',
        'nama',
        'jk',
        'tmp_lahir',
        'tgl_lahir',
        'agama',
        'status',
        'anakke',
        'alamat',
        'notelp',
        'sek_asal',
        'sek_asal_alamat',
        'diterima_kelas',
        'diterima_tgl',
        'diterima_smt',
        'ijazah_no',
        'ijazah_thn',
        'skhun_no',
        'skhun_thn',
        'ortu_ayah',
        'ortu_ibu',
        'ortu_alamat',
        'ortu_notelp',
        'ortu_ayah_pkj',
        'ortu_ibu_pkj',
        'wali',
        'wali_alamat',
        'notelp_rumah',
        'wali_pkj',
        'inputID',
        'tgl_input',
        'tgl_update',
        'stat_data',
        'foto',
    ];

    /**
     * Relasi ke akun login pengguna di siakad_users (role = 'siswa').
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id_siswa', 'id');
    }

    /**
     * Scope pencarian nama, NIS, atau NISN.
     */
    public function scopeSearch($query, ?string $q)
    {
        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('nis', 'like', "%{$q}%")
                    ->orWhere('nisn', 'like', "%{$q}%");
            });
        }

        return $query;
    }

    /**
     * Scope filter status data (A, K, M, L).
     */
    public function scopeStatus($query, ?string $status)
    {
        if (!empty($status) && in_array($status, ['A', 'K', 'M', 'L'])) {
            $query->where('stat_data', $status);
        }

        return $query;
    }

    /**
     * Helper untuk format tanggal yang aman (menghindari '0000-00-00').
     */
    public function getFormattedDate(?string $field): ?string
    {
        $val = $this->{$field} ?? null;
        if (empty($val) || $val === '0000-00-00' || str_starts_with($val, '0000')) {
            return null;
        }

        return $val;
    }
}
