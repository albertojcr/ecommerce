<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use Faker\Factory;
use Illuminate\Support\Str;

trait TestHelpers
{
    protected function createCategory()
    {
        return Category::factory()->create();
    }

    protected function createSubcategory($categoryId, $hasColor = false)
    {
        return Subcategory::factory()->create([
            'category_id' => $categoryId,
            'color' => $hasColor
        ]);
    }

    protected function createColor()
    {
        return Color::create([
            'name' => Factory::create()->colorName() // Duda
        ]);
    }

    protected function createImage($imageableId, $imageableType)
    {
        return Image::factory()->create([
            'imageable_id' => $imageableId,
            'imageable_type' => $imageableType
        ]);
    }

    protected function createProduct($subcategoryId, $status = Product::PUBLICADO, $hasColor = false)
    {
        $brand = Brand::factory()->create();

        $subcategory = Subcategory::find($subcategoryId);
        $subcategory->category->brands()->attach($brand);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategoryId,
            'brand_id' => $brand->id,
            'quantity' => $subcategory->color ? null : 15,
            'status' => $status
        ]);

        if ($hasColor) {
            $color = $this->createColor();
            $product->colors()->attach($color, [
                'quantity' => 5
            ]);
        }

        $this->createImage($product->id, Product::class);

        return $product;
    }

}
