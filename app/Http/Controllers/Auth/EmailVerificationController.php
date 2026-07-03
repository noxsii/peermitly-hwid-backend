<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\VerifyEmailAction;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class EmailVerificationController
{
    public function notice(Request $request): RedirectResponse|Response
    {
        $user = $request->user();

        if ($user instanceof User && $user->hasVerifiedEmail()) {
            return to_route('dashboard');
        }

        return Inertia::render('auth/VerifyEmail', [
            'status' => $request->session()->get('status'),
        ]);
    }

    public function verify(Request $request, int $id, string $hash, VerifyEmailAction $verify): Response
    {
        $user = User::query()->find($id);

        $valid = $request->hasValidSignature()
            && $user instanceof User
            && hash_equals($hash, sha1($user->getEmailForVerification()));

        if (! $valid) {
            return Inertia::render('auth/EmailConfirmed', ['state' => 'invalid']);
        }

        $confirmed = $verify->handle($user);

        return Inertia::render('auth/EmailConfirmed', [
            'state' => $confirmed ? 'confirmed' : 'already',
        ]);
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user instanceof User && ! $user->hasVerifiedEmail()) {
            $user->notify(new VerifyEmailNotification());
        }

        return back()->with('status', 'A fresh confirmation link is on its way to your inbox.');
    }
}
