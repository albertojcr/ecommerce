<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class CreateUser extends Component
{
    public $name, $email, $email_confirmation, $password, $password_confirmation, $isAdmin = false;

    public $rules = [
        'name' => 'required|string|max:25',
        'email' => 'required|email|confirmed|unique:users,email',
        'password' => 'required|confirmed',
        'isAdmin' => 'required|bool'
    ];

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password)
        ]);

        if ($this->isAdmin) {
            $user->assignRole('admin');
        }

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.create-user')->layout('layouts.admin');
    }
}
