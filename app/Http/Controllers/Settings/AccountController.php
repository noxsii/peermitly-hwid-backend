<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Auth\LogoutAction;
use App\Actions\Users\DeleteAccountAction;
use App\Http\Requests\Settings\DeleteAccountRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Throwable;

final class AccountController
{
    /**
     * @throws Throwable
     */
    public function destroy(
        DeleteAccountRequest $request,
        DeleteAccountAction $delete,
        LogoutAction $logout,
    ): RedirectResponse {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        $logout->handle($request);
        $delete->handle($user);

        return to_route('login')->with('status', 'account-deleted');
    }
}
