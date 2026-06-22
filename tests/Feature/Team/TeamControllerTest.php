<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('admin can view team index with owned teams', function (): void {
    $admin = User::factory()->admin()->create();
    $team = Team::factory()->ownedBy($admin)->create(['name' => 'Alpha']);

    $this->actingAs($admin)
        ->get('/team')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->component('team/Index'));

    // Deferred closure runs only on partial reload; verify the underlying query directly.
    $teams = $admin->ownedTeams()->orderBy('name')->get(['id', 'uuid', 'name']);

    expect($teams)->toHaveCount(1)
        ->and($teams->first()->uuid)->toBe($team->uuid)
        ->and($teams->first()->name)->toBe('Alpha');
});

test('super admin can view team index', function (): void {
    $superAdmin = User::factory()->superAdmin()->create();
    Team::factory()->ownedBy($superAdmin)->create();

    $this->actingAs($superAdmin)
        ->get('/team')
        ->assertOk();
});

test('admin can rename own team', function (): void {
    $admin = User::factory()->admin()->create();
    $team = Team::factory()->ownedBy($admin)->create(['name' => 'Old Name']);

    $this->actingAs($admin)
        ->from('/team')
        ->patch("/team/{$team->uuid}", ['name' => 'New Name'])
        ->assertRedirect('/team')
        ->assertSessionHas('success');

    expect($team->fresh()->name)->toBe('New Name');
});

test('regular user gets 403 on team index', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/team')
        ->assertForbidden();
});

test('guest cannot access team index', function (): void {
    $this->get('/team')->assertRedirect('/login');
});

test('guest cannot update team', function (): void {
    $team = Team::factory()->create(['name' => 'Keep']);

    $this->patch("/team/{$team->uuid}", ['name' => 'Hacked'])
        ->assertRedirect('/login');

    expect($team->fresh()->name)->toBe('Keep');
});

test('regular user gets 403 on team update', function (): void {
    $user = User::factory()->create();
    $admin = User::factory()->admin()->create();
    $team = Team::factory()->ownedBy($admin)->create(['name' => 'Keep']);

    $this->actingAs($user)
        ->patch("/team/{$team->uuid}", ['name' => 'Hacked'])
        ->assertForbidden();

    expect($team->fresh()->name)->toBe('Keep');
});

test('admin cannot update foreign team', function (): void {
    $admin = User::factory()->admin()->create();
    $otherAdmin = User::factory()->admin()->create();
    $foreignTeam = Team::factory()->ownedBy($otherAdmin)->create(['name' => 'Foreign']);

    $this->actingAs($admin)
        ->patch("/team/{$foreignTeam->uuid}", ['name' => 'Hacked'])
        ->assertForbidden();

    expect($foreignTeam->fresh()->name)->toBe('Foreign');
});

test('update fails when name is missing', function (): void {
    $admin = User::factory()->admin()->create();
    $team = Team::factory()->ownedBy($admin)->create(['name' => 'Keep']);

    $this->actingAs($admin)
        ->from('/team')
        ->patch("/team/{$team->uuid}", ['name' => ''])
        ->assertSessionHasErrors('name');

    expect($team->fresh()->name)->toBe('Keep');
});

test('update fails when name exceeds 255 chars', function (): void {
    $admin = User::factory()->admin()->create();
    $team = Team::factory()->ownedBy($admin)->create(['name' => 'Keep']);

    $this->actingAs($admin)
        ->from('/team')
        ->patch("/team/{$team->uuid}", ['name' => str_repeat('a', 256)])
        ->assertSessionHasErrors('name');
});

test('team route names resolve correctly', function (): void {
    expect(route('team.index', absolute: false))->toBe('/team');
});
