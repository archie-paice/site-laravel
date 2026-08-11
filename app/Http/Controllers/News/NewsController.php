<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        // The management list shows every announcement, including ones scheduled
        // ahead of now — unlike the homepage, which only surfaces published ones.
        $news = News::orderBy('published_at', 'desc')->paginate(25);

        return view('news.admin.index', ['news' => $news]);
    }

    public function create()
    {
        return view('news.admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string|max:250',
        ]);

        $news = new News([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'published_at' => now(),
        ]);

        $news->save();

        return redirect()->route('home');

    }
}
