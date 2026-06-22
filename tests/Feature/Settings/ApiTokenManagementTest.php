<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;
use App\Support\TokenAbility;
use Laravel\Sanctum\PersonalAccessToken;

test('settings page exposes token abilities catalogue', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk();
});

test('store creates personal access token with selected abilities', function (): void {
    $user = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($user)
        ->postJson('/settings/api-tokens', [
            'name' => 'OfficeEfficient prod',
            'abilities' => [TokenAbility::LICENSE_KEYS_CHECK],
        ]);

    $response->assertCreated()
        ->assertJsonPath('name', 'OfficeEfficient prod')
        ->assertJsonStructure(['id', 'name', 'abilities', 'plain_text_token']);

    expect(PersonalAccessToken::query()->where('tokenable_id', $user->id)->count())->toBe(1);
});

test('store rejects unknown ability', function (): void {
    $user = User::factory()->create(['role' => UserRole::ADMIN]);

    $this->actingAs($user)
        ->postJson('/settings/api-tokens', [
            'name' => 'bad',
            'abilities' => ['foo:bar'],
        ])->assertStatus(422)
        ->assertJsonValidationErrors('abilities.0');
});

test('non-admin user gets 403 when creating a token', function (): void {
    $user = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($user)
        ->postJson('/settings/api-tokens', [
            'name' => 'blocked',
            'abilities' => [TokenAbility::LICENSE_KEYS_CHECK],
        ])
        ->assertForbidden();

    expect(PersonalAccessToken::query()->count())->toBe(0);
});

test('destroy removes token owned by user', function (): void {
    $user = User::factory()->create(['role' => UserRole::ADMIN]);
    $token = $user->createToken('test', [TokenAbility::LICENSE_KEYS_CHECK])->accessToken;

    $this->actingAs($user)
        ->delete('/settings/api-tokens/'.$token->id)
        ->assertRedirect();

    expect(PersonalAccessToken::query()->find($token->id))->toBeNull();
});

test('destroy returns 404 for foreign token', function (): void {
    $user = User::factory()->create(['role' => UserRole::ADMIN]);
    $other = User::factory()->create(['role' => UserRole::ADMIN]);
    $token = $other->createToken('test', [TokenAbility::LICENSE_KEYS_CHECK])->accessToken;

    $this->actingAs($user)
        ->delete('/settings/api-tokens/'.$token->id)
        ->assertNotFound();

    expect(PersonalAccessToken::query()->find($token->id))->not->toBeNull();
});

test('non-admin user gets 403 when deleting a token', function (): void {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $token = $admin->createToken('test', [TokenAbility::LICENSE_KEYS_CHECK])->accessToken;

    $user = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($user)
        ->delete('/settings/api-tokens/'.$token->id)
        ->assertForbidden();

    expect(PersonalAccessToken::query()->find($token->id))->not->toBeNull();
});
