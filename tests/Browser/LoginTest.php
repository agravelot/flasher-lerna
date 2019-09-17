<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     */
    public function test_admin_can_login_and_redirected_to_home(): void
    {
        $password = '123456789';
        /** @var User $admin */
        $admin = factory(User::class)->state('admin')->create([
            'password' => $password,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $password) {
            $browser->visit('/login')
                ->type('email', $admin->email)
                ->type('password', $password)
                ->press('Se connecter')
                ->assertPathIs('/')
                ->assertAuthenticatedAs($admin);
        });
    }

    public function test_admin_redirected_to_login_from_admin_and_can_login_and_redirected_to_admin(): void
    {
        $password = '123456789';
        /** @var User $admin */
        $admin = factory(User::class)->state('admin')->create([
            'password' => $password,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $password) {
            $browser->visit('/admin')
                ->assertPathIs('/login')
                ->type('email', $admin->email)
                ->type('password', $password)
                ->press('Se connecter')
                ->assertPathIs('/admin')
                ->assertAuthenticatedAs($admin);
        });
    }
}
