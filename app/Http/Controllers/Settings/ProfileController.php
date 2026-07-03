<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Users\UpdateProfileAction;
use App\Http\Requests\Settings\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class ProfileController
{
    public function edit(): Response
    {
        return Inertia::render('Profile');
    }

    public function update(UpdateProfileRequest $request, UpdateProfileAction $update): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        $update->handle($user, $request->string('name')->toString());

        return back()->with('status', 'profile-updated');
    }
}
