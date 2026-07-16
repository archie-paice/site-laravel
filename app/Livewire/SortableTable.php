<?php

namespace App\Livewire;

use Livewire\Component;

class SortableTable extends Component
{
    public string $search;

    public string $sortField;

    public string $sortDirection;
}
