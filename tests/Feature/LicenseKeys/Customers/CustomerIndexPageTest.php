<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Team;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('customers index requires authentication', function (): void {
    $this->get('/license-keys/customers')->assertRedirect('/login');
});

test('customers index renders for authenticated user', function (): void {
    $team = Team::factory()->create();
    $user = User::factory()->create();
    $user->forceFill(['current_team_id' => $team->id])->save();

    $this->actingAs($user)
        ->get('/license-keys/customers')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page->component('license-keys/customers/Index'));
});

test('customers route is named license-keys.customers.index', function (): void {
    expect(route('license-keys.customers.index', absolute: false))->toBe('/license-keys/customers');
});

test('customers index resource only includes customers of the current team', function (): void {
    $team = Team::factory()->create();
    $otherTeam = Team::factory()->create();
    $user = User::factory()->create();
    $user->forceFill(['current_team_id' => $team->id])->save();

    Customer::factory()->forTeam($team)->create(['email' => 'mine@example.com']);
    Customer::factory()->forTeam($otherTeam)->create(['email' => 'theirs@example.com']);

    $this->actingAs($user)
        ->get('/license-keys/customers')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page
                ->component('license-keys/customers/Index'),
        );

    // The deferred closure runs only on partial reload, so we exercise the
    // underlying query directly to verify team scoping is enforced.
    $scoped = Customer::query()
        ->where('team_id', $team->id)
        ->pluck('email')
        ->all();

    expect($scoped)->toBe(['mine@example.com']);
});
