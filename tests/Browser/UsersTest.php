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
                ->screenshot('celulares-subcategories')
/*                ->assertDontSee('TV y audio')
                ->assertDontSee('Audios')
                ->assertDontSee('Audio para autos')
                ->assertDontSee('Xbox')
                ->assertDontSee('Play Station')
                ->assertDontSee('Videojuegos para PC')
                ->assertDontSee('Nintendo')
                ->assertDontSee('Portátiles')
                ->assertDontSee('PC escritorio')
                ->assertDontSee('Almacenamiento')
                ->assertDontSee('Accesorios computadoras')
                ->assertDontSee('Mujeres')
                ->assertDontSee('Hombres')
                ->assertDontSee('Lentes')
                ->assertDontSee('Relojes')*/;
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
                ->screenshot('tv-subcategories')
/*                ->assertDontSee('Celulares y smartphones')
                ->assertDontSee('Accesorios para celulares')
                ->assertDontSee('Smartwatches')
                ->assertDontSee('Xbox')
                ->assertDontSee('Play Station')
                ->assertDontSee('Videojuegos para PC')
                ->assertDontSee('Nintendo')
                ->assertDontSee('Portátiles')
                ->assertDontSee('PC escritorio')
                ->assertDontSee('Almacenamiento')
                ->assertDontSee('Accesorios computadoras')
                ->assertDontSee('Mujeres')
                ->assertDontSee('Hombres')
                ->assertDontSee('Lentes')
                ->assertDontSee('Relojes')*/;
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
                ->screenshot('consola-subcategories')
/*                ->assertDontSee('TV y audio')
                ->assertDontSee('Audios')
                ->assertDontSee('Audio para autos')
                ->assertDontSee('Celulares y smartphones')
                ->assertDontSee('Accesorios para celulares')
                ->assertDontSee('Smartwatches')
                ->assertDontSee('Portátiles')
                ->assertDontSee('PC escritorio')
                ->assertDontSee('Almacenamiento')
                ->assertDontSee('Accesorios computadoras')
                ->assertDontSee('Mujeres')
                ->assertDontSee('Hombres')
                ->assertDontSee('Lentes')
                ->assertDontSee('Relojes')*/;
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
                ->screenshot('computacion-subcategories')
/*                ->assertDontSee('Xbox')
                ->assertDontSee('Play Station')
                ->assertDontSee('Videojuegos para PC')
                ->assertDontSee('Nintendo')
                ->assertDontSee('TV y audio')
                ->assertDontSee('Audios')
                ->assertDontSee('Audio para autos')
                ->assertDontSee('Celulares y smartphones')
                ->assertDontSee('Accesorios para celulares')
                ->assertDontSee('Smartwatches')
                ->assertDontSee('Mujeres')
                ->assertDontSee('Hombres')
                ->assertDontSee('Lentes')
                ->assertDontSee('Relojes')*/;
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
                ->screenshot('moda-subcategories')
/*                ->assertDontSee('Portátiles')
                ->assertDontSee('PC escritorio')
                ->assertDontSee('Almacenamiento')
                ->assertDontSee('Accesorios computadoras')
                ->assertDontSee('Xbox')
                ->assertDontSee('Play Station')
                ->assertDontSee('Videojuegos para PC')
                ->assertDontSee('Nintendo')
                ->assertDontSee('TV y audio')
                ->assertDontSee('Audios')
                ->assertDontSee('Audio para autos')
                ->assertDontSee('Celulares y smartphones')
                ->assertDontSee('Accesorios para celulares')
                ->assertDontSee('Smartwatches')*/;
        });
    }
}
