<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsApiController extends Controller
{
    /**
     * Return list of news for mobile app (FlutterFlow)
     */
    public function index()
    {
        // Get latest news first
        $news = News::orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Return as JSON (FlutterFlow will consume this)
        return response()->json($news);
    }

    /**
     * Optional: show single news item by id
     */
    public function show(News $news)
    {
        return response()->json($news);
    }
}
