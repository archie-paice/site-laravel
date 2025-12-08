<?php

namespace App\Http\Controllers;

use App\Models\OnlineController;
use App\Models\SoloCert;
use DateTime;
use Date;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {

        return view('home', [
            'onlineSessions' => OnlineController::all(),
            'soloCerts' => SoloCert::where('created_at', '>', new DateTime('-30 days'))->where('revoked', false)->get()
        ]);
    }
}
