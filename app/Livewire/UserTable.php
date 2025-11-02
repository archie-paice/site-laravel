<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class UserTable extends Component
{
    private Collection $users;

    public function mount() {
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.user-table', ['users' => $this->users]);
    }
}
