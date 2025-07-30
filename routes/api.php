<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;



    // Register
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Protected routes can be added here
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    return response()->json(['message' => 'You are authenticated!']);
});
