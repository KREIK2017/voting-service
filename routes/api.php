<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OptionController;
use App\Http\Controllers\Api\V1\PollController;
use App\Http\Controllers\Api\V1\VoteController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/polls', [PollController::class, 'index']);
    Route::get('/polls/{poll}', [PollController::class, 'show']);
    Route::get('/polls/{poll}/results', [PollController::class, 'results']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::post('/polls/{poll}/vote', [VoteController::class, 'vote']);
        Route::get('/my-votes', [VoteController::class, 'myVotes']);

        Route::middleware('role:admin')->group(function () {
            Route::post('/polls', [PollController::class, 'store']);
            Route::put('/polls/{poll}', [PollController::class, 'update']);
            Route::delete('/polls/{poll}', [PollController::class, 'destroy']);

            Route::post('/polls/{poll}/options', [OptionController::class, 'store']);
            Route::put('/options/{option}', [OptionController::class, 'update']);
            Route::delete('/options/{option}', [OptionController::class, 'destroy']);

            Route::get('/polls/{poll}/votes', [VoteController::class, 'pollVotes']);
        });
    });
});
