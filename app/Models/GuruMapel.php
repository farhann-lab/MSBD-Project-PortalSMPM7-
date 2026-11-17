<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class GuruMapel extends Model
{
    /**
     * Nama tabel MySQL bersama bawaan sistem RKM.
     */
    protected $table = 't_guru_mapel';

    /**
     * Tabel t_guru_mapel pada RKM tidak memakai sistem timestamp otomatis Laravel.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'tasm',
        'id_guru',
        'id_kelas',
        'id_mapel',
    ];

    /**
     * Relasi ke Guru (m_guru).
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id');
    }

    /**
     * Relasi ke Kelas (m_kelas).
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id');
    }

    /**
     * Relasi ke Mata Pelajaran (m_mapel).
     */
    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id');
    }

    /**
     * Scope filter periode semester (tasm).
     */
    public function scopeTasm($query, ?string $tasm)
    {
        if (!empty($tasm)) {
            $query->where('tasm', $tasm);
        }
        return $query;
    }

    /**
     * Scope filter kelas.
     */
    public function scopeKelas($query, $idKelas)
    {
        if (!empty($idKelas)) {
            $query->where('id_kelas', $idKelas);
        }
        return $query;
    }

    /**
     * Scope filter guru.
     */
    public function scopeGuru($query, $idGuru)
    {
        if (!empty($idGuru)) {
            $query->where('id_guru', $idGuru);
        }
        return $query;
    }

    /**
     * Pengecekan dependensi rekaman nilai di RKM (PGS-7).
     * Memeriksa tabel t_nilai, t_nilai_ket, t_nilai_sikap_so, t_nilai_sikap_sp.
     */
    public function hasNilaiDependencies(): bool
    {
        $id = $this->id;

        $hasNilai = DB::table('t_nilai')->where('id_guru_mapel', $id)->exists();
        if ($hasNilai) return true;

        $hasNilaiKet = DB::table('t_nilai_ket')->where('id_guru_mapel', $id)->exists();
        if ($hasNilaiKet) return true;

        $hasNilaiSo = DB::table('t_nilai_sikap_so')->where('id_guru_mapel', $id)->exists();
        if ($hasNilaiSo) return true;

        $hasNilaiSp = DB::table('t_nilai_sikap_sp')->where('id_guru_mapel', $id)->exists();
        if ($hasNilaiSp) return true;

        return false;
    }
}
