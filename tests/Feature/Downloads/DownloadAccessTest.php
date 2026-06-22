<?php

declare(strict_types=1);

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Http\Middleware\HandleInertiaRequests;
use App\Models\AppRelease;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia;

function subscribedUser(): User
{
    $user = User::factory()->create(['is_active' => true]);
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::WEEK);

    return $user;
}

/**
 * Partial reload that resolves the deferred `releases` prop.
 */
function loadDownloads(User $user): TestResponse
{
    $version = (string) resolve(HandleInertiaRequests::class)->version(request());

    return test()->actingAs($user)->withHeaders([
        'X-Inertia' => 'true',
        'X-Inertia-Version' => $version,
        'X-Inertia-Partial-Component' => 'downloads/Index',
        'X-Inertia-Partial-Data' => 'releases',
    ])->get('/downloads');
}

test('guests are redirected to login from the downloads page', function (): void {
    $this->get('/downloads')->assertRedirect('/login');
});

test('active users without a subscription cannot see downloads', function (): void {
    $user = User::factory()->create(['is_active' => true]);

    $this->actingAs($user)->get('/downloads')->assertForbidden();
});

test('inactive users with a subscription cannot see downloads', function (): void {
    $user = User::factory()->create(['is_active' => false]);
    resolve(GrantSubscriptionAction::class)->handle($user, SubscriptionPlan::WEEK);

    $this->actingAs($user)->get('/downloads')->assertForbidden();
});

test('users whose subscription expired cannot see downloads', function (): void {
    $user = User::factory()->create(['is_active' => true]);
    Subscription::factory()->for($user)->expired()->create();

    $this->actingAs($user)->get('/downloads')->assertForbidden();
});

test('the downloads page loads with releases deferred', function (): void {
    AppRelease::factory()->create(['version' => 'v2.0.0']);

    $this->actingAs(subscribedUser())
        ->get('/downloads')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->component('downloads/Index')
            ->missing('releases'));
});

test('active subscribed users resolve the deferred releases', function (): void {
    AppRelease::factory()->create(['version' => 'v2.0.0']);

    loadDownloads(subscribedUser())
        ->assertOk()
        ->assertJsonPath('component', 'downloads/Index')
        ->assertJsonCount(1, 'props.releases.data')
        ->assertJsonPath('props.releases.data.0.version', 'v2.0.0')
        ->assertJsonPath('props.releases.data.0.download_url', fn (?string $value): bool => is_string($value));
});

test('active subscribed users can download a release file', function (): void {
    Storage::fake('local');
    Storage::disk('local')->put('app-releases/peermitly-v1.exe', 'binary-content');

    $release = AppRelease::factory()->create([
        'version' => 'v1.0.0',
        'file_path' => 'app-releases/peermitly-v1.exe',
        'file_name' => 'peermitly-v1.exe',
    ]);

    $this->actingAs(subscribedUser())
        ->get(route('downloads.download', $release))
        ->assertOk()
        ->assertDownload('peermitly-v1.exe');
});

test('users without access cannot download a release file', function (): void {
    Storage::fake('local');
    Storage::disk('local')->put('app-releases/peermitly-v1.exe', 'binary-content');

    $release = AppRelease::factory()->create([
        'file_path' => 'app-releases/peermitly-v1.exe',
        'file_name' => 'peermitly-v1.exe',
    ]);

    $user = User::factory()->create(['is_active' => true]); // no subscription

    $this->actingAs($user)
        ->get(route('downloads.download', $release))
        ->assertForbidden();
});

test('a missing release file returns 404', function (): void {
    Storage::fake('local');

    $release = AppRelease::factory()->create([
        'file_path' => 'app-releases/does-not-exist.exe',
    ]);

    $this->actingAs(subscribedUser())
        ->get(route('downloads.download', $release))
        ->assertNotFound();
});
