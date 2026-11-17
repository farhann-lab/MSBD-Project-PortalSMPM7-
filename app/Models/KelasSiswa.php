<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelasSiswa extends Model
{
    protected $table = 't_kelas_siswa';
    public $timestamps = false;

    protected $fillable = [
        'id_kelas',
        'id_siswa',
        'ta',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id');
    }

    public function scopeTahunAjaran($query, $ta)
    {
        if (!empty($ta)) {
            $query->where('ta', $ta);
        }
        return $query;
    }
}
