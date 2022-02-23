<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts extends Component
{
    use WithPagination;

    public $search, $shownSize;

    public $categories = [], $subcategories = [];

    public $category_id = '', $subcategory_id = '';

    public $open = false, $openFilters = false;

    public $filters = [
        'rowsToShow' => '10'
    ];

    public function mount()
    {
        $this->categories = Category::all();
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

    public function render()
    {
        $products = Product::where('name', 'LIKE', "%{$this->search}%")
            ->paginate($this->filters['rowsToShow']);

        return view('livewire.admin.show-products', compact('products'))->layout('layouts.admin');
    }
}
