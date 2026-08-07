<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\FeaturedField;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventFieldController extends Controller
{
    public function index()
    {
        return view('event-fields.index', [
            'featuredFields' => FeaturedField::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('featured_fields', 'name')],
        ]);

        FeaturedField::create(['name' => strtoupper(trim($validated['name']))]);

        return redirect()->route('admin.events.event-fields.index')
            ->with('success', 'Event field added.');
    }

    public function destroy(FeaturedField $eventField)
    {
        // Detaching first would silently drop the field from past events, so refuse
        // instead and let the operator decide.
        if ($eventField->events()->exists()) {
            return back()->with('error', 'This field is still attached to an event and cannot be removed.');
        }

        $eventField->delete();

        return redirect()->route('admin.events.event-fields.index')
            ->with('success', 'Event field removed.');
    }
}
