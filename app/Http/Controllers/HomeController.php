<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use App\Models\OnlineController;
use App\Models\SoloCert;
use DateTime;
use Date;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {

        $events = Event::where('start', '>=', now())->orderBy('start')->take(3)->get();
        $news = News::where('published_at', '<=', now())->orderBy('published_at', 'desc')->take(3)->get();

        return view('home', [
            'onlineSessions' => OnlineController::all(),
            'events' => $events,
            'news' => $news,
            'soloCerts' => SoloCert::where('created_at', '>', new DateTime('-30 days'))->where('revoked', false)->get()
        ]);
    }
}
