<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Attendance;

class MemberApiController extends Controller
{
    public function showByMembershipId(string $membership_id)
    {
        $member = Member::where('membership_id', $membership_id)->first();

        if (! $member) {
            return response()->json([
                'message' => 'Member not found',
            ], 404);
        }

        // Example simple stats
        $totalEventsAttended = Attendance::where('member_id', $member->id)->count();

        return response()->json([
            'id'              => $member->id,
            'membership_id'   => $member->membership_id,
            'first_name'      => $member->first_name,
            'last_name'       => $member->last_name,
            'full_name'       => $member->full_name,
            'gender'          => $member->gender,
            'date_of_birth'   => optional($member->date_of_birth)->toDateString(),
            'purok'           => $member->purok,
            'email'           => $member->email,
            'profile_photo'   => $member->profile_photo_path
                ? url('storage/'.$member->profile_photo_path)
                : null,
            'total_events_attended' => $totalEventsAttended,
        ]);
    }
}
