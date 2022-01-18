<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Subcategory;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoryTest extends DuskTestCase
{
    use DatabaseMigrations;

    // Para que ejecute seeders antes de cada prueba
    // No es práctico, tarda mucho
/*    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }*/

    /** @test */
    public function it_shows_the_categories_when_clicking_the_categories_button()
    {
        $firstCategory = Category::factory()->create([
            'name' => 'Primera categoría'
        ]);

        $secondCategory = Category::factory()->create([
            'name' => 'Segunda categoría'
        ]);

        $this->browse(function (Browser $browser) use ($firstCategory, $secondCategory) {
            $browser->visit('/')
                ->click('@categories-button')
                ->assertSee($firstCategory->name)
                ->assertSee($secondCategory->name)
                ->screenshot('show-categories');
        });
    }

    /** @test */
    public function it_shows_the_subcategories_of_a_category_when_hovering_it()
    {
        $firstCategory = Category::factory()->create([
            'name' => 'Primera categoría'
        ]);

        $secondCategory = Category::factory()->create([
            'name' => 'Segunda categoría'
        ]);

        $firstSubcategory = Subcategory::factory()->create([
            'name' => 'Primera subcategoría',
            'category_id' => $firstCategory->id
        ]);

        $secondSubcategory = Subcategory::factory()->create([
            'name' => 'Segunda subcategoría',
            'category_id' => $secondCategory->id
        ]);

        $this->browse(function (Browser $browser) use ($firstCategory, $firstSubcategory, $secondSubcategory) {
            $browser->visit('/')
                ->click('@categories-button')
                ->mouseover('@category-' . $firstCategory->id)
                ->assertSee($firstSubcategory->name)
                ->assertDontSee($secondSubcategory->name)
                ->screenshot('show-subcategories');
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
