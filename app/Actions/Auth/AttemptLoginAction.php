<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Mail\NewLoginNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

final class AttemptLoginAction
{
    /**
     * @throws ValidationException
     */
    public function handle(Request $request, string $email, string $password, bool $remember): void
    {
        if (! Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();

        if ($user instanceof User) {
            Mail::to($user)->send(new NewLoginNotification(
                user: $user,
                ipAddress: $request->ip() ?? 'unknown',
                userAgent: (string) $request->userAgent() ?: 'unknown',
                loggedInAt: now(),
            ));
        }
    }
}
