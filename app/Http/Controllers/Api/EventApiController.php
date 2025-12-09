<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    public function index()
    {
        // For mobile: only upcoming+ongoing
        $events = Event::orderBy('starts_at', 'asc')
            ->get()
            ->map(function ($event) {
                return [
                    'id'        => $event->id,
                    'name'      => $event->name,
                    'location'  => $event->location,
                    'starts_at' => $event->starts_at->toIso8601String(),
                    'ends_at'   => optional($event->ends_at)->toIso8601String(),
                    'status'    => $event->status, // accessor we made earlier
                    'description' => $event->description,
                ];
            });

        return response()->json($events);
    }

    public function show(Event $event)
    {
        return response()->json([
            'id'        => $event->id,
            'name'      => $event->name,
            'location'  => $event->location,
            'starts_at' => $event->starts_at->toIso8601String(),
            'ends_at'   => optional($event->ends_at)->toIso8601String(),
            'status'    => $event->status,
            'description' => $event->description,
        ]);
    }
}
