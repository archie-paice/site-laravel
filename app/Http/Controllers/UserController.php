<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    
    public function show(int $id) {
        $user = User::findOrFail($id);

        return view('users.show', ['user' => $user]);
    }
}
