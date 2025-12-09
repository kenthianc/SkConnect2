<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ChatbotController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest routes (login/register) - handled by Laravel auth
Auth::routes();

// Redirect root to dashboard or login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Protected routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/member-growth', [DashboardController::class, 'getMemberGrowthData']);
    
    // Members Management
    Route::resource('members', MemberController::class);
    Route::get('/members/export/excel', [MemberController::class, 'export'])->name('members.export');
    
    // Events Management
    Route::resource('events', EventController::class);
    Route::post('/events/{event}/attendance', [EventController::class, 'markAttendance'])->name('events.attendance');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');

    
    // News & Announcements
    Route::resource('news', NewsController::class)->only(['index', 'store', 'show', 'edit', 'update', 'destroy']);
    
    // Attendance Management
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('/attendance/member/{member}', [AttendanceController::class, 'memberSummary'])->name('attendance.member');
    Route::post('/record-attendance', [AttendanceController::class, 'recordAttendance'])->name('attendance.record');
    Route::post('/events/{event}/record-attendance', [AttendanceController::class, 'recordAttendance'])->name('events.record-attendance');
    Route::post('/events/{event}/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('events.attendance.checkin');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.update-notifications');
    
    // AI Chatbot API
    Route::middleware(['auth']) // or your admin middleware
    ->post('/admin/chatbot/ask', [ChatbotController::class, 'ask'])
    ->name('chatbot.ask');

    Route::post('/api/chatbot/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');
});