<?php

namespace Tests\Browser;

use App\Models\Product;
use App\Models\Size;
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

    /** @test */
    public function it_shows_the_details_view_of_a_product()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);
        $product = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($category, $subcategory, $product) {
            $browser->visit('/')
                ->click('@show-category-' . $category->id)
                ->click('@view-product-' . $product->id)
                ->assertUrlIs(route('products.show', $product))
                ->assertSee($product->name)
                ->assertSee(Str::title($product->brand->name))
                ->assertSee($product->price)
                ->screenshot('show-product-details');
        });
    }

    /** @test */
    public function the_details_view_of_a_product_without_color_and_size_contains_the_necessary_elements()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);
        $product = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($category, $subcategory, $product) {
            $browser->visit('/')
                ->click('@show-category-' . $category->id)
                ->click('@view-product-' . $product->id)
                ->assertUrlIs(route('products.show', $product))
                ->assertAttribute('@product-image-' . $product->images->find(1)->id, 'src', '/storage/' . $product->images->find(1)->url)
                ->assertAttribute('@product-image-' . $product->images->find(2)->id, 'src', '/storage/' . $product->images->find(2)->url)
                ->assertAttribute('@product-image-' . $product->images->find(3)->id, 'src', '/storage/' . $product->images->find(3)->url)
                ->assertAttribute('@product-image-' . $product->images->find(4)->id, 'src', '/storage/' . $product->images->find(4)->url)
                //->assertSee($product->description)
                ->assertSee($product->name)
                ->assertSee($product->price)
                ->assertSee('Stock disponible: ' . $product->quantity)
                ->assertSeeIn('@decrease-quantity-btn', '-')
                ->assertSeeIn('@increase-quantity-btn', '+')
                ->assertPresent('@add-to-cart-btn')
                ->screenshot('show-product-details-without-color-and-size');
        });
    }

    /** @test */
    public function the_details_view_of_a_product_with_color_contains_the_necessary_elements()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true);
        $brand = $this->createBrand($category->id);

        $colorA = $this->createColor();
        $colorB = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($colorA, $colorB));

        $productQuantity = $product->colors->first()->pivot->quantity;

        $this->browse(function (Browser $browser) use ($category, $subcategory, $product, $productQuantity, $colorA, $colorB) {
            $browser->visit('/')
                ->click('@show-category-' . $category->id)
                ->click('@view-product-' . $product->id)
                ->assertUrlIs(route('products.show', $product))
                ->assertAttribute('@product-image-' . $product->images->find(1)->id, 'src', '/storage/' . $product->images->find(1)->url)
                ->assertAttribute('@product-image-' . $product->images->find(2)->id, 'src', '/storage/' . $product->images->find(2)->url)
                ->assertAttribute('@product-image-' . $product->images->find(3)->id, 'src', '/storage/' . $product->images->find(3)->url)
                ->assertAttribute('@product-image-' . $product->images->find(4)->id, 'src', '/storage/' . $product->images->find(4)->url)
                ->assertSee($product->name)
                ->assertSee($product->price)
                ->pause(2000)
                ->assertSelectHasOptions('@color-dropdown', [$colorA->id, $colorB->id])
                ->select('@color-dropdown', $colorA->id)
                ->assertSelected('@color-dropdown', $colorA->id)
                //->assertSee($productQuantity)
                ->assertSeeIn('@decrease-quantity-btn', '-')
                ->assertSeeIn('@increase-quantity-btn', '+')
                ->assertPresent('@add-to-cart-btn')
                ->screenshot('show-product-details-with-color');
        });
    }

    /** @test */
    public function the_details_view_of_a_product_with_color_and_size_contains_the_necessary_elements()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true, true);
        $brand = $this->createBrand($category->id);

        $colorA = $this->createColor();
        $colorB = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($colorA, $colorB));

        $sizeA = $this->createSize($product->id, array($colorA, $colorB));
        $sizeB = $this->createSize($product->id, array($colorA, $colorB));

        $productQuantity = $product->sizes->find($sizeA->id)->colors->find($colorA->id)->pivot->quantity;

        $this->browse(function (Browser $browser) use ($category, $subcategory, $product, $productQuantity, $colorA, $colorB, $sizeA, $sizeB) {
            $browser->visit('/')
                ->click('@show-category-' . $category->id)
                ->click('@view-product-' . $product->id)
                ->assertUrlIs(route('products.show', $product))
                ->assertAttribute('@product-image-' . $product->images->find(1)->id, 'src', '/storage/' . $product->images->find(1)->url)
                ->assertAttribute('@product-image-' . $product->images->find(2)->id, 'src', '/storage/' . $product->images->find(2)->url)
                ->assertAttribute('@product-image-' . $product->images->find(3)->id, 'src', '/storage/' . $product->images->find(3)->url)
                ->assertAttribute('@product-image-' . $product->images->find(4)->id, 'src', '/storage/' . $product->images->find(4)->url)
                ->assertSee($product->name)
                ->assertSee($product->price)
                ->pause(2000)
                ->assertSelectHasOptions('@size-dropdown', [$sizeA->id, $sizeB->id])
                ->select('@size-dropdown', $sizeA->id)
                ->assertSelected('@size-dropdown', $sizeA->id)
                ->assertSelectHasOptions('@color-dropdown', [$colorA->id, $colorB->id])
                ->select('@color-dropdown', $colorA->id)
                ->assertSelected('@color-dropdown', $colorA->id)
                //->assertSee($productQuantity)
                ->assertSeeIn('@decrease-quantity-btn', '-')
                ->assertSeeIn('@increase-quantity-btn', '+')
                ->assertPresent('@add-to-cart-btn')
                ->screenshot('show-product-details-with-color-and-size');
        });
    }

    /** @test */
    public function the_decrease_quantity_button_in_the_details_view_has_a_limit()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);
        $product = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit(route('products.show', $product))
                ->assertSeeIn('@product-quantity', '1')
                ->assertButtonDisabled('@decrease-quantity-btn')
                ->press('@decrease-quantity-btn')
                ->assertSeeIn('@product-quantity', '1')
                ->assertButtonDisabled('@decrease-quantity-btn')
                ->screenshot('decrease-product-qty-button-limit');
        });
    }

    /** @test */
    public function the_increase_quantity_button_in_the_details_view_has_a_limit()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);
        $product = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit(route('products.show', $product))
                ->assertSeeIn('@product-quantity', '1')
                ->assertButtonEnabled('@increase-quantity-btn');

            for ($i = 1; $i < $product->quantity; $i++) {
                $browser->press('@increase-quantity-btn')
                ->pause(500);
            }

            $browser->assertSeeIn('@product-quantity', $product->quantity)
                ->assertButtonDisabled('@increase-quantity-btn')
                ->screenshot('increase-product-qty-button-limit');
        });
    }
}
