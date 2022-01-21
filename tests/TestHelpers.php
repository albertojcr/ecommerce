<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
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
            'color' => $hasColor,
            'category_id' => $categoryId
        ]);
    }

    protected function createColor()
    {
        return Color::create([
            'name' => Factory::create()->colorName()
        ]);
    }

    protected function createProduct($hasColor = false, $status = Product::PUBLICADO)
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category->id, $hasColor);
        $brand = Brand::factory()->create();
        $category->brands()->attach($brand);
        //$name = Factory::create()->sentence(2); // Duda

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
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

        return $product;
    }
}
