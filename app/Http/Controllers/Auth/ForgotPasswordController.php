<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\SendPasswordResetLinkAction;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class ForgotPasswordController
{
    public function show(): Response
    {
        return Inertia::render('auth/ForgotPassword');
    }

    public function store(ForgotPasswordRequest $request, SendPasswordResetLinkAction $action): RedirectResponse
    {
        $action->handle($request->string('email')->toString());

        return back()->with(
            'status',
            'If an account with that email exists, a password reset link has been sent.',
        );
    }
}
