<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use Livewire\Component;

class ColorComponent extends Component
{
    public $colors;
    public $color;

    protected $listeners = ['delete'];

    public $createForm = [
        'name' => ''
    ];

    public $editForm = [
        'open' => false,
        'name' => ''
    ];

    public $rules = [
        'createForm.name' => 'required|unique:colors,name'
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre'
    ];

    public function mount()
    {
        $this->getColors();
    }

    public function getColors()
    {
        $this->colors = Color::all();
    }

    public function save()
    {
        $this->validate();

        Color::create($this->createForm);

        $this->reset('createForm');

        $this->getColors();

        $this->emit('saved');
    }

    public function edit(Color $color)
    {
        $this->color = $color;

        $this->resetValidation();

        $this->editForm['open'] = true;
        $this->editForm['name'] = $color->name;
    }

    public function update()
    {
        $this->validate([
            "editForm.name" => 'required|unique:colors,name,' . $this->color->id
        ]);

        $this->color->name = $this->editForm['name'];
        $this->color->save();

        $this->reset('editForm');

        $this->getColors();
    }

    public function delete(Color $color)
    {
        $color->delete();
        $this->getColors();
    }

    public function render()
    {
        return view('livewire.admin.color-component')->layout('layouts.admin');
    }
}
