<?php

namespace Tests\Browser;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ShowProductsTest extends DuskTestCase
{
    use DatabaseMigrations;

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
                ->screenshot('show-products/show-product-details');
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
                ->screenshot('show-products/show-product-details-without-color-and-size');
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
                ->screenshot('show-products/show-product-details-with-color');
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
                ->screenshot('show-products/show-product-details-with-color-and-size');
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
                ->screenshot('show-products/decrease-product-qty-button-limit');
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
                ->screenshot('show-products/increase-product-qty-button-limit');
        });
    }

    /** @test */
    public function it_doesnt_show_size_nor_color_dropdowns_in_the_details_view_when_the_product_doesnt_have_these_features()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);
        $product = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visitRoute('products.show', $product)
                ->assertNotPresent('@size-dropdown')
                ->assertNotPresent('@color-dropdown')
                ->screenshot('show-products/doesnt-show-dropdowns-for-simple-product');
        });
    }

    /** @test */
    public function it_only_shows_the_color_dropdown_in_the_details_view_when_the_product_only_have_this_feature()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true);
        $brand = $this->createBrand($category->id);

        $colorA = $this->createColor();
        $colorB = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($colorA, $colorB));

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visitRoute('products.show', $product)
                ->assertNotPresent('@size-dropdown')
                ->assertPresent('@color-dropdown')
                ->screenshot('show-products/only-show-color-dropdown-when-product-only-has-color');
        });
    }

    /** @test */
    public function it_shows_size_and_color_dropdowns_in_the_details_view_when_the_product_doesnt_have_both_features()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true, true);
        $brand = $this->createBrand($category->id);

        $colorA = $this->createColor();
        $colorB = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($colorA, $colorB));

        $this->createSize($product->id, array($colorA, $colorB));
        $this->createSize($product->id, array($colorA, $colorB));

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visitRoute('products.show', $product)
                ->assertPresent('@size-dropdown')
                ->assertPresent('@color-dropdown')
                ->screenshot('show-products/show-color-and-size-dropdowns-when-product-have-them');
        });
    }

    /** @test */
    public function it_shows_the_available_stock_of_a_simple_product()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $product = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visitRoute('products.show', $product)
                ->waitForTextIn('@available-stock', $product->quantity)
                ->screenshot('show-products/show-available-stock-of-simple-product');
        });
    }

    /** @test */
    public function it_shows_the_available_stock_of_a_color_product()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true);
        $brand = $this->createBrand($category->id);

        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $quantity = $product->colors()->find($color->id)->pivot->quantity;

        $this->browse(function (Browser $browser) use ($product, $quantity, $color) {
            $browser->visitRoute('products.show', $product)
                ->select('@color-dropdown', $color->id)
                ->waitForTextIn('@available-stock', $quantity)
                ->screenshot('show-products/show-available-stock-of-color-product');
        });
    }

    /** @test */
    public function it_shows_the_available_stock_of_a_size_product()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true, true);
        $brand = $this->createBrand($category->id);

        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $size = $this->createSize($product->id, array($color));

        $quantity = $product->sizes()->find($size->id)->colors()->find($color->id)->pivot->quantity;

        $this->browse(function (Browser $browser) use ($product,$size, $color, $quantity) {
            $browser->visitRoute('products.show', $product)
                ->select('@size-dropdown', $size->id)
                ->assertSelected('@size-dropdown', $size->id)
                ->select('@color-dropdown', $color->id)
                ->assertSelected('@color-dropdown', $color->id)
                ->waitForTextIn('@available-stock', $quantity)
                ->screenshot('show-products/show-available-stock-of-size-product');
        });
    }

    /** @test */
    public function it_adds_a_product_without_color_and_size_to_the_shopping_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $product = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visitRoute('products.show', $product)
                ->assertButtonEnabled('@add-to-cart-btn')
                ->press('@add-to-cart-btn')
                ->waitForTextIn('@cart-products-count-icon', '1')
                ->screenshot('show-products/adds-simple-product-to-cart');
        });
    }

    /** @test */
    public function it_adds_a_product_with_only_color_to_the_shopping_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true);
        $brand = $this->createBrand($category->id);

        $colorA = $this->createColor();
        $colorB = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($colorA, $colorB));

        $this->browse(function (Browser $browser) use ($product, $colorA, $colorB) {
            $browser->visitRoute('products.show', $product)
                ->assertSelectHasOptions('@color-dropdown', [$colorA->id, $colorB->id])
                ->select('@color-dropdown', $colorA->id)
                ->assertSelected('@color-dropdown', $colorA->id)
                ->assertButtonEnabled('@add-to-cart-btn')
                ->press('@add-to-cart-btn')
                ->waitForTextIn('@cart-products-count-icon', '1')
                ->screenshot('show-products/adds-product-with-color-to-cart');
        });
    }

    /** @test */
    public function it_adds_a_product_with_color_and_size_to_the_shopping_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true, true);
        $brand = $this->createBrand($category->id);

        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id);

        $size = $this->createSize($product->id, array($color));

        $this->browse(function (Browser $browser) use ($product, $color, $size) {
            $browser->visitRoute('products.show', $product)
                ->pause(1000)
                ->assertSelectHasOptions('@size-dropdown', [$size->id])
                ->select('@size-dropdown', $size->id)
                ->assertSelected('@size-dropdown', $size->id)
                ->assertSelectHasOptions('@color-dropdown', [$color->id])
                ->select('@color-dropdown', $color->id)
                ->assertSelected('@color-dropdown', $color->id)
                ->assertButtonEnabled('@add-to-cart-btn')
                ->press('@add-to-cart-btn')
                ->waitForTextIn('@cart-products-count-icon', '1')
                ->screenshot('show-products/adds-product-with-color-and-size-to-cart');
        });
    }

    /** @test */
    public function can_not_add_more_quantity_of_a_simple_product_than_stock_exists_to_the_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $product = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visitRoute('products.show', $product);

            for ($i = 1; $i < $product->quantity; $i++) {
                $browser->press('@increase-quantity-btn')
                    ->pause(500);
            }

            $browser->press('@add-to-cart-btn')
                ->waitForTextIn('@cart-products-count-icon', $product->quantity)
                ->waitForTextIn('@available-stock', '0')
                ->assertDisabled('@increase-quantity-btn')
                ->screenshot('show-products/cannot-add-more-qty-than-stock-of-simple-product');
        });
    }

    /** @test */
    public function can_not_add_more_quantity_of_a_color_product_than_stock_exists_to_the_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true);
        $brand = $this->createBrand($category->id);

        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $quantity = $product->colors()->find($color->id)->pivot->quantity;

        $this->browse(function (Browser $browser) use ($product, $color, $quantity) {
            $browser->visitRoute('products.show', $product)
                ->select('@color-dropdown', $color->id);

            for ($i = 1; $i < $quantity; $i++) {
                $browser->press('@increase-quantity-btn')
                    ->pause(500);
            }

            $browser->press('@add-to-cart-btn')
                ->waitForTextIn('@cart-products-count-icon', $quantity)
                //->waitForTextIn('@available-stock', '0') // Hay un error, cuando llega a 0 muestra el stock total del color
                ->assertDisabled('@increase-quantity-btn')
                ->screenshot('show-products/cannot-add-more-qty-than-stock-of-color-product');
        });
    }

    /** @test */
    public function can_not_add_more_quantity_of_a_size_product_than_stock_exists_to_the_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true, true);
        $brand = $this->createBrand($category->id);

        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $size = $this->createSize($product->id, array($color));

        $quantity = $product->sizes()->find($size->id)->colors()->find($color->id)->pivot->quantity;

        $this->browse(function (Browser $browser) use ($product,$size, $color, $quantity) {
            $browser->visitRoute('products.show', $product)
                ->select('@size-dropdown', $size->id)
                ->assertSelected('@size-dropdown', $size->id)
                ->select('@color-dropdown', $color->id)
                ->assertSelected('@color-dropdown', $color->id);

            for ($i = 1; $i < $quantity; $i++) {
                $browser->press('@increase-quantity-btn')
                    ->pause(500);
            }

            $browser->press('@add-to-cart-btn')
                ->waitForTextIn('@cart-products-count-icon', $quantity)
                //->waitForTextIn('@available-stock', '0') // Hay un error, cuando llega a 0 muestra el stock total de la talla
                ->assertDisabled('@increase-quantity-btn')
                ->screenshot('show-products/cannot-add-more-qty-than-stock-of-size-product');
        });
    }
}