<?php

declare(strict_types=1);

use App\Actions\Subscriptions\ExpireSubscriptionsAction;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;

test('expires only active subscriptions that are past due', function (): void {
    $pastDue = Subscription::factory()->pastDue()->create();
    $active = Subscription::factory()->create();
    $alreadyExpired = Subscription::factory()->expired()->create();

    $count = resolve(ExpireSubscriptionsAction::class)->handle();

    expect($count)->toBe(1)
        ->and($pastDue->refresh()->status)->toBe(SubscriptionStatus::EXPIRED)
        ->and($active->refresh()->status)->toBe(SubscriptionStatus::ACTIVE)
        ->and($alreadyExpired->refresh()->status)->toBe(SubscriptionStatus::EXPIRED);
});

test('returns zero when nothing is past due', function (): void {
    Subscription::factory()->create();

    expect(resolve(ExpireSubscriptionsAction::class)->handle())->toBe(0);
});
