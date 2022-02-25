<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ProductFilter extends QueryFilter
{
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

}
