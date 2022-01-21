<?php

namespace Tests\Browser;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function the_welcome_view_shows_at_least_five_products()
    {
        $categoryA = $this->createCategory();
        $subcategoryA = $this->createSubcategory($categoryA->id);

        $productA = $this->createProduct($subcategoryA->id);
        $productB = $this->createProduct($subcategoryA->id);
        $productC = $this->createProduct($subcategoryA->id);
        $productD = $this->createProduct($subcategoryA->id);
        $productE = $this->createProduct($subcategoryA->id);

        $this->browse(function (Browser $browser) use ($productA, $productB, $productC, $productD, $productE) {
            $browser->visit('/')
                ->assertSee(Str::limit($productA->name, 20))
                ->assertSee(Str::limit($productB->name, 20))
                ->assertSee(Str::limit($productC->name, 20))
                ->assertSee(Str::limit($productD->name, 20))
                ->assertSee(Str::limit($productE->name, 20))
                ->screenshot('show-five-products');
        });
    }

    /** @test */
    public function the_welcome_view_only_shows_published_products()
    {
        $categoryA = $this->createCategory();
        $subcategoryA = $this->createSubcategory($categoryA->id);

        $productA = $this->createProduct($subcategoryA->id);
        $productB = $this->createProduct($subcategoryA->id);
        $productC = $this->createProduct($subcategoryA->id);
        $productD = $this->createProduct($subcategoryA->id);
        $productE = $this->createProduct($subcategoryA->id);

        $unpublishedProductA = $this->createProduct($subcategoryA->id, Product::BORRADOR);
        $unpublishedProductB = $this->createProduct($subcategoryA->id, Product::BORRADOR);

        $this->browse(function (Browser $browser) use ($productA, $productB, $productC, $productD, $productE, $unpublishedProductA, $unpublishedProductB) {
            $browser->visit('/')
                ->assertSee(Str::limit($productA->name, 20))
                ->assertSee(Str::limit($productB->name, 20))
                ->assertSee(Str::limit($productC->name, 20))
                ->assertSee(Str::limit($productD->name, 20))
                ->assertSee(Str::limit($productE->name, 20))
                ->assertDontSee(Str::limit($unpublishedProductA->name, 20))
                ->assertDontSee(Str::limit($unpublishedProductB->name, 20))
                ->screenshot('only-show-published-products');
        });
    }
}
