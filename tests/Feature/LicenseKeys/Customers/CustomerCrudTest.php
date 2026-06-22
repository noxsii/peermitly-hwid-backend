<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Team;
use App\Models\User;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create();
    $this->user->forceFill(['current_team_id' => $this->team->id])->save();
});

test('store creates a customer for the current team', function (): void {
    $this->actingAs($this->user)
        ->from('/license-keys/customers')
        ->post('/license-keys/customers', [
            'email' => 'ada@example.com',
            'name' => 'Ada Lovelace',
            'company' => 'Analytical Engines Ltd.',
        ])
        ->assertRedirect('/license-keys/customers');

    expect(Customer::query()->where('team_id', $this->team->id)->count())->toBe(1);

    $customer = Customer::query()->firstOrFail();
    expect($customer->email)->toBe('ada@example.com')
        ->and($customer->name)->toBe('Ada Lovelace')
        ->and($customer->company)->toBe('Analytical Engines Ltd.')
        ->and($customer->team_id)->toBe($this->team->id);
});

test('store requires a valid email', function (): void {
    $this->actingAs($this->user)
        ->from('/license-keys/customers')
        ->post('/license-keys/customers', ['email' => 'not-an-email'])
        ->assertRedirect('/license-keys/customers')
        ->assertSessionHasErrors('email');
});

test('store rejects duplicate email within the same team', function (): void {
    Customer::factory()->forTeam($this->team)->create(['email' => 'ada@example.com']);

    $this->actingAs($this->user)
        ->from('/license-keys/customers')
        ->post('/license-keys/customers', ['email' => 'ada@example.com'])
        ->assertRedirect('/license-keys/customers')
        ->assertSessionHasErrors('email');
});

test('store allows the same email in different teams', function (): void {
    $otherTeam = Team::factory()->create();
    Customer::factory()->forTeam($otherTeam)->create(['email' => 'ada@example.com']);

    $this->actingAs($this->user)
        ->from('/license-keys/customers')
        ->post('/license-keys/customers', ['email' => 'ada@example.com'])
        ->assertRedirect('/license-keys/customers')
        ->assertSessionDoesntHaveErrors('email');
});

test('update changes the customer fields', function (): void {
    $customer = Customer::factory()->forTeam($this->team)->create(['email' => 'before@example.com']);

    $this->actingAs($this->user)
        ->from('/license-keys/customers')
        ->patch("/license-keys/customers/{$customer->uuid}", [
            'email' => 'after@example.com',
            'name' => 'After',
            'company' => 'New Co',
        ])
        ->assertRedirect('/license-keys/customers');

    expect($customer->fresh()->email)->toBe('after@example.com')
        ->and($customer->fresh()->name)->toBe('After')
        ->and($customer->fresh()->company)->toBe('New Co');
});

test('update returns 404 for a customer from another team', function (): void {
    $otherTeam = Team::factory()->create();
    $customer = Customer::factory()->forTeam($otherTeam)->create();

    $this->actingAs($this->user)
        ->patch("/license-keys/customers/{$customer->uuid}", [
            'email' => 'hijack@example.com',
        ])
        ->assertNotFound();
});

test('destroy deletes the customer', function (): void {
    $customer = Customer::factory()->forTeam($this->team)->create();

    $this->actingAs($this->user)
        ->from('/license-keys/customers')
        ->delete("/license-keys/customers/{$customer->uuid}")
        ->assertRedirect('/license-keys/customers');

    expect(Customer::query()->find($customer->id))->toBeNull();
});

test('destroy returns 404 for a customer from another team', function (): void {
    $otherTeam = Team::factory()->create();
    $customer = Customer::factory()->forTeam($otherTeam)->create();

    $this->actingAs($this->user)
        ->delete("/license-keys/customers/{$customer->uuid}")
        ->assertNotFound();

    expect(Customer::query()->find($customer->id))->not->toBeNull();
});
