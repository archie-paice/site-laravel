<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Enums\EventType;
use App\Models\FeaturedField;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('admin.events.index', ['events' => $events]);
    }

    public function create()
    {
        $event = new Event();
        $types = EventType::cases();
        $featuredFields = FeaturedField::orderBy('name')->pluck('name');

        return view('admin.events.create', [
            'types' => $types,
            'featuredFields' => $featuredFields,
        ]);
    }

    public function store(Request $request)
    {
        $featuredFields = FeaturedField::pluck('name')->toArray();

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'type' => [new Enum(EventType::class)],
            'featured_fields' => ['array'],
            'featured_fields.*' => ['string', Rule::in($featuredFields)],
        ]);

        $event = Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start' => $validated['start'],
            'end' => $validated['end'],
            'type' => $validated['type'],
            'featured_fields' => $validated['featured_fields'] ?? [],
        ]);


        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }


    public function show(string $id)
    {
        $event = Event::findOrFail($id);

        return view('admin.events.show', ['event' => $event]);
    }

    public function edit($id)
    {
        $event = Event::find($id);
        $types = EventType::cases();
        $featuredFields = FeaturedField::orderBy('name')->pluck('name');
        
        return view('admin.events.edit', ['event' => $event, 'types' => $types, 'featuredFields' => $featuredFields]);
    }

    public function update(Request $request, $id)
    {
        $featuredFields = FeaturedField::pluck('name')->toArray();

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'type' => [new Enum(EventType::class)],
            'featured_fields' => ['array'],
            'featured_fields.*' => ['string', Rule::in($featuredFields)],
        ]);

        $event = Event::find($id);
        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Post deleted successfully');
    }


    // TODO

    // public function edit($id) {

    // }
}
