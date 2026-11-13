<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublikSiswa extends Model
{
    /**
     * Nama view database MySQL 8.0 (skema read-only).
     */
    protected $table = 'v_publik_siswa';

    /**
     * View tidak memiliki timestamps dan bersifat read-only.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat dibaca.
     */
    protected $fillable = [];

    /**
     * Lindungi dari operasi insert/update/delete langsung.
     */
    public function save(array $options = [])
    {
        throw new \BadMethodCallException('PublikSiswa adalah model read-only berbasis database view.');
    }

    public function delete()
    {
        throw new \BadMethodCallException('PublikSiswa adalah model read-only berbasis database view.');
    }

    /**
     * Scope filter pencarian nama siswa.
     */
    public function scopeSearch($query, ?string $q)
    {
        if (!empty($q)) {
            $query->where('nama', 'like', "%{$q}%");
        }
        return $query;
    }

    /**
     * Scope filter kelas (mendukung pencarian eksak atau awalan seperti '7A', 'VII-1').
     */
    public function scopeKelas($query, ?string $kelas)
    {
        if (!empty($kelas)) {
            $query->where(function ($q) use ($kelas) {
                $q->where('nama_kelas', $kelas)
                  ->orWhere('nama_kelas', 'like', "{$kelas}%");
            });
        }
        return $query;
    }
}
