<?php

declare(strict_types=1);

use App\Models\User;

test('the dashboard offers the macOS download with the same link as the landing page', function (): void {
    $user = User::factory()->create();

    $page = $this->actingAs($user)->visit('/dashboard');

    $page->assertNoJavascriptErrors()
        ->assertSee('Download for macOS')
        ->assertSee('xattr -cr /Applications/peermitly.app')
        ->assertAttribute(
            'a[download]',
            'href',
            'https://peermitly.de/storage/releases/peermitly_universal.dmg',
        );
});
