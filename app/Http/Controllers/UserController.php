<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Log;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware() {
        return [
            new Middleware('permission:manage users', only: ['edit', 'update'])
        ];
    }

    public function index() {
        $users = User::all();

        return view('users.index', ['users' => $users]);
    }


    public function show(int $id) {
        $user = User::findOrFail($id);

        return view('users.show', ['user' => $user]);
    }

    public function edit(Request $request, int $id) {
        $user = User::findOrFail($id);
        
        return view('users.edit', ['user'=> $user]);
    }

    public function update(Request $request) {
        $validated = $request->validate([
            'id' => 'required|integer',
            'operatingInitials' => 'string|nullable|size:2'
        ], [
            'operatingInitials.max' => 'Operating initials must be 2 characters long'
        ]);

        $user = User::findOrFail($validated['id']);

        $user->update([
            'operating_initials' => $validated['operatingInitials']
        ]);

        Log::info('User {id} updated by user {id2}', [
            'id' => $user->id,
            'id2' => auth()->user()->id
        ]);
        return redirect()->route('users.edit', ['user' => $user->id])->with('success', 'User updated successfully');
    }
}
