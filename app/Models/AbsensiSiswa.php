<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiSiswa extends Model
{
    /**
     * Nama tabel MySQL siakad_absensi_siswa.
     */
    protected $table = 'siakad_absensi_siswa';

    /**
     * Tabel ini tidak memiliki kolom created_at bawaan Laravel (hanya updated_at).
     */
    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'pertemuan_id',
        'id_siswa',
        'status',
        'keterangan',
        'diubah_oleh',
    ];

    /**
     * Relasi ke sesi pertemuan (siakad_pertemuan).
     */
    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id', 'id');
    }

    /**
     * Relasi ke data siswa (m_siswa).
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id');
    }

    /**
     * Relasi ke akun pengubah (siakad_users).
     */
    public function pengubah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh', 'id');
    }
}
