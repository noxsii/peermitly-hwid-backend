<?php

declare(strict_types=1);

use App\Actions\Team\UpdateTeamAction;
use App\Data\Team\UpdateTeamData;
use App\Models\Team;

test('handle persists the new name on the team', function (): void {
    $team = Team::factory()->create(['name' => 'Old Name']);

    new UpdateTeamAction()->handle($team, new UpdateTeamData(name: 'New Name'));

    expect($team->fresh()->name)->toBe('New Name');
});

test('handle leaves the owner_id unchanged', function (): void {
    $team = Team::factory()->create(['name' => 'Keep Owner']);
    $originalOwnerId = $team->owner_id;

    new UpdateTeamAction()->handle($team, new UpdateTeamData(name: 'Renamed'));

    expect($team->fresh()->owner_id)->toBe($originalOwnerId);
});

test('handle returns void', function (): void {
    $team = Team::factory()->create();

    $result = new UpdateTeamAction()->handle($team, new UpdateTeamData(name: 'Whatever'));

    expect($result)->toBeNull();
});

test('handle persists names containing unicode characters', function (): void {
    $team = Team::factory()->create(['name' => 'Plain']);

    new UpdateTeamAction()->handle($team, new UpdateTeamData(name: 'Größer & Bäcker 🍞'));

    expect($team->fresh()->name)->toBe('Größer & Bäcker 🍞');
});
