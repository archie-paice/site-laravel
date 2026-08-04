<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;

class EventFieldController extends Controller
{
    public function index()
    {
        return view('event-fields.index');
    }
}
