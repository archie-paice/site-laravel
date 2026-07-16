<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OnlineController extends Component
{
    public ?User $user;

    public int $userId; // this is a fallback if user not stored in db

    public string $callsign;

    public DateTime $onlineSince;

    /**
     * Create a new component instance.
     */
    public function __construct(?User $user, int $userId, string $callsign, DateTime $onlineSince)
    {
        $this->user = $user;
        $this->userId = $userId;
        $this->callsign = $callsign;
        $this->onlineSince = $onlineSince;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.online-controller');
    }
}
