<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAktivitas extends Model
{
    /**
     * Nama tabel MySQL siakad_log_aktivitas.
     */
    protected $table = 'siakad_log_aktivitas';

    /**
     * Tabel ini hanya memiliki created_at bawaan dan tidak menggunakan updated_at.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'user_id',
        'aksi',
        'entity_type',
        'entity_id',
        'ringkasan',
        'created_at',
    ];

    /**
     * Casting tipe data kolom.
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke User / Aktor (siakad_users).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Helper untuk mencatat log aktivitas baru secara cepat dan aman.
     */
    public static function catat(?int $userId, string $aksi, string $entityType, ?int $entityId, ?string $ringkasan): ?self
    {
        try {
            return self::create([
                'user_id'     => $userId,
                'aksi'        => $aksi,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'ringkasan'   => $ringkasan,
                'created_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            // Jangan biarkan kegagalan log menghentikan transaksi utama bisnis
            report($e);
            return null;
        }
    }
}
