<?php

declare(strict_types=1);

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Actions\Users\DeactivateUnsubscribedUserAction;
use App\Enums\SubscriptionPlan;
use App\Enums\UserRole;
use App\Models\Subscription;
use App\Models\User;

function deactivateAction(): DeactivateUnsubscribedUserAction
{
    return resolve(DeactivateUnsubscribedUserAction::class);
}

test('it deactivates an active user without a subscription', function (): void {
    $user = User::factory()->create(['is_active' => true, 'role' => UserRole::USER]);

    expect(deactivateAction()->handle($user))->toBeTrue()
        ->and($user->refresh()->is_active)->toBeFalse();
});

test('it keeps a user with an active subscription', function (): void {
    $user = User::factory()->create(['is_active' => true, 'role' => UserRole::USER]);
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::WEEK);

    expect(deactivateAction()->handle($user))->toBeFalse()
        ->and($user->refresh()->is_active)->toBeTrue();
});

test('it deactivates a user whose subscription has expired', function (): void {
    $user = User::factory()->create(['is_active' => true, 'role' => UserRole::USER]);
    Subscription::factory()->for($user)->expired()->create();

    expect(deactivateAction()->handle($user))->toBeTrue()
        ->and($user->refresh()->is_active)->toBeFalse();
});

test('it never deactivates admins', function (): void {
    $admin = User::factory()->create(['is_active' => true, 'role' => UserRole::ADMIN]);
    $super = User::factory()->create(['is_active' => true, 'role' => UserRole::SUPER_ADMIN]);

    expect(deactivateAction()->handle($admin))->toBeFalse()
        ->and($admin->refresh()->is_active)->toBeTrue()
        ->and(deactivateAction()->handle($super))->toBeFalse()
        ->and($super->refresh()->is_active)->toBeTrue();
});

test('it ignores already inactive users', function (): void {
    $user = User::factory()->create(['is_active' => false, 'role' => UserRole::USER]);

    expect(deactivateAction()->handle($user))->toBeFalse();
});
