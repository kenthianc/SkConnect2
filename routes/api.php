<?php
use App\Http\Controllers\Api\MemberApiController;
use Illuminate\Support\Facades\Route;

Route::post('/member/login', [MemberApiController::class, 'login']);