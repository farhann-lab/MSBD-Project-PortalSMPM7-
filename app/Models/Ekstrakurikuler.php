<?php

namespace App\Models;

use App\Models\Concerns\UsesAdminConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ekstrakurikuler extends Model
{
    use UsesAdminConnection;

    protected $table = 'm_ekstra';

    public $timestamps = false;

    protected $fillable = ['nama'];

    public function publik(): HasOne
    {
        return $this->hasOne(EkstrakurikulerPublik::class, 'id_ekstra');
    }
}
