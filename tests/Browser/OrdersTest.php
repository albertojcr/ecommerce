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
}
