<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

use App\Data\LicenseKeys\LicenseKeyExportFilters;
use App\Enums\LicenseKeyStatus;
use App\Models\LicenseKey;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class ExportLicenseKeysAction
{
    /**
     * @var array<int, string>
     */
    private const array HEADER = [
        'key',
        'product',
        'customer',
        'status',
        'validity_amount',
        'validity_unit',
        'requires_hwid_check',
        'max_activations',
        'activated_at',
        'expires_at',
        'created_at',
    ];

    public function handle(LicenseKeyExportFilters $filters): StreamedResponse
    {
        $filename = 'license-keys-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($filters): void {
            $handle = fopen('php://output', 'w');
            throw_if($handle === false, RuntimeException::class, 'Unable to open php://output for CSV export.');

            fputcsv($handle, self::HEADER, separator: $filters->delimiter, escape: '\\');

            LicenseKey::query()
                ->where('team_id', $filters->teamId)
                ->when($filters->status instanceof LicenseKeyStatus, fn ($q) => $q->where('status', $filters->status->value))
                ->when($filters->productId !== null, fn ($q) => $q->where('product_id', $filters->productId))
                ->with(['product', 'customer'])
                ->chunkById(500, static function ($keys) use ($handle, $filters): void {
                    foreach ($keys as $key) {
                        fputcsv($handle, [
                            $key->key,
                            $key->product->slug,
                            $key->customer?->email,
                            $key->status->value,
                            $key->validity_amount,
                            $key->validity_unit->value,
                            $key->requires_hwid_check ? 'yes' : 'no',
                            $key->max_activations,
                            $key->activated_at?->toIso8601String(),
                            $key->expires_at?->toIso8601String(),
                            $key->created_at?->toIso8601String(),
                        ], separator: $filters->delimiter, escape: '\\');
                    }
                });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
