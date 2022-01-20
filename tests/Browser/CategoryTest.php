<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoryTest extends DuskTestCase
{
    use DatabaseMigrations;

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
    public function it_shows_unlogged_user_options_when_an_unlogged_user_clicks_the_account_icon()
    {
        Category::factory()->create();

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
        Category::factory()->create();

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
