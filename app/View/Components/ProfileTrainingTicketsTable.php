<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfileTrainingTicketsTable extends Component
{
    public mixed $trainingTickets;

    /**
     * Create a new component instance.
     */
    public function __construct(mixed $trainingTickets)
    {
        $this->trainingTickets = $trainingTickets;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.profile-training-tickets-table');
    }
}
