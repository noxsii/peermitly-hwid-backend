<?php

declare(strict_types=1);

namespace App\Http\Controllers\LicenseKeys;

use App\Actions\LicenseKeys\RestoreLicenseKeyAction;
use App\Models\LicenseKey;
use Illuminate\Http\RedirectResponse;

final class LicenseKeyRestoreController
{
    public function restore(LicenseKey $licenseKey, RestoreLicenseKeyAction $restore): RedirectResponse
    {
        abort_unless($licenseKey->team_id === (int) auth()->user()?->current_team_id, 404);

        $restore->handle($licenseKey);

        return back()->with('success', 'License key restored.');
    }
}
