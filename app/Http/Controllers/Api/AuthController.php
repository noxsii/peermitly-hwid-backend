<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Api\AuthenticateApiUserAction;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\Api\SubscriptionResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

final class AuthController
{
    public function login(LoginRequest $request, AuthenticateApiUserAction $authenticate): JsonResponse
    {
        $result = $authenticate->handle(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
            $request->deviceName(),
            $request->hwid(),
        );

        return response()->json([
            'token' => $result->token,
            'user' => new UserResource($result->user),
            'subscription' => new SubscriptionResource($result->user->activeSubscription),
        ]);
    }
}
