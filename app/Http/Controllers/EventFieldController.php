<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventFieldController extends Controller
{

    public function index()
    {
        return view('admin.events.event-field.index');
    }
}
