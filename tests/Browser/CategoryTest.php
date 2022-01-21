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

    /** @test */
    public function it_shows_the_categories_when_clicking_the_categories_button()
    {
        $firstCategory = $this->createCategory();

        $secondCategory = $this->createCategory();

        $this->browse(function (Browser $browser) use ($firstCategory, $secondCategory) {
            $browser->visit('/')
                ->click('@categories-button')
                ->waitForText($firstCategory->name)
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
                ->waitForText($firstSubcategory->name)
                ->assertDontSee($secondSubcategory->name)
                ->screenshot('show-subcategories');
        });
    }



}
