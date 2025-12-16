<?php

namespace App\View\Components;

use App\Models\SoloCert;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SoloCertCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public SoloCert $soloCert)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.solo-cert-card');
    }
}
