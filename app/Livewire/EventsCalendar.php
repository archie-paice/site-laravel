<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Collection;
use App\Models\Event;

class EventsCalendar extends Component
{
    public int $currentYear;
    public int $currentMonth;

    public function mount(): void
    {
        $this->currentYear = now()->year;
        $this->currentMonth = now()->month;
    }

    public function previousMonth(): void
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentYear = $date->year;
        $this->currentMonth = $date->month;
    }

    public function nextMonth(): void
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentYear = $date->year;
        $this->currentMonth = $date->month;
    }

    public function goToToday(): void
    {
        $this->currentYear = now()->year;
        $this->currentMonth = now()->month;
    }

    /**
     * The visible calendar grid spans from the Sunday on/before the 1st of the
     * month to the Saturday on/after the last day, so it includes trailing days
     * from the adjacent months.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function gridBounds(): array
    {
        $start = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $end = $start->copy()->endOfMonth();

        $gridStart = $start->copy()->startOfWeek(Carbon::SUNDAY);
        $gridEnd = $end->copy()->endOfWeek(Carbon::SATURDAY);

        return [$gridStart, $gridEnd];
    }

    private function monthGrid(): Collection
    {
        [$gridStart, $gridEnd] = $this->gridBounds();

        $days = collect();
        $current = $gridStart->copy();

        while ($current->lte($gridEnd)) {
            $days->push($current->copy());
            $current->addDay();
        }

        return $days->chunk(7);
    }

    private function events(): Collection
    {
        [$gridStart, $gridEnd] = $this->gridBounds();

        return Event::where('hidden', false)
            ->whereBetween('start', [$gridStart, $gridEnd])
            ->orderBy('start')
            ->get();
    }

    public function render()
    {
        $monthStart = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $events = $this->events();

        $eventsGrouped = $events->groupBy(fn ($e) => $e->start->format('Y-m-d'));

        return view('livewire.events-calendar', [
            'monthLabel' => $monthStart->format('F Y'),
            'monthGrid' => $this->monthGrid(),
            'events' => $events,
            'eventsGrouped' => $eventsGrouped,
            'monthStart' => $monthStart,
            'isCurrentMonth' => now()->month === $this->currentMonth && now()->year === $this->currentYear,
        ]);
    }
}
