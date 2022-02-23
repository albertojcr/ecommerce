<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use App\Models\Size;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts extends Component
{
    use WithPagination;

    public $search, $shownSize;

    public $open = false;

    public $filters = [
        'rowsToShow' => '10'
    ];

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
