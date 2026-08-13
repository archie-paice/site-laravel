<?php

namespace App\Http\Controllers;

use App\Models\StaffingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StaffingRequestController extends Controller
{
    public function index()
    {
        return view('staffing-requests.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
        ]);

        $staffingRequest = StaffingRequest::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        Log::info('New staffing request submitted by '.$request->user()->id.': '.$staffingRequest->name);

        return redirect()->route('staffing-requests.index')->with('success', 'Thank you! Your staffing request has been submitted. Our events team will follow up by email.');
    }

    public function manage(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $staffingRequests = StaffingRequest::search($request->input('search'))->orderBy('created_at', 'desc')->paginate(25);

        return view('manage-staffing-requests.index', ['staffingRequests' => $staffingRequests]);
    }

    public function show(StaffingRequest $staffingRequest)
    {
        $staffingRequest->load('user');

        return view('manage-staffing-requests.show', ['staffingRequest' => $staffingRequest]);
    }

    public function destroy(StaffingRequest $staffingRequest)
    {
        $name = $staffingRequest->name;
        $staffingRequest->delete();

        Log::info('Staffing request "'.$name.'" closed.');

        return redirect()->route('admin.staffing-requests.index')->with('success', 'Staffing request closed.');
    }
}
