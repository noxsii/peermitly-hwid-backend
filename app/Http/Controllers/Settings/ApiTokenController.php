<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Settings\StoreApiTokenRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

final class ApiTokenController
{
    public function store(StoreApiTokenRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $newToken = $user->createToken(
            $request->string('name')->toString(),
            $request->array('abilities'),
        );

        return new JsonResponse([
            'id' => $newToken->accessToken->id,
            'name' => $newToken->accessToken->name,
            'abilities' => $newToken->accessToken->abilities,
            'plain_text_token' => $newToken->plainTextToken,
            'created_at' => $newToken->accessToken->created_at?->toIso8601String(),
            'last_used_at' => null,
        ], 201);
    }

    public function destroy(Request $request, PersonalAccessToken $apiToken): RedirectResponse
    {
        abort_unless($apiToken->tokenable_id === $request->user()?->id, 404);

        $apiToken->delete();

        return back();
    }
}
