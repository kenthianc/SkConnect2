<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AttendanceController extends Controller
{
    /**
     * Display attendance management
     */
    public function index(Request $request)
    {
        $events = Event::orderBy('date', 'desc')->get();
        
        $selectedEventId = $request->get('event_id');
        $attendance = [];
        $selectedEvent = null;

        if ($selectedEventId) {
            $selectedEvent = Event::findOrFail($selectedEventId);
            $attendance = Attendance::byEvent($selectedEventId)
                ->with('member')
                ->get();
        }

        return view('attendance.index', compact('events', 'attendance', 'selectedEvent'));
    }

    /**
     * Generate attendance report
     */
    public function report(Request $request)
    {
        $query = Attendance::query()->with(['member', 'event']);

        // Filter by event
        if ($request->has('event_id') && $request->event_id) {
            $query->byEvent($request->event_id);
        }

        // Filter by member
        if ($request->has('member_id') && $request->member_id) {
            $query->byMember($request->member_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $attendance = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('attendance.report', compact('attendance'));
    }

    /**
     * Get member attendance summary
     */
    public function memberSummary(Member $member)
    {
        $attendance = $member->attendance()
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $attendance->count(),
            'present' => $attendance->where('status', 'Present')->count(),
            'absent' => $attendance->where('status', 'Absent')->count(),
            'excused' => $attendance->where('status', 'Excused')->count(),
            'late' => $attendance->where('status', 'Late')->count(),
            'rate' => $member->attendance_rate,
        ];

        return view('attendance.member-summary', compact('member', 'attendance', 'stats'));
    }

    public function recordAttendance(Request $request, Event $event)
    {
        // 1) Validate input
        $validated = $request->validate([
            'member_id' => ['required', 'string'],
        ]);

        // 2) Find member using their public member_id (e.g. "25-00001")
        $member = Member::where('member_id', $validated['member_id'])->first();

        if (!$member) {
            return back()
                ->withErrors(['member_id' => 'Member ID not found.'])
                ->withInput();
        }

        // 3) Check if already marked present for this event
        $existing = Attendance::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'This member is already marked present for this event.');
        }

        // 4) Create attendance record
        $attendance = Attendance::create([
            'event_id'      => $event->id,
            'member_id'     => $member->id,
            'status'        => 'Present',
            'check_in_time' => now(),
        ]);

        // 5) Send data to n8n webhook for automatic e-cert sending
        $response = Http::post('http://localhost:5678/webhook-test/send-certificate', [
            'member_id' => $member->member_id,
            'name' => trim(implode(' ', array_filter([$member->first_name ?? '', $member->middle_name ?? '', $member->last_name ?? '']))), // Adjust if you use a different attribute for the full name
            'email' => $member->email,
            'event' => $event->title,
            'date' => $event->date->toDateString(),
        ]);

        // 6) Check if the request to n8n was successful
        if ($response->successful()) {
            return back()->with('success', "Attendance recorded for {$member->last_name} and e-certificate sent.");
        }

        return back()->with('error', 'Attendance recorded, but failed to send e-certificate.');
    }
}
