<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tahun extends Model
{
    /**
     * Nama tabel MySQL bersama bawaan sistem RKM.
     */
    protected $table = 'tahun';

    /**
     * Tabel tahun pada RKM tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'tahun',
        'aktif',
        'nama_kepsek',
        'nip_kepsek',
        'tgl_raport',
        'tgl_raport_kelas3',
    ];

    /**
     * Cast attributes.
     */
    protected function casts(): array
    {
        return [
            'tgl_raport' => 'date:Y-m-d',
            'tgl_raport_kelas3' => 'date:Y-m-d',
        ];
    }

    /**
     * Scope untuk memfilter periode aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', 'Y');
    }

    /**
     * Cek apakah baris ini merupakan periode aktif.
     */
    public function isAktif(): bool
    {
        return $this->aktif === 'Y';
    }

    /**
     * Accessor label semester (Ganjil / Genap).
     */
    public function getSemesterAttribute(): string
    {
        return substr($this->tahun, -1) === '1' ? 'Ganjil' : 'Genap';
    }

    /**
     * Accessor 4 digit tahun awal ajaran.
     */
    public function getTahunAjaranMulaiAttribute(): string
    {
        return substr($this->tahun, 0, 4);
    }

    /**
     * Accessor label lengkap Tahun Ajaran (misal: "T.A. 2024/2025 Semester Ganjil").
     */
    public function getNamaLengkapAttribute(): string
    {
        $thnAwal = (int) substr($this->tahun, 0, 4);
        $thnAkhir = $thnAwal + 1;
        $smt = $this->semester;

        return "T.A. {$thnAwal}/{$thnAkhir} Semester {$smt}";
    }

    /**
     * Helper static untuk mengambil instance periode aktif.
     */
    public static function getAktif(): ?self
    {
        return self::where('aktif', 'Y')->first();
    }

    /**
     * Helper static untuk mengambil kode 5 digit TASM aktif (e.g. '20241').
     */
    public static function getTasmAktif(): string
    {
        $aktif = self::getAktif();
        return $aktif ? $aktif->tahun : date('Y') . '1';
    }

    /**
     * Helper static untuk mengambil kode 4 digit TA aktif (e.g. '2024').
     */
    public static function getTaAktif(): string
    {
        $tasm = self::getTasmAktif();
        return substr($tasm, 0, 4);
    }
}
