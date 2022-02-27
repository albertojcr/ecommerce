<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
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

    public $columns = ['name', 'category', 'subcategory', 'brand', 'sizes', 'colors', 'status', 'price', 'created_at'];
    public $selectedColumns = [];

    public $categories = [], $subcategories = [], $brands;

    public $filters = [
        'category_id' => '',
        'subcategory_id' => '',
        'brand_id' => '',
    ];

    public function mount()
    {
        $this->categories = Category::all();

        $this->brands = Brand::all();

        $this->selectedColumns = ['name', 'category', 'subcategory', 'brand', 'sizes', 'colors', 'status'];
    }

    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }

    public function updatedFiltersCategoryId($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();
        $this->filters['subcategory_id'] = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
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
