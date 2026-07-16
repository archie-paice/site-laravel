<?php

namespace App\Livewire;

use App\Enums\EventType;
use Livewire\Component;

class CreateEvent extends Component
{
    public $name = '';

    public string $type;

    public array $types;

    public bool $hidden;

    public bool $positionsLocked;

    public bool $manualPositionsOpen;

    public array $featuredFieldsOptions = [
        'KMCO',
        'KJAX',
        'KDAB',
    ];

    public array $featuredFields = [];

    public $archived;

    public $eventStart;

    public $eventEnd;

    public function mount()
    {
        $this->types = array_column(EventType::cases(), 'value');
    }

    public function save() {}

    public function render()
    {
        return view('livewire.create-event');
    }
}
