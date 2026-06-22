<?php

declare(strict_types=1);

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Models\AppRelease;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

function actingAsSubscribedClient(): User
{
    $user = User::factory()->create(['is_active' => true]);
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::WEEK);
    Sanctum::actingAs($user, ['spoofer:use']);

    return $user;
}

test('update check requires authentication', function (): void {
    $this->getJson('/api/update/windows/x86_64/0.1.0')->assertUnauthorized();
});

test('clients without an active subscription are forbidden', function (): void {
    $user = User::factory()->create(['is_active' => true]);
    Sanctum::actingAs($user, ['spoofer:use']);

    $this->getJson('/api/update/windows/x86_64/0.1.0')->assertForbidden();
});

test('returns 204 when the client is already up to date', function (): void {
    actingAsSubscribedClient();
    AppRelease::factory()->version('1.0.0')->create(['platform' => 'windows-x86_64']);

    $this->getJson('/api/update/windows/x86_64/1.0.0')->assertNoContent();
});

test('returns 204 when the client is ahead of the latest release', function (): void {
    actingAsSubscribedClient();
    AppRelease::factory()->version('1.0.0')->create(['platform' => 'windows-x86_64']);

    $this->getJson('/api/update/windows/x86_64/2.5.0')->assertNoContent();
});

test('returns the update manifest when a newer release exists', function (): void {
    actingAsSubscribedClient();
    $release = AppRelease::factory()->version('0.2.0')->create([
        'platform' => 'windows-x86_64',
        'signature' => 'dummy-signature-content',
        'notes' => 'Shiny new build',
        'published_at' => now(),
    ]);

    $this->getJson('/api/update/windows/x86_64/0.1.0')
        ->assertOk()
        ->assertJsonPath('version', '0.2.0')
        ->assertJsonPath('signature', 'dummy-signature-content')
        ->assertJsonPath('notes', 'Shiny new build')
        ->assertJsonPath('url', route('api.update.download', ['version' => '0.2.0']))
        ->assertJsonStructure(['version', 'pub_date', 'url', 'signature', 'notes']);
});

test('a v-prefixed stored version still compares correctly', function (): void {
    actingAsSubscribedClient();
    AppRelease::factory()->version('v0.2.0')->create(['platform' => 'windows-x86_64']);

    $this->getJson('/api/update/windows/x86_64/0.1.0')->assertOk();
    $this->getJson('/api/update/windows/x86_64/0.2.0')->assertNoContent();
});

test('inactive releases are never offered', function (): void {
    actingAsSubscribedClient();
    AppRelease::factory()->inactive()->version('0.2.0')->create(['platform' => 'windows-x86_64']);

    $this->getJson('/api/update/windows/x86_64/0.1.0')->assertNoContent();
});

test('releases for a different platform are ignored', function (): void {
    actingAsSubscribedClient();
    AppRelease::factory()->version('0.2.0')->create(['platform' => 'macos-aarch64']);

    $this->getJson('/api/update/windows/x86_64/0.1.0')->assertNoContent();
});

test('the latest published release wins', function (): void {
    actingAsSubscribedClient();
    AppRelease::factory()->version('0.2.0')->create([
        'platform' => 'windows-x86_64',
        'published_at' => now()->subDays(5),
    ]);
    AppRelease::factory()->version('0.3.0')->create([
        'platform' => 'windows-x86_64',
        'published_at' => now(),
    ]);

    $this->getJson('/api/update/windows/x86_64/0.1.0')
        ->assertOk()
        ->assertJsonPath('version', '0.3.0');
});

test('the updater can download the installer', function (): void {
    Storage::fake('local');
    Storage::disk('local')->put('app-releases/peermitly-0.2.0.exe', 'installer-bytes');

    actingAsSubscribedClient();
    AppRelease::factory()->version('0.2.0')->create([
        'file_path' => 'app-releases/peermitly-0.2.0.exe',
        'file_name' => 'peermitly-0.2.0.exe',
    ]);

    $this->get('/api/update/download/0.2.0')
        ->assertOk()
        ->assertDownload('peermitly-0.2.0.exe');
});

test('downloading an inactive version returns 404', function (): void {
    Storage::fake('local');
    actingAsSubscribedClient();
    AppRelease::factory()->inactive()->version('0.2.0')->create();

    $this->get('/api/update/download/0.2.0')->assertNotFound();
});
