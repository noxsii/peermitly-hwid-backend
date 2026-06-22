<?php

declare(strict_types=1);

namespace App\Actions\Customers;

use App\Models\Customer;

final class ResolveCustomerAction
{
    /**
     * Resolves a Customer from either a UUID (existing pick) or an email
     * (find-or-create scoped to the team). Returns null when neither is
     * provided.
     */
    public function handle(int $teamId, ?string $customerUuid, ?string $customerEmail): ?Customer
    {
        if ($customerUuid !== null && $customerUuid !== '') {
            return Customer::query()
                ->where('team_id', $teamId)
                ->where('uuid', $customerUuid)
                ->first();
        }

        $email = mb_trim($customerEmail ?? '');

        if ($email === '') {
            return null;
        }

        return Customer::query()->firstOrCreate(
            ['team_id' => $teamId, 'email' => $email],
        );
    }
}
