<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\NewsApiController;

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

    return response()->json([
        'token' => $token,
        'user'  => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            // add more fields if you want
        ],
    ]);
});

    //Profile route
    Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'show']);

    //News API routes for mobile app (FlutterFlow) 
    Route::get('/news', [NewsApiController::class, 'index']);
    Route::get('/news/{news}', [NewsApiController::class, 'show']); // optional detail endpoint