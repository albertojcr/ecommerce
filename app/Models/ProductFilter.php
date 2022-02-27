<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ProductFilter extends QueryFilter
{
    public function rules(): array
    {
        return [
            'category_id' => 'filled|integer|exists:categories,id',
            'subcategory_id' => 'filled|integer|exists:subcategories,id',
            'brand_id' => 'filled|integer|exists:brands,id',
        ];
    }

    public function filterByCategoryId($query, $categoryId)
    {
        return $query->whereRelation('subcategory.category', 'id', $categoryId);
    }

    public function orderByCategory($query)
    {
        return $query->orderBy(DB::table('categories AS c')
            ->selectRaw('c.name')
            ->join('subcategories', 'c.id', '=', 'subcategories.category_id')
            ->whereColumn('subcategories.id', 'products.subcategory_id')
        );
    }

    public function orderBySubcategory($query)
    {
        return $query->orderBy(Subcategory::select('name')
            ->whereColumn('subcategories.id', 'products.subcategory_id')
        );
    }

    public function orderByBrand($query)
    {
        return $query->orderBy(Brand::select('name')
            ->whereColumn('brands.id', 'products.brand_id')
        );
    }

    public function orderByColors($query)
    {
        return $query->orderBy(Subcategory::select('name')
            ->whereColumn('subcategories.id', 'products.subcategory_id')
            ->where('color', '1'), 'DESC'
        );
    }

    public function orderBySizes($query)
    {
        return $query->orderBy(Subcategory::select('name')
            ->whereColumn('subcategories.id', 'products.subcategory_id')
            ->where('size', '1'), 'DESC'
        );
    }

}
