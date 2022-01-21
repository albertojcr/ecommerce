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
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $productA = $this->createProduct($subcategory->id, $brand->id);
        $productB = $this->createProduct($subcategory->id, $brand->id);
        $productC = $this->createProduct($subcategory->id, $brand->id);
        $productD = $this->createProduct($subcategory->id, $brand->id);
        $productE = $this->createProduct($subcategory->id, $brand->id);

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
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $productA = $this->createProduct($subcategory->id, $brand->id);
        $productB = $this->createProduct($subcategory->id, $brand->id);
        $productC = $this->createProduct($subcategory->id, $brand->id);
        $productD = $this->createProduct($subcategory->id, $brand->id);
        $productE = $this->createProduct($subcategory->id, $brand->id);

        $unpublishedProductA = $this->createProduct($subcategory->id, $brand->id, Product::BORRADOR);
        $unpublishedProductB = $this->createProduct($subcategory->id, $brand->id, Product::BORRADOR);

        $this->browse(function (Browser $browser) use ($productA, $productB, $productC, $productD, $productE, $unpublishedProductA, $unpublishedProductB) {
            $browser->visit('/')
                ->waitForText(Str::limit($productA->name, 20))
                ->assertSee(Str::limit($productB->name, 20))
                ->assertSee(Str::limit($productC->name, 20))
                ->assertSee(Str::limit($productD->name, 20))
                ->assertSee(Str::limit($productE->name, 20))
                ->assertDontSee(Str::limit($unpublishedProductA->name, 20))
                ->assertDontSee(Str::limit($unpublishedProductB->name, 20))
                ->screenshot('only-show-published-products');
        });
    }

    /** @test */
    public function it_filters_products_by_subcategory()
    {
        $category = $this->createCategory();

        $subcategoryA = $this->createSubcategory($category->id);
        $subcategoryB = $this->createSubcategory($category->id);

        $brandA = $this->createBrand($category->id);
        $brandB = $this->createBrand($category->id);

        $productA = $this->createProduct($subcategoryA->id, $brandA->id);
        $productB = $this->createProduct($subcategoryA->id, $brandB->id);
        $productC = $this->createProduct($subcategoryB->id, $brandA->id);
        $productD = $this->createProduct($subcategoryB->id, $brandB->id);

        $this->browse(function (Browser $browser) use ($category, $subcategoryA, $productA, $productB, $productC, $productD) {
            $browser->visit('/')
                ->click('@show-category-' . $category->id)
                ->click('@filter-subcategory-' . $subcategoryA->id)
                ->assertSee(Str::limit($productA->name, 20))
                ->assertSee(Str::limit($productB->name, 20))
                ->assertDontSee(Str::limit($productC->name, 20))
                ->assertDontSee(Str::limit($productD->name, 20))
                ->screenshot('filter-products-by-subcategory');
        });
    }

    /** @test */
    public function it_filters_products_by_brand()
    {
        $category = $this->createCategory();

        $subcategoryA = $this->createSubcategory($category->id);
        $subcategoryB = $this->createSubcategory($category->id);

        $brandA = $this->createBrand($category->id);
        $brandB = $this->createBrand($category->id);

        $productA = $this->createProduct($subcategoryA->id, $brandA->id);
        $productB = $this->createProduct($subcategoryB->id, $brandA->id);
        $productC = $this->createProduct($subcategoryA->id, $brandB->id);
        $productD = $this->createProduct($subcategoryB->id, $brandB->id);

        $this->browse(function (Browser $browser) use ($category, $brandA, $productA, $productB, $productC, $productD) {
            $browser->visit('/')
                ->click('@show-category-' . $category->id)
                ->click('@filter-brand-' . $brandA->id)
                ->assertSee(Str::limit($productA->name, 20))
                ->assertSee(Str::limit($productB->name, 20))
                ->assertDontSee(Str::limit($productC->name, 20))
                ->assertDontSee(Str::limit($productD->name, 20))
                ->screenshot('filter-products-by-brand');
        });
    }
}
