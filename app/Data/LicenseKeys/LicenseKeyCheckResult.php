<?php

declare(strict_types=1);

namespace App\Data\LicenseKeys;

use App\Enums\LicenseCheckStatus;
use Illuminate\Support\Carbon;

final readonly class LicenseKeyCheckResult
{
    public function __construct(
        public bool $valid,
        public LicenseCheckStatus $status,
        public bool $firstActivation,
        public ?string $licenseKey,
        public string $productSlug,
        public ?Carbon $activatedAt,
        public ?Carbon $expiresAt,
        public ?int $daysRemaining,
        public bool $lifetime,
        public ?string $message,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'valid' => $this->valid,
            'status' => $this->status->value,
            'first_activation' => $this->firstActivation,
            'license_key' => $this->licenseKey,
            'product' => $this->productSlug,
            'activated_at' => $this->activatedAt?->toIso8601String(),
            'expires_at' => $this->expiresAt?->toIso8601String(),
            'days_remaining' => $this->daysRemaining,
            'lifetime' => $this->lifetime,
        ];

        if ($this->message !== null) {
            $payload['message'] = $this->message;
        }

        return $payload;
    }
}
