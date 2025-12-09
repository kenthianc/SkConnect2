<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
{
    public function checkIn(Request $request, Event $event)
    {
        $data = $request->validate([
            'membership_id' => ['required', 'string'],
        ]);

        $member = Member::where('membership_id', $data['membership_id'])->first();

        if (! $member) {
            return response()->json([
                'message' => 'Member not found',
            ], 404);
        }

        $attendance = Attendance::firstOrCreate(
            [
                'member_id' => $member->id,
                'event_id'  => $event->id,
            ],
            [
                'status'        => 'present',
                'check_in_time' => now(),
            ]
        );

        return response()->json([
            'message'  => 'Checked in successfully',
            'member'   => [
                'id'            => $member->id,
                'membership_id' => $member->membership_id,
                'full_name'     => $member->full_name,
            ],
            'event_id' => $event->id,
            'status'   => $attendance->status,
            'check_in_time' => $attendance->check_in_time->toIso8601String(),
        ]);
    }
}
