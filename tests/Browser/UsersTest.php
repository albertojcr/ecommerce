<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersTest extends DuskTestCase
{

    /** @test */
    public function it_shows_the_categories_when_clicking_the_categories_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('@categories-button')
                ->assertSee('Celulares y tablets')
                ->assertSee('TV, audio y video')
                ->assertSee('Consola y videojuegos')
                ->assertSee('Computación')
                ->assertSee('Moda');
        });
    }
}
