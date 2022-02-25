<?php

namespace App\Http\Livewire\Admin;

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

    public $search, $shownSize, $fieldToOrder;

    public $categories = [], $subcategories = [];

    public $category_id = '', $subcategory_id = '';

    public $open = false, $openFilters = false;

    public $columns = ['name', 'category', 'subcategory', 'brand', 'sizes', 'colors', 'status', 'price', 'created_at'];
    public $selectedColumns = [];

    public $filters = [
        'rowsToShow' => '10'
    ];

    public function mount()
    {
        $this->categories = Category::all();

        $this->selectedColumns = $this->columns;
    }

    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }

    public function updatedCategoryId($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();
        $this->subcategory_id = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showSizeInfo(Size $size)
    {
        $this->open = true;

        $this->shownSize = $size;
    }

    public function render(ProductFilter $productFilter)
    {
        $products = Product::where('name', 'LIKE', "%{$this->search}%")
            ->when($this->fieldToOrder, function ($query) use ($productFilter) {
                $query->orderByField($productFilter, $this->fieldToOrder);
            })
            ->paginate($this->filters['rowsToShow']);

        return view('livewire.admin.show-products', compact('products'))->layout('layouts.admin');
    }
}
