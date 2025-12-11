<?php
// app/Http/Controllers/Api/EventApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventApiController extends Controller
{
    public function index()
    {
        // Just order by created_at for now
        $events = Event::orderBy('created_at', 'desc')->get();

        return response()->json($events);
    }

    public function show(Event $event)
    {
        return response()->json($event);
    }
}
