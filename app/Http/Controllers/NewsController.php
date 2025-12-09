<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of news
     */
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->paginate(10);

        return view('news.index', compact('news'));
    }

    /**
     * Store a newly created news item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:EVENT,ANNOUNCEMENT,UPDATE,REMINDER',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validated['author'] = Auth::user()->name ?? 'Admin User';
        $validated['published_at'] = now();
        $validated['notify_members'] = true;

        News::create($validated);

        // TODO: Send notifications to all members (email/SMS)
        // You can implement this using Laravel Notifications

        return redirect()->route('news.index')
            ->with('success', 'Announcement posted and all members have been notified!');
    }

    /**
     * Display the specified news item
     */
    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified news item
     */
    public function edit(News $news)
    {
        return view('news.edit', compact('news'));
    }

    /**
     * Update the specified news item
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'type' => 'required|in:EVENT,ANNOUNCEMENT,UPDATE,REMINDER',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $news->update($validated);

        return redirect()->route('news.index')
            ->with('success', 'News updated successfully!');
    }

    /**
     * Remove the specified news item
     */
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'News deleted successfully!');
    }
}
