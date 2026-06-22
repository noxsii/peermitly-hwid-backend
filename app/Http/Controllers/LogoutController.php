<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Auth\LogoutAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class LogoutController
{
    public function logout(Request $request, LogoutAction $logout): RedirectResponse
    {
        $logout->handle($request);

        return redirect('/login');
    }
}
