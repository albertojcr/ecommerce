<?php

namespace Tests\Browser;

use App\Models\City;
use App\Models\Department;
use App\Models\District;
use App\Models\Order;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
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

    /** @test */
    public function it_creates_an_order_then_destroy_cart_and_redirect_when_store_pickup_is_selected()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $product = $this->createProduct($subcategory->id, $brand->id);

        $user = User::factory()->create();

        $this->assertDatabaseCount('orders', 0);

        $this->browse(function (Browser $browser) use ($product, $user) {
            $browser->loginAs($user)
                ->visitRoute('products.show', $product)
                ->press('@add-to-cart-btn')
                ->pause(1000)
                ->visitRoute('orders.create')
                ->type('@contact-name', 'Nombre')
                ->type('@contact-phone', '657485734')
                ->radio('envio_type', 1)
                ->press('@create-order')
                ->pause(1000)
                ->assertRouteIs('orders.payment', Order::first())
                ->screenshot('orders/it-creates-an-order-when-store-pickup-option');
        });

        $this->assertSame(0, Cart::count());

        $this->assertDatabaseCount('orders', 1);
    }

    /** @test */
    public function it_creates_an_order_then_destroy_cart_and_redirect_when_home_delivery_is_selected()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id);
        $brand = $this->createBrand($category->id);

        $product = $this->createProduct($subcategory->id, $brand->id);

        $user = User::factory()->create();

        $deparment = Department::factory()->create();

        $city = City::factory()->create([
            'department_id' => $deparment->id
        ]);

        $district = District::factory()->create([
           'city_id' => $city->id
        ]);

        $this->assertDatabaseCount('orders', 0);

        $this->browse(function (Browser $browser) use ($product, $user, $deparment, $city, $district) {
            $browser->loginAs($user)
                ->visitRoute('products.show', $product)
                ->press('@add-to-cart-btn')
                ->pause(1000)
                ->visitRoute('orders.create')
                ->radio('envio_type', 2)
                ->select('@department', $deparment->id)
                ->pause(1000)
                ->select('@city', $city->id)
                ->pause(1000)
                ->select('@district', $district->id)
                ->type('@contact-name', 'Nombre')
                ->type('@contact-phone', '657485734')
                ->type('@address', 'Calle Ejemplo 1')
                ->type('@reference', 'Referencia')
                ->press('@create-order')
                ->pause(1000)
                ->assertRouteIs('orders.payment', Order::first())
                ->screenshot('orders/it-creates-an-order-when-home-delivery-option');
        });

        $this->assertSame(0, Cart::count());

        $this->assertDatabaseCount('orders', 1);
    }


}
