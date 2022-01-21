<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function the_welcome_view_shows_at_least_five_products()
    {
        $productA = $this->createProduct();


        $this->browse(function (Browser $browser) use ($productA) {
            $browser->visit('/')
                ->waitForText($productA->name)
                ->screenshot('show-five-products');
        });
    }
}
