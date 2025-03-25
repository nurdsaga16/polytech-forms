<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AnswerOptionController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\GroupController;
use App\Http\Controllers\Api\V1\PracticeController;
use App\Http\Controllers\Api\V1\QuestionController;
use App\Http\Controllers\Api\V1\ResponseController;
use App\Http\Controllers\Api\V1\ScheduleController;
use App\Http\Controllers\Api\V1\SurveyController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['throttle:api'])->group(function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::get('/surveys/{survey}', [SurveyController::class, 'show']);

    Route::post('/responses', [ResponseController::class, 'store']);

    Route::get('/responses', [ResponseController::class, 'index']);

    Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verifyEmail'])
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('/forgot-password', [UserController::class, 'forgotPassword'])
        ->middleware('guest')
        ->name('password.email');

    Route::get('/reset-password/{token}', function (string $token) {
        $email = request()->query('email');
        $frontendUrl = config('app.frontend_url').'/reset-password/'.$token;

        if ($email) {
            $frontendUrl .= '?email='.urlencode($email);
        }

        return redirect()->away($frontendUrl);
    })->middleware('guest')->name('password.reset');

    Route::post('/reset-password', [UserController::class, 'resetPassword'])
        ->middleware('guest')
        ->name('password.update');
});

Route::prefix('v1')->middleware(['throttle:api', 'auth:sanctum'])->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::put('/users', [UserController::class, 'update']);

    Route::middleware('verified')->group(function () {

        Route::apiResource('schedules', ScheduleController::class);

        Route::apiResource('surveys', SurveyController::class)
            ->except(['show']);

        Route::apiResource('responses', ResponseController::class)
            ->only(['show']);

        Route::get('practices', [PracticeController::class, 'index']);

        Route::get('groups', [GroupController::class, 'index']);

        Route::apiResource('questions', QuestionController::class)
            ->only(['store', 'update', 'destroy']);

        Route::apiResource('answer-options', AnswerOptionController::class)
            ->only(['store', 'update', 'destroy']);

    });

    Route::post('/email/verification-notification', [UserController::class, 'sendVerificationEmail'])
        ->middleware(['auth:sanctum', 'throttle:3,1'])
        ->name('verification.send');
});
