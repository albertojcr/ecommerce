<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoryTest extends DuskTestCase
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

    /** @test */
    public function it_shows_the_celulares_subcategories_when_hovering_its_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('@categories-button')
                ->mouseover('@category-1')
                ->assertSee('Celulares y smartphones')
                ->assertSee('Accesorios para celulares')
                ->assertSee('Smartwatches')
                ->screenshot('celulares-subcategories');
        });
    }

    /** @test */
    public function it_shows_the_tv_subcategories_when_hovering_its_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('@categories-button')
                ->mouseover('@category-2')
                ->assertSee('TV y audio')
                ->assertSee('Audios')
                ->assertSee('Audio para autos')
                ->screenshot('tv-subcategories');
        });
    }

    /** @test */
    public function it_shows_the_consola_subcategories_when_hovering_its_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('@categories-button')
                ->mouseover('@category-3')
                ->assertSee('Xbox')
                ->assertSee('Play Station')
                ->assertSee('Videojuegos para PC')
                ->assertSee('Nintendo')
                ->screenshot('consola-subcategories');
        });
    }

    /** @test */
    public function it_shows_the_computacion_subcategories_when_hovering_its_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('@categories-button')
                ->mouseover('@category-4')
                ->assertSee('Portátiles')
                ->assertSee('PC escritorio')
                ->assertSee('Almacenamiento')
                ->assertSee('Accesorios computadoras')
                ->screenshot('computacion-subcategories');
        });
    }

    /** @test */
    public function it_shows_the_moda_subcategories_when_hovering_its_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('@categories-button')
                ->mouseover('@category-5')
                ->assertSee('Mujeres')
                ->assertSee('Hombres')
                ->assertSee('Lentes')
                ->assertSee('Relojes')
                ->screenshot('moda-subcategories');
        });
    }
}
