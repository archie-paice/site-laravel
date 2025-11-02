<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;
    public string $search = '';
    public string $sortField = 'last_name';
    public string $sortDirection = 'asc';

    public function render()
    {
        $users = User::query()
        ->orWhere('first_name', 'ilike', "%$this->search%")
        ->orWhere('last_name', 'ilike', "%$this->search%")
        ->orWhere('id', 'ilike', "%$this->search%")
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate(25);
        
        return view('livewire.user-table', ['users' => $users]);
    }
}
