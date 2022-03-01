<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductFilter extends QueryFilter
{
    public function rules(): array
    {
        return [
            'category_id' => 'filled|integer|exists:categories,id',
            'subcategory_id' => 'filled|integer|exists:subcategories,id',
            'brand_id' => 'filled|integer|exists:brands,id',
            'status' => 'in:any,1,2',
            'colors' => 'array|exists:colors,id',
            'sizes' => 'array|exists:sizes,name',
            'stock' => 'integer|min:1|max:9999',
            'from' => 'date_format:Y-m-d',
            'to' => 'date_format:Y-m-d',
            'price' => 'min:1|max:200'
        ];
    }

    public function filterByCategoryId($query, $categoryId)
    {
        return $query->whereRelation('subcategory.category', 'id', $categoryId);
    }

    public function filterByStatus($query, $status)
    {
        if ($status === 'any') {
            $query->whereIn('status', [1, 2]);
        } else {
            $query->where('status', $status);
        }
    }

    public function filterByColors($query, $colors)
    {
        $query->where(function ($query) use ($colors) {
            foreach ($colors as $colorId) {
                $query->whereRelation('colors', 'color_id', $colorId)
                    ->orWhereRelation('sizes.colors', 'color_id', $colorId);
            }
        });
    }

    public function filterBySizes($query, $sizes)
    {
        $query->where(function ($query) use ($sizes) {
            foreach ($sizes as $size) {
                $query->whereRelation('sizes', 'name', $size);
            }
        });
    }

    public function filterByStock($query, $stock)
    {
        // Consulta anterior que no filtra por stock total
        /*$query->where(function ($query) use ($stock) {
            $query->where('quantity', '>=', $stock)
                ->orWhereRelation('colors', 'quantity', '>=', $stock)
                ->orWhereRelation('sizes.colors', 'quantity', '>=', $stock);
        });*/

        $query->where(function ($query) use ($stock) {
            $query->where('quantity', '>=', $stock)

                ->orWhereHas('colors', function ($query) use ($stock) {
                    $query->where(function ($query) use ($stock) {
                        $query->selectRaw('SUM(quantity)')
                            ->from('color_product AS cp2')
                            ->whereColumn('color_product.product_id', 'cp2.product_id');
                    }, '>=', $stock);
                })

                ->orWhereHas('sizes.colors', function ($query) use ($stock) {
                    $query->where(function ($query) use ($stock) {
                        $query->selectRaw('SUM(quantity)')
                            ->from('color_size AS cs2')
                            ->whereIn('cs2.size_id', function ($query) {
                                $query->select('id')
                                    ->from('sizes AS s2')
                                    ->whereColumn('s2.product_id', 'products.id');
                            });
                    }, '>=', $stock);
                });
        });
    }

    public function filterByFrom($query, $date)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);

        $query->whereDate('created_at', '>=', $date);
    }

    public function filterByTo($query, $date)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);

        $query->whereDate('created_at', '<=', $date);
    }

    public function filterByPrice($query, $price)
    {
        $query->whereBetween('price', $price);
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
