<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tahun extends Model
{
    protected $table = 'tahun';
    public $timestamps = false;

    protected $fillable = [
        'tahun',
        'aktif',
        'nama_kepsek',
        'nip_kepsek',
        'tgl_raport',
        'tgl_raport_kelas3',
    ];

    public static function getAktif(): ?self
    {
        return self::where('aktif', 'Y')->first();
    }

    public static function getTasmAktif(): string
    {
        $aktif = self::getAktif();
        return $aktif ? $aktif->tahun : date('Y') . '1';
    }

    public static function getTaAktif(): string
    {
        $tasm = self::getTasmAktif();
        return substr($tasm, 0, 4);
    }
}
