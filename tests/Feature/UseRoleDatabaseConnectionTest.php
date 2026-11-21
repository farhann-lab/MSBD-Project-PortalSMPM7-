<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Middleware\UseRoleDatabaseConnection;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class UseRoleDatabaseConnectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware([UseRoleDatabaseConnection::class])
            ->get('/__test/siakad-role-connection', static function () {
                return response()->json(['connection' => DB::getDefaultConnection()]);
            });
    }

    public function test_it_uses_the_role_connection_during_request_and_restores_previous_default(): void
    {
        config()->set('siakad_rbac.enabled', true);
        $initial = DB::getDefaultConnection();
        DB::setDefaultConnection('mysql_public');

        try {
            foreach (['admin' => 'mysql_admin', 'guru' => 'mysql_guru', 'siswa' => 'mysql_siswa'] as $role => $expected) {
                $user = (new User())->forceFill(['role' => $role, 'is_active' => true]);
                $this->actingAs($user)
                    ->get('/__test/siakad-role-connection')
                    ->assertOk()
                    ->assertJsonPath('connection', $expected);

                $this->assertSame('mysql_public', DB::getDefaultConnection());
            }
        } finally {
            DB::setDefaultConnection($initial);
        }
    }

    public function test_disabled_mode_does_not_switch_database(): void
    {
        config()->set('siakad_rbac.enabled', false);
        $initial = DB::getDefaultConnection();
        DB::setDefaultConnection('mysql_public');

        try {
            $this->get('/__test/siakad-role-connection')
                ->assertOk()
                ->assertJsonPath('connection', 'mysql_public');
        } finally {
            DB::setDefaultConnection($initial);
        }
    }

    public function test_inactive_user_is_rejected(): void
    {
        config()->set('siakad_rbac.enabled', true);
        $user = (new User())->forceFill(['role' => 'guru', 'is_active' => false]);

        $this->actingAs($user)
            ->get('/__test/siakad-role-connection')
            ->assertForbidden();
    }
}
