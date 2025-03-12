<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AnswerOptionController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\GroupController;
use App\Http\Controllers\Api\V1\PracticeController;
use App\Http\Controllers\Api\V1\QuestionController;
use App\Http\Controllers\Api\V1\ResponseController;
use App\Http\Controllers\Api\V1\ScheduleController;
use App\Http\Controllers\Api\V1\SurveyController; // Добавляем QuestionController
use App\Http\Controllers\Api\V1\UserController; // Добавляем AnswerOptionController
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->middleware(['throttle:api'])->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::get('/surveys/{survey}', [SurveyController::class, 'show']);
    Route::post('/responses', [ResponseController::class, 'store']);
});

Route::prefix('v1')->middleware(['throttle:api', 'auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users', [UserController::class, 'update']);

    Route::apiResource('schedules', ScheduleController::class);

    Route::apiResource('surveys', SurveyController::class)->except(['show']);

    Route::apiResource('responses', ResponseController::class)->only(['index', 'show']);

    Route::get('practices', [PracticeController::class, 'index']);

    Route::get('groups', [GroupController::class, 'index']);

    Route::apiResource('questions', QuestionController::class)->only(['store', 'update', 'destroy']);

    Route::apiResource('answer-options', AnswerOptionController::class)->only(['store', 'update', 'destroy']);
});
