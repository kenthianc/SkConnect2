<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user(); // the authenticated user from Sanctum

        // Find the related member by email
        $member = Member::where('email', $user->email)->first();

        if (! $member) {
            return response()->json([
                'message' => 'Member record not found for this user.',
            ], 404);
        }

        return response()->json([
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
            'member' => [
                'id'               => $member->id,
                'member_id'        => $member->member_id,
                'first_name'       => $member->first_name,
                'middle_name'      => $member->middle_name,
                'last_name'        => $member->last_name,
                'email'            => $member->email,
                'phone'            => $member->phone,
                'birthdate'        => $member->birthdate,
                'age'              => $member->age,
                'gender'           => $member->gender,
                'purok'            => $member->purok,
                'address'          => $member->address,
                'guardian_name'    => $member->guardian_name,
                'guardian_contact' => $member->guardian_contact,
                'date_joined'      => $member->date_joined,
            ],
        ]);
    }
}
