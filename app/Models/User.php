<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Support\RoleDatabase;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'siakad_users';

    public function getConnectionName()
    {
        return $this->connection ?? RoleDatabase::auth() ?? parent::getConnectionName();
    }

    protected $fillable = [
        'username',
        'password',
        'role',
        'id_guru',
        'id_siswa',
        'is_active',
        'wajib_ganti_password',
        'is_admin_utama',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'wajib_ganti_password' => 'boolean',
            'is_admin_utama' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAdminUtama(): bool
    {
        return $this->isAdmin() && (bool) $this->is_admin_utama;
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function guru(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id');
    }

    public function dashboardUrl(): string
    {
        return match ($this->role) {
            'admin' => '/admin',
            'guru'  => '/guru-panel',
            'siswa' => '/siswa-panel',
            default => '/',
        };
    }
}
