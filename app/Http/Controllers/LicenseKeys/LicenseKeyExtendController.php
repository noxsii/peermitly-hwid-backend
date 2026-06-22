<?php

declare(strict_types=1);

namespace App\Http\Controllers\LicenseKeys;

use App\Actions\LicenseKeys\ExtendLicenseKeyAction;
use App\Enums\LicenseValidityUnit;
use App\Http\Requests\LicenseKeys\ExtendLicenseKeyRequest;
use App\Models\LicenseKey;
use Illuminate\Http\RedirectResponse;

final class LicenseKeyExtendController
{
    public function extend(ExtendLicenseKeyRequest $request, LicenseKey $licenseKey, ExtendLicenseKeyAction $extend): RedirectResponse
    {
        abort_unless($licenseKey->team_id === (int) auth()->user()?->current_team_id, 404);

        $extend->handle(
            $licenseKey,
            (int) $request->integer('amount'),
            LicenseValidityUnit::from($request->string('unit')->toString()),
        );

        return back()->with('success', 'License key extended.');
    }
}
