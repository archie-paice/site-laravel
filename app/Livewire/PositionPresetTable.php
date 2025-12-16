<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use App\Models\EventPositionPreset;

class PositionPresetTable extends Component
{
    private Collection $positions;

    public function mount() {
        $this->positions = EventPositionPreset::all();
    }

    public function render()
    {
        return view('livewire.position-preset-table', ['positions' => $this->positions]);
    }
}
