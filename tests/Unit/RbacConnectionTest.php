<?php

namespace Tests\Unit;

use App\Models\Guru;
use App\Models\PublikGuru;
use App\Models\User;
use App\Support\RoleDatabase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RbacConnectionTest extends TestCase
{
    public function test_connection_selection_matches_role_when_enabled(): void
    {
        config(['siakad_rbac.enabled' => true]);
        foreach (['admin' => 'mysql_admin', 'guru' => 'mysql_guru', 'siswa' => 'mysql_siswa'] as $role => $expected) {
            $user = new User();
            $user->setRawAttributes(['role' => $role, 'is_active' => true], true);
            Auth::guard('web')->setUser($user);
            $this->assertSame($expected, RoleDatabase::forAuthenticatedRole());
        }
        $this->assertSame('mysql_auth', (new User())->getConnectionName());
        $this->assertSame('mysql_admin', (new Guru())->getConnectionName());
        $this->assertSame('mysql_public', (new PublikGuru())->getConnectionName());
    }

    public function test_unrecognized_role_rejected(): void
    {
        config(['siakad_rbac.enabled' => true]);
        $user = new User();
        $user->setRawAttributes(['role' => 'unknown', 'is_active' => true], true);
        Auth::guard('web')->setUser($user);
        $this->expectException(AuthorizationException::class);
        RoleDatabase::forAuthenticatedRole();
    }

    public function test_connections_can_be_overridden_explicitly_for_admin_writes(): void
    {
        config(['siakad_rbac.enabled' => true]);
        $user = new User();
        $user->setConnection('mysql_admin');
        $this->assertSame('mysql_admin', $user->getConnectionName());
    }
}
