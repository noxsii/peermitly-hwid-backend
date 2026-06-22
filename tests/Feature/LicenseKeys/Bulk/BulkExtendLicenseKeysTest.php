<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Jobs\BulkExtendLicenseKeysJob;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

beforeEach(function (): void {
    Queue::fake();

    $this->team = Team::factory()->create();
    $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $this->admin->forceFill(['current_team_id' => $this->team->id])->save();
});

test('admin can dispatch bulk extend job with valid input', function (): void {
    $this->actingAs($this->admin)
        ->from('/license-keys')
        ->post('/license-keys/bulk-extend', [
            'from_expires_at' => now()->addDays(5)->toIso8601String(),
            'amount' => 3,
            'unit' => 'months',
        ])
        ->assertRedirect('/license-keys')
        ->assertSessionHas('success');

    Queue::assertPushed(BulkExtendLicenseKeysJob::class, fn (BulkExtendLicenseKeysJob $job): bool => $job->teamId === $this->team->id
        && $job->amount === 3
        && $job->unit === 'months'
        && $job->createdById === $this->admin->id);
});

test('regular user can also trigger bulk extend', function (): void {
    $user = User::factory()->create(['role' => UserRole::USER]);
    $user->forceFill(['current_team_id' => $this->team->id])->save();

    $this->actingAs($user)
        ->from('/license-keys')
        ->post('/license-keys/bulk-extend', [
            'from_expires_at' => now()->addDay()->toIso8601String(),
            'amount' => 1,
            'unit' => 'days',
        ])
        ->assertRedirect('/license-keys')
        ->assertSessionHasNoErrors();

    Queue::assertPushed(BulkExtendLicenseKeysJob::class);
});

test('guest is redirected to login', function (): void {
    $this->post('/license-keys/bulk-extend', [
        'from_expires_at' => now()->addDay()->toIso8601String(),
        'amount' => 1,
        'unit' => 'days',
    ])->assertRedirect('/login');
});

test('validation rejects missing date', function (): void {
    $this->actingAs($this->admin)
        ->from('/license-keys')
        ->post('/license-keys/bulk-extend', [
            'amount' => 1,
            'unit' => 'days',
        ])
        ->assertSessionHasErrors('from_expires_at');
});

test('validation rejects amount < 1', function (): void {
    $this->actingAs($this->admin)
        ->from('/license-keys')
        ->post('/license-keys/bulk-extend', [
            'from_expires_at' => now()->toIso8601String(),
            'amount' => 0,
            'unit' => 'days',
        ])
        ->assertSessionHasErrors('amount');
});

test('validation rejects amount > 10000', function (): void {
    $this->actingAs($this->admin)
        ->from('/license-keys')
        ->post('/license-keys/bulk-extend', [
            'from_expires_at' => now()->toIso8601String(),
            'amount' => 10001,
            'unit' => 'days',
        ])
        ->assertSessionHasErrors('amount');
});

test('validation rejects unknown unit', function (): void {
    $this->actingAs($this->admin)
        ->from('/license-keys')
        ->post('/license-keys/bulk-extend', [
            'from_expires_at' => now()->toIso8601String(),
            'amount' => 1,
            'unit' => 'foo',
        ])
        ->assertSessionHasErrors('unit');
});

test('validation rejects lifetime unit', function (): void {
    $this->actingAs($this->admin)
        ->from('/license-keys')
        ->post('/license-keys/bulk-extend', [
            'from_expires_at' => now()->toIso8601String(),
            'amount' => 1,
            'unit' => 'lifetime',
        ])
        ->assertSessionHasErrors('unit');
});

test('validation accepts all time-based units', function (): void {
    foreach (['hours', 'days', 'weeks', 'months', 'years'] as $unit) {
        Queue::fake();

        $this->actingAs($this->admin)
            ->from('/license-keys')
            ->post('/license-keys/bulk-extend', [
                'from_expires_at' => now()->toIso8601String(),
                'amount' => 1,
                'unit' => $unit,
            ])
            ->assertRedirect('/license-keys')
            ->assertSessionHasNoErrors();
    }
});
