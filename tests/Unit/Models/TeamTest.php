<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;

test('team has bigint id and separate uuid column', function (): void {
    $team = Team::factory()->create();

    expect($team->id)->toBeInt();
    expect($team->uuid)
        ->toBeString()
        ->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i');
});

test('team belongs to owner user', function (): void {
    $owner = User::factory()->create();
    $team = Team::factory()->ownedBy($owner)->create();

    expect($team->owner->is($owner))->toBeTrue();
    expect($team->owner_id)->toBe($owner->id);
});

test('team has many users via pivot', function (): void {
    $team = Team::factory()->create();
    $users = User::factory()->count(3)->create();
    $team->users()->attach($users->pluck('id'));

    expect($team->users)->toHaveCount(3);
});

test('user has many teams via pivot', function (): void {
    $user = User::factory()->create();
    $teams = Team::factory()->count(2)->create();
    $user->teams()->attach($teams->pluck('id'));

    expect($user->teams)->toHaveCount(2);
});

test('user current team relation resolves', function (): void {
    $team = Team::factory()->create();
    $user = User::factory()->create(['current_team_id' => $team->id]);

    expect($user->currentTeam->is($team))->toBeTrue();
});

test('user owned teams relation lists teams where user is owner', function (): void {
    $owner = User::factory()->create();
    Team::factory()->ownedBy($owner)->count(2)->create();

    expect($owner->ownedTeams)->toHaveCount(2);
});

test('team route key is uuid', function (): void {
    $team = Team::factory()->create();

    expect($team->getRouteKey())->toBe($team->uuid);
});

test('deleting owner cascades and deletes their teams', function (): void {
    $owner = User::factory()->create();
    $team = Team::factory()->ownedBy($owner)->create();
    $teamId = $team->id;

    $owner->delete();

    expect(Team::query()->find($teamId))->toBeNull();
});
