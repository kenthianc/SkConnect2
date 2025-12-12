<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Member;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\EventApiController;
use App\Http\Controllers\Api\ProfileController;

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if (! $user || ! Hash::check($credentials['password'], $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    // Create a token for this mobile device/app
    $token = $user->createToken('mobile')->plainTextToken;

    $member = Member::where('email', $user->email)->first();

    return response()->json([
        'token' => $token,
        'user'  => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            // add more fields if you want
        ],
        'member' => $member ? [
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
            'role'             => $member->role,
            'registered_via'   => $member->registered_via,
            'is_active'        => $member->is_active,
        ] : null,
    ]);
});


    //News API routes for mobile app (FlutterFlow) 
    Route::get('/news', [NewsApiController::class, 'index']);
    Route::get('/news/{news}', [NewsApiController::class, 'show']); // optional detail endpoint


    //Event API routes for mobile app (FlutterFlow)
    Route::get('/events', [EventApiController::class, 'index']);
    Route::get('/events/{event}', [EventApiController::class, 'show']); // optional detail endpoint


    //Profile API routes for mobile app (FlutterFlow)
    Route::middleware('auth:sanctum')->get('/me', [ProfileController::class, 'me']);
    Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'show']);


    // Logout route
    Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
        // delete current token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    });