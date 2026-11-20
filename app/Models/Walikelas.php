<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Walikelas extends Model
{
    protected $table = 't_walikelas';
    public $timestamps = false;

    protected $fillable = [
        'id_guru',
        'id_kelas',
        'tasm',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id');
    }

    public function scopeTasm($query, $tasm)
    {
        if (!empty($tasm)) {
            $query->where('tasm', $tasm);
        }
        return $query;
    }
}
