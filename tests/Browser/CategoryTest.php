<?php

namespace Tests\Browser;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoryTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function createCategory()
    {
        return Category::factory()->create();
    }

    protected function createSubcategory($categoryId)
    {
        return Subcategory::factory()->create([
            'category_id' => $categoryId
        ]);
    }

    protected function createProduct($status = 2)
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = Brand::factory()->create();
        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'quantity' => '',
            'status' => $status
        ]);

        return $product;
    }

    /** @test */
    public function it_shows_the_categories_when_clicking_the_categories_button()
    {
        $firstCategory = $this->createCategory();

        $secondCategory = $this->createCategory();

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
        $firstCategory = $this->createCategory();
        $secondCategory = $this->createCategory();

        $firstSubcategory = $this->createSubcategory($firstCategory->id);
        $secondSubcategory = $this->createSubcategory($secondCategory->id);

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
