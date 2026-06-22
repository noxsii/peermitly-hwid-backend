<?php

declare(strict_types=1);

namespace App\Actions\Passkey;

use App\Exceptions\Passkey\InvalidPasskeyAssertionException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\LaravelPasskeys\Actions\FindPasskeyToAuthenticateAction as SpatieFindPasskeyAction;
use Spatie\LaravelPasskeys\Models\Passkey;
use Throwable;

final readonly class AuthenticateWithPasskeyAction
{
    public function __construct(
        private SpatieFindPasskeyAction $spatieAction,
    ) {}

    /**
     * @param  array<string, mixed>  $assertion
     *
     * @throws Throwable
     */
    public function handle(array $assertion): Passkey
    {
        $optionsJson = Session::get('passkey.authentication_options');

        throw_if(! is_string($optionsJson) || $optionsJson === '', InvalidPasskeyAssertionException::class);

        try {
            $passkey = $this->spatieAction->execute(
                json_encode($assertion, JSON_THROW_ON_ERROR),
                $optionsJson,
            );
        } catch (Throwable $throwable) {
            Session::forget('passkey.authentication_options');
            throw new InvalidPasskeyAssertionException(
                message: $throwable->getMessage(),
                code: $throwable->getCode(),
                previous: $throwable,
            );
        }

        Session::forget('passkey.authentication_options');

        throw_unless($passkey instanceof Passkey, InvalidPasskeyAssertionException::class);

        $user = $passkey->getAttribute('authenticatable');

        throw_unless($user instanceof User, InvalidPasskeyAssertionException::class);
        throw_if($user->is_active === false, InvalidPasskeyAssertionException::class, 'User is inactive.');

        Auth::login($user, remember: true);

        return $passkey;
    }
}
