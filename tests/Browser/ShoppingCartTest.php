<?php

namespace Tests\Browser;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ShoppingCartTest extends DuskTestCase
{
    use DatabaseMigrations;

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
    public function the_shopping_cart_view_shows_the_cart_content()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $productA = $this->createProduct($subcategory->id, $brand->id);
        $productB = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($productA, $productB) {
            $browser->visitRoute('products.show', $productA)
                ->press('@add-to-cart-btn')
                ->visitRoute('products.show', $productB)
                ->press('@add-to-cart-btn')
                ->pause(1000)
                ->visitRoute('shopping-cart')
                ->assertSee($productA->name)
                ->assertSee($productB->name)
                ->screenshot('shopping-cart/show-cart-content');
        });
    }

    /** @test */
    public function it_can_change_a_simple_product_quantity_in_the_cart_and_the_total_updates()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $product = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visitRoute('products.show', $product)
                ->press('@add-to-cart-btn')
                ->pause(1000)
                ->visitRoute('shopping-cart')
                ->assertSee($product->name)
                ->assertSeeIn('@product-' . $product->id . '-total-cost', $product->price)
                ->assertSeeIn('@total-cost', $product->price)
                ->press('@increase-quantity-btn')
                ->pause(1000)
                ->assertSeeIn('@product-' . $product->id . '-total-cost', $product->price * 2)
                ->assertSeeIn('@total-cost', $product->price * 2)
                ->screenshot('shopping-cart/change-product-quantity-and-total-updates');
        });
    }

    /** @test */
    public function it_can_change_a_color_product_quantity_in_the_cart_and_the_total_updates()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true);
        $brand = $this->createBrand($category->id);
        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $this->browse(function (Browser $browser) use ($product, $color) {
            $browser->visitRoute('products.show', $product)
                ->select('@color-dropdown', $color->id)
                ->assertSelected('@color-dropdown', $color->id)
                ->press('@add-to-cart-btn')
                ->pause(1000)
                ->visitRoute('shopping-cart')
                ->assertSee($product->name)
                ->assertSeeIn('@product-' . $product->id . '-total-cost', $product->price)
                ->assertSeeIn('@total-cost', $product->price)
                ->press('@increase-quantity-btn')
                ->pause(1000)
                ->assertSeeIn('@product-' . $product->id . '-total-cost', $product->price * 2)
                ->assertSeeIn('@total-cost', $product->price * 2)
                ->screenshot('shopping-cart/change-color-product-quantity-and-total-updates');
        });
    }

    /** @test */
    public function it_can_change_a_size_product_quantity_in_the_cart_and_the_total_updates()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, true, true);
        $brand = $this->createBrand($category->id);
        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $size = $this->createSize($product->id, array($color));

        $this->browse(function (Browser $browser) use ($product, $color, $size) {
            $browser->visitRoute('products.show', $product)
                ->select('@size-dropdown', $size->id)
                ->assertSelected('@size-dropdown', $size->id)
                ->select('@color-dropdown', $color->id)
                ->assertSelected('@color-dropdown', $color->id)
                ->press('@add-to-cart-btn')
                ->pause(1000)
                ->visitRoute('shopping-cart')
                ->assertSee($product->name)
                ->assertSeeIn('@product-' . $product->id . '-total-cost', $product->price)
                ->assertSeeIn('@total-cost', $product->price)
                ->press('@increase-quantity-btn')
                ->pause(1000)
                ->assertSeeIn('@product-' . $product->id . '-total-cost', $product->price * 2)
                ->assertSeeIn('@total-cost', $product->price * 2)
                ->screenshot('shopping-cart/change-size-product-quantity-and-total-updates');
        });
    }

    /** @test */
    public function it_can_remove_an_item_from_the_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $productA = $this->createProduct($subcategory->id, $brand->id);
        $productB = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($productA, $productB) {
            $browser->visitRoute('products.show', $productA)
                ->press('@add-to-cart-btn')
                ->visitRoute('products.show', $productB)
                ->press('@add-to-cart-btn')
                ->pause(1000)
                ->visitRoute('shopping-cart')
                ->assertSee($productA->name)
                ->assertSee($productB->name)
                ->press('@remove-product-' . $productB->id)
                ->pause(1000)
                ->assertSee($productA->name)
                ->assertDontSee($productB->name)
                ->screenshot('shopping-cart/can-remove-an-item-from-cart');
        });
    }

    /** @test */
    public function it_can_clear_the_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $product = $this->createProduct($subcategory->id, $brand->id);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visitRoute('products.show', $product)
                ->press('@add-to-cart-btn')
                ->pause(1000)
                ->visitRoute('shopping-cart')
                ->assertSee($product->name)
                ->press('@clear-cart')
                ->pause(1000)
                ->assertDontSee($product->name)
                ->assertSee('TU CARRITO DE COMPRAS ESTÁ VACÍO')
                ->screenshot('shopping-cart/can-clear-the-cart');
        });
    }
}
