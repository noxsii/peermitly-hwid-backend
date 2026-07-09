<?php

declare(strict_types=1);

use App\Enums\SubscriptionPlan;
use App\Enums\UserRole;
use App\Filament\Widgets\ApiTokensChart;
use App\Filament\Widgets\SubscriptionPlansChart;
use App\Filament\Widgets\SubscriptionsChart;
use App\Models\Subscription;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->actingAs(User::factory()->create(['role' => UserRole::SUPER_ADMIN]));
});

/**
 * @return array<string, mixed>
 */
function chartData(object $widget): array
{
    /** @var array<string, mixed> */
    return (fn (): array => $this->getData())->call($widget);
}

test('the subscriptions chart renders and switches the period filter', function (): void {
    Livewire::test(SubscriptionsChart::class)
        ->assertOk()
        ->assertSee('Abonnements')
        ->set('filter', '7')
        ->assertOk();
});

test('the subscriptions chart buckets new subscriptions per day', function (): void {
    Subscription::factory()->plan(SubscriptionPlan::MONTH)->count(2)->create();
    Subscription::factory()->plan(SubscriptionPlan::FREE)->create();

    $widget = new SubscriptionsChart;
    $widget->filter = '7';
    $data = chartData($widget);

    expect($data['labels'])->toHaveCount(7)
        ->and(array_sum($data['datasets'][0]['data']))->toBe(3)
        ->and(array_sum($data['datasets'][1]['data']))->toBe(2);
});

test('the plan distribution chart lists only plans with active subscriptions', function (): void {
    Subscription::factory()->plan(SubscriptionPlan::MONTH)->count(2)->create();
    Subscription::factory()->plan(SubscriptionPlan::LIFETIME)->create();
    Subscription::factory()->plan(SubscriptionPlan::WEEK)->expired()->create();

    $data = chartData(new SubscriptionPlansChart);

    expect($data['labels'])->toBe(['Monthly', 'Lifetime'])
        ->and($data['datasets'][0]['data'])->toBe([2, 1]);

    Livewire::test(SubscriptionPlansChart::class)->assertOk();
});

test('the api tokens chart counts created and used tokens', function (): void {
    $token = User::factory()->create()->createToken('test')->accessToken;
    $token->forceFill(['last_used_at' => now()])->save();
    User::factory()->create()->createToken('unused');

    $widget = new ApiTokensChart;
    $widget->filter = '7';
    $data = chartData($widget);

    expect(array_sum($data['datasets'][0]['data']))->toBe(2)
        ->and(array_sum($data['datasets'][1]['data']))->toBe(1);

    Livewire::test(ApiTokensChart::class)->assertOk();
});
