<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Nette\NotImplementedException;

class RosterController extends Controller {
    public function index() {
        $users = User::where('rostered', true)->orderBy('last_name')->get();
        return view('roster.index', ['users' => $users]);
    }
}