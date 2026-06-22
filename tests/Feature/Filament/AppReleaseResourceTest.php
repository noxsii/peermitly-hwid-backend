<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Filament\Resources\AppReleases\Pages\CreateAppRelease;
use App\Filament\Resources\AppReleases\Pages\ListAppReleases;
use App\Models\AppRelease;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->actingAs(User::factory()->create(['role' => UserRole::SUPER_ADMIN]));
});

test('the app versions list shows uploaded releases', function (): void {
    $release = AppRelease::factory()->create(['version' => 'v3.1.0']);

    Livewire::test(ListAppReleases::class)
        ->assertCanSeeTableRecords([$release])
        ->assertCanRenderTableColumn('version')
        ->assertCanRenderTableColumn('file_size');
});

test('an admin can upload a new app version', function (): void {
    Storage::fake('local');

    $contents = str_repeat('A', 4096);

    Livewire::test(CreateAppRelease::class)
        ->fillForm([
            'version' => 'v4.0.0',
            'file_path' => UploadedFile::fake()->createWithContent('peermitly-setup.exe', $contents),
            'notes' => 'First public build.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $release = AppRelease::query()->where('version', 'v4.0.0')->firstOrFail();

    expect($release->file_name)->toBe('peermitly-setup.exe')
        ->and($release->file_size)->toBe(strlen($contents))
        ->and($release->notes)->toBe('First public build.');

    Storage::disk('local')->assertExists($release->file_path);
});

test('version must be unique', function (): void {
    AppRelease::factory()->create(['version' => 'v5.0.0']);
    Storage::fake('local');

    Livewire::test(CreateAppRelease::class)
        ->fillForm([
            'version' => 'v5.0.0',
            'file_path' => UploadedFile::fake()->create('dup.exe', 100),
        ])
        ->call('create')
        ->assertHasFormErrors(['version']);
});
