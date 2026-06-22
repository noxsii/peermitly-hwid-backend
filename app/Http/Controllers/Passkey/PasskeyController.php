<?php

declare(strict_types=1);

namespace App\Http\Controllers\Passkey;

use App\Actions\Passkey\GeneratePasskeyRegistrationOptionsAction;
use App\Actions\Passkey\StorePasskeyAction;
use App\Data\Passkey\StorePasskeyData;
use App\Exceptions\Passkey\InvalidPasskeyAttestationException;
use App\Http\Requests\Passkey\StorePasskeyRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use JsonException;
use Spatie\LaravelPasskeys\Models\Passkey;

final class PasskeyController
{
    /**
     * @throws JsonException
     */
    public function options(
        Request $request,
        GeneratePasskeyRegistrationOptionsAction $action,
    ): JsonResponse {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        return new JsonResponse($action->handle($user));
    }

    public function store(
        StorePasskeyRequest $request,
        StorePasskeyAction $action,
    ): JsonResponse {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        try {
            $passkey = $action->handle(new StorePasskeyData(
                user: $user,
                name: $request->string('name')->toString(),
                credential: $request->array('credential'),
            ));
        } catch (InvalidPasskeyAttestationException) {
            return new JsonResponse(['error' => 'invalid_attestation'], 422);
        }

        return new JsonResponse([
            'id' => $passkey->getAttribute('id'),
            'name' => $passkey->getAttribute('name'),
        ], 201);
    }

    public function destroy(Passkey $passkey): RedirectResponse
    {
        Gate::authorize('delete', $passkey);

        $passkey->delete();

        return back()->with('status', 'Passkey removed.');
    }
}
