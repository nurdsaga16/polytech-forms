<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        $user->update($data);

        return response(['status' => 'success'], 200);
    }
}
