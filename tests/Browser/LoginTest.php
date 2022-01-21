<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_shows_unlogged_user_options_when_an_unlogged_user_clicks_the_account_icon()
    {
        $this->createCategory();

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertGuest()
                ->click('@user-btn')
                ->waitForText('Iniciar sesión')
                ->waitForText('Registrarse')
                ->assertDontSee('Administrar cuenta')
                ->assertDontSee('Perfil')
                ->assertDontSee('Finalizar sesión')
                ->screenshot('unlogged-user');
        });
    }

    /** @test */
    public function it_shows_authenticated_user_options_when_a_logged_user_clicks_the_account_icon()
    {
        $this->createCategory();

        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/')
                ->click('@user-btn')
                ->waitForText('Administrar cuenta')
                ->waitForText('Perfil')
                ->waitForText('Finalizar sesión')
                ->assertDontSee('Iniciar sesión')
                ->assertDontSee('Registrarse')
                ->screenshot('logged-user');
        });
    }
}
