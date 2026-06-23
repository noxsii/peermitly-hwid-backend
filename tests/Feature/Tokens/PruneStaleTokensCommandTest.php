<?php

declare(strict_types=1);

use App\Actions\Tokens\DeleteStaleTokenAction;
use App\Jobs\DeleteStaleTokenJob;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\PersonalAccessToken;

function makeToken(?Carbon $lastUsedAt): PersonalAccessToken
{
    $token = User::factory()->create()->createToken('test')->accessToken;
    $token->forceFill(['last_used_at' => $lastUsedAt])->save();

    return $token->refresh();
}

test('the command runs end-to-end and deletes only stale tokens', function (): void {
    $neverUsed = makeToken(null);
    $stale = makeToken(Carbon::now()->subDays(4));
    $fresh = makeToken(Carbon::now()->subDay());

    $this->artisan('tokens:prune')->assertSuccessful();

    expect(PersonalAccessToken::find($neverUsed->id))->toBeNull()
        ->and(PersonalAccessToken::find($stale->id))->toBeNull()
        ->and(PersonalAccessToken::find($fresh->id))->not->toBeNull();
});

test('a token used exactly three days ago is deleted', function (): void {
    $token = makeToken(Carbon::now()->subDays(3));

    $this->artisan('tokens:prune')->assertSuccessful();

    expect(PersonalAccessToken::find($token->id))->toBeNull();
});

test('the command dispatches one job per stale token', function (): void {
    Queue::fake();

    makeToken(null);
    makeToken(Carbon::now()->subDays(5));
    makeToken(Carbon::now()->subDay());

    $this->artisan('tokens:prune')->assertSuccessful();

    Queue::assertPushed(DeleteStaleTokenJob::class, 2);
});

test('the command dispatches nothing when there are no stale tokens', function (): void {
    Queue::fake();

    makeToken(Carbon::now()->subDay());

    $this->artisan('tokens:prune')->assertSuccessful();

    Queue::assertNothingPushed();
});

test('the job deletes its stale token when handled', function (): void {
    $token = makeToken(null);

    new DeleteStaleTokenJob($token)->handle(resolve(DeleteStaleTokenAction::class));

    expect(PersonalAccessToken::find($token->id))->toBeNull();
});

test('the action keeps a fresh token', function (): void {
    $token = makeToken(Carbon::now()->subDay());

    $deleted = resolve(DeleteStaleTokenAction::class)->handle($token);

    expect($deleted)->toBeFalse()
        ->and(PersonalAccessToken::find($token->id))->not->toBeNull();
});

test('the prune command is scheduled daily at 00:01', function (): void {
    $events = collect(resolve(Schedule::class)->events());

    $event = $events->first(
        fn ($event): bool => str_contains((string) $event->command, 'tokens:prune'),
    );

    expect($event)->not->toBeNull()
        ->and($event->expression)->toBe('1 0 * * *');
});
