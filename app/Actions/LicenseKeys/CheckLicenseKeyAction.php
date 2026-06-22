<?php

declare(strict_types=1);

namespace App\Actions\LicenseKeys;

use App\Data\LicenseKeys\LicenseKeyCheckRequest;
use App\Data\LicenseKeys\LicenseKeyCheckResult;
use App\Enums\LicenseCheckStatus;
use App\Enums\LicenseKeyStatus;
use App\Models\LicenseKey;
use App\Models\LicenseKeyActivation;
use App\Models\LicenseKeyCheck;
use App\Models\LicenseKeyType;
use Illuminate\Support\Facades\DB;

final readonly class CheckLicenseKeyAction
{
    public function __construct(
        private NormalizeLicenseKeyAction $normalize,
        private ActivateLicenseKeyAction $activate,
        private BuildLicenseKeyConfigurationAction $buildConfiguration,
    ) {}

    public function handle(LicenseKeyCheckRequest $request): LicenseKeyCheckResult
    {
        $licenseKey = $this->findLicenseKey($request);

        if (! $licenseKey instanceof LicenseKey) {
            return $this->log(
                $request,
                null,
                LicenseCheckStatus::INVALID,
                false,
                'License key is invalid.',
                LicenseCheckStatus::NOT_FOUND,
            );
        }

        if ($licenseKey->product_id !== $request->product->id) {
            return $this->log(
                $request,
                $licenseKey,
                LicenseCheckStatus::PRODUCT_MISMATCH,
                false,
                'License key is not valid for this product.',
            );
        }

        if ($licenseKey->status === LicenseKeyStatus::REVOKED) {
            return $this->log(
                $request,
                $licenseKey,
                LicenseCheckStatus::REVOKED,
                false,
                'License key has been revoked.',
            );
        }

        if ($licenseKey->status === LicenseKeyStatus::BLOCKED) {
            return $this->log(
                $request,
                $licenseKey,
                LicenseCheckStatus::BLOCKED,
                false,
                'License key has been blocked.',
            );
        }

        if ($licenseKey->requires_hwid_check && $request->hwid === null) {
            return $this->log(
                $request,
                $licenseKey,
                LicenseCheckStatus::HWID_REQUIRED,
                false,
                'Hardware ID is required for this license key.',
            );
        }

        $firstActivation = $licenseKey->status === LicenseKeyStatus::PENDING;

        if ($firstActivation) {
            $licenseKey = $this->activate->handle(
                $licenseKey,
                $request->hwid,
                $request->ipAddress,
                $request->userAgent,
            );
        }

        if ($licenseKey->expires_at !== null && $licenseKey->expires_at->isPast()) {
            $licenseKey->forceFill(['status' => LicenseKeyStatus::EXPIRED->value])->save();

            return $this->log(
                $request,
                $licenseKey,
                LicenseCheckStatus::EXPIRED,
                false,
                'License key has expired.',
            );
        }

        if ($licenseKey->requires_hwid_check && $request->hwid !== null && ! $firstActivation) {
            $limitStatus = $this->ensureHwidActivation($licenseKey, $request);

            if ($limitStatus instanceof LicenseCheckStatus) {
                return $this->log(
                    $request,
                    $licenseKey,
                    $limitStatus,
                    false,
                    'Activation limit reached for this license key.',
                );
            }
        }

        $this->touchLastChecked($licenseKey);

        return $this->log(
            $request,
            $licenseKey,
            LicenseCheckStatus::VALID,
            $firstActivation,
            null,
        );
    }

    private function findLicenseKey(LicenseKeyCheckRequest $request): ?LicenseKey
    {
        $types = LicenseKeyType::query()
            ->whereTeam($request->teamId)
            ->get();

        $variants = [];

        foreach ($types as $type) {
            $variants[] = $this->normalize->handle($request->rawKey, $this->buildConfiguration->handle($type)->caseSensitive);
        }

        $variants[] = $this->normalize->handle($request->rawKey, false);
        $variants[] = $this->normalize->handle($request->rawKey, true);
        $variants = array_values(array_unique($variants));

        return LicenseKey::query()
            ->whereTeam($request->teamId)
            ->whereIn('normalized_key', $variants)
            ->first();
    }

    private function ensureHwidActivation(LicenseKey $licenseKey, LicenseKeyCheckRequest $request): ?LicenseCheckStatus
    {
        $existing = LicenseKeyActivation::query()
            ->where('license_key_id', $licenseKey->id)
            ->where('machine_id', $request->hwid)
            ->first();

        if ($existing !== null) {
            $existing->forceFill([
                'last_seen_at' => now(),
                'ip_address' => $request->ipAddress,
                'user_agent' => $request->userAgent,
            ])->save();

            return null;
        }

        $current = LicenseKeyActivation::query()
            ->where('license_key_id', $licenseKey->id)
            ->count();

        if ($licenseKey->max_activations !== null && $current >= $licenseKey->max_activations) {
            return LicenseCheckStatus::ACTIVATION_LIMIT_REACHED;
        }

        LicenseKeyActivation::query()->create([
            'team_id' => $licenseKey->team_id,
            'license_key_id' => $licenseKey->id,
            'machine_id' => $request->hwid,
            'ip_address' => $request->ipAddress,
            'user_agent' => $request->userAgent,
            'activated_at' => now(),
            'last_seen_at' => now(),
        ]);

        return null;
    }

    private function touchLastChecked(LicenseKey $licenseKey): void
    {
        DB::table('license_keys')
            ->where('id', $licenseKey->id)
            ->update([
                'last_checked_at' => now(),
                'check_count' => DB::raw('check_count + 1'),
            ]);
    }

    private function log(
        LicenseKeyCheckRequest $request,
        ?LicenseKey $licenseKey,
        LicenseCheckStatus $publicStatus,
        bool $firstActivation,
        ?string $message,
        ?LicenseCheckStatus $logStatus = null,
    ): LicenseKeyCheckResult {
        $expiresAt = $licenseKey?->expires_at;

        $result = new LicenseKeyCheckResult(
            valid: $publicStatus->isValid(),
            status: $publicStatus,
            firstActivation: $firstActivation,
            licenseKey: $licenseKey?->key,
            productSlug: $request->product->slug,
            activatedAt: $licenseKey?->activated_at,
            expiresAt: $expiresAt,
            daysRemaining: $expiresAt !== null ? max(0, (int) now()->diffInDays($expiresAt, false)) : null,
            lifetime: $licenseKey?->isLifetime() ?? false,
            message: $message,
        );

        LicenseKeyCheck::query()->create([
            'team_id' => $request->teamId,
            'license_key_id' => $licenseKey?->id,
            'product_id' => $request->product->id,
            'status' => ($logStatus ?? $publicStatus)->value,
            'ip_address' => $request->ipAddress,
            'user_agent' => $request->userAgent,
            'hwid' => $request->hwid,
            'request_payload' => [
                'key' => $request->rawKey,
                'product' => $request->product->slug,
                'hwid' => $request->hwid,
            ],
            'response_payload' => $result->toArray(),
            'checked_at' => now(),
        ]);

        return $result;
    }
}
