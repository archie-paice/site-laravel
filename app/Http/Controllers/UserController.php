<?php

namespace App\Http\Controllers;

use App\Models\TrainingAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Log;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware() {
        return [
            new Middleware('permission:manage users', only: ['edit', 'update'])
        ];
    }

    public function show(int $id) {
        $user = User::findOrFail($id);
        $soloCerts = $user->soloCerts()->paginate(10, ['*'], 'soloCerts');

        return view('users.show', [
            'user' => $user,
            'soloCerts' => $soloCerts ?? collect()
        ]);
    }

    public function edit(Request $request, int $id) {
        $user = User::findOrFail($id);

        return view('users.edit', ['user'=> $user]);
    }

    public function update(Request $request) {
        $validated = $request->validate([
            'id' => 'required|integer',
            'operatingInitials' => 'string|nullable|size:2', // can only be edited if admin
            'image' => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
            'biography' => 'string|nullable|max:1000'
        ], [
            'operatingInitials.max' => 'Operating initials must be 2 characters long'
        ]);

        if (Auth::user()->id != $validated['id'] && !Auth::user()->hasPermissionTo('manage users')) {
            return redirect()->back()->with('error', 'You do not have permission to edit this user.');
        }
        $oiCount = User::where('operating_initials', strtoupper($validated['operatingInitials']))->where('id', '!=', $validated['id'])->count();

        if ($oiCount > 0) {
            return redirect()->back()->with('error', 'OIs already assigned.');
        }

        $user = User::findOrFail($validated['id']);

        if ($request->hasFile('image')) {
            $imageName = 'profile_'.$user->id.'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/profiles'), $imageName);
            $user->profile_image_route = 'images/profiles/'.$imageName;
        }

        $user->biography = $validated['biography'] ?? null;

        if (Auth::user()->hasPermissionTo('manage users')) {
            $user->operating_initials = strtoupper($validated['operatingInitials'] ?? $user->operating_initials);
        }

        $user->save();

        return redirect()->route('users.edit', ['user' => $user->id])->with('success', 'User updated successfully');
    }

    public function trainingAssignments(int $id) {
        $user = User::findOrFail($id);

        $trainingAssignments = $user->trainingAssignmentsAsStudent()->paginate(25, ['*'], 'assignmentsPage');

        return view('users.training-assignments', [
            'user' => $user,
            'trainingAssignments' => $trainingAssignments
        ]);
    }

    public function trainingTickets(int $id) {
        $user = User::findOrFail($id);

        $trainingTickets = $user->trainingTicketsAsStudent()->paginate(25, ['*'], 'ticketsPage');

        return view('users.training-tickets', [
            'user' => $user,
            'trainingTickets' => $trainingTickets
        ]);
    }

    public function soloCerts(int $id) {
        $user = User::findOrFail($id);

        $soloCerts = $user->soloCerts()->paginate(25, ['*'], 'soloCertsPage');

        return view('users.solo-certs', [
            'user' => $user,
            'soloCerts' => $soloCerts
        ]);
    }
}
