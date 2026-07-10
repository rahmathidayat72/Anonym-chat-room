<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatController::class, 'index']);

Route::post('/room/create', [ChatController::class, 'createRoom']);

Route::middleware('room.expiry')->group(function () {
    Route::get('/room/{code}', [ChatController::class, 'joinByCode']);
    Route::post('/room/{code}/join', [ChatController::class, 'registerUser']);
    Route::post('/room/{code}/message', [ChatController::class, 'sendMessage']);
    Route::get('/room/{code}/sync', [ChatController::class, 'sync']);
    Route::post('/room/{code}/typing', [ChatController::class, 'typing']);
    Route::post('/room/{code}/leave', [ChatController::class, 'leaveRoom']);
});
