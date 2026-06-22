<?php

declare(strict_types=1);

namespace App\Http\Controllers\LicenseKeys;

use App\Actions\LicenseKeys\ExportLicenseKeysAction;
use App\Data\LicenseKeys\LicenseKeyExportFilters;
use App\Enums\LicenseKeyStatus;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class LicenseKeyExportController
{
    public function export(Request $request, ExportLicenseKeysAction $export): StreamedResponse
    {
        $teamId = (int) auth()->user()?->current_team_id;

        $status = LicenseKeyStatus::tryFrom($request->string('status')->toString());

        $productId = null;
        $productUuid = $request->string('product_uuid')->toString();
        if ($productUuid !== '') {
            $product = Product::query()->where('team_id', $teamId)->where('uuid', $productUuid)->first();
            $productId = $product?->id ?? -1;
        }

        $filters = LicenseKeyExportFilters::fromRequest(
            teamId: $teamId,
            status: $status,
            productId: $productId,
            delimiterInput: $request->string('delimiter')->toString(),
        );

        return $export->handle($filters);
    }
}
