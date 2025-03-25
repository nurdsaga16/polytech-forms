<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class AuthController extends Controller
{
    public function login(LoginUserRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Неверный email или пароль',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (isset($user->active) && ! $user->active) {
            Auth::logout();

            return response()->json([
                'message' => 'Ваш аккаунт деактивирован',
            ], 403);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth-token')->plainTextToken;

        $emailVerified = $user->hasVerifiedEmail();

        return response()->json([
            'user' => $user,
            'token' => $token,
            'email_verified' => $emailVerified,
            'message' => $emailVerified
                ? 'Успешный вход в систему'
                : 'Пожалуйста, подтвердите ваш email',
        ], 200);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Выход выполнен успешно'], 200);
    }
}
