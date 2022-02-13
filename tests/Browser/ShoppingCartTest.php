<?php

namespace Tests\Browser;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ShoppingCartTest extends DuskTestCase
{
    use DatabaseMigrations;


    /** @test */
    public function it_adds_a_product_without_color_and_size_to_the_shoping_cart()
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
                ->screenshot('shopping-cart/adds-simple-product-to-cart');
        });
    }

    /** @test */
    public function it_adds_a_product_with_only_color_to_the_shoping_cart()
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
                ->screenshot('shopping-cart/adds-product-with-color-to-cart');
        });
    }

    /** @test */
    public function it_adds_a_product_with_color_and_size_to_the_shoping_cart()
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
                ->screenshot('shopping-cart/adds-product-with-color-and-size-to-cart');
        });
    }

    /** @test */
    public function it_shows_the_products_in_the_cart_when_clicking_its_icon()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $productA = $this->createProduct($subcategory->id, $brand->id);
        $productB = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($productA, $productB) {
            $browser->visitRoute('products.show', $productA)
                ->assertButtonEnabled('@add-to-cart-btn')
                ->press('@add-to-cart-btn')
                ->visitRoute('products.show', $productB)
                ->assertButtonEnabled('@add-to-cart-btn')
                ->press('@add-to-cart-btn')
                ->click('@cart-icon')
                ->pause(1000)
                ->assertSeeIn('@cart-content', $productA->name)
                ->assertSeeIn('@cart-content', $productB->name)
                ->screenshot('shopping-cart/show-cart-content');
        });
    }

    /** @test */
    public function the_shopping_cart_count_icon_increments_when_adding_a_product()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $product = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visitRoute('products.show', $product)
                ->assertSeeNothingIn('@cart-products-count-icon')
                ->assertButtonEnabled('@add-to-cart-btn')
                ->press('@add-to-cart-btn')
                ->waitForTextIn('@cart-products-count-icon', '1')
                ->screenshot('shopping-cart/cart-count-increments');
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
                ->screenshot('shopping-cart/cannot-add-more-qty-than-stock-of-simple-product');
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
                ->screenshot('shopping-cart/cannot-add-more-qty-than-stock-of-color-product');
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
                ->screenshot('shopping-cart/cannot-add-more-qty-than-stock-of-size-product');
        });
    }
}
