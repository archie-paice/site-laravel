<?php

namespace App\View\Components;

use Closure;
use DateInterval;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfileStatisticsTimeLabel extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $label, public DateInterval $timeInterval)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.profile-statistics-time-label');
    }
}
