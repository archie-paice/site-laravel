<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('date', '<=', now())->orderBy('date', 'desc')->take(3)->get();
        return view ('news.index', ['news' => $news]);
    }

    public function show(string $id) {
        $news = News::findOrFail($id);

        return view('news.show', ['news' => $news]);
    }
}
