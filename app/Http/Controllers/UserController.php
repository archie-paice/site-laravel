<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Nette\NotImplementedException;

class UserController extends Controller
{
    public function show(int $id) {
        $user = User::findOrFail($id);

        throw new NotImplementedException("Profile page not implemented yet.");
    }
}
