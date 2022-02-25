<?php

namespace App\Models;

class ProductFilter extends QueryFilter
{
    public function orderBySubcategory($query)
    {
        return $query->orderBy(Subcategory::select('name')
            ->whereColumn('subcategories.id', 'products.subcategory_id')
        )->get();
    }
}
