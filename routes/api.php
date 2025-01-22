<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//authenticate
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-account', [AuthController::class, 'verifyaccount']);
Route::post('/reset-twofa', [AuthController::class, 'resettwofa']);
Route::post(('/reset-password'), [AuthController::class, 'resetpassword']);
Route::post(('forget-password'), [AuthController::class, 'forgetpassword']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::middleware(['jwt'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Welcome to the dashboard']);
    });
});
