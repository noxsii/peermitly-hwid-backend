<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Mockery\MockInterface;
use Spatie\LaravelPasskeys\Actions\FindPasskeyToAuthenticateAction as SpatieFindPasskeyAction;
use Spatie\LaravelPasskeys\Actions\GeneratePasskeyAuthenticationOptionsAction as SpatieGenerateAuthOptionsAction;
use Spatie\LaravelPasskeys\Models\Passkey;

test('guest can request passkey assertion options and session stores raw options', function (): void {
    $this->mock(
        SpatieGenerateAuthOptionsAction::class,
        function (MockInterface $mock): void {
            $mock->shouldReceive('execute')->once()->andReturn('{"challenge":"xyz"}');
        },
    );

    $this->postJson('/auth/passkey/options')
        ->assertOk()
        ->assertExactJson(['challenge' => 'xyz']);

    expect(Session::get('passkey.authentication_options'))->toBe('{"challenge":"xyz"}');
});

test('verify with missing assertion options session returns 422', function (): void {
    $this->postJson('/auth/passkey/verify', [
        'assertion' => ['id' => 'abc'],
    ])
        ->assertStatus(422)
        ->assertJson(['error' => 'invalid_assertion']);

    $this->assertGuest();
});

test('verify with invalid assertion returns 422 and clears session entry', function (): void {
    Session::put('passkey.authentication_options', '{"challenge":"xyz"}');

    $this->mock(SpatieFindPasskeyAction::class, function (MockInterface $mock): void {
        $mock->shouldReceive('execute')->once()->andThrow(new RuntimeException('bad assertion'));
    });

    $this->postJson('/auth/passkey/verify', [
        'assertion' => ['id' => 'abc'],
    ])
        ->assertStatus(422)
        ->assertJson(['error' => 'invalid_assertion']);

    $this->assertGuest();
    expect(Session::get('passkey.authentication_options'))->toBeNull();
});

test('verify with valid assertion authenticates user and returns dashboard redirect', function (): void {
    $user = User::factory()->create();
    Session::put('passkey.authentication_options', '{"challenge":"xyz"}');

    $passkey = new Passkey;
    $passkey->forceFill([
        'id' => 1,
        'authenticatable_id' => $user->id,
        'name' => 'Macbook',
        'credential_id' => 'cred-id',
    ]);
    $passkey->setRelation('authenticatable', $user);

    $this->mock(SpatieFindPasskeyAction::class, function (MockInterface $mock) use ($passkey): void {
        $mock->shouldReceive('execute')->once()->andReturn($passkey);
    });

    $this->postJson('/auth/passkey/verify', [
        'assertion' => ['id' => 'abc'],
    ])
        ->assertOk()
        ->assertJson(['redirect' => '/dashboard']);

    $this->assertAuthenticatedAs($user);
});

test('verify rejects inactive user even with valid passkey', function (): void {
    $user = User::factory()->create(['is_active' => false]);
    Session::put('passkey.authentication_options', '{"challenge":"xyz"}');

    $passkey = new Passkey;
    $passkey->forceFill([
        'id' => 1,
        'authenticatable_id' => $user->id,
        'name' => 'Macbook',
        'credential_id' => 'cred-id',
    ]);
    $passkey->setRelation('authenticatable', $user);

    $this->mock(SpatieFindPasskeyAction::class, function (MockInterface $mock) use ($passkey): void {
        $mock->shouldReceive('execute')->once()->andReturn($passkey);
    });

    $this->postJson('/auth/passkey/verify', [
        'assertion' => ['id' => 'abc'],
    ])
        ->assertStatus(422)
        ->assertJson(['error' => 'invalid_assertion']);

    $this->assertGuest();
});

test('verify validates assertion is present', function (): void {
    $this->postJson('/auth/passkey/verify', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors('assertion');
});
