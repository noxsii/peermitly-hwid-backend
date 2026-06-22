<?php

declare(strict_types=1);

namespace App\Http\Controllers\Passkey;

use App\Actions\Passkey\AuthenticateWithPasskeyAction;
use App\Actions\Passkey\GeneratePasskeyAssertionOptionsAction;
use App\Exceptions\Passkey\InvalidPasskeyAssertionException;
use App\Http\Requests\Passkey\VerifyPasskeyAssertionRequest;
use Illuminate\Http\JsonResponse;
use JsonException;

final class PasskeyLoginController
{
    /**
     * @throws JsonException
     */
    public function options(GeneratePasskeyAssertionOptionsAction $action): JsonResponse
    {
        return new JsonResponse($action->handle());
    }

    public function verify(
        VerifyPasskeyAssertionRequest $request,
        AuthenticateWithPasskeyAction $action,
    ): JsonResponse {
        try {
            $action->handle($request->array('assertion'));
        } catch (InvalidPasskeyAssertionException) {
            return new JsonResponse(['error' => 'invalid_assertion'], 422);
        }

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return new JsonResponse(['redirect' => '/dashboard']);
    }
}
