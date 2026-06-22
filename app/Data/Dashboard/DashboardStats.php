<?php

declare(strict_types=1);

namespace App\Data\Dashboard;

final readonly class DashboardStats
{
    public function __construct(
        public int $activeLicenseKeys,
        public int $pendingLicenseKeys,
        public int $expiringSoon,
        public int $customers,
        public int $products,
        public int $licenseKeyTypes,
        public int $apiCallsLast24h,
    ) {}

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'active_license_keys' => $this->activeLicenseKeys,
            'pending_license_keys' => $this->pendingLicenseKeys,
            'expiring_soon' => $this->expiringSoon,
            'customers' => $this->customers,
            'products' => $this->products,
            'license_key_types' => $this->licenseKeyTypes,
            'api_calls_last_24h' => $this->apiCallsLast24h,
        ];
    }
}
