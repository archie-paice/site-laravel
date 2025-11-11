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

    public function index() {
        return view('training-assignment.index');
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
            "trainee_id" => Auth::user()->id,
            "active" => true
        ])->get();

        if(count($activeAssignments) > 0) {
            return redirect()->back(400)->with('error', 'Training already assigned');
        }

        if (!Auth::user()->rostered) {
            return redirect()->back(400)->with('error', 'Not an active controller.');
        }

        TrainingAssignment::create([
            'training_type' => $validated['trainingType'],
            'trainee_id' => Auth::user()->id,
            'instructor_id' => null
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

    public function update(Request $request) {

    }

    public function claim(Request $request, int $id) {
        if (!Auth::user()->hasPermissionTo('claim students')) {
            return redirect()->back()->with('error', 'You do not have permission to claim training assignments.');
        }

        $assignment = TrainingAssignment::findOrFail($id);

        if ($assignment->trainee_id == Auth::user()->id) {
            return redirect()->back()->with('error', 'You cannot claim yourself.');
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

        if ($assignment->trainee_id == $user->id || $user->hasPermissionTo('deactivate training assignments')) {
            $assignment->active = false;
            $assignment->save();

            return redirect()->back()->with('success', 'Training assignment deactivated successfully');
        } else {
            return redirect()->back(400)->with('error', 'Invalid permissions');
        }
    }
}
