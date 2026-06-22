<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Mockery\MockInterface;
use Spatie\LaravelPasskeys\Actions\GeneratePasskeyRegisterOptionsAction as SpatieGenerateRegisterOptionsAction;
use Spatie\LaravelPasskeys\Actions\StorePasskeyAction as SpatieStorePasskeyAction;
use Spatie\LaravelPasskeys\Models\Passkey;

function createPasskeyRow(int $userId, string $name = 'Macbook', string $credentialId = 'cred-id'): int
{
    return (int) DB::table('passkeys')->insertGetId([
        'authenticatable_id' => $userId,
        'name' => $name,
        'credential_id' => $credentialId,
        'data' => '{}',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

test('guests cannot request passkey registration options', function (): void {
    $this->postJson('/settings/passkeys/options')->assertUnauthorized();
});

test('authenticated user receives registration options as json and session stores raw options', function (): void {
    $user = User::factory()->create();

    $this->mock(
        SpatieGenerateRegisterOptionsAction::class,
        function (MockInterface $mock) use ($user): void {
            $mock->shouldReceive('execute')
                ->once()
                ->withArgs(fn (User $passed): bool => $passed->is($user))
                ->andReturn('{"challenge":"abc"}');
        },
    );

    $this->actingAs($user)
        ->postJson('/settings/passkeys/options')
        ->assertOk()
        ->assertExactJson(['challenge' => 'abc']);

    expect(Session::get('passkey.registration_options'))->toBe('{"challenge":"abc"}');
});

test('guests cannot store a passkey', function (): void {
    $this->postJson('/settings/passkeys', [
        'name' => 'Macbook',
        'credential' => ['id' => 'abc'],
    ])->assertUnauthorized();
});

test('store validates name and credential', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/settings/passkeys', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'credential']);
});

test('storing a passkey returns id and name on success', function (): void {
    $user = User::factory()->create();
    Session::put('passkey.registration_options', '{"challenge":"abc"}');

    $passkey = new Passkey;
    $passkey->forceFill([
        'id' => 42,
        'name' => 'Macbook',
        'authenticatable_id' => $user->id,
        'credential_id' => 'cred-id',
    ]);

    $this->mock(SpatieStorePasskeyAction::class, function (MockInterface $mock) use ($passkey): void {
        $mock->shouldReceive('execute')->once()->andReturn($passkey);
    });

    $this->actingAs($user)
        ->postJson('/settings/passkeys', [
            'name' => 'Macbook',
            'credential' => ['id' => 'abc'],
        ])
        ->assertCreated()
        ->assertJson(['id' => 42, 'name' => 'Macbook']);
});

test('invalid attestation from Spatie returns 422', function (): void {
    $user = User::factory()->create();
    Session::put('passkey.registration_options', '{"challenge":"abc"}');

    $this->mock(SpatieStorePasskeyAction::class, function (MockInterface $mock): void {
        $mock->shouldReceive('execute')->once()->andThrow(new RuntimeException('bad attestation'));
    });

    $this->actingAs($user)
        ->postJson('/settings/passkeys', [
            'name' => 'Macbook',
            'credential' => ['id' => 'abc'],
        ])
        ->assertStatus(422)
        ->assertJson(['error' => 'invalid_attestation']);
});

test('store fails when registration options session entry is missing', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/settings/passkeys', [
            'name' => 'Macbook',
            'credential' => ['id' => 'abc'],
        ])
        ->assertStatus(422)
        ->assertJson(['error' => 'invalid_attestation']);
});

test('user can delete own passkey', function (): void {
    $user = User::factory()->create();
    $passkeyId = createPasskeyRow($user->id);

    $this->actingAs($user)
        ->delete('/settings/passkeys/'.$passkeyId)
        ->assertRedirect();

    expect(DB::table('passkeys')->where('id', $passkeyId)->exists())->toBeFalse();
});

test('user cannot delete foreign passkey', function (): void {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $passkeyId = createPasskeyRow($owner->id);

    $this->actingAs($intruder)
        ->delete('/settings/passkeys/'.$passkeyId)
        ->assertForbidden();

    expect(DB::table('passkeys')->where('id', $passkeyId)->exists())->toBeTrue();
});

test('settings page renders without error when user has passkeys', function (): void {
    $user = User::factory()->create();
    createPasskeyRow($user->id);

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk();
});
