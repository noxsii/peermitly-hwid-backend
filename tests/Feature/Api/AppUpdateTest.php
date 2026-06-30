<?php

declare(strict_types=1);

use App\Models\AppRelease;

test('the updater returns the latest published release in the tauri manifest shape', function (): void {
    AppRelease::factory()->create([
        'version' => '0.1.0',
        'published_at' => now()->subDay(),
    ]);
    $latest = AppRelease::factory()->create([
        'version' => '0.2.0',
        'notes' => 'New stuff',
        'platforms' => [
            ['platform' => 'darwin-aarch64', 'path' => 'releases/app_0.2.0.app.tar.gz', 'signature' => 'sig-mac'],
            ['platform' => 'windows-x86_64', 'path' => 'releases/app_0.2.0_x64.msi.zip', 'signature' => 'sig-win'],
        ],
        'published_at' => now(),
    ]);

    $this->getJson('/api/update/latest')
        ->assertOk()
        ->assertJsonPath('version', '0.2.0')
        ->assertJsonPath('notes', 'New stuff')
        ->assertJsonPath('platforms.darwin-aarch64.signature', 'sig-mac')
        ->assertJsonPath('platforms.windows-x86_64.signature', 'sig-win')
        ->assertJsonPath('platforms.darwin-aarch64.url', url('/storage/releases/app_0.2.0.app.tar.gz'));

    expect($latest->version)->toBe('0.2.0');
});

test('unpublished releases are not served', function (): void {
    AppRelease::factory()->unpublished()->create(['version' => '0.9.0']);

    $this->getJson('/api/update/latest')->assertNoContent();
});

test('the updater responds 204 when no release exists', function (): void {
    $this->getJson('/api/update/latest')->assertNoContent();
});

test('the update endpoint is public and needs no auth', function (): void {
    AppRelease::factory()->create();

    $this->getJson('/api/update/latest')->assertOk();
});
