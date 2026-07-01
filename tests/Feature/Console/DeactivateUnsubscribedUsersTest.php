<?php

declare(strict_types=1);

use App\Actions\Users\DeactivateUnsubscribedUserAction;
use App\Enums\UserRole;
use App\Jobs\EnforceUserSubscriptionJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;

test('it dispatches an enforcement job for every active user', function (): void {
    Bus::fake();

    $active = User::factory()->create(['is_active' => true, 'role' => UserRole::USER]);
    User::factory()->create(['is_active' => true, 'role' => UserRole::USER]);

    $this->artisan('users:enforce-subscription')->assertSuccessful();

    Bus::assertDispatchedTimes(EnforceUserSubscriptionJob::class, 2);
    Bus::assertDispatched(
        EnforceUserSubscriptionJob::class,
        fn (EnforceUserSubscriptionJob $job): bool => $job->user->is($active),
    );
});

test('it skips inactive users and admins', function (): void {
    Bus::fake();

    User::factory()->create(['is_active' => false, 'role' => UserRole::USER]);
    User::factory()->create(['is_active' => true, 'role' => UserRole::ADMIN]);
    User::factory()->create(['is_active' => true, 'role' => UserRole::SUPER_ADMIN]);

    $this->artisan('users:enforce-subscription')->assertSuccessful();

    Bus::assertNotDispatched(EnforceUserSubscriptionJob::class);
});

test('the job deactivates a user without a subscription', function (): void {
    $user = User::factory()->create(['is_active' => true, 'role' => UserRole::USER]);

    new EnforceUserSubscriptionJob($user)->handle(
        resolve(DeactivateUnsubscribedUserAction::class),
    );

    expect($user->refresh()->is_active)->toBeFalse();
});
