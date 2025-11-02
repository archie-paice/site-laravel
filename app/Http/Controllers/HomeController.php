<?php

namespace App\Http\Controllers;

use App\Models\OnlineController;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        return view('home', ['onlineSessions' => OnlineController::all()]);
    }
}
