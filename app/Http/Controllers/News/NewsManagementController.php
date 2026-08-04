<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NewsManagementController extends Controller
{
    public function index()
    {
        $news = News::all();

        return view('news.admin', ['news' => $news]);
    }

    public function create()
    {
        $news = new News;

        return view('news.create', ['news' => $news]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required | string',
            'content' => 'required | string | max:500',
        ]);

        News::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'date' => Carbon::now(),
        ]);

    }
}
