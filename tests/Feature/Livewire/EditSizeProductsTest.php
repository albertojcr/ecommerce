<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Admin\ColorSize;
use App\Http\Livewire\Admin\SizeProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EditSizeProductsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_updates_a_product_with_color_and_size()
    {

    }

    /** @test */
    public function it_deletes_a_product_with_color_and_size()
    {

    }

    /** @test */
    public function it_adds_a_size_to_a_product()
    {

    }

    /** @test */
    public function it_updates_a_size_name()
    {

    }

    /** @test */
    public function it_deletes_a_size()
    {

    }

    /** @test */
    public function it_adds_a_color_to_a_size()
    {

    }

    /** @test */
    public function it_updates_the_name_and_quantity_of_a_color()
    {

    }

    /** @test */
    public function it_deletes_a_color()
    {

    }

    /** @test */
    public function it_can_not_add_an_existing_size()
    {

    }

    /** @test */
    public function it_updates_the_color_quantity_if_we_add_it_again()
    {

    }

    /** @test  */
    public function the_size_name_field_is_required_when_adding_a_size_to_a_product()
    {
        $category = $this->createCategory();

        $subcategory = $this->createSubcategory($category->id, true, true);
        $brand = $this->createBrand($category->id);
        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $size = $this->createSize($product->id, array($color));

        $user = $this->createAdminUser();

        $this->actingAs($user);

        Livewire::test(SizeProduct::class, [
            'product' => $product
        ])->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);

        $this->assertDatabaseCount('sizes', 1)
            ->assertDatabaseHas('sizes', [
                'id' => $size->id,
                'name' => $size->name,
                'product_id' => $product->id
            ]);
    }

    /** @test  */
    public function the_color_id_field_is_required_when_adding_a_color_to_a_size()
    {
        $category = $this->createCategory();

        $subcategory = $this->createSubcategory($category->id, true, true);
        $brand = $this->createBrand($category->id);
        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $size = $this->createSize($product->id, array($color));

        $user = $this->createAdminUser();

        $this->actingAs($user);

        Livewire::test(ColorSize::class, [
            'size' => $size
        ])->set('color_id', '')
            ->call('save')
            ->assertHasErrors(['color_id' => 'required']);

        $this->assertDatabaseCount('color_size', 1)
            ->assertDatabaseHas('color_size', [
                'color_id' => $color->id,
                'size_id' => $size->id
            ]);
    }

    /** @test  */
    public function the_color_quantity_field_is_required_when_adding_a_color_to_a_size()
    {
        $category = $this->createCategory();

        $subcategory = $this->createSubcategory($category->id, true, true);
        $brand = $this->createBrand($category->id);
        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $size = $this->createSize($product->id, array($color));

        $user = $this->createAdminUser();

        $this->actingAs($user);

        Livewire::test(ColorSize::class, [
            'size' => $size
        ])->set('quantity', '')
            ->call('save')
            ->assertHasErrors(['quantity' => 'required']);

        $this->assertDatabaseCount('color_size', 1)
            ->assertDatabaseHas('color_size', [
                'color_id' => $color->id,
                'size_id' => $size->id,
                'quantity' => $size->colors()->find($color->id)->pivot->quantity
            ]);
    }

    /** @test  */
    public function the_color_quantity_field_is_numeric_when_adding_a_color_to_a_size()
    {
        $category = $this->createCategory();

        $subcategory = $this->createSubcategory($category->id, true, true);
        $brand = $this->createBrand($category->id);
        $color = $this->createColor();

        $product = $this->createProduct($subcategory->id, $brand->id, Product::PUBLICADO, array($color));

        $size = $this->createSize($product->id, array($color));

        $user = $this->createAdminUser();

        $this->actingAs($user);

        Livewire::test(ColorSize::class, [
            'size' => $size
        ])->set('quantity', 'not-a-number')
            ->call('save')
            ->assertHasErrors(['quantity' => 'numeric']);

        $this->assertDatabaseCount('color_size', 1)
            ->assertDatabaseHas('color_size', [
                'color_id' => $color->id,
                'size_id' => $size->id,
                'quantity' => $size->colors()->find($color->id)->pivot->quantity
            ]);
    }
}
