<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatisticsPrefixes;
use Illuminate\Http\Request;
use Log;

class StatisticsPrefixesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.statistics-prefixes.index', ['prefixes' => StatisticsPrefixes::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:5|min:2'
        ]);

        StatisticsPrefixes::findOrNew([
            'name' => $validated['name']
        ]);

        Log::info('Statistics prefix {prefix} added by user {user}', ['prefix' => $validated['name'], 'user' => auth()->user()->id]);

        return redirect()->back()->with('success', 'Prefix added successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        StatisticsPrefixes::destroy($id);

        Log::info('Statistics prefix {prefix} deleted by user {user}', ['prefix' => $id, 'user' => auth()->user()->id]);

        return redirect()->back()->with('success', 'Prefix removed successfully');
    }
}
