<?php

declare(strict_types=1);

namespace App\Http\Controllers\LicenseKeys;

use App\Actions\LicenseKeys\RevokeLicenseKeyAction;
use App\Http\Requests\LicenseKeys\RevokeLicenseKeyRequest;
use App\Models\LicenseKey;
use Illuminate\Http\RedirectResponse;

final class LicenseKeyRevokeController
{
    public function revoke(RevokeLicenseKeyRequest $request, LicenseKey $licenseKey, RevokeLicenseKeyAction $revoke): RedirectResponse
    {
        abort_unless($licenseKey->team_id === (int) auth()->user()?->current_team_id, 404);

        $revoke->handle($licenseKey, $request->string('reason')->toString());

        return back()->with('success', 'License key revoked.');
    }
}
