<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kelas extends Model
{
    protected $table = 'm_kelas';
    public $timestamps = false;

    protected $fillable = [
        'tingkat',
        'nama',
    ];

    public function kelasSiswa(): HasMany
    {
        return $this->hasMany(KelasSiswa::class, 'id_kelas', 'id');
    }

    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 't_kelas_siswa', 'id_kelas', 'id_siswa')
            ->withPivot('id', 'ta');
    }

    public function waliKelas(): HasMany
    {
        return $this->hasMany(Walikelas::class, 'id_kelas', 'id');
    }

    public function guruMapel(): HasMany
    {
        return $this->hasMany(GuruMapel::class, 'id_kelas', 'id');
    }

    public function scopeTingkat($query, $tingkat)
    {
        if (!empty($tingkat)) {
            $query->where('tingkat', $tingkat);
        }
        return $query;
    }
}
