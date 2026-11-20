<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pertemuan extends Model
{
    /**
     * Nama tabel MySQL siakad_pertemuan.
     */
    protected $table = 'siakad_pertemuan';

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'jadwal_id',
        'tanggal',
        'sesi_ke',
        'catatan',
        'dibuat_oleh',
    ];

    /**
     * Relasi ke Jadwal (siakad_jadwal).
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'id');
    }

    /**
     * Relasi ke pembuat (siakad_users).
     */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id');
    }

    /**
     * Relasi ke presensi siswa (siakad_absensi_siswa).
     */
    public function absensiSiswa(): HasMany
    {
        return $this->hasMany(AbsensiSiswa::class, 'pertemuan_id', 'id');
    }
}
