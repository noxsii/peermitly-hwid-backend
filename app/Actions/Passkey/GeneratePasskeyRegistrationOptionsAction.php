<?php

declare(strict_types=1);

namespace App\Actions\Passkey;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use JsonException;
use Spatie\LaravelPasskeys\Actions\GeneratePasskeyRegisterOptionsAction as SpatieGenerateRegisterOptionsAction;

final readonly class GeneratePasskeyRegistrationOptionsAction
{
    public function __construct(
        private SpatieGenerateRegisterOptionsAction $spatieAction,
    ) {}

    /**
     * @return array<string, mixed>
     *
     * @throws JsonException
     */
    public function handle(User $user): array
    {
        $optionsJson = (string) $this->spatieAction->execute($user);

        Session::put('passkey.registration_options', $optionsJson);

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($optionsJson, associative: true, flags: JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
