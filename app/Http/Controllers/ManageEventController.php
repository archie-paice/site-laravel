<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Enums\EventType;
use App\Models\FeaturedField;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class ManageEventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('manage-events.index', ['events' => $events]);
    }

    public function create()
    {
        $event = new Event();
        $types = EventType::cases();
        $featuredFields = FeaturedField::orderBy('name')->pluck('name');

        return view('manage-events.create', [
            'types' => $types,
            'featuredFields' => $featuredFields,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'type' => [new Enum(EventType::class)],
            'featured_fields' => 'required|string',
        ]);

        // for validated:
         //   'featured_fields' => ['array'],
          //  'featured_fields.*' => ['string', Rule::in($featuredFields)],

        $featuredFields = explode(', ', $validated['featured_fields']);
        $featuredFields = array_map('trim', $featuredFields);

        $event = Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start' => $validated['start'],
            'end' => $validated['end'],
            'type' => $validated['type'],
            'featured_fields' => $featuredFields,
        ]);


        return redirect()->route('manage-events.index')->with('success', 'Event created successfully!');
    }


    public function show(string $id)
    {
        $event = Event::findOrFail($id);

        return view('manage-events.show', ['event' => $event]);
    }

    public function edit($id)
    {
        $event = Event::find($id);
        $types = EventType::cases();
        $featuredFields = FeaturedField::orderBy('name')->pluck('name');
        
        return view('manage-events.edit', ['event' => $event, 'types' => $types, 'featuredFields' => $featuredFields]);
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

        return redirect()->route('manage-events.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();
        return redirect()->route('manage-events.index')->with('success', 'Post deleted successfully');
    }
}
