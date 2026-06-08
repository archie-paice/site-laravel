<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('published_at', '<=', now())->orderBy('published_at', 'desc')->take(3)->get();
        return view ('news.admin.index', ['news' => $news]);
    }

    public function create() {
        return view ('news.admin.create');
    }

    public function show(string $id) {
        $news = News::findOrFail($id);

        return view('news.show', ['news' => $news]);
    }

    public function store(Request $request) {
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
