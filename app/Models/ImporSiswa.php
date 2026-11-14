<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImporSiswa extends Model
{
    protected $table = 'siakad_impor_siswa';

    public $timestamps = false;

    protected $fillable = [
        'admin_user_id',
        'nama_file',
        'jumlah_baris',
        'jumlah_berhasil',
        'jumlah_gagal',
        'status',
        'created_at',
    ];

    public function baris(): HasMany
    {
        return $this->hasMany(ImporSiswaBaris::class, 'impor_id', 'id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id', 'id');
    }
}
