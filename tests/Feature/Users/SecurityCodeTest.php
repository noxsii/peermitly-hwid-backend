<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

test('a security code is generated when a user is created', function (): void {
    $user = User::factory()->create();

    expect($user->security_code)
        ->not->toBeNull()
        ->toMatch('/^[23456789ABCDEFGHJKMNPQRSTUVWXYZ]{4}$/');
});

test('the security code is stored encrypted at rest', function (): void {
    $user = User::factory()->create();

    $raw = DB::table('users')->where('id', $user->id)->value('security_code');

    expect($raw)
        ->not->toBe($user->security_code)
        ->and(Crypt::decryptString($raw))->toBe($user->security_code);
});

test('an explicitly provided security code is not overwritten', function (): void {
    $user = User::factory()->create(['security_code' => 'AB23']);

    expect($user->security_code)->toBe('AB23');
});

test('each user receives an independent security code', function (): void {
    User::factory()->count(5)->create();

    $codes = User::query()->pluck('security_code');

    expect($codes)->each->not->toBeNull();
});
