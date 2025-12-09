<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\Attendance;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of events
     */
    public function index(Request $request)
    {
        Event::updateEventStatuses();

        $query = Event::query();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        $events = $query->orderBy('date', 'desc')->paginate(12);

        return view('events.index', compact('events'));
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'target_participants' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1',
            'registration_deadline' => 'nullable|date',
            'status' => 'nullable|in:Upcoming,Ongoing,Completed',
        ]);

        $validated['status'] = $validated['status'] ?? 'Upcoming';

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $event->load('attendances.member');
        $members = Member::active()->get();
        
        return view('events.show', compact('event', 'members'));
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'target_participants' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1',
            'status' => 'nullable|in:Upcoming,Ongoing,Completed',
        ]);

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Mark attendance for event
     */
    public function markAttendance(Request $request, Event $event)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'status' => 'required|in:Present,Absent,Excused,Late',
            'remarks' => 'nullable|string',
        ]);

        Attendance::updateOrCreate(
            [
                'event_id' => $event->id,
                'member_id' => $validated['member_id'],
            ],
            [
                'status' => $validated['status'],
                'check_in_time' => now(),
                'remarks' => $validated['remarks'] ?? null,
            ]
        );

        return redirect()->back()
            ->with('success', 'Attendance marked successfully!');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
