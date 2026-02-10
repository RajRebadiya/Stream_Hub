<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// User endpoint that uses sanctum/session as needed
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public Auth Routes (require session for login/register UI)
// These endpoints need the session store (web middleware) because they
// perform session-based login (Auth::attempt, session regeneration).
Route::middleware('web')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/registration-plans', [AuthController::class, 'getAvailablePlans']); // Get plans for registration page

    // Logout (uses session auth)
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Token verification endpoint (public, no auth required)
Route::post('/verify-token', [PlatformController::class, 'verifyToken']);

