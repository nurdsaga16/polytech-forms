<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

final class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response(new UserResource($user), 200);
    }

    public function update(UserRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        if (isset($data['reset_verification']) && $data['reset_verification'] && isset($data['email']) && $data['email'] !== $user->email) {
            $data['email_verified_at'] = null;
        }
        unset($data['reset_verification']);
        $user->update($data);

        return response(['status' => 'success'], 200);
    }

    public function sendVerificationEmail(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Электронная почта уже подтверждена'], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Ссылка для подтверждения отправлена']);
    }

    public function verifyEmail(Request $request, $id, $hash): \Illuminate\Http\RedirectResponse
    {
        $user = User::findOrFail($id);

        abort_if(! URL::hasValidSignature($request), 403, 'Недействительная ссылка для подтверждения');

        abort_if(! hash_equals($hash, sha1($user->getEmailForVerification())), 403, 'Недействительная ссылка');

        if ($user->hasVerifiedEmail()) {
            return redirect(config('app.frontend_url').'/')->with('message', 'Email уже подтвержден');
        }

        $user->markEmailAsVerified();

        return redirect(config('app.frontend_url').'/')->with('success', 'Email успешно подтвержден!');
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                // Кастомная логика отправки письма
                $resetUrl = config('app.frontend_url').'/reset-password?'.http_build_query([
                    'token' => $token,
                    'email' => $user->email,
                ]);

                $user->sendPasswordResetNotification($token, $resetUrl);
            }
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)])
            : response()->json(['message' => __($status)], 400);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)])
            : response()->json(['message' => __($status)], 400);
    }
}
