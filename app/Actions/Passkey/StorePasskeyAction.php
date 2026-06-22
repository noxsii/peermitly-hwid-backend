<?php

declare(strict_types=1);

namespace App\Actions\Passkey;

use App\Data\Passkey\StorePasskeyData;
use App\Exceptions\Passkey\InvalidPasskeyAttestationException;
use Illuminate\Support\Facades\Session;
use RuntimeException;
use Spatie\LaravelPasskeys\Actions\StorePasskeyAction as SpatieStorePasskeyAction;
use Spatie\LaravelPasskeys\Models\Passkey;
use Throwable;

final readonly class StorePasskeyAction
{
    public function __construct(
        private SpatieStorePasskeyAction $spatieAction,
    ) {}

    public function handle(StorePasskeyData $data): Passkey
    {
        $optionsJson = Session::get('passkey.registration_options');

        throw_if(
            ! is_string($optionsJson) || $optionsJson === '',
            InvalidPasskeyAttestationException::class,
            previous: new RuntimeException('Missing registration options in session.'),
        );

        try {
            $passkey = $this->spatieAction->execute(
                $data->user,
                json_encode($data->credential, JSON_THROW_ON_ERROR),
                $optionsJson,
                (string) parse_url((string) config('app.url'), PHP_URL_HOST),
                ['name' => $data->name],
            );
        } catch (Throwable $throwable) {
            Session::forget('passkey.registration_options');
            throw new InvalidPasskeyAttestationException(
                message: $throwable->getMessage(),
                code: $throwable->getCode(),
                previous: $throwable,
            );
        }

        Session::forget('passkey.registration_options');

        return $passkey;
    }
}
