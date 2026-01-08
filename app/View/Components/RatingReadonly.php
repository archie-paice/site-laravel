<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RatingReadonly extends Component
{
    public int $rating;
    /**
     * Create a new component instance.
     */
    public function __construct(int $rating)
    {
        $this->rating = $rating;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.rating-readonly');
    }
}
