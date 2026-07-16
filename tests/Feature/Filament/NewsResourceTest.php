<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Pages\EditNews;
use App\Filament\Resources\News\Pages\ListNews;
use App\Models\News;
use App\Models\User;
use Livewire\Livewire;

test('the news list shows created articles', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $article = News::factory()->create(['title' => 'Big Launch']);

    $this->actingAs($admin);

    Livewire::test(ListNews::class)
        ->assertCanSeeTableRecords([$article])
        ->assertCanRenderTableColumn('title');
});

test('the create page renders the expected fields', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $this->actingAs($admin);

    Livewire::test(CreateNews::class)
        ->assertOk()
        ->assertFormFieldExists('title')
        ->assertFormFieldExists('description')
        ->assertFormFieldExists('image_path')
        ->assertFormFieldExists('content');
});

test('creating a news article auto-generates a slug when left empty', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $this->actingAs($admin);

    Livewire::test(CreateNews::class)
        ->fillForm([
            'title' => 'Our Big Launch',
            'description' => 'A short summary of the launch.',
            'content' => '<p>Full content here.</p>',
            'published_at' => now(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(News::query()->where('slug', 'our-big-launch')->exists())->toBeTrue();
});

test('editing a news article auto-generates a slug when cleared', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $news = News::factory()->create([
        'title' => 'Old Title',
        'slug' => 'old-title',
        'published_at' => now(),
    ]);
    $this->actingAs($admin);

    Livewire::test(EditNews::class, ['record' => $news->slug])
        ->fillForm([
            'title' => 'Updated Title',
            'slug' => '',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($news->refresh()->slug)->toBe('updated-title');
});

test('slug must be unique', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    News::factory()->create(['slug' => 'taken']);
    $this->actingAs($admin);

    Livewire::test(CreateNews::class)
        ->fillForm([
            'title' => 'Another',
            'slug' => 'taken',
            'description' => 'Summary.',
            'content' => '<p>Body.</p>',
        ])
        ->call('create')
        ->assertHasFormErrors(['slug' => 'unique']);
});
