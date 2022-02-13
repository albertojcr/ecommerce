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
}
