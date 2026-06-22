<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

final readonly class SendPasswordResetLinkAction
{
    /**
     * @throws ValidationException
     */
    public function handle(string $email): void
    {
        $status = Password::sendResetLink(
            ['email' => $email],
            static function (User $user, string $token): void {
                $user->notify(new ResetPasswordNotification($token));
            },
        );

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }
    }
}
