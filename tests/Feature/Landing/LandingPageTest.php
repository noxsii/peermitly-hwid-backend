<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('home route responds with 200 for guests', function (): void {
    $this->get('/')->assertOk();
});

test('home route renders landing/Index inertia component for guests', function (): void {
    $this->get('/')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page->component('landing/Index'));
});

test('authenticated users see the landing page too', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page->component('landing/Index'));
});

test('home route is named home', function (): void {
    expect(route('home', absolute: false))->toBe('/');
});

test('home route renders without error after redesign', function (): void {
    $this->get('/')->assertOk()->assertSee('Peermitly', escape: false);
});

test('home route passes seo props for meta tags', function (): void {
    $this->get('/')
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->component('landing/Index')
            ->where('canonical', url('/'))
            ->where('ogImage', url('/og-image.png')));
});
