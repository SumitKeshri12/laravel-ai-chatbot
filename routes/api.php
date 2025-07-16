<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });
    Route::post('chat/message', [ChatbotController::class, 'sendMessage']);
    Route::get('tickets', [\App\Http\Controllers\TicketController::class, 'index']);
    Route::get('tickets/{id}', [\App\Http\Controllers\TicketController::class, 'show']);
    Route::middleware('auth:api')->get('chat/history', [ChatbotController::class, 'getChatHistory']);
});
