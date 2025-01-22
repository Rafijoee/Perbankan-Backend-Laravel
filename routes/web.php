<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::get('/auth/google', function () {
//     return Socialite::driver('google')->redirect();
// });

// Route::get('/auth/google/callback', function () {
//     $user = Socialite::driver('google')->user();
// });

Route::get('auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
