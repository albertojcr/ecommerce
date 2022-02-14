<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class OrdersTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function only_logged_users_can_access_to_the_create_order_view()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $product = $this->createProduct($subcategory->id, $brand->id);

        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($product, $user) {
            $browser->assertGuest()
                ->visitRoute('products.show', $product)
                ->press('@add-to-cart-btn')
                ->pause(1000)
                ->visitRoute('orders.create')
                ->assertRouteIs('login')
                ->loginAs($user)
                ->visitRoute('orders.create')
                ->assertRouteIs('orders.create')
                ->screenshot('orders/only-logged-users-can-access-to-create-order-view');
        });
    }

    /** @test */
    public function address_form_is_hidden_if_store_pickup_option_is_selected()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $product = $this->createProduct($subcategory->id, $brand->id);

        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($product, $user) {
            $browser->loginAs($user)
                ->visitRoute('products.show', $product)
                ->press('@add-to-cart-btn')
                ->pause(1000)
                ->visitRoute('orders.create')
                ->radio('envio_type', 1)
                ->assertAttributeContains('@address-form', 'class', 'hidden')
                ->screenshot('orders/address-form-is-hidden-when-store-pickup');
        });
    }

    /** @test */
    public function address_form_is_shown_if_home_delivery_option_is_selected()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $product = $this->createProduct($subcategory->id, $brand->id);

        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($product, $user) {
            $browser->loginAs($user)
                ->visitRoute('products.show', $product)
                ->press('@add-to-cart-btn')
                ->pause(1000)
                ->visitRoute('orders.create')
                ->radio('envio_type', 2)
                ->assertVisible('@address-form')
                ->screenshot('orders/address-form-is-shown-when-home-delivery');
        });
    }
}
