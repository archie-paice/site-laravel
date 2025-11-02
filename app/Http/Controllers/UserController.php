<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

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

    public function edit(int $id) {
        $user = User::findOrFail($id);
        
        return view('users.edit', ['user'=> $user]);
    }

    public function update(int $id, User $user) {

    }
}
