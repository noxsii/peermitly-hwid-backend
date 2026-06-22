<?php

declare(strict_types=1);

use App\Actions\LicenseKeys\BulkExtendLicenseKeysAction;
use App\Enums\LicenseValidityUnit;
use App\Enums\UserRole;
use App\Jobs\BulkExtendLicenseKeysJob;
use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use App\Notifications\LicenseKeysBulkExtended;
use Illuminate\Support\Facades\Notification;

beforeEach(function (): void {
    Notification::fake();

    $this->team = Team::factory()->create();
    $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $this->admin->forceFill(['current_team_id' => $this->team->id])->save();

    $this->product = Product::factory()->forTeam($this->team)->create();
    $this->type = LicenseKeyType::factory()->forTeam($this->team)->create();
});

test('job extends keys and notifies the dispatching user', function (): void {
    LicenseKey::factory()
        ->forTeam($this->team)
        ->active()
        ->state([
            'license_key_type_id' => $this->type->id,
            'product_id' => $this->product->id,
            'expires_at' => now()->addDays(30),
        ])
        ->count(3)
        ->create();

    $job = new BulkExtendLicenseKeysJob(
        teamId: $this->team->id,
        fromExpiresAtIso: now()->toIso8601String(),
        amount: 1,
        unit: 'months',
        createdById: $this->admin->id,
    );

    $job->handle(resolve(BulkExtendLicenseKeysAction::class));

    Notification::assertSentTo(
        $this->admin,
        LicenseKeysBulkExtended::class,
        fn (LicenseKeysBulkExtended $notification): bool => $notification->count === 3
            && $notification->amount === 1
            && $notification->unit->value === 'months',
    );
});

test('notification database payload contains expected fields', function (): void {
    $notification = new LicenseKeysBulkExtended(
        count: 7,
        amount: 2,
        unit: LicenseValidityUnit::WEEKS,
    );

    $data = $notification->toDatabase();

    expect($data['title'])->toBe('License keys extended')
        ->and($data['count'])->toBe(7)
        ->and($data['amount'])->toBe(2)
        ->and($data['unit'])->toBe('weeks')
        ->and($data['url'])->toBe('/license-keys')
        ->and($data['message'])->toContain('7 license keys')
        ->and($data['message'])->toContain('2 weeks');
});
