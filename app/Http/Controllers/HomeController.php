<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\OnlineController;
use App\Models\SoloCert;
use DateTime;
use Date;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {

        $events = Event::where('start', '>=', now())->orderBy('start')->take(3)->get();

        return view('home', [
            'onlineSessions' => OnlineController::all(),
            'events' => $events,
            'soloCerts' => SoloCert::where('created_at', '>', new DateTime('-30 days'))->where('revoked', false)->get()
        ]);
    }
}
