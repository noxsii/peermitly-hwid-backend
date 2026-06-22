<?php

declare(strict_types=1);

use App\Enums\SubscriptionStatus;
use App\Jobs\ExpireSubscriptionsJob;
use App\Models\Subscription;
use Illuminate\Support\Facades\Queue;

test('the command dispatches the expiry job', function (): void {
    Queue::fake();

    $this->artisan('subscriptions:expire')->assertSuccessful();

    Queue::assertPushed(ExpireSubscriptionsJob::class);
});

test('running the job expires past-due subscriptions', function (): void {
    $pastDue = Subscription::factory()->pastDue()->create();

    (new ExpireSubscriptionsJob())->handle(resolve(App\Actions\Subscriptions\ExpireSubscriptionsAction::class));

    expect($pastDue->refresh()->status)->toBe(SubscriptionStatus::EXPIRED);
});
