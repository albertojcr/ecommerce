<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Admin\ColorProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EditColorProductsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_updates_a_product_with_color()
    {

    }

    /** @test */
    public function it_deletes_a_product_with_color()
    {

    }

    /** @test */
    public function it_adds_color_to_a_product()
    {

    }

    /** @test */
    public function it_deletes_a_color_of_a_product()
    {

    }

    /** @test */
    public function it_updates_the_name_and_the_quantity_of_a_color()
    {

    }

    /** @test  */
    public function the_color_id_field_is_required_when_adding_a_color_to_a_product()
    {
        $category = $this->createCategory();

        $subcategory = $this->createSubcategory($category->id, true);
        $brand = $this->createBrand($category->id);
        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $user = $this->createAdminUser();

        $this->actingAs($user);

        Livewire::test(ColorProduct::class, [
            'product' => $product
        ])->set('color_id', '')
            ->call('save')
            ->assertHasErrors(['color_id' => 'required']);

        $this->assertDatabaseCount('color_product', 1)
            ->assertDatabaseHas('color_product', [
                'color_id' => $color->id,
                'product_id' => $product->id
            ]);
    }

    /** @test  */
    public function the_color_quantity_field_is_required_when_adding_a_color_to_a_product()
    {
        $category = $this->createCategory();

        $subcategory = $this->createSubcategory($category->id, true);
        $brand = $this->createBrand($category->id);
        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $user = $this->createAdminUser();

        $this->actingAs($user);

        Livewire::test(ColorProduct::class, [
            'product' => $product
        ])->set('color_id', $color->id)
            ->set('quantity', '')
            ->call('save')
            ->assertHasErrors(['quantity' => 'required']);

        $this->assertDatabaseCount('color_product', 1)
            ->assertDatabaseHas('color_product', [
                'color_id' => $color->id,
                'product_id' => $product->id,
                'quantity' => $product->colors()->find($color->id)->pivot->quantity
            ]);
    }

    /** @test  */
    public function the_color_quantity_field_is_numeric_when_adding_a_color_to_a_product()
    {
        $category = $this->createCategory();

        $subcategory = $this->createSubcategory($category->id, true);
        $brand = $this->createBrand($category->id);
        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $user = $this->createAdminUser();

        $this->actingAs($user);

        Livewire::test(ColorProduct::class, [
            'product' => $product
        ])->set('color_id', $color->id)
            ->set('quantity', 'not-a-number')
            ->call('save')
            ->assertHasErrors(['quantity' => 'numeric']);

        $this->assertDatabaseCount('color_product', 1)
            ->assertDatabaseHas('color_product', [
                'color_id' => $color->id,
                'product_id' => $product->id,
                'quantity' => $product->colors()->find($color->id)->pivot->quantity
            ]);
    }
}
