<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;

// Public API
Route::post('/login', [ApiController::class, 'login']);

// Protected API (requires Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reset-password', [ApiController::class, 'resetPassword']);
    Route::post('/leave/apply', [ApiController::class, 'applyLeave']);
    Route::get('/leave/history', [ApiController::class, 'leaveHistory']);
    Route::get('/leave/balance', [ApiController::class, 'leaveBalance']);
    Route::get('/leave/{id}', [ApiController::class, 'leaveStatus']);
    Route::post('/complaint', [ApiController::class, 'createComplaint']);
    Route::get('/complaint/{id}', [ApiController::class, 'complaintStatus']);
});
