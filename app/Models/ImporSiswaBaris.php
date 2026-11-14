<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImporSiswaBaris extends Model
{
    protected $table = 'siakad_impor_siswa_baris';

    public $timestamps = false;

    protected $fillable = [
        'impor_id',
        'nomor_baris',
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
        'status_validasi',
        'pesan_error',
    ];

    public function impor(): BelongsTo
    {
        return $this->belongsTo(ImporSiswa::class, 'impor_id', 'id');
    }
}
