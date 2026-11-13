<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublikGuru extends Model
{
    /**
     * Nama view database MySQL 8.0 (skema read-only).
     */
    protected $table = 'v_publik_guru';

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
        throw new \BadMethodCallException('PublikGuru adalah model read-only berbasis database view.');
    }

    public function delete()
    {
        throw new \BadMethodCallException('PublikGuru adalah model read-only berbasis database view.');
    }

    /**
     * Scope filter nama guru.
     */
    public function scopeSearch($query, ?string $q)
    {
        if (!empty($q)) {
            $query->where('nama', 'like', "%{$q}%");
        }
        return $query;
    }

    /**
     * Scope filter mata pelajaran.
     */
    public function scopeMapel($query, ?string $mapel)
    {
        if (!empty($mapel)) {
            $query->where('mata_pelajaran', 'like', "%{$mapel}%");
        }
        return $query;
    }
}
