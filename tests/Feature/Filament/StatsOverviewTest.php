<?php

declare(strict_types=1);

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Enums\UserRole;
use App\Filament\Widgets\StatsOverview;
use App\Models\User;
use Livewire\Livewire;

test('the stats widget renders the headline numbers', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    User::factory()->count(3)->create();

    resolve(GrantSubscriptionAction::class)->handle($admin, SubscriptionPlan::MONTH);

    $this->actingAs($admin);

    Livewire::test(StatsOverview::class)
        ->assertOk()
        ->assertSee('Benutzer')
        ->assertSee('Aktive Abonnements')
        ->assertSee('API-Schlüssel')
        ->assertSee((string) User::count())
        ->assertSee('1');
});
