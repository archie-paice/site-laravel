<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\TrainingAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingAssignmentController extends Controller
{
    private readonly array $VALID_TRAINING_TYPES;

    public function __construct()
    {
        $this->VALID_TRAINING_TYPES = ['S1', 'S2', 'S3', 'C1', 'MCO GND', 'MCO TWR', 'F11 TRACON'];
    }

    public function index(Request $request) {
        $request->validate([
            'search' => 'nullable|string',
            'showInactive' => 'nullable|sometimes:on',
        ]);

        $query = TrainingAssignment::search($request->input('search'));

        if (!$request->boolean('showInactive')) {
            $query->where('active', true);
        }

        $trainingAssignments = $query->paginate(20);

        return view('training-assignment.index', compact('trainingAssignments'));
    }

    public function create(Request $request) {
        $validated = $request->validate([
            'trainingType' => 'string|required'
        ], [
            'trainingType.required' => 'Training Type must be specified.'
        ]);

        if (!in_array($validated['trainingType'], $this->VALID_TRAINING_TYPES)) {
            return redirect()->back(400)->with('error', 'Invalid Training Type');
        }

        $activeAssignments = TrainingAssignment::where([
            "user_id" => Auth::user()->id,
            "active" => true
        ])->get();

        if(count($activeAssignments) > 0) {
            return redirect()->back()->withErrors('Training already assigned');
        }

        if (!Auth::user()->rostered) {
            return redirect()->back()->withErrors( 'Not an active controller.');
        }

        TrainingAssignment::create([
            'training_type' => $validated['trainingType'],
            'user_id' => Auth::user()->id,
            'instructor_id' => null,
        ]);

        return redirect()->back()->with('success', 'Training requested successfully');
    }

    public function edit(int $id) {
        $trainingAssignment = TrainingAssignment::findOrFail($id);
        $instructors = Staff::orWhere([
            "title_short" => "INS"
        ])->orWhere([
            "title_short" => "MTR"
        ])->get();

        return view('training-assignment.edit', ["assignment" => $trainingAssignment, "instructors" => $instructors]);
    }

    public function update(Request $request, int $id) {
        $user = Auth::user();

        if (!$user->hasPermissionTo('manage students')) {
            return redirect()->back()->with('error', 'Insufficient permissions to edit training assignment');
        }

        $validated = $request->validate([
            'instructorId' => 'string|nullable',
            'active' => 'sometimes|in:on,1',
            'status' => 'int|required|min:1|max:5'
        ]);

        $trainingAssignment = TrainingAssignment::findOrFail($id);
        $trainingAssignment->instructor_id = $validated['instructorId'];
        $trainingAssignment->active = $request->boolean('active');
        $trainingAssignment->status = $validated['status'];
        $trainingAssignment->save();

        return redirect()->back()->with('success', 'Training request updated successfully');
    }

    public function claim(Request $request, int $id) {
        if (!Auth::user()->hasPermissionTo('claim students')) {
            return redirect()->back()->with('error', 'You do not have permission to claim training assignments.');
        }

        $assignment = TrainingAssignment::findOrFail($id);

        if ($assignment->user_id == Auth::user()->id) {
            return redirect()->back()->with('error', 'You cannot claim yourself.');
        }

        if (!$assignment->active) {
            return redirect()->back()->with('error', 'Cannot update inactive training assignment.');
        }

        $assignment->update([
            "instructor_id" => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Training assignment claimed successfully');
    }

    public function drop(Request $request, int $id) {
        $user = Auth::user();
        if (!$user->hasPermissionTo('claim students')) {
            return redirect()->back()->with('error', 'You do not have permission to claim training assignments.');
        }

        $assignment = TrainingAssignment::findOrFail($id);

        if (!$assignment->active) {
            return redirect()->back()->with('error', 'Cannot update inactive training assignment.');
        }

        if ($assignment->instructor_id == $user->id || $user->hasPermissionTo('manage students')) {
            $assignment->update([
                "instructor_id" => null,
            ]);
        }

        return redirect()->back()->with('success', 'Training assignment dropped successfully');
    }

    public function destroy(Request $request) {
        $user = Auth::user();
        $validated = $request->validate([
            'id' => 'string|required'
        ]);

        $assignment = TrainingAssignment::find($validated['id']);

        if (is_null($assignment)) {
            return redirect()->back(400)->with('error', 'Training assignment not found');
        }

        if ($assignment->user_id == $user->id || $user->hasPermissionTo('deactivate training assignments')) {
            $assignment->active = false;
            $assignment->status = 'withdrawn';
            $assignment->save();

            return redirect()->back()->with('success', 'Training assignment deactivated successfully');
        } else {
            return redirect()->back(400)->with('error', 'Invalid permissions');
        }
    }
}
