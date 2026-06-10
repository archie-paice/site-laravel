<?php

namespace App\Http\Controllers;

use App\Models\AtcBooking;
use Illuminate\Http\Request;

class AtcBookingController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'position' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after:start'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        AtcBooking::create([
            'user_id' => $request->user()->id,
            'position' => strtoupper($validated['position']),
            'start' => $validated['start'],
            'end' => $validated['end'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('home')->with('success', 'Booking created!');
    }
}
