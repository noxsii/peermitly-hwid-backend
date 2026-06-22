<?php

declare(strict_types=1);

namespace App\Actions\Passkey;

use Illuminate\Support\Facades\Session;
use JsonException;
use Spatie\LaravelPasskeys\Actions\GeneratePasskeyAuthenticationOptionsAction as SpatieGenerateAuthOptionsAction;

final readonly class GeneratePasskeyAssertionOptionsAction
{
    public function __construct(
        private SpatieGenerateAuthOptionsAction $spatieAction,
    ) {}

    /**
     * @return array<string, mixed>
     *
     * @throws JsonException
     */
    public function handle(): array
    {
        $optionsJson = $this->spatieAction->execute();

        Session::put('passkey.authentication_options', $optionsJson);

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($optionsJson, associative: true, flags: JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
