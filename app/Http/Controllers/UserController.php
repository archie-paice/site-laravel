<?php

namespace App\Http\Controllers;

use App\Enums\LoaStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(int $id)
    {
        $user = User::findOrFail($id);

        return view('users.show', [
            'user' => $user,
        ]);
    }

    public function edit(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->id != $user->id && ! $authenticatedUser->hasPermissionTo('manage users')) {
            return response('Unauthorized', 403);
        }

        return view('users.edit', ['user' => $user]);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'operatingInitials' => 'string|nullable|size:2', // can only be edited if admin
            'image' => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
            'biography' => 'string|nullable|max:1000',
        ], [
            'operatingInitials.max' => 'Operating initials must be 2 characters long',
        ]);

        if (Auth::user()->id != $id && ! Auth::user()->hasPermissionTo('manage users')) {
            return response('Unauthorized', 403);
        }

        $user = User::findOrFail($id);

        if ($request->hasFile('image')) {
            $imageName = 'profile_'.$user->id.'.'.$request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('profile', $imageName, 'public');

            $user->profile_image_route = 'storage/'.$path;
        }

        $user->biography = $validated['biography'] ?? null;

        if (Auth::user()->hasPermissionTo('manage users') && isset($validated['operatingInitials'])) {
            $oiCount = User::where('operating_initials', strtoupper($validated['operatingInitials']))->where('id', '!=', $id)->count();

            if ($oiCount > 0) {
                return redirect()->back()->with('error', 'OIs already assigned.');
            }

            $user->operating_initials = strtoupper($validated['operatingInitials'] ?? $user->operating_initials);
        }

        $user->save();

        return redirect()->route('users.edit', ['user' => $user->id])->with('success', 'User updated successfully');
    }

    public function trainingAssignments(int $id)
    {
        $user = User::findOrFail($id);

        if (! $user->rostered) {
            return redirect()->back()->with('error', 'Training assignments are only available for rostered users.');
        }

        $trainingAssignments = $user->trainingAssignmentsAsStudent()->paginate(25, ['*'], 'assignmentsPage');

        return view('users.training-assignments', [
            'user' => $user,
            'trainingAssignments' => $trainingAssignments,
        ]);
    }

    public function trainingTickets(int $id)
    {
        $user = User::findOrFail($id);

        if (! $user->rostered) {
            return redirect()->back()->with('error', 'Training tickets are only available for rostered users.');
        }

        $trainingTickets = $user->trainingTicketsAsStudent()->paginate(25, ['*'], 'ticketsPage');

        return view('users.training-tickets', [
            'user' => $user,
            'trainingTickets' => $trainingTickets,
        ]);
    }

    public function loa(int $id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->id != $user->id) {
            return response('Unauthorized', 403);
        }

        $activeLoa = $user->loas()->where('status', '!=', LoaStatus::INACTIVE)->first();
        $loaHistory = $user->loas()->where('status', LoaStatus::INACTIVE)->paginate(25, ['*'], 'loaPage');

        return view('users.loa', [
            'user' => $user,
            'activeLoa' => $activeLoa,
            'loaHistory' => $loaHistory,
        ]);
    }

    public function soloCerts(int $id)
    {
        $user = User::findOrFail($id);
        if (! $user->rostered) {
            return redirect()->back()->with('error', 'Solo certifications are only available for rostered users.');
        }

        $soloCerts = $user->soloCerts()->paginate(25, ['*'], 'soloCertsPage');

        return view('users.solo-certs', [
            'user' => $user,
            'soloCerts' => $soloCerts,
        ]);
    }
}
