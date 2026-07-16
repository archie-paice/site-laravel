<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('user-management.index', ['users' => $users]);
    }
}
