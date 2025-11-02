<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index() {
        $users = User::all();

        return view('users.index', ['users' => $users]);
    }


    public function show(int $id) {
        $user = User::findOrFail($id);

        return view('users.show', ['user' => $user]);
    }

    public function edit(int $id) {

    }

    public function update(int $id, User $user) {

    }
}
