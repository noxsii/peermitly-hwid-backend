<?php

declare(strict_types=1);

use App\Actions\Subscriptions\ExpireSubscriptionAction;
use App\Enums\SubscriptionStatus;
use App\Jobs\ExpireSubscriptionJob;
use App\Models\Subscription;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Queue;

test('the command runs end-to-end and expires only past-due subscriptions', function (): void {
    $pastDue = Subscription::factory()->pastDue()->create();
    $active = Subscription::factory()->create();
    $alreadyExpired = Subscription::factory()->expired()->create();

    // Queue connection is sync in tests, so each dispatched job runs immediately.
    $this->artisan('subscriptions:expire')->assertSuccessful();

    expect($pastDue->refresh()->status)->toBe(SubscriptionStatus::EXPIRED)
        ->and($active->refresh()->status)->toBe(SubscriptionStatus::ACTIVE)
        ->and($alreadyExpired->refresh()->status)->toBe(SubscriptionStatus::EXPIRED);
});

test('the command dispatches one job per subscription', function (): void {
    Queue::fake();

    Subscription::factory()->count(3)->create();

    $this->artisan('subscriptions:expire')->assertSuccessful();

    Queue::assertPushed(ExpireSubscriptionJob::class, 3);
});

test('the command dispatches nothing when there are no subscriptions', function (): void {
    Queue::fake();

    $this->artisan('subscriptions:expire')->assertSuccessful();

    Queue::assertNothingPushed();
});

test('the job expires its past-due subscription when handled', function (): void {
    $pastDue = Subscription::factory()->pastDue()->create();

    new ExpireSubscriptionJob($pastDue)->handle(resolve(ExpireSubscriptionAction::class));

    expect($pastDue->refresh()->status)->toBe(SubscriptionStatus::EXPIRED);
});

test('the expiry command is scheduled hourly', function (): void {
    $events = collect(resolve(Schedule::class)->events());

    $event = $events->first(
        fn ($event): bool => str_contains((string) $event->command, 'subscriptions:expire'),
    );

    expect($event)->not->toBeNull()
        ->and($event->expression)->toBe('0 * * * *');
});
