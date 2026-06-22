<?php

declare(strict_types=1);

use App\Actions\Dashboard\GetDashboardStatsAction;
use App\Enums\LicenseKeyStatus;
use App\Models\Customer;
use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('unauthenticated visitor is redirected to login', function (): void {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated verified user can access dashboard', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page->component('Dashboard'));
});

test('dashboard route is named dashboard', function (): void {
    expect(route('dashboard', absolute: false))->toBe('/dashboard');
});

test('GetDashboardStatsAction scopes counts to the given team', function (): void {
    $team = Team::factory()->create();
    $otherTeam = Team::factory()->create();

    $product = Product::factory()->forTeam($team)->create();
    $type = LicenseKeyType::factory()->forTeam($team)->create();

    LicenseKey::factory()
        ->forTeam($team)
        ->active()
        ->state([
            'license_key_type_id' => $type->id,
            'product_id' => $product->id,
        ])
        ->count(3)
        ->create();

    LicenseKey::factory()
        ->forTeam($team)
        ->state([
            'license_key_type_id' => $type->id,
            'product_id' => $product->id,
            'status' => LicenseKeyStatus::PENDING->value,
        ])
        ->count(2)
        ->create();

    // Noise in another team — must NOT be counted.
    $otherProduct = Product::factory()->forTeam($otherTeam)->create();
    $otherType = LicenseKeyType::factory()->forTeam($otherTeam)->create();
    LicenseKey::factory()
        ->forTeam($otherTeam)
        ->active()
        ->state([
            'license_key_type_id' => $otherType->id,
            'product_id' => $otherProduct->id,
        ])
        ->count(5)
        ->create();

    Customer::factory()->forTeam($team)->count(4)->create();
    Customer::factory()->forTeam($otherTeam)->count(7)->create();

    $stats = resolve(GetDashboardStatsAction::class)->handle($team->id);

    expect($stats->activeLicenseKeys)->toBe(3)
        ->and($stats->pendingLicenseKeys)->toBe(2)
        ->and($stats->customers)->toBe(4)
        ->and($stats->products)->toBe(1)
        ->and($stats->licenseKeyTypes)->toBe(1);
});
