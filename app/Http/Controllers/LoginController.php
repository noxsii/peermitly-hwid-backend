<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Auth\AttemptLoginAction;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class LoginController
{
    public function index(): Response
    {
        return Inertia::render('auth/Login');
    }

    public function store(LoginRequest $request, AttemptLoginAction $attempt): RedirectResponse
    {
        $attempt->handle(
            $request,
            $request->string('email')->toString(),
            $request->string('password')->toString(),
            $request->boolean('remember'),
        );

        return redirect()->intended(route('dashboard'));
    }
}
