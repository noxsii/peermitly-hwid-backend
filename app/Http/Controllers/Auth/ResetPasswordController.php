<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ResetUserPasswordAction;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ResetPasswordController
{
    public function show(Request $request, string $token): Response
    {
        return Inertia::render('auth/ResetPassword', [
            'token' => $token,
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function store(ResetPasswordRequest $request, ResetUserPasswordAction $action): RedirectResponse
    {
        $action->handle(
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
            token: $request->string('token')->toString(),
        );

        return to_route('login')->with('status', 'Your password has been reset. You can sign in with the new password.');
    }
}
