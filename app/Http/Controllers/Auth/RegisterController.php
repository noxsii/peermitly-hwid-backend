<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class RegisterController
{
    public function show(): Response
    {
        return Inertia::render('auth/Register');
    }

    public function store(RegisterRequest $request, RegisterUserAction $register): RedirectResponse
    {
        $register->handle(
            $request->string('name')->toString(),
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );

        return to_route('login')->with(
            'status',
            'Your account has been created. Check your email for the confirmation link — it is valid for 3 hours.',
        );
    }
}
