<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    private function actor(string $role, bool $active = true): User
    {
        $user = new User();
        $user->setRawAttributes([
            'id' => 999001,
            'username' => 'penguji_role',
            'role' => $role,
            'is_active' => $active,
            'wajib_ganti_password' => false,
            'is_admin_utama' => false,
        ], true);
        $user->exists = true;

        return $user;
    }

    public function test_guru_cannot_visit_admin_route(): void
    {
        $this->actingAs($this->actor('guru'))->get('/admin')->assertForbidden();
    }

    public function test_siswa_cannot_visit_admin_master_route(): void
    {
        $this->actingAs($this->actor('siswa'))->get('/admin/master/guru')->assertForbidden();
    }

    public function test_siswa_cannot_visit_guru_route(): void
    {
        $this->actingAs($this->actor('siswa'))->get('/guru/jadwal')->assertForbidden();
    }

    public function test_admin_cannot_visit_siswa_dashboard(): void
    {
        $this->actingAs($this->actor('admin'))->get('/siswa-panel')->assertForbidden();
    }

    public function test_inactive_account_is_rejected_even_when_session_exists(): void
    {
        $this->actingAs($this->actor('guru', false))->get('/guru-panel')->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/guru-panel')->assertRedirect('/login');
    }
}
