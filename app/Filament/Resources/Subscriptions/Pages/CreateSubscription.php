<?php

declare(strict_types=1);

namespace App\Filament\Resources\Subscriptions\Pages;

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Filament\Resources\Subscriptions\SubscriptionResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

final class CreateSubscription extends CreateRecord
{
    protected static string $resource = SubscriptionResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $user = User::query()->whereKey($data['user_id'])->firstOrFail();
        $plan = SubscriptionPlan::from(is_string($data['plan'] ?? null) ? $data['plan'] : '');

        $startsAt = is_string($data['starts_at'] ?? null) && $data['starts_at'] !== ''
            ? Date::parse($data['starts_at'])
            : null;
        $endsAt = is_string($data['ends_at'] ?? null) && $data['ends_at'] !== ''
            ? Date::parse($data['ends_at'])
            : null;

        $subscription = resolve(GrantSubscriptionAction::class)->handle($user, $plan, $startsAt, $endsAt);

        $status = SubscriptionStatus::from(
            is_string($data['status'] ?? null) ? $data['status'] : SubscriptionStatus::ACTIVE->value,
        );

        if ($status !== SubscriptionStatus::ACTIVE) {
            $subscription->update([
                'status' => $status,
                'canceled_at' => $status === SubscriptionStatus::CANCELED ? now() : null,
            ]);
        }

        return $subscription;
    }
}
