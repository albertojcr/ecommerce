<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductFilter;
use App\Models\Size;
use App\Models\Subcategory;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts extends Component
{
    use WithPagination;

    public $search, $rowsPerPage = 10, $fieldToOrder;

    public $columns = ['name', 'category', 'subcategory', 'brand', 'sizes', 'colors', 'stock', 'status', 'price', 'created_at'];
    public $selectedColumns = [];

    public $categories, $subcategories, $brands, $colors, $sizes;

    public $filters = [
        'category_id' => '',
        'subcategory_id' => '',
        'brand_id' => '',
        'status',
        'colors' => [],
        'sizes' => [],
        'stock',
        'from' => null,
        'to' => null,
        'price',
    ];

    public function mount()
    {
        $this->categories = Category::all();

        $this->subcategories = Subcategory::all();

        $this->brands = Brand::all();

        $this->colors = Color::all();

        $this->sizes = Size::selectRaw('DISTINCT name')->pluck('name');

        $this->selectedColumns = ['name', 'category', 'subcategory', 'brand', 'sizes', 'colors', 'stock', 'status'];
    }

    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }

    public function clearFilters()
    {
        $this->reset('filters');
    }

    public function render(ProductFilter $productFilter)
    {
        $products = Product::where('name', 'LIKE', "%{$this->search}%")
            ->filterBy($productFilter, $this->filters)
            ->when($this->fieldToOrder, function ($query) use ($productFilter) {
                $query->orderByField($productFilter, $this->fieldToOrder);
            })
            ->paginate($this->rowsPerPage);

        return view('livewire.admin.show-products', compact('products'))->layout('layouts.admin');
    }
}
