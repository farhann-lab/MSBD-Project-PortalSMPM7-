<?php

namespace App\Models;

use App\Models\Concerns\UsesAdminConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EkstrakurikulerPublik extends Model
{
    use UsesAdminConnection;

    public const IMAGE_DIRECTORY = 'ekstrakurikuler-publik';

    public const CREATED_AT = null;

    protected $table = 'siakad_ekstra_publik';

    protected $primaryKey = 'id_ekstra';

    public $incrementing = false;

    protected $fillable = ['deskripsi', 'gambar_path', 'status'];

    public function hasManagedImage(): bool
    {
        return Str::startsWith($this->gambar_path ?? '', self::IMAGE_DIRECTORY.'/');
    }

    public function imageUrl(string $managedUrl): ?string
    {
        if (! $this->gambar_path) {
            return null;
        }

        if ($this->hasManagedImage()) {
            return $managedUrl;
        }

        if (Str::startsWith($this->gambar_path, ['http://', 'https://', '/'])) {
            return $this->gambar_path;
        }

        return asset('storage/'.$this->gambar_path);
    }
}
